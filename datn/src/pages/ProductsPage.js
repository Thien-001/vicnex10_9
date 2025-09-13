import React, { useEffect, useState, useCallback, useRef } from "react";
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
  const [categoryId, setCategoryId] = useState(null);
  const [hotProducts, setHotProducts] = useState([]);
  const categoryIdRef = useRef(null);

  // Stabilize setFilters function
  const handleFiltersChange = useCallback((newFilters) => {
    console.log('📥 ProductsPage: Received filters from FilterSidebar:', newFilters);
    setFilters(newFilters);
  }, []);

  // Lấy tên danh mục khi có categorySlug
  useEffect(() => {
    if (!categorySlug) {
      setCategoryName("");
      setCategoryId(null);
      categoryIdRef.current = null;
      return;
    }
    fetch(`http://localhost:8000/api/categories?slug=${categorySlug}`)
      .then(res => res.json())
      .then(data => {
        const cat = Array.isArray(data) ? data[0] : data;
        setCategoryName(cat?.Name || "");
        setCategoryId(cat?.Categories_ID || null);
        categoryIdRef.current = cat?.Categories_ID || null;
      });
  }, [categorySlug]);

  // Lấy sản phẩm hot tháng
  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_hot=1")
      .then(res => res.json())
      .then(data => setHotProducts(data.data || []));
  }, []);

  // Debug: Log khi filters thay đổi và update mappedFilters
  useEffect(() => {
    console.log('🎯 ProductsPage: Filters state changed:', filters);
    const newMappedFilters = mapFilters(filters, categoryIdRef.current);
    setMappedFilters(newMappedFilters);
  }, [filters, mapFilters]);

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

  // Map filters từ tiếng Việt sang key backend API
  const mapFilters = useCallback((filters, categoryId) => {
    const mapped = {};
    
    console.log('🔍 Mapping filters:', { filters, categoryId }); // Debug log
    
    // Map các filter cơ bản
    if (categoryId) {
      mapped.Categories_ID = categoryId;
    }
    
    // Map thương hiệu
    if (filters["Lọc theo thương hiệu"] && filters["Lọc theo thương hiệu"].length > 0) {
      mapped.brand = filters["Lọc theo thương hiệu"].join(",");
    }
    
    // Map giá
    if (filters["Lọc theo giá"] && filters["Lọc theo giá"].length > 0) {
      mapped.price = filters["Lọc theo giá"].join(",");
    }
    
    console.log('✅ Mapped filters:', mapped); // Debug log
    return mapped;
  }, []); // Bỏ categoryId khỏi dependency để tránh re-render

  return (
    <>
      <Header />
      <BreadcrumbNav />
      <SectionHeading
        title={categoryName ? categoryName.toUpperCase() : "TẤT CẢ SẢN PHẨM"}
        subtitle="Tìm kiếm sản phẩm dễ dàng với bộ lọc thông minh!"
      />
      <div className="layout">
        <FilterSidebar setFilters={handleFiltersChange} filters={filters} />
        <div className="product-list-container">
          <ProductSortDropdown sort={sort} setSort={setSort} />
          <ProductList
            page={page}
            filters={mappedFilters}
            sort={sort}
            addToCart={addToCart}
          />
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