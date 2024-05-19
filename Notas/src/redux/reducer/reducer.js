import { GET_USER, REGISTER, SIGNIN, RESET_PASSWORD, UPDATE_PROFILE } from "../actions/actionsTypes"



const initialState = {
    newUser: {},
    user: {},
    fpassword: {},
    updateProfile: {}
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
        default:
            return {
                ...state
            }
    }
}
