import React, { useEffect, useState } from "react";
import { fetchProducts } from "../../api/productApi";
import { useNavigate } from "react-router-dom";

// Helper functions giống ProductOptions.js
function parseVariantName(variantName) {
  return variantName.split(" - ");
}

function getOptionsByPosition(variants, pos) {
  return Array.from(
    new Set(
      variants
        .map(v => {
          const parts = parseVariantName(v.Variant_name);
          return parts[pos];
        })
        .filter(Boolean)
    )
  );
}

function findVariant(variants, weight, stiffness, balance, playStyle) {
  return variants.find(v => {
    const parts = parseVariantName(v.Variant_name);
    return (
      parts[0] === weight &&
      parts[1] === stiffness &&
      parts[2] === balance &&
      parts[3] === playStyle
    );
  });
}

function ProductList({ page, filters, onAddCompare, compareProducts = [], sort }) {
  const [products, setProducts] = useState([]);
  const [showVariantPopup, setShowVariantPopup] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);

  // State cho từng option
  const [weight, setWeight] = useState("");
  const [stiffness, setStiffness] = useState("");
  const [balance, setBalance] = useState("");
  const [playStyle, setPlayStyle] = useState("");
  const [selectedVariant, setSelectedVariant] = useState(null);

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
      } catch (err) {
        console.error("Lỗi gọi API:", err);
      }
    };
    fetchData();
  }, [page, filters]);

  // Reset option khi mở popup
  useEffect(() => {
    if (showVariantPopup && selectedProduct && selectedProduct.variants && selectedProduct.variants.length > 0) {
      const variants = selectedProduct.variants;
      setWeight(getOptionsByPosition(variants, 0)[0] || "");
      setStiffness(getOptionsByPosition(variants, 1)[0] || "");
      setBalance(getOptionsByPosition(variants, 2)[0] || "");
      setPlayStyle(getOptionsByPosition(variants, 3)[0] || "");
    }
  }, [showVariantPopup, selectedProduct]);

  // Tìm variant đúng khi chọn option
  useEffect(() => {
    if (selectedProduct && selectedProduct.variants && weight && stiffness && balance && playStyle) {
      const variant = findVariant(selectedProduct.variants, weight, stiffness, balance, playStyle);
      setSelectedVariant(variant || null);
    } else {
      setSelectedVariant(null);
    }
  }, [weight, stiffness, balance, playStyle, selectedProduct]);

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

  const handleAddToCart = (product) => {
    if (product.variants && product.variants.length > 0) {
      setSelectedProduct(product);
      setShowVariantPopup(true);
    } else {
      addToCart(product);
    }
  };

  const addToCart = (product, variant = null) => {
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    let item = { ...product, quantity: 1 };
    if (variant) {
      item.selectedVariant = variant;
      item.SKU = variant.SKU || variant.sku;
      item.Price = variant.Price || variant.price || product.Price;
      item.Discount_price = variant.Discount_price || variant.discount_price || product.Discount_price;
    }
    const exist = cart.find(
      (i) => i.Product_ID === product.Product_ID && (!variant || JSON.stringify(i.selectedVariant) === JSON.stringify(variant))
    );
    let updated;
    if (exist) {
      updated = cart.map((i) =>
        i.Product_ID === product.Product_ID && (!variant || JSON.stringify(i.selectedVariant) === JSON.stringify(variant))
          ? { ...i, quantity: (i.quantity || 1) + 1 }
          : i
      );
    } else {
      updated = [...cart, item];
    }
    localStorage.setItem("cart", JSON.stringify(updated));
    window.dispatchEvent(new Event("cartUpdated"));
    setShowVariantPopup(false);
    setSelectedProduct(null);
    setSelectedVariant(null);
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
                className="product-list-cart-btn"
                onClick={(e) => {
                  e.stopPropagation();
                  handleAddToCart(product);
                }}
                disabled={!product.Status}
              >
                🛒 Thêm vào giỏ hàng
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

      {/* Popup chọn biến thể giống ProductOptions */}
      {showVariantPopup && selectedProduct && (
        <div className="variant-popup-overlay">
          <div className="variant-popup">
            <h3>Chọn biến thể cho {selectedProduct.Name}</h3>
            <div>
              <p>Trọng lượng:</p>
              {getOptionsByPosition(selectedProduct.variants, 0).map(opt => (
                <button
                  key={opt}
                  className={weight === opt ? "selected" : ""}
                  onClick={() => setWeight(opt)}
                  type="button"
                  style={{ marginRight: 8 }}
                >
                  {opt}
                </button>
              ))}
            </div>
            <div>
              <p>Độ cứng:</p>
              {getOptionsByPosition(selectedProduct.variants, 1).map(opt => (
                <button
                  key={opt}
                  className={stiffness === opt ? "selected" : ""}
                  onClick={() => setStiffness(opt)}
                  type="button"
                  style={{ marginRight: 8 }}
                >
                  {opt}
                </button>
              ))}
            </div>
            <div>
              <p>Điểm cân bằng:</p>
              {getOptionsByPosition(selectedProduct.variants, 2).map(opt => (
                <button
                  key={opt}
                  className={balance === opt ? "selected" : ""}
                  onClick={() => setBalance(opt)}
                  type="button"
                  style={{ marginRight: 8 }}
                >
                  {opt}
                </button>
              ))}
            </div>
            <div>
              <p>Lối chơi:</p>
              {getOptionsByPosition(selectedProduct.variants, 3).map(opt => (
                <button
                  key={opt}
                  className={playStyle === opt ? "selected" : ""}
                  onClick={() => setPlayStyle(opt)}
                  type="button"
                  style={{ marginRight: 8 }}
                >
                  {opt}
                </button>
              ))}
            </div>
            {selectedVariant ? (
              <div style={{ margin: "12px 0", color: "#0154b9" }}>
                Giá: {Number(selectedVariant.Discount_price || selectedVariant.Price).toLocaleString("vi-VN")}₫
                {selectedVariant.Quantity ? ` | Số lượng còn lại: ${selectedVariant.Quantity}` : ""}
                <br />
                <span style={{ color: "#333", fontWeight: 500 }}>
                  SKU: {selectedVariant.SKU}
                </span>
              </div>
            ) : (
              <div style={{ margin: "12px 0", color: "#d70018" }}>
                Vui lòng chọn đầy đủ biến thể!
              </div>
            )}
            <div style={{ marginTop: 16 }}>
              <button
                className="confirm-btn"
                disabled={!selectedVariant}
                onClick={() => addToCart(selectedProduct, selectedVariant)}
              >
                Xác nhận
              </button>
              <button
                className="cancel-btn"
                onClick={() => {
                  setShowVariantPopup(false);
                  setSelectedProduct(null);
                  setSelectedVariant(null);
                }}
                style={{ marginLeft: 8 }}
              >
                Hủy
              </button>
            </div>
          </div>
        </div>
      )}
    </main>
  );
}

export default ProductList;
