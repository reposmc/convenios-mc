import axios from "axios";

const directionApi = axios.create({
    baseURL: "/api/web/direction",
});

export default directionApi;
