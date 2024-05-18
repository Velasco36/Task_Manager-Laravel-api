import { createStore, applyMiddleware, compose } from "redux";
import { thunk } from "redux-thunk"; // Importar redux-thunk usando una importación con nombre
import { reducer } from "../reducer/reducer";

// Configurar Redux DevTools Extension
const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;

// Crear el store con el reducer y el middleware thunk
const store = createStore(
    reducer,
    composeEnhancers(applyMiddleware(thunk))
);

export default store;
