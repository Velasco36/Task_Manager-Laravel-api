import { GET_USER, REGISTER, SIGNIN } from "../actions/actionsTypes"



const initialState = {
    newUser: {},
    user: {}
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
        default:
            return {
                ...state
            }
    }
}
