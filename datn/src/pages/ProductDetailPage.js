import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import Footer from "../components/home/Footer";
import Header from "../components/home/Header";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import ProductInfo from "../components/productdetail/ProductInfo";
import RecentlyViewed from "../components/product/RecentlyViewed";
import ProductOptions from "../components/productdetail/ProductOptions";
// import ProductActions from "../components/productdetail/ProductActions";
import CustomerSupport from "../components/productdetail/CustomerSupport";
import RecomendProduct from "../components/product/RecomendProduct";
import ShippingFeatures from "../components/productdetail/ShippingFeatures";
import ProductImageGallery from "../components/productdetail/ProductImageGallery";
import QuickSupportSection from "../components/productdetail/QuickSupportSection";
import ProductDetailSection from "../components/productdetail/ProductDetailSection";
import Hotmonthproduct from "../components/product/Hotmonthproduct";
import ProductComments from "../components/productdetail/ProductComments";
import ProductRating from "../components/productdetail/ProductRating";
import CompareModal from "../components/product/CompareModal";
import CompareFloatingButton from "../components/product/CompareFloatingButton";

function ProductDetailPage() {
  const { slug } = useParams();
  const [product, setProduct] = useState(null);
  const [hotProducts, setHotProducts] = useState([]);
  const [compareProducts, setCompareProducts] = useState([]);
  const [showCompare, setShowCompare] = useState(false);

  // Lấy user đăng nhập từ localStorage
  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    fetch(`http://localhost:8000/api/products/slug/${slug}`)
      .then(res => {
        if (!res.ok) throw new Error("Product not found");
        return res.json();
      })
      .then(data => setProduct(data))
      .catch(() => setProduct(null));
  }, [slug]);

  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_hot=1")
      .then(res => res.json())
      .then(data => {
        setHotProducts(data.data);
      })
      .catch(() => setHotProducts([]));
  }, []);

  useEffect(() => {
    if (product) {
      let viewed = JSON.parse(localStorage.getItem("recentlyViewed") || "[]");
      viewed = viewed.filter(p => p.Product_ID !== product.Product_ID);
      viewed.unshift(product);
      if (viewed.length > 12) viewed = viewed.slice(0, 12);
      localStorage.setItem("recentlyViewed", JSON.stringify(viewed));
    }
  }, [product]);

  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  // So sánh sản phẩm
  const handleAddCompare = (product) => {
    if (
      compareProducts.length < 2 &&
      !compareProducts.some((p) => p.Product_ID === product.Product_ID)
    ) {
      const updated = [...compareProducts, product];
      setCompareProducts(updated);
      if (updated.length === 2) setShowCompare(true); // Mở modal khi đủ 2 sản phẩm
    }
  };

  const handleRemoveCompare = (productId) => {
    setCompareProducts(compareProducts.filter((p) => p.Product_ID !== productId));
  };

  const handleEmptyCompare = () => {
    alert("Bạn cần chọn đủ 2 sản phẩm để so sánh!");
  };

  return (
    <div>
      <Header />
      <BreadcrumbNav category={product?.category} product={product} />
      <div className="product-container">
        <div className="product-image">
          <ProductImageGallery product={product} />
          <ShippingFeatures />
        </div>
        <div className="product-details">
          <ProductInfo product={product} />
          <ProductOptions product={product} />
          {/* <ProductActions product={product} /> */}
        </div>
      </div>
      <ProductDetailSection product={product} />

      <ProductRating productId={product?.Product_ID} user={user} />
      <ProductComments productId={product?.Product_ID} user={user} />

      <Hotmonthproduct products={hotProducts} />
      <RecentlyViewed />
      <RecomendProduct />
      <CustomerSupport />
      <QuickSupportSection />
      <Footer />

      {/* Nút nổi và modal so sánh sản phẩm */}
      <CompareFloatingButton
        count={compareProducts.length}
        onClick={() => {
          if (compareProducts.length === 2) setShowCompare(true);
          else handleEmptyCompare();
        }}
        onEmptyCompare={handleEmptyCompare}
      />
      {showCompare && compareProducts.length === 2 && (
        <CompareModal
          products={compareProducts}
          onClose={() => setShowCompare(false)}
          onRemove={handleRemoveCompare}
        />
      )}

      
    </div>
  );
}

export default ProductDetailPage;
