import axios from "axios";

const dependenceApi = axios.create({
    baseURL: "/api/web/dependence",
});

export default dependenceApi;
