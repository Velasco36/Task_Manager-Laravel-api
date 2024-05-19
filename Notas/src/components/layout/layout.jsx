import { Outlet } from "react-router-dom";
import Navbar from "../navbar/Navbar";

const Layout = () => {
  return (
    <>
      <Navbar />
      <div>
        <Outlet />
      </div>
    </>
  );
};

export default Layout;
