import { useState } from "react";
import { useDispatch } from "react-redux";
import { useNavigate } from "react-router-dom";
import { register } from "../../redux/actions/actions";
import Swal from "sweetalert2";

const Register = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const [input, setInput] = useState({
    nombre: "",
    email: "",
    password: "",
    passwordConfimm: "",
  });

  const [showConfirmPassword, setShowConfimPassword] = useState(false);

  const handleChange = (event) => {
    setInput({
      ...input,
      [event.target.name]: event.target.value,
    });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    const newUser = { ...input };
    if (input.passwordConfimm !== input.password) {
      Swal.fire({
        title: "Verifica la informacion.",
        text: "Por favor, verifique que las contraseñas sean iguales",
        icon: "warning",
      });
    } else {
      try {
        await dispatch(register(newUser));
        Swal.fire({
          position: "center",
          icon: "success",
          title: "¡El usuario se registró exitosamente!",
          showConfirmButton: false,
          timer: 2000,
        });
        setInput({
          nombre: "",
          email: "",
          password: "",
          passwordConfimm: "",
        });
        navigate("/login");
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Este usuario ya esta registrado.",
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
        <div className="my-auto mx-auto flex flex-col justify-center px-6 pt-8 md:justify-start lg:w-[28rem]">
          <p className="text-center text-3xl font-bold md:text-left md:leading-tight">
            Create your free account
          </p>
          <p className="mt-6 text-center font-medium md:text-left">
            Already using wobble?
            <a
              href="#"
              className="whitespace-nowrap font-semibold text-blue-700"
            >
              Login here
            </a>
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
                  name="nombre"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Name"
                  value={input.nombre}
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
                  type="password"
                  id="login-password"
                  name="password"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Password (minimum 8 characters)"
                  value={input.password}
                  onChange={handleChange}
                />
              </div>
            </div>

            <div className="mb-4 flex flex-col pt-4">
              <div className="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type={showConfirmPassword ? "text" : "password"}
                  id="login-password-confirm"
                  name="passwordConfimm"
                  className="w-full flex-shrink appearance-none border-gray-300 bg-white py-2 px-4 text-base text-gray-700 placeholder-gray-400 focus:outline-none"
                  placeholder="Confirm Password"
                  value={input.passwordConfimm}
                  onChange={handleChange}
                />
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
