import axios from "axios";

const typeApi = axios.create({
    baseURL: "/api/web/type",
});

export default typeApi;