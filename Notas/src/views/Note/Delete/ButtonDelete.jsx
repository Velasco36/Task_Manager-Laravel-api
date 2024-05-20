import Button from "@mui/material/Button";
import { useDispatch } from "react-redux";
import { DeleteNote } from "../../../redux/actions/actions";
import Swal from "sweetalert2";
import PropTypes from "prop-types";

export default function ButtonDelete({ formattedToken, id }) {
  const dispatch = useDispatch();
  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const confirmation = await Swal.fire({
        width: "700px", // Set desired width for the modal
        title: "¿Estás seguro que deseas eliminar esta nota?",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        icon: "warning",
        customClass: {
          popup: "w-full", // Adjust width for full-screen behavior (optional)
        },
      });

      if (confirmation.isConfirmed) {
        await dispatch(DeleteNote(formattedToken, id));
        Swal.fire({
          width: "20em",
          title: "Nota eliminada",
          showConfirmButton: false,
          icon: "success",
          timer: 3000,
          timerProgressBar: true,
        });
        setTimeout(() => {
          window.location.href = "/list";
        }, 2000);
      }
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
      <Button
        variant="outlined"
        size="small"
        color="error"
        onClick={handleSubmit}
      >
        Eliminar
      </Button>
    </div>
  );
}

ButtonDelete.propTypes = {
  formattedToken: PropTypes.string.isRequired,
  id: PropTypes.string.isRequired,
};
