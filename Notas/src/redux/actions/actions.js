import { GET_USER, REGISTER, SIGNIN } from "./actionsTypes"
import axios from 'axios'


export const register = (newUser) => async (dispatch) => {
    try {
        const { data } = await axios.post('http://127.0.0.1:8000/api/register/', newUser)
        console.log(data)
        dispatch({
            type: REGISTER,
            payload: data,
        });
    } catch (error) {
        console.log('Error: ', error)
        throw error
    }
}

export const signin = (user) => async (dispatch) => {
    try {
        const { data } = await axios.post('http://localhost:3001/auth/signin', user)
        localStorage.setItem("usuario", JSON.stringify(data.data.foundUser));

        dispatch({
            type: SIGNIN,
            payload: data,
        });
    } catch (error) {
        console.log('Error: ', error)
        throw error
    }
}
export const getUsers = () => async (dispatch) => {
    try {
        const { data } = await axios.get('http://localhost:3001/user/get_alluser')

        dispatch({
            type: GET_USER,
            payload: data,
        });
    } catch (error) {
        console.log('Error: ', error)
        throw error
    }
}
