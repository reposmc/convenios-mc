import axios from "axios";

const entityApi = axios.create({
    baseURL: "/api/web/entity",
});

export default entityApi;
