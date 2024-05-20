import { useEffect, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { getUsers, perfilUpdate } from "../../redux/actions/actions";
import Swal from "sweetalert2";
import TextField from "@mui/material/TextField";

export default function Profile() {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const token = localStorage.getItem("Token");
  const formattedToken = token.replace(/["',]/g, ""); // Remueve las comillas dobles y las comas del token
  const user = useSelector((state) => state.user);

  const { message } = user;

  const [profile, setProfile] = useState({
    id: "",
    name: "",
    apellido: "",
    email: "",
  });

  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    if (formattedToken) {
      dispatch(getUsers(formattedToken));
    }
  }, [dispatch, formattedToken]);

  useEffect(() => {
    if (message) {
      setProfile({
        id: message?.id,
        name: message?.name,
        email: message?.email,
      });
    }
  }, [message]);

  const handleChange = (event) => {
    const { name, value } = event.target;
    setProfile((prevUser) => ({
      ...prevUser,
      [name]: value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await dispatch(perfilUpdate(profile, formattedToken));
      Swal.fire({
        width: "20em",
        title: "Datos Actualizados",
        showConfirmButton: false,
        icon: "success",
        timer: 3000,
        timerProgressBar: true,
      });
      navigate("/profile");
    } catch (error) {
      const errorMessage = error.response.data.error || "Error desconocido";
      Swal.fire({
        width: "20em",
        title: `${errorMessage}`,
        text: "No se pudo Actualizar los datos",
        icon: "error",
        showConfirmButton: false,
        timer: 3000,
      });
    }
  };

  return (
    <>
      <div className="relative min-h-screen flex items-center justify-center bg-gray-100">
        <img
          className="h-full w-full object-cover absolute inset-0 z-0"
          src="/bg.jpg"
          alt="Imagen de fondo"
        />
        <div>
          <div className="relative max-w-md w-full p-6 bg-white bg-opacity-90 rounded-lg shadow-lg z-10">
            <div className="rounded-t-lg h-32 overflow-hidden">
              <img
                className="object-cover object-top w-full"
                src="https://images.unsplash.com/photo-1549880338-65ddcdfd017b?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ"
                alt="Mountain"
              />
            </div>
            <div className="mx-auto w-32 h-32 relative -mt-16 border-4 border-white rounded-full overflow-hidden">
              <img
                className="object-cover object-center h-32"
                src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=400&fit=max&ixid=eyJhcHBfaWQiOjE0NTg5fQ"
                alt="Woman looking front"
              />
            </div>
            <div className="text-center mt-2">
              <h2 className="font-semibold">{message?.name}</h2>
              <p className="text-gray-500">{message?.email}</p>
            </div>

            {isEditing ? (
              <form
                className="sm:w-2/3 w-full px-4 lg:px-0 mx-auto"
                onSubmit={handleSubmit}
              >
                <div className="pb-2 pt-4">
                  <TextField
                    label="Name"
                    value={profile.name}
                    variant="outlined"
                    fullWidth
                    onChange={handleChange}
                    type="text"
                    required
                    name="name"
                    id="name"
                  />
                </div>
                <div className="pb-2 pt-4">
                  <TextField
                    label="apellido"
                    value={profile.apellido}
                    variant="outlined"
                    fullWidth
                    onChange={handleChange}
                    type="text"
                    required
                    name="apellido"
                    id="apellido"
                  />
                </div>
                <div className="pb-2 pt-4">
                  <TextField
                    label="Email"
                    value={profile.email}
                    variant="outlined"
                    fullWidth
                    onChange={handleChange}
                    type="text"
                    required
                    name="email"
                    id="email"
                  />
                </div>
                <div className="flex justify-between  py-4">
                  <button
                    type="submit"
                    className="w-full mx-1 rounded-full bg-gray-900 hover:shadow-lg font-semibold text-white px-6 py-2"
                  >
                    Confirmar
                  </button>
                  <button
                    type="button"
                    onClick={() => setIsEditing(false)}
                    className="w-full mx-1 rounded-full bg-red-500 hover:shadow-lg font-semibold text-white px-6 py-2"
                  >
                    Cancelar
                  </button>
                </div>
              </form>
            ) : (
              <div className="p-4 border-t mx-8 mt-2 flex justify-between space-x-3">
                <button
                  onClick={() => setIsEditing(true)}
                  className="w-1/2 block mx-auto rounded-full bg-gray-900 hover:shadow-lg font-semibold text-white px-6 py-2"
                >
                  Editar Perfil
                </button>

                <Link
                  to="/"
                  className="w-1/2 text-center block mx-auto rounded-full bg-gray-900 hover:shadow-lg font-semibold text-white px-6 py-2"
                >
                  Regresar
                </Link>
              </div>
            )}
          </div>
        </div>
      </div>
    </>
  );
}
