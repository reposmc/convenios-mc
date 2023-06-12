import axios from "axios";

const reportsApi = axios.create({
    baseURL: "/api/web/reports",
});

export default reportsApi;