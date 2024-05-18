import axios from "axios";

export const baseURL = "http//localhost:3001/";

export const axiosFetch = axios.create({
    baseURL: baseURL,
    timeout: 10000,
});
