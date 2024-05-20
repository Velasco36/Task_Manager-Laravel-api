import Button from "@mui/material/Button";
import NoteList from "../Note/List/NoteList";
const Index = () => {
  return (
    <div className="bg-blue-50 flex flex-col  items-center justify-between px-4 py-6">
      <div>
        <p className="text-3xl text-start">Tablero de tareas ordenar por:</p>
        <div className="flex space-x-2 justify-center mt-5">
          <Button
            variant="outlined"
            className="rounded-full px-4 py-2 hover:bg-gray-200"
          >
            Título
          </Button>
          <Button
            variant="contained"
            color="primary"
            className="rounded-full px-4 py-2 hover:bg-gray-200"
          >
            Fecha
          </Button>
        </div>
      </div>

      {/* Note List */}
      <NoteList />
    </div>
  );
};

export default Index;
