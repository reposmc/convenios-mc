import axios from "axios";

const exonerationApi = axios.create({
    baseURL: "/api/web/exoneration",
});

export default exonerationApi;
