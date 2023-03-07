import axios from "axios";

const instece = axios.create({
  baseURL: "/backend",
});

export default instece;
