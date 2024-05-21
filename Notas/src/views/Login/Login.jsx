import { useState } from "react";
import { useDispatch } from "react-redux";
import { Link, useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import { signin } from "../../redux/actions/actions";
import TextField from "@mui/material/TextField";
import VisibilityOutlinedIcon from "@mui/icons-material/VisibilityOutlined";
import InputAdornment from "@mui/material/InputAdornment";

const Login = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const [cUser, setUser] = useState({
    email: "",
    password: "",
  });
  const [showPassword, setShowPassword] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await dispatch(signin(cUser));
      Swal.fire({
        width: "20em",
        title: "Sesion iniciada.",
        showConfirmButton: false,
        icon: "success",
        timer: 3000,
        timerProgressBar: true,
      });
      navigate("/");
    } catch (error) {
      const errorMessage = error.response.data.error || "Error desconocido";
      Swal.fire({
        width: "20em",
        title: `${errorMessage}`,
        text: "No se pudo iniciar sesion",
        icon: "error",
        showConfirmButton: false,
        timer: 3000,
      });
    }
  };

  const handleChange = (event) => {
    const { name, value } = event.target;
    setUser((prevUser) => ({
      ...prevUser,
      [name]: value,
    }));
  };
  return (
    <section className="min-h-screen flex items-stretch text-white select-none">
      <div className="lg:flex w-1/2 hidden bg-gray-500 bg-no-repeat bg-cover relative items-center">
        <img
          className="h-full w-full object-cover absolute inset-0 z-0"
          src="/bg.jpg"
          alt="Imagen de fondo"
        />
        <div className="absolute bg-black opacity-60 inset-0 z-0"></div>
        <div className="w-full px-24 z-10">
          <h1 className="text-5xl font-bold text-left tracking-wide">
            Administrador de tareas
          </h1>
          <p className="text-3xl my-4 text-center">
            Organiza tus prioridades y registra todo para que no se te olvide
          </p>
        </div>
      </div>
      <div className="lg:w-1/2 bg-gray-100 w-full flex items-center justify-center text-center md:px-16 px-0 z-0">
        <div className="absolute lg:hidden z-10 inset-0 bg-gray-500 bg-no-repeat bg-cover items-center">
          <img
            className="h-full w-full object-cover absolute inset-0 z-0"
            src="/bg.jpg"
            alt="Imagen de fondo"
          />
          <div className="absolute bg-white opacity-60 inset-0 z-0"></div>
        </div>
        <div className="w-full py-6 z-20 bg-white rounded-lg shadow-lg">
          <h1 className="text-5xl font-bold py-2 tracking-wide text-indigo-600 text-center">
            Iniciar Sesión
          </h1>
          <p className="text-gray-700">
            Bienvenido, inicie sesión en su cuenta
          </p>
          <form
            className="sm:w-2/3 w-full px-4 lg:px-0 mx-auto"
            onSubmit={handleSubmit}
          >
            <div className="pb-2 pt-4">
              <TextField
                label="Email"
                value={cUser.email}
                variant="outlined"
                fullWidth
                onChange={handleChange}
                type="text"
                required
                name="email"
                id="email"
              />
            </div>
            <div className="pb-2 pt-4">
              <TextField
                label="contraseña"
                value={cUser.password}
                variant="outlined"
                fullWidth
                onChange={handleChange}
                type={showPassword ? "text" : "password"}
                name="password"
                required
                id="password"
                InputProps={{
                  endAdornment: (
                    <InputAdornment position="end">
                      <VisibilityOutlinedIcon
                        onClick={() => setShowPassword((prev) => !prev)}
                        className="cursor-pointer text-black"
                      />
                    </InputAdornment>
                  ),
                }}
              />
            </div>
            <div className="text-right text-gray-400 hover:underline hover:text-indigo-400">
              <Link to="/forgot-password">¿Olvidaste tu contraseña?</Link>
            </div>
            <div className="px-4 pb-2 pt-4">
              <button
                type="submit"
                className="uppercase block w-full p-4 text-lg rounded-full bg-indigo-500 hover:bg-indigo-600 focus:outline-none"
              >
                Iniciar sesión
              </button>
            </div>
          </form>
          <div className="text-center text-gray-400 hover:underline hover:text-indigo-600">
            <Link to="/register">¿No tiene una cuenta? </Link>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Login;
