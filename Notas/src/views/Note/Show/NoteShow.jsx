import { useState, useEffect } from "react";
import { updateNote } from "../../../redux/actions/actions";
import { useNavigate } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { ShowNote } from "../../../redux/actions/actions";
import { useParams } from "react-router-dom";
import Button from "@mui/material/Button";
import TextField from "@mui/material/TextField";
import EditIcon from "@mui/icons-material/Edit";
import Swal from "sweetalert2";

export default function NoteShow() {
  const { id } = useParams(); //Obtener id
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const token = localStorage.getItem("Token");
  const formattedToken = token.replace(/["',]/g, ""); // Remueve las comillas dobles y las comas del token
  const showNote = useSelector((state) => state.showNote);
    console.log(showNote);

    useEffect(() => {
      if (formattedToken) {
        dispatch(ShowNote(formattedToken, id));
      }
    }, [dispatch, formattedToken, id]);


  const [colorPin, setColorPin] = useState(false);
  const [card, setCard] = useState({
    title: "",
    body: "",
    created_at: "",
  });

  useEffect(() => {
    if (showNote) {
      setCard({
        title: showNote?.title,
        body: showNote?.body,
        created_at: showNote?.created_at,
      });
    }
  }, [showNote]);
  const handlePin = () => {
    setColorPin(!colorPin);
  };

  const handleChange = (e) => {
    setCard({
      ...card,
      [e.target.name]: e.target.value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log(card);
    try {
       await dispatch(updateNote(formattedToken,id, card));

      Swal.fire({
        width: "20em",
        title: "Datos Actualizados",
        showConfirmButton: false,
        icon: "success",
        timer: 3000,
        timerProgressBar: true,
      });
       navigate("/");
    } catch (error) {
      const errorMessage = error.response?.data?.error || "Error desconocido";
      Swal.fire({
        width: "20em",
        title: `${errorMessage}`,
        text: "No se pudo actualizar los datos",
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
          <div className="relative  max-w-screen-2xl p-6 bg-white bg-opacity-90 rounded-lg shadow-lg z-10 sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl">
            <div>
              <form onSubmit={handleSubmit}>
                <div className="w-full overflow-hidden">
                  <div>
                    <p className="text-3xl font-semibold text-center">
                      Mostrar Nota
                    </p>
                    <div
                      className="flex justify-center py-3 cursor-pointer"
                      onClick={handlePin}
                    >
                      <img
                        src="/icons/pin-push.svg"
                        alt="icon"
                        className={`h-6 w-6 mr-2 cursor-pointer ${
                          colorPin ? "text-green-500" : "text-gray-500"
                        }`}
                      />
                      {colorPin ? (
                        <p className="text-green-500">Fijado</p>
                      ) : (
                        <p className="text-gray-500">Dale click para fijar</p>
                      )}
                    </div>
                  </div>
                  <div className="px-6 py-4">
                    <div className="flex items-center mb-4">
                      <TextField
                        name="title"
                        value={card.title}
                        fullWidth
                        onChange={handleChange}
                        label="Escribe un título para la nota"
                        variant="standard"
                      />
                      <EditIcon />
                    </div>
                    <TextField
                      name="body"
                      value={card.body}
                      onChange={handleChange}
                      label="Añade una descripción a la nota"
                      id="standard-multiline-static"
                      fullWidth
                      multiline
                      rows={10}
                      variant="standard"
                    />
                  </div>
                  <div className="px-6 pt-4 pb-2 flex justify-center space-x-6">
                    <Button
                      variant="outlined"
                      size="small"
                      color="primary"
                      type="submit"
                    >
                      Aceptar
                    </Button>
                    <Button
                    href="/"
                      type="button"
                      color="warning"
                      variant="outlined"
                      size="small"
                    >
                      Regresar
                    </Button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
