import { useState } from "react";
import Button from "@mui/material/Button";
import NoteList from "../Note/List/NoteList";

const Index = () => {
  const [filter, setFilter] = useState("date");

  const handleFilterChange = (filterType) => {
    setFilter(filterType);
  };

  return (
    <div className="bg-blue-50 flex flex-col items-center justify-between px-4 py-6">
      <div>
        <p className="text-3xl text-start">Tablero de tareas ordenar por:</p>
        <div className="flex space-x-2 justify-center mt-5">
          <Button
            variant={filter === "title" ? "contained" : "outlined"}
            sx={{ borderRadius: "9999px", px: 4, py: 2 }}
            onClick={() => handleFilterChange("title")}
          >
            Título
          </Button>
          <Button
            variant={filter === "date" ? "contained" : "outlined"}
            color="primary"
            sx={{ borderRadius: "9999px", px: 4, py: 2 }}
            onClick={() => handleFilterChange("date")}
          >
            Fecha
          </Button>
        </div>
      </div>

      {/* Note List */}
      <NoteList filter={filter} />
    </div>
  );
};

export default Index;
