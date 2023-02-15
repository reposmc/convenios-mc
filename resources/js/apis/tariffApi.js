import axios from "axios";

const tariffApi = axios.create({
    baseURL: "/api/web/tariff",
});

export default tariffApi;