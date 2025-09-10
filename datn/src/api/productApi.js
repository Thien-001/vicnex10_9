// src/api/productApi.js
import axios from "axios";

const API_BASE_URL = "http://localhost:8000/api";

// Gọi danh sách sản phẩm
export const fetchProducts = (page = 1, filters = {}) => {
  const params = { page, ...filters };
  return axios.get(`${API_BASE_URL}/products`, { params });
};

// Gọi chi tiết sản phẩm theo ID
export const fetchProductById = (id) => {
  return axios.get(`${API_BASE_URL}/products/${id}`);
};

// Gọi chi tiết sản phẩm theo slug (sửa lại đường dẫn cho đúng với backend)
export const fetchProductBySlug = (slug) => {
  return axios.get(`${API_BASE_URL}/products/slug/${slug}`);
};



