import axios from "axios";

const exonerationDetailApi = axios.create({
    baseURL: "/api/web/exonerationDetail",
});

export default exonerationDetailApi;