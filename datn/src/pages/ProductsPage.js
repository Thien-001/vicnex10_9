import React, { useEffect, useState, useCallback } from "react";
import { useLocation } from "react-router-dom";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import SectionHeading from "../components/home/SectionHeading";
import FilterSidebar from "../components/product/FilterSidebar";
import ProductList from "../components/product/ProductList";
import ProductSortDropdown from "../components/product/ProductSortDropdown";
import RecentlyViewed from "../components/product/RecentlyViewed";
import SupportSection from "../components/product/SupportSection";
import Hotmonthproduct from "../components/product/Hotmonthproduct";
import RecomendProduct from "../components/product/RecomendProduct";

function ProductsPage() {
  const location = useLocation();
  const params = new URLSearchParams(location.search);
  const categorySlug = params.get("category");

  const [filters, setFilters] = useState({});
  const [mappedFilters, setMappedFilters] = useState({});
  const [page, setPage] = useState(1);
  const [sort, setSort] = useState("default");
  const [categoryName, setCategoryName] = useState("");
  const [categoryId, setCategoryId] = useState(undefined);
  const [hotProducts, setHotProducts] = useState([]);
  const [products, setProducts] = useState([]);
  const [loadingProducts, setLoadingProducts] = useState(true);

  // Lấy tên danh mục và id khi có categorySlug
  useEffect(() => {
    setCategoryId(undefined); // Đánh dấu chưa xác định
    setCategoryName("");
    if (!categorySlug) {
      setCategoryId(null); // null nghĩa là tất cả sản phẩm
      setCategoryName("");
      return;
    }
    fetch(`http://localhost:8000/api/categories?slug=${categorySlug}`)
      .then(res => res.json())
      .then(data => {
        const cat = Array.isArray(data) ? data[0] : data;
        setCategoryId(cat?.Categories_ID ?? null);
        setCategoryName(cat?.Name || "");
      });
  }, [categorySlug]);

  // Lấy sản phẩm hot tháng
  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_hot=1")
      .then(res => res.json())
      .then(data => setHotProducts(data.data || []));
  }, []);

  // Map filters từ tiếng Việt sang key backend API
  const mapFilters = useCallback((filters, categoryId) => {
    const mapped = {};
    if (categoryId) mapped.Categories_ID = categoryId;
    if (filters["Lọc theo thương hiệu"] && filters["Lọc theo thương hiệu"].length > 0) {
      mapped.brand = filters["Lọc theo thương hiệu"].join(",");
    }
    if (filters["Lọc theo giá"] && filters["Lọc theo giá"].length > 0) {
      mapped.price = filters["Lọc theo giá"].join(",");
    }
    return mapped;
  }, []);

  // Cập nhật mappedFilters khi filters hoặc categoryId thay đổi
  useEffect(() => {
    if (categoryId === undefined) return;
    setMappedFilters(mapFilters(filters, categoryId));
  }, [filters, mapFilters, categoryId]);

  // Lấy sản phẩm theo danh mục và bộ lọc
  useEffect(() => {
    if (categoryId === undefined) return; // Chưa xác định danh mục, không gọi API
    setLoadingProducts(true);
    let url = "http://localhost:8000/api/products?";
    const params = [];
    if (categoryId) params.push(`Categories_ID=${categoryId}`);
    if (mappedFilters.brand) params.push(`brand=${mappedFilters.brand}`);
    if (mappedFilters.price) params.push(`price=${mappedFilters.price}`);
    if (sort && sort !== "default") params.push(`sort=${sort}`);
    if (page) params.push(`page=${page}`);
    url += params.join("&");

    fetch(url)
      .then(res => res.json())
      .then(data => {
        setProducts(data.data || []);
        setLoadingProducts(false);
      })
      .catch(() => setLoadingProducts(false));
  }, [mappedFilters, sort, page, categoryId]);

  // Thêm vào giỏ hàng
  const addToCart = (product) => {
    const cart = JSON.parse(localStorage.getItem("cartItems") || "[]");
    const exist = cart.find(item => item.Product_ID === product.Product_ID);
    let updated;
    if (exist) {
      updated = cart.map(item =>
        item.Product_ID === product.Product_ID
          ? { ...item, quantity: item.quantity + 1 }
          : item
      );
    } else {
      updated = [...cart, { ...product, quantity: 1 }];
    }
    localStorage.setItem("cartItems", JSON.stringify(updated));
    window.dispatchEvent(new Event("cartUpdated"));
  };

  return (
    <>
      <Header />
      <BreadcrumbNav />
      <SectionHeading
        title={categoryName ? categoryName.toUpperCase() : "TẤT CẢ SẢN PHẨM"}
        subtitle="Tìm kiếm sản phẩm dễ dàng với bộ lọc thông minh!"
      />
      <div className="layout">
        <FilterSidebar setFilters={setFilters} filters={filters} />
        <div className="product-list-container">
          <ProductSortDropdown sort={sort} setSort={setSort} />
          {/* Chỉ render ProductList khi đã xác định xong danh mục và không loading */}
          {categoryId !== undefined && !loadingProducts ? (
            <ProductList
              page={page}
              filters={mappedFilters}
              sort={sort}
              addToCart={addToCart}
              products={products}
            />
          ) : (
            <div style={{ textAlign: "center", padding: "40px 0" }}>
              Đang tải sản phẩm...
            </div>
          )}
        </div>
      </div>
      <RecomendProduct />
      <Hotmonthproduct products={hotProducts} />
      <RecentlyViewed />
      <SupportSection />
      <Footer />
    </>
  );
}

export default ProductsPage;