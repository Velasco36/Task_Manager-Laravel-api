import  { useState } from "react";
import { Link } from "react-router-dom";
import { useDispatch } from "react-redux";
import { TextField } from "@mui/material";
import { fPassword } from "../../redux/actions/actions";
import Swal from "sweetalert2";


function ForgotPassword() {
      const dispatch = useDispatch();
  const [email, setEmail] = useState("");

    const handleSubmit = async (e) => {
      e.preventDefault();
      try {
        await dispatch(fPassword({email: email}));
        Swal.fire({
          width: "20em",
          title: "Email Envio",
          showConfirmButton: false,
          icon: "success",
          timer: 3000,
          timerProgressBar: true,
        });
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
  return (
    <div className="relative min-h-screen flex items-center justify-center bg-gray-100">
      <img
        className="h-full w-full object-cover absolute inset-0 z-0"
        src="/bg.jpg"
        alt="Imagen de fondo"
      />
      <div className="relative max-w-md w-full p-6 bg-white bg-opacity-90 rounded-lg shadow-lg z-10">
        <h1 className="text-2xl font-semibold text-center text-gray-500 mt-8 mb-6">
          Recuperación de contraseña
        </h1>
        <p className="text-sm text-gray-600 text-center mt-8 mb-6">
          Introduce tu correo electrónico para restablecer tu contraseña
        </p>
        <form onSubmit={handleSubmit}>
          <div className="mb-6">
            <label className="block mb-2 text-sm text-gray-600"></label>
            <TextField
              label="Correo electrónico"
              value={email}
              variant="outlined"
              fullWidth
              onChange={(e) => setEmail(e.target.value)}
              type="text"
              required
              name="email"
              id="email"
            />
          </div>
          <button
            type="submit"
            className="w-32 bg-gradient-to-r from-indigo-600 to-indigo-800 hover:bg-indigo-800 text-white py-2 rounded-lg mx-auto block focus:outline-none focus:ring-2 focus:ring-offset-2 mt-4 mb-4"
          >
            Enviar
          </button>
        </form>
        <div className="text-center">
          <p className="text-sm">
            Volver a{" "}
            <Link to="/login" className="text-indigo-600 hover:text-indigo-800">
              Iniciar sesión
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
}

export default ForgotPassword;
