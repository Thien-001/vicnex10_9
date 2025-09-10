import React, { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import SectionHeading from "../components/home/SectionHeading";
import FilterSidebar from "../components/product/FilterSidebar";
import ProductList from "../components/product/ProductList";
import RecentlyViewed from "../components/product/RecentlyViewed";
import SupportSection from "../components/product/SupportSection";
import Hotmonthproduct from "../components/product/Hotmonthproduct";
import RecomendProduct from "../components/product/RecomendProduct";
import CompareFloatingButton from "../components/product/CompareFloatingButton";
import CompareModal from "../components/product/CompareModal";
import CompareNoticeModal from "../components/product/CompareNoticeModal";

function ProductsPage() {
  const location = useLocation();
  const params = new URLSearchParams(location.search);
  const categorySlug = params.get("category");

  const [filters, setFilters] = useState({});
  const [page, setPage] = useState(1);
  const [categoryName, setCategoryName] = useState("");
  const [categoryId, setCategoryId] = useState(null);
  const [hotProducts, setHotProducts] = useState([]);
  const [compareProducts, setCompareProducts] = useState(() => {
    const saved = localStorage.getItem("compareProducts");
    return saved ? JSON.parse(saved) : [];
  });
  const [showCompare, setShowCompare] = useState(false);
  const [showNotice, setShowNotice] = useState(false);

  // Lấy tên danh mục khi có categorySlug
  useEffect(() => {
    if (!categorySlug) {
      setCategoryName("");
      setCategoryId(null);
      return;
    }
    fetch(`http://localhost:8000/api/categories?slug=${categorySlug}`)
      .then(res => res.json())
      .then(data => {
        const cat = Array.isArray(data) ? data[0] : data;
        setCategoryName(cat?.Name || "");
        setCategoryId(cat?.Categories_ID || null);
      });
  }, [categorySlug]);

  // Lấy sản phẩm hot tháng
  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_hot=1")
      .then(res => res.json())
      .then(data => setHotProducts(data.data || []));
  }, []);

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

  // So sánh sản phẩm
  const handleAddCompare = (product) => {
    if (
      compareProducts.length < 2 &&
      !compareProducts.some((p) => p.Product_ID === product.Product_ID)
    ) {
      const updated = [...compareProducts, product];
      setCompareProducts(updated);
      // Chỉ mở modal khi đã đủ 2 sản phẩm
      if (updated.length === 2) setShowCompare(true);
    }
  };

  const handleRemoveCompare = (productId) => {
    setCompareProducts(compareProducts.filter((p) => p.Product_ID !== productId));
  };
  const handleEmptyCompare = () => {
    alert("Bạn cần chọn đủ 2 sản phẩm để so sánh!");
  };

  // Map filters từ tiếng Việt sang key backend
  function mapFilters(filters, categoryId) {
    const mapped = {};
    if (categoryId) mapped.Categories_ID = categoryId; // Đúng tên tham số backend
    if (filters["Lọc theo loại sản phẩm"] && filters["Lọc theo loại sản phẩm"].length > 0) {
      mapped.category = filters["Lọc theo loại sản phẩm"][0];
    }
    if (filters["Lọc theo thương hiệu"] && filters["Lọc theo thương hiệu"].length > 0) {
      mapped.brand = filters["Lọc theo thương hiệu"].join(",");
    }
    if (filters["Lọc theo giá"] && filters["Lọc theo giá"].length > 0) {
      mapped.price = filters["Lọc theo giá"].join(",");
    }
    if (filters.keyword) mapped.keyword = filters.keyword;
    return mapped;
  }

  useEffect(() => {
    localStorage.setItem("compareProducts", JSON.stringify(compareProducts));
  }, [compareProducts]);

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
          <ProductList
            page={page}
            filters={mapFilters(filters, categoryId)}
            addToCart={addToCart}
            onAddCompare={handleAddCompare}
            compareProducts={compareProducts}
          />
        </div>
      </div>
      <RecomendProduct />
      <Hotmonthproduct products={hotProducts} />
      <RecentlyViewed />
      <SupportSection />
      <Footer />
      <CompareFloatingButton
        count={compareProducts.length}
        onClick={() => {
          if (compareProducts.length > 0) setShowCompare(true);
          else setShowNotice(true);
        }}
        onEmptyCompare={() => setShowNotice(true)}
      />
      {showCompare && compareProducts.length > 0 && (
        <CompareModal
          products={compareProducts}
          onClose={() => setShowCompare(false)}
          onRemove={handleRemoveCompare}
        />
      )}
      {showNotice && (
        <CompareNoticeModal
          message="Vui lòng chọn ít nhất 1 sản phẩm để so sánh!"
          onClose={() => setShowNotice(false)}
        />
      )}
    </>
  );
}

export default ProductsPage;