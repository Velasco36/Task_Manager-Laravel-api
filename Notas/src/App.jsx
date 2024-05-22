import { Routes, Route } from "react-router-dom";
import Layout from "./components/layout/layout";
import Register from "./views/Register/Register";
import Login from "./views/Login/Login";
import Index from "./views/Home/Index";
import ForgotPassword from "./views/ForgotPassword/ForgotPassword";
import Profile from "./views/User/Profile";
import NoteList from "./views/Note/List/NoteList";
import NoteShow from "./views/Note/Show/NoteShow";

function App() {
  return (
    <Routes>
      <Route element={<Layout />}>
        <Route path="/" element={<Index />} />
        <Route path="/profile" element={<Profile />} />
        <Route path="/list" element={<NoteList />} />
        <Route path="/show/:id" element={<NoteShow />} />
      </Route>
      <Route path="/register" element={<Register />} />
      <Route path="/login" element={<Login />} />
      <Route path="/forgot-password" element={<ForgotPassword />} />

      <Route path="*" element={<Index />} />
    </Routes>
  );
}

export default App;
