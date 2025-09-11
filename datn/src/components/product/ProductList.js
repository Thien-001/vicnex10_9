import React, { useEffect, useState } from "react";
import { fetchProducts } from "../../api/productApi";
import { useNavigate } from "react-router-dom";
import ProductOptions from "../productdetail/ProductOptions";

// Component popup chọn biến thể với option từng thuộc tính
function VariantSelector({ product, onClose, onSelectVariant }) {
  const [selectedVariant, setSelectedVariant] = useState(null);

  if (!product.variants || product.variants.length === 0) return null;

  return (
    <div style={{
      position: "fixed",
      inset: 0,
      background: "rgba(0,0,0,0.45)",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      zIndex: 9999,
      animation: "fadeIn 0.2s"
    }}>
      <div style={{
        background: "#fff",
        borderRadius: 16,
        boxShadow: "0 8px 32px rgba(0,0,0,0.18)",
        minWidth: 360,
        maxWidth: 420,
        padding: "32px 28px 24px 28px",
        position: "relative",
        animation: "popupScale 0.2s"
      }}>
        <button
          onClick={onClose}
          style={{
            position: "absolute",
            top: 16,
            right: 16,
            background: "transparent",
            border: "none",
            fontSize: 22,
            color: "#888",
            cursor: "pointer",
            fontWeight: 700,
          }}
          aria-label="Đóng"
        >
          ×
        </button>
        <h2 style={{
          marginBottom: 18,
          fontWeight: 700,
          fontSize: 22,
          color: "#0154b9",
          textAlign: "center"
        }}>
          Chọn biến thể cho <span style={{color: "#222"}}>{product.Name}</span>
        </h2>
        <ProductOptions
          variants={product.variants}
          onVariantChange={setSelectedVariant}
        />
        {selectedVariant && (
          <div style={{
            margin: "18px 0 10px 0",
            padding: "12px",
            background: "#f6f8fa",
            borderRadius: 8,
            fontSize: 15,
            boxShadow: "0 2px 8px rgba(0,0,0,0.06)"
          }}>
            <div style={{fontWeight: 600, fontSize: 16, marginBottom: 2}}>
              {selectedVariant.Variant_name}
            </div>
            <div style={{color: "#0154b9", fontWeight: 500}}>
              Giá: {Number(selectedVariant.Discount_price || selectedVariant.Price).toLocaleString("vi-VN")}₫
            </div>
            <div style={{color: "#666"}}>
              Kho: {selectedVariant.Quantity}
            </div>
            {selectedVariant.Quantity < 1 && (
              <div style={{color: "red", fontWeight: 600, marginTop: 8}}>
                Sản phẩm đã hết
              </div>
            )}
          </div>
        )}
        <button
          onClick={() => {
            if (selectedVariant && selectedVariant.Quantity > 0) onSelectVariant(selectedVariant);
          }}
          disabled={!selectedVariant || selectedVariant.Quantity < 1}
          style={{
            width: "100%",
            background: selectedVariant && selectedVariant.Quantity > 0 ? "#0154b9" : "#ccc",
            color: "#fff",
            border: "none",
            borderRadius: 8,
            padding: "12px 0",
            fontWeight: 700,
            fontSize: 17,
            cursor: selectedVariant && selectedVariant.Quantity > 0 ? "pointer" : "not-allowed",
            marginBottom: 8,
            transition: "background 0.15s"
          }}
        >
          Thêm vào giỏ hàng
        </button>
      </div>
      {/* Hiệu ứng CSS */}
      <style>
        {`
          @keyframes fadeIn {
            from { opacity: 0 }
            to { opacity: 1 }
          }
          @keyframes popupScale {
            from { transform: scale(0.95); opacity: 0 }
            to { transform: scale(1); opacity: 1 }
          }
          .options button {
            margin: 0 6px 6px 0;
            padding: 7px 16px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: #f8fafd;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
          }
          .options button.selected {
            background: #0154b9;
            color: #fff;
            border-color: #0154b9;
          }
          .options p {
            margin: 10px 0 6px 0;
            font-weight: 600;
            color: #0154b9;
          }
        `}
      </style>
    </div>
  );
}

function ProductList({ page, filters, onAddCompare, compareProducts = [], sort }) {
  const [products, setProducts] = useState([]);
  const [meta, setMeta] = useState({});
  const [showVariantPopup, setShowVariantPopup] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetchProducts(page, filters);
        let data = res.data.data;
        if (page === 1) {
          data = [...data].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
        setProducts(data);
        setMeta({
          currentPage: res.data.current_page,
          lastPage: res.data.last_page,
        });
      } catch (err) {
        console.error("Lỗi gọi API:", err);
      }
    };

    fetchData();
  }, [page, filters]);

  const handleProductClick = (slug) => {
    navigate(`/product/${slug}`);
  };

  // Lọc theo ID danh mục
  let filteredProducts = products;
  if (filters && filters.category_id) {
    filteredProducts = filteredProducts.filter(product => {
      return product.Categories_ID === Number(filters.category_id);
    });
  }

  // Lọc theo thương hiệu
  if (filters && filters.brand) {
    const brandArr = filters.brand.split(",").map((b) => b.trim().toLowerCase());
    filteredProducts = filteredProducts.filter(
      (product) =>
        product.Brand &&
        brandArr.includes(product.Brand.toLowerCase())
    );
  }

  // Lọc theo giá
  if (filters && filters.price) {
    const priceArr = filters.price.split(",");
    filteredProducts = filteredProducts.filter((product) => {
      return priceArr.some((priceRange) => {
        priceRange = priceRange.trim();
        const price = Number(product.Discount_price || product.Price);
        if (priceRange === "Dưới 500.000đ") return price < 500000;
        if (priceRange === "500.000đ - 1.000.000đ") return price >= 500000 && price <= 1000000;
        if (priceRange === "1.000.000đ - 2.000.000đ") return price > 1000000 && price <= 2000000;
        if (priceRange === "Trên 2.000.000đ") return price > 2000000;
        return true;
      });
    });
  }

  // Lọc theo các biến thể khác (size, color, gender...)
  Object.keys(filters || {}).forEach((key) => {
    if (
      !["category_id", "brand", "price"].includes(key)
    ) {
      const filterValues = filters[key].split(",").map((v) => v.trim().toLowerCase());
      filteredProducts = filteredProducts.filter((product) => {
        if (product[key]) {
          if (Array.isArray(product[key])) {
            return product[key].some((val) =>
              filterValues.includes(String(val).toLowerCase())
            );
          }
          return filterValues.includes(String(product[key]).toLowerCase());
        }
        if (product.variants && Array.isArray(product.variants)) {
          return product.variants.some((variant) =>
            variant.title === key &&
            filterValues.includes(String(variant.value).toLowerCase())
          );
        }
        return false;
      });
    }
  });

  // Sắp xếp sản phẩm theo lựa chọn sort (nếu có)
  let sortedProducts = [...filteredProducts];
  if (sort === "price-asc") {
    sortedProducts.sort((a, b) => (a.Discount_price || a.Price) - (b.Discount_price || b.Price));
  } else if (sort === "price-desc") {
    sortedProducts.sort((a, b) => (b.Discount_price || b.Price) - (a.Discount_price || a.Price));
  } else if (sort === "bestseller") {
    sortedProducts.sort((a, b) => (b.is_best_seller || 0) - (a.is_best_seller || 0));
  }
  // Nếu không có sort, mặc định đã là newest lên đầu do đã sort khi fetch

  if (!sortedProducts || sortedProducts.length === 0) {
    return <p className="product-list-empty">Không tìm thấy sản phẩm phù hợp.</p>;
  }

  // Hàm thêm vào giỏ hàng
  const addToCart = (product, selectedVariant = null) => {
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    let productToAdd = { ...product };
    if (selectedVariant) productToAdd.selectedVariant = selectedVariant;
    const exist = cart.find(
      (item) =>
        item.Product_ID === product.Product_ID &&
        (!selectedVariant || JSON.stringify(item.selectedVariant) === JSON.stringify(selectedVariant))
    );
    let updated;
    if (exist) {
      updated = cart.map((item) =>
        item.Product_ID === product.Product_ID &&
        (!selectedVariant || JSON.stringify(item.selectedVariant) === JSON.stringify(selectedVariant))
          ? { ...item, quantity: (item.quantity || 1) + 1 }
          : item
      );
    } else {
      updated = [...cart, { ...productToAdd, quantity: 1 }];
    }
    localStorage.setItem("cart", JSON.stringify(updated));
    window.dispatchEvent(new Event("cartUpdated"));
  };

  // Xử lý khi bấm nút thêm vào giỏ hàng
  const handleAddToCart = (product) => {
    if (product.variants && product.variants.length > 0) {
      setSelectedProduct(product);
      setShowVariantPopup(true);
    } else {
      addToCart(product);
    }
  };

  return (
    <main className="product-list-main">
      {sortedProducts.map((product) => (
        <div
          className="product-list-item"
          key={product.Product_ID}
          onClick={() => handleProductClick(product.slug)}
        >
          {/* Trái tim yêu thích */}
          <div className="product-list-fav tooltip-parent">
            ♡
            <span className="tooltip">Yêu thích</span>
          </div>
          {/* Ribbon trạng thái */}
          <div className="product-list-ribbons">
            {product.is_hot && (
              <div className="product-list-ribbon hot">HOT</div>
            )}
            {product.is_best_seller && (
              <div className="product-list-ribbon best">BEST</div>
            )}
            {product.is_featured && (
              <div className="product-list-ribbon featured">FEATURED</div>
            )}
          </div>
          {/* Ảnh sản phẩm */}
          <img
            src={`/${product.Image}`}
            alt={product.Name}
            className="product-list-image"
          />
          {/* Nội dung sản phẩm */}
          <div className="product-list-info">
            <h3 className="product-list-name">{product.Name}</h3>
            <div className="product-list-category">
              {product?.category?.Name || ""}
            </div>
            <div className="product-list-brand">
              {product.Brand || ""}
            </div>
            <div className="product-list-price">
              {product.Discount_price ? (
                <>
                  <span className="product-list-price-sale">
                    {Number(product.Discount_price).toLocaleString("vi-VN")}₫
                  </span>
                  <del className="product-list-price-old">
                    {Number(product.Price).toLocaleString("vi-VN")}₫
                  </del>
                </>
              ) : (
                <span>{Number(product.Price).toLocaleString("vi-VN")}₫</span>
              )}
            </div>
            <div className="product-list-rating">
              {Array.from({ length: 5 }).map((_, i) => {
                if (product.rating >= i + 1) return <span key={i} style={{color:'#FFD700'}}>★</span>;
                if (product.rating > i) return <span key={i} style={{color:'#FFD700'}}>☆</span>;
                return <span key={i} style={{color:'#ddd'}}>★</span>;
              })}
              <span style={{marginLeft: 4, color: "#888", fontSize: "0.95em"}}>
                ({product.rating ? product.rating.toFixed(1) : "0"})
              </span>
            </div>
            <div className="product-list-actions">
              <button
                className="product-list-cart-btn tooltip-parent"
                onClick={(e) => {
                  e.stopPropagation();
                  handleAddToCart(product);
                }}
                disabled={!product.Status}
                style={{
                  background: "#0154b9",
                  color: "#fff",
                  border: "none",
                  borderRadius: 4,
                  padding: "6px 12px",
                  cursor: product.Status ? "pointer" : "not-allowed",
                  opacity: product.Status ? 1 : 0.5,
                  fontWeight: 600,
                  marginRight: 8,
                }}
              >
                Thêm vào giỏ hàng
                <span className="tooltip">Thêm vào giỏ hàng</span>
              </button>
              <button
                className="product-list-compare-btn tooltip-parent"
                onClick={(e) => {
                  e.stopPropagation();
                  onAddCompare && onAddCompare(product);
                }}
                disabled={
                  compareProducts.some(
                    (p) => p.Product_ID === product.Product_ID
                  ) || compareProducts.length >= 2
                }
              >
                {compareProducts.some(
                  (p) => p.Product_ID === product.Product_ID
                )
                  ? "Đã chọn"
                  : "So sánh"}
                <span className="tooltip">So sánh sản phẩm</span>
              </button>
            </div>
          </div>
        </div>
      ))}
      {showVariantPopup && selectedProduct && (
        <VariantSelector
          product={selectedProduct}
          onClose={() => setShowVariantPopup(false)}
          onSelectVariant={(variant) => {
            addToCart(selectedProduct, variant);
            setShowVariantPopup(false);
          }}
        />
      )}
    </main>
  );
}

export default ProductList;
