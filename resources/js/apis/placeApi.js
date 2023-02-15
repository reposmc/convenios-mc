import axios from "axios";

const placeApi = axios.create({
    baseURL: "/api/web/place",
});

export default placeApi;