// Layout.jsx
import { useEffect, useState } from "react";
import { Outlet, useNavigate } from "react-router-dom";
import { useSelector, useDispatch } from "react-redux";

import { getUsers } from "../../redux/actions/actions"; // Assuming memoized getUsers
import Navbar from "../navbar/Navbar";

const Layout = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const token = localStorage.getItem("Token");
  const formattedToken = token ? token.replace(/["',]/g, "") : null;
  const [loading, setLoading] = useState(true);
  const user = useSelector((state) => state.user);

  useEffect(() => {
    if (formattedToken) {
      setLoading(true);
      dispatch(getUsers(formattedToken))
        .then(() => {
          setLoading(false);
        })
        .catch((error) => {
          console.error("Error fetching user data:", error);
          navigate("/login");
        });
    } else {
      setLoading(false);
      navigate("/login");
    }
  }, [dispatch, formattedToken, navigate]);

  if (loading) {
    // Consider using a skeleton screen or loading animation here
    return <div>Loading...</div>;
  }

  return (
    <>
      <Navbar dataUser={user} formattedToken={formattedToken} />
      <div>
        <Outlet />
      </div>
    </>
  );
};

export default Layout;
