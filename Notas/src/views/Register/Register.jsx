import { useState } from "react";
import { Link } from "react-router-dom";
import { useDispatch } from "react-redux";
import { useNavigate } from "react-router-dom";
import { register } from "../../redux/actions/actions";
import Swal from "sweetalert2";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";

const Register = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const [input, setInput] = useState({
    name: "",
    last_name: "",
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  const handleChange = (event) => {
    setInput({
      ...input,
      [event.target.name]: event.target.value,
    });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (input.password_confirmation !== input.password) {
      Swal.fire({
        title: "Verifica la información.",
        text: "Por favor, verifique que las contraseñas sean iguales",
        icon: "warning",
      });
    } else {
      try {
        await dispatch(register(input));
        Swal.fire({
          position: "center",
          icon: "success",
          title: "¡El usuario se registró exitosamente!",
          showConfirmButton: false,
          timer: 2000,
        });

        navigate("/login");
      } catch (error) {
        const errorData = error.response.data;
        let errorMessage = "";

        if (errorData.username) {
          errorMessage += `Username: ${errorData.username.join(", ")}\n`;
        }

        if (errorData.email) {
          errorMessage += `Email: ${errorData.email.join(", ")}`;
        }
        Swal.fire({
          icon: "error",
          title: "Error al registrar usuario",
          text: errorMessage || "Ocurrió un error inesperado",
        });
      }
    }
  };

  return (
    <div className="flex w-screen flex-wrap text-slate-800">
      <div className="relative hidden h-screen select-none flex-col justify-center text-center md:flex md:w-1/2">
        <img
          className="h-full w-full object-cover"
          src="/bg.jpg"
          alt="Imagen de fondo"
        />
      </div>
      <div className="flex w-full flex-col md:w-1/2">
        <div className="my-auto rounded-lg h-4/5 shadow-2xl mx-auto flex flex-col justify-center px-6 pt-8 md:justify-start lg:w-[32rem]">
          <p className="text-center text-3xl font-bold md:text-left md:leading-tight">
            Crea tu cuenta
          </p>
          <p className="mt-6 text-center font-medium md:text-left">
            Ya tienes cuenta
            <Link
              to="/login"
              className="whitespace-nowrap font-semibold text-blue-700"
            >
              {" "}
              Inicia Sesión aquí
            </Link>
          </p>

          <form
            className="flex flex-col items-stretch pt-3 md:pt-8"
            onSubmit={handleSubmit}
          >
            <div className="flex flex-col pt-4">
              <div className="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type="text"
                  id="login-name"
                  name="name"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Nombre"
                  value={input.name}
                  onChange={handleChange}
                />
              </div>
            </div>
            <div className="flex flex-col pt-4">
              <div className="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type="text"
                  id="login-apellido"
                  name="last_name"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Apellido"
                  value={input.last_name}
                  onChange={handleChange}
                />
              </div>
            </div>
            <div className="flex flex-col pt-4">
              <div className="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type="text"
                  id="login-username"
                  name="username"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Usuario"
                  value={input.username}
                  onChange={handleChange}
                />
              </div>
            </div>
            <div className="flex flex-col pt-4">
              <div className="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type="email"
                  id="login-email"
                  name="email"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Email"
                  value={input.email}
                  onChange={handleChange}
                />
              </div>
            </div>
            <div className="mb-4 flex flex-col pt-4">
              <div className="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type={showPassword ? "text" : "password"}
                  id="login-password"
                  name="password"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Password (minimum 8 characters)"
                  value={input.password}
                  onChange={handleChange}
                />
                <span
                  className="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                  onClick={() => setShowPassword(!showPassword)}
                >
                  <FontAwesomeIcon icon={showPassword ? faEyeSlash : faEye} />
                </span>
              </div>
            </div>

            <div className="mb-4 flex flex-col pt-4">
              <div className="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type={showConfirmPassword ? "text" : "password"}
                  id="login-password-confirm"
                  name="password_confirmation"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Confirm Password"
                  value={input.password_confirmation}
                  onChange={handleChange}
                />
                <span
                  className="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                  onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                >
                  <FontAwesomeIcon
                    icon={showConfirmPassword ? faEyeSlash : faEye}
                  />
                </span>
              </div>
            </div>

            <button
              type="submit"
              className="mt-6 w-full rounded-lg bg-blue-600 px-4 py-2 text-center text-base font-semibold text-white shadow-md outline-none ring-blue-500 ring-offset-2 transition hover:bg-blue-700 focus:ring-2"
            >
              Sign in
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Register;
