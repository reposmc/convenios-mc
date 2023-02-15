import axios from "axios";

const agreementApi = axios.create({
    baseURL: "/api/web/agreement",
});

export default agreementApi;
