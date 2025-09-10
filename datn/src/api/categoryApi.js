import axios from "axios";

export const fetchCategories = () => {
  return axios.get("http://localhost:8000/api/categories");
};
