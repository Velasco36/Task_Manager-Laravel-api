import { Routes, Route } from "react-router-dom";
import Register from "./views/Register/Register";
import Login from "./views/Login/Login";

function App() {
  return (
    // Asegúrate de retornar JSX aquí
    <Routes>
      <Route path="/register" element={<Register />} />
      <Route path="/login" element={<Login />} />
    </Routes>
  );
}

export default App;
