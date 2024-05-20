
import {
    GET_USER,
    REGISTER,
    SIGNIN,
    RESET_PASSWORD,
    UPDATE_PROFILE,
    NOTE_LIST,
    NOTE_UPDATE,
    NOTE_SHOW
} from "../actions/actionsTypes"



const initialState = {
    newUser: {},
    user: {},
    fpassword: {},
    updateProfile: {},
    list: {},
    updateNote: {},
    showNote: {}
}

export const reducer = (state = initialState, { type, payload }) => {


    switch (type) {
        case REGISTER: {
            return {
                ...state,
                newUser: payload
            }
        }

        case SIGNIN: {
            return {
                ...state,
                user: payload
            }
        }
        case GET_USER: {
            return {
                ...state,
                user: payload
            }
        }
        case RESET_PASSWORD: {
            return {
                ...state,
                fpassword: payload
            }
        }

        case UPDATE_PROFILE: {
            return {
                ...state,
                updateProfile: payload
            }
        }
        case NOTE_LIST: {
            return {
                ...state,
                list: payload
            }
        }
        case NOTE_UPDATE: {
            return {
                ...state,
                updateNote: payload
            }
        }
        case NOTE_SHOW: {
            return {
                ...state,
                showNote: payload
            }
        }
        default:
            return {
                ...state
            }
    }
}
