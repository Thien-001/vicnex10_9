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
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Lấy user đăng nhập từ localStorage
  const user = JSON.parse(localStorage.getItem("user"));

  // SỬA LẠI FETCH PRODUCT DETAIL
  useEffect(() => {
    const fetchProductDetail = async () => {
      try {
        setLoading(true);
        setError(null);
        
        console.log("🔍 Fetching product detail for slug:", slug);
        
        const response = await fetch(`http://localhost:8000/api/products/slug/${slug}`);
        
        if (!response.ok) {
          throw new Error(`HTTP ${response.status}: Product not found`);
        }
        
        const data = await response.json();
        console.log("📦 Product detail received:", data);
        
        // Validate product data
        if (!data || !data.Product_ID) {
          throw new Error("Invalid product data received");
        }
        
        // Debug variants
        console.log("🔍 Product variants:", {
          hasVariants: !!data.variants,
          variantsType: typeof data.variants,
          variantsLength: data.variants?.length || 0,
          variants: data.variants,
          firstVariant: data.variants?.[0]
        });
        
        // Validate variants data
        if (data.variants && Array.isArray(data.variants)) {
          data.variants.forEach((variant, index) => {
            console.log(`Variant ${index}:`, {
              id: variant.Variant_ID,
              name: variant.Variant_name,
              quantity: variant.Quantity,
              price: variant.Price,
              sku: variant.SKU
            });
          });
        }
        
        setProduct(data);
        
      } catch (err) {
        console.error("❌ Error fetching product detail:", err);
        setError(err.message);
        setProduct(null);
      } finally {
        setLoading(false);
      }
    };

    if (slug) {
      fetchProductDetail();
    }
  }, [slug]);

  useEffect(() => {
    const fetchHotProducts = async () => {
      try {
        const response = await fetch("http://localhost:8000/api/products?is_hot=1");
        const data = await response.json();
        setHotProducts(data.data || []);
      } catch (err) {
        console.error("❌ Error fetching hot products:", err);
        setHotProducts([]);
      }
    };

    fetchHotProducts();
  }, []);

  // SỬA LẠI RECENTLY VIEWED LOGIC
  useEffect(() => {
    if (product && product.Product_ID) {
      try {
        let viewed = JSON.parse(localStorage.getItem("recentlyViewed") || "[]");
        
        // Đảm bảo viewed là array
        if (!Array.isArray(viewed)) {
          viewed = [];
        }
        
        // Remove existing product if present
        viewed = viewed.filter(p => p && p.Product_ID !== product.Product_ID);
        
        // Add current product to beginning
        viewed.unshift(product);
        
        // Keep only last 12 items
        if (viewed.length > 12) {
          viewed = viewed.slice(0, 12);
        }
        
        localStorage.setItem("recentlyViewed", JSON.stringify(viewed));
        console.log("💾 Recently viewed updated:", viewed.length, "products");
        
      } catch (err) {
        console.error("❌ Error updating recently viewed:", err);
      }
    }
  }, [product]);

  useEffect(() => {
    window.scrollTo(0, 0);
  }, [slug]); // Thêm slug dependency

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

  // LOADING STATE
  if (loading) {
    return (
      <div>
        <Header />
        <div style={{ 
          display: 'flex', 
          justifyContent: 'center', 
          alignItems: 'center', 
          height: '400px',
          fontSize: '18px',
          color: '#666'
        }}>
          🔄 Đang tải thông tin sản phẩm...
        </div>
        <Footer />
      </div>
    );
  }

  // ERROR STATE
  if (error || !product) {
    return (
      <div>
        <Header />
        <div style={{ 
          display: 'flex', 
          flexDirection: 'column',
          justifyContent: 'center', 
          alignItems: 'center', 
          height: '400px',
          fontSize: '18px',
          color: '#dc3545'
        }}>
          <h2>❌ Không tìm thấy sản phẩm</h2>
          <p>{error || "Sản phẩm không tồn tại hoặc đã bị xóa"}</p>
          <button 
            onClick={() => window.history.back()}
            style={{
              padding: '10px 20px',
              backgroundColor: '#007bff',
              color: 'white',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer',
              marginTop: '16px'
            }}
          >
            ← Quay lại
          </button>
        </div>
        <Footer />
      </div>
    );
  }

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

      <Hotmonthproduct 
        products={hotProducts} 
        onAddCompare={handleAddCompare}
        compareProducts={compareProducts}
      />
      <RecentlyViewed 
        onAddCompare={handleAddCompare}
        compareProducts={compareProducts}
      />
      <RecomendProduct 
        onAddCompare={handleAddCompare}
        compareProducts={compareProducts}
      />
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
