import { Routes, Route } from "react-router-dom";
import Layout from "./components/layout/layout";
import Register from "./views/Register/Register";
import Login from "./views/Login/Login";
import Index from "./views/Home/Index";
import ForgotPassword from "./views/ForgotPassword/ForgotPassword";
import Profile from "./views/User/Profile";

function App() {
  return (
    <Routes>
      <Route element={<Layout />}>

        <Route path="/" element={<Index />} />
        <Route path="/profile" element={<Profile />} />
      </Route>
      <Route path="/register" element={<Register />} />
      <Route path="/login" element={<Login />} />
      <Route path="/forgot-password" element={<ForgotPassword />} />
    </Routes>
  );
}

export default App;
