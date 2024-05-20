import {
    GET_USER,
    REGISTER, SIGNIN,
    RESET_PASSWORD,
    UPDATE_PROFILE,
    NOTE_LIST,
    NOTE_UPDATE,
    NOTE_CREATE,
    NOTE_SHOW
} from "./actionsTypes";
import axios from 'axios';

const BASE_URL = 'http://127.0.0.1:8000/api/';

export const register = (newUser) => async (dispatch) => {
    try {
        const { data } = await axios.post(`${BASE_URL}register/`, newUser);
        console.log(data);
        dispatch({ type: REGISTER, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};

export const signin = (user) => async (dispatch) => {
    try {
        const { data } = await axios.post(`${BASE_URL}login`, user);

        localStorage.setItem("Token", JSON.stringify(data.access_token));
        dispatch({ type: SIGNIN, payload: data });
    } catch (error) {
        console.log(error);
        throw error;
    }
};

export const getUsers = (token) => async (dispatch) => {
    try {
        const { data } = await axios.get(`${BASE_URL}userProfile`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: GET_USER, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};

export const logout = (token) => async (dispatch) => {
    try {
        console.log(token)
        const { data } = await axios.get(`${BASE_URL}logout`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: GET_USER, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};

export const fPassword = (email) => async (dispatch) => {
    try {
        const { data } = await axios.post(`${BASE_URL}forget-password`, email);
        dispatch({ type: RESET_PASSWORD, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};

export const perfilUpdate = (update, token) => async (dispatch) => {
    try {

        const { data } = await axios.post(`${BASE_URL}profile-update`, update, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: UPDATE_PROFILE, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};

export const listNote = (token) => async (dispatch) => {
    try {

        const { data } = await axios.get(`${BASE_URL}notes_all`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: NOTE_LIST, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};

export const updateNote = (token,id, update) => async (dispatch) => {
    try {

        const { data } = await axios.put(`${BASE_URL}note-update/ ${id}`,update, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: NOTE_UPDATE, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};

export const DeleteNote = (token, id) => async (dispatch) => {
    try {

        const { data } = await axios.delete(`${BASE_URL}note-delete/ ${id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: NOTE_UPDATE, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};


export const CreateNote = (token, info) => async (dispatch) => {
    try {

        const { data } = await axios.post(`${BASE_URL}notes_creacte/ `, info, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: NOTE_CREATE, payload: data });
    } catch (error) {
        console.log('Error  : ', error);
        throw error;
    }
};

export const ShowNote = (token, id) => async (dispatch) => {
    try {

        const { data } = await axios.get(`${BASE_URL}note-detail/ ${id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        dispatch({ type: NOTE_SHOW, payload: data });
    } catch (error) {
        console.log('Error: ', error);
        throw error;
    }
};


