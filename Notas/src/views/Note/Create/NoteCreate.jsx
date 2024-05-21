import { useState } from "react";
import { CreateNote } from "../../../redux/actions/actions";
import { useDispatch } from "react-redux";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import TextField from "@mui/material/TextField";
import Modal from "@mui/material/Modal";
import EditIcon from "@mui/icons-material/Edit";
import Swal from "sweetalert2";
import PropTypes from "prop-types";

const style = {
  position: "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: 500,
  bgcolor: "background.paper",
  boxShadow: 24,
  p: 4,
};

export default function NoteCreate({ formattedToken }) {
  const dispatch = useDispatch();
  const [open, setOpen] = useState(false);
  const [colorPin, setColorPin] = useState(false);
  const [card, setCard] = useState({
    title: "",
    body: "",
  });

  const handleOpen = () => setOpen(true);
  const handleClose = () => setOpen(false);

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
    console.log("click");
    e.preventDefault();
    await dispatch(CreateNote(formattedToken, card));
    handleClose()
    try {
      Swal.fire({
        width: "20em",
        title: "Nota Creada",
        showConfirmButton: false,
        icon: "success",
        timer: 3000,
        timerProgressBar: true,
      });
      setTimeout(() => {
        window.location.href = "/";
      }, 2000);
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
    <div>
      <Button onClick={handleOpen} variant="contained" color="primary">
        Crear Nota
      </Button>
      <Modal
        open={open}
        onClose={handleClose}
        aria-labelledby="modal-modal-title"
        aria-describedby="modal-modal-description"
      >
        <Box sx={style}>
          <form onSubmit={handleSubmit}>
            <div className="max-w-md overflow-hidden">
              <div>
                <p className="text-3xl font-semibold text-center">
                  Crear una Nota
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
                  type="button"
                  color="error"
                  variant="outlined"
                  size="small"
                  onClick={handleClose}
                >
                  Cancelar
                </Button>
              </div>
            </div>
          </form>
        </Box>
      </Modal>
    </div>
  );
}

NoteCreate.propTypes = {
  formattedToken: PropTypes.string.isRequired,
};
