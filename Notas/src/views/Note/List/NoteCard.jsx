import PropTypes from 'prop-types';
import { useEffect, useState } from "react";
import { updateNote } from "../../../redux/actions/actions";
import { useDispatch } from "react-redux";

import TextField from "@mui/material/TextField";
import Button from "@mui/material/Button";
import ButtonDelete from "../Delete/ButtonDelete";
import Swal from "sweetalert2";

export default function NoteCard({ note, formattedToken }) {
  const dispatch = useDispatch();

  const [card, setCard] = useState({
    title: note.title,
    body: note.body,
    created_at: note.created_at,
  });
  const [isChanged, setIsChanged] = useState(false);

  const formatDate = (dateTimeString) => {
    const dateTime = new Date(dateTimeString);
    const year = dateTime.getFullYear();
    const month = String(dateTime.getMonth() + 1).padStart(2, "0");
    const day = String(dateTime.getDate()).padStart(2, "0");
    const hours = String(dateTime.getHours()).padStart(2, "0");
    const minutes = String(dateTime.getMinutes()).padStart(2, "0");
    const seconds = String(dateTime.getSeconds()).padStart(2, "0");
    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
  };

  useEffect(() => {
    setIsChanged(card.title !== note.title || card.body !== note.body);
  }, [card, note.title, note.body]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setCard((prevCard) => ({
      ...prevCard,
      [name]: value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      await dispatch(updateNote(formattedToken, note.id, card));
      Swal.fire({
        width: "20em",
        title: "Datos Actualizados",
        showConfirmButton: false,
        icon: "success",
        timer: 3000,
        timerProgressBar: true,
      });
    } catch (error) {
      const errorMessage = error.response?.data?.error || "Error desconocido";
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
    <form onSubmit={handleSubmit}>
      <div className="max-w-sm  bg-white rounded overflow-hidden shadow-lg hover:shadow-2xl cursor-pointer">
        <div className="px-6 py-4">
          <div className="flex items-center mb-4">
            <img
              src="/icons/pin-push.svg"
              alt="icon"
              className="h-6 w-6 mr-2 cursor-pointer"
            />
            <TextField
              name="title"
              fullWidth
              value={card.title}
              onChange={handleChange}
              variant="standard"
            />
          </div>
          <TextField
            name="body"
            id="standard-multiline-static"
            fullWidth
            multiline
            rows={4}
            value={card.body}
            onChange={handleChange}
            variant="standard"
          />
        </div>

        <p className="mx-7 text-gray-500">
          Fecha: {formatDate(note.created_at)}
        </p>
        <div className="px-6 pt-4 pb-2 flex justify-center space-x-6">
          <ButtonDelete formattedToken={formattedToken} id={note.id} />
          <Button type="submit" href={`/show/${note.id}`} variant="outlined" size="small">
            Ver
          </Button>
          {isChanged && (
            <Button type="submit" variant="outlined" size="small">
              Guardar
            </Button>
          )}
        </div>
      </div>
    </form>
  );
}

NoteCard.propTypes = {
  note: PropTypes.shape({
    id: PropTypes.number.isRequired,
    title: PropTypes.string.isRequired,
    body: PropTypes.string.isRequired,
    created_at: PropTypes.string.isRequired,
  }).isRequired,
  formattedToken: PropTypes.string.isRequired,
};
