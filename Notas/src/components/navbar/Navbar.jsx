import { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { Transition } from "@headlessui/react";
import { getUsers, logout } from "../../redux/actions/actions";
import AccountCircleIcon from "@mui/icons-material/AccountCircle";
import Swal from "sweetalert2";

export default function Navbar() {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const token = localStorage.getItem("Token");
  const formattedToken = token.replace(/["',]/g, ""); // Remueve las comillas dobles y las comas del token

  const user = useSelector((state) => state.user);
  const { message } = user;

  const [isUserMenuOpen, setIsUserMenuOpen] = useState(false);

  useEffect(() => {
    if (formattedToken) {

      dispatch(getUsers(formattedToken));
    }
  }, [dispatch, formattedToken]);

  const toggleUserMenu = () => {
    setIsUserMenuOpen(!isUserMenuOpen);
  };


  const handleLogout = async(e) => {
     e.preventDefault();

        try {
      await dispatch(logout(formattedToken));
      Swal.fire({
        width: "20em",
        title: "Sesion Cerrada.",
        showConfirmButton: false,
        icon: "success",
        timer: 3000,
        timerProgressBar: true,
      });
       localStorage.removeItem("Token");
      navigate("/login");
    } catch (error) {
      const { response } = error
      Swal.fire({
        width: "20em",
        title: `${response.data.data}`,
        text: "No se pudo iniciar sesion",
        icon: "error",
        showConfirmButton: false,
        timer: 1000,
      });
    }

  };

  return (
    <div>
      <nav className="bg-white dark:bg-white shadow-2xl h-18 ">
        <div className=" flex flex-wrap items-center justify-between mx-auto p-4">
          <p className="text-1xl font-weight-lighter text-center">
            Administrador de Tareas
          </p>
          <div className="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button
              type="button"
              className="flex text-sm  rounded-full md:me-0 "
              id="user-menu-button"
              aria-expanded={isUserMenuOpen}
              onClick={toggleUserMenu}
            >
              <AccountCircleIcon className="text-black " />
            </button>

            <Transition
              show={isUserMenuOpen}
              enter="transition ease-out duration-100 transform"
              enterFrom="opacity-0 scale-95"
              enterTo="opacity-100 scale-100"
              leave="transition ease-in duration-75 transform"
              leaveFrom="opacity-100 scale-100"
              leaveTo="opacity-0 scale-95"
            >
              <div
                className="z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600 absolute right-0 top-12"
                id="user-dropdown"
              >
                <div className="px-4 py-3">
                  <span className="block text-sm text-gray-900 dark:text-white">
                    {message?.name}
                  </span>
                  <span className="block text-sm  text-gray-500 truncate dark:text-gray-400">
                    {message?.email}
                  </span>
                </div>
                <ul className="py-2" aria-labelledby="user-menu-button">
                  <li>
                    <Link
                      to="/profile"
                      className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                    >
                      Profile
                    </Link>
                  </li>
                  <li>
                    <Link
                      to="/"
                      className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                    >
                      Settings
                    </Link>
                  </li>

                  <li>
                    <a
                      onClick={handleLogout}
                      href="#"
                      className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                    >
                      Sign out
                    </a>
                  </li>
                </ul>
              </div>
            </Transition>

          </div>

        </div>
      </nav>
    </div>
  );
}
