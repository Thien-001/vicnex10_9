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

async function fetchProductRatings(productId) {
  const res = await fetch(`http://localhost:8000/api/products/${productId}/ratings`);
  return await res.json();
}

function ProductList({ page, filters, onAddCompare, compareProducts = [], sort }) {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [showVariantPopup, setShowVariantPopup] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);

  // State cho từng option
  const [weight, setWeight] = useState("");
  const [stiffness, setStiffness] = useState("");
  const [balance, setBalance] = useState("");
  const [playStyle, setPlayStyle] = useState("");
  const [selectedVariant, setSelectedVariant] = useState(null);
  const pageSize = 20; // 5 hàng × 4 sản phẩm

  const navigate = useNavigate();

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        setError(null);
        const res = await fetchProducts(page, filters);
        let data = res.data.data || res.data;
        if (page === 1) {
          data = [...data].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }

        // Lấy rating cho từng sản phẩm
        const productsWithRatings = await Promise.all(
          data.map(async (product) => {
            try {
              const ratingData = await fetchProductRatings(product.Product_ID);
              return {
                ...product,
                rating: ratingData.avg || 0,
                ratingCount: ratingData.count || 0,
              };
            } catch {
              return { ...product, rating: 0, ratingCount: 0 };
            }
          })
        );
        setProducts(productsWithRatings);
      } catch (err) {
        setError("Không thể tải sản phẩm. Vui lòng thử lại sau.");
        setProducts([]);
      } finally {
        setLoading(false);
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

  // Sắp xếp sản phẩm theo lựa chọn sort (nếu có)
  let sortedProducts = [...products];
  if (sort === "price-asc") {
    sortedProducts.sort((a, b) => (a.Discount_price || a.Price) - (b.Discount_price || b.Price));
  } else if (sort === "price-desc") {
    sortedProducts.sort((a, b) => (b.Discount_price || b.Price) - (a.Discount_price || a.Price));
  } else if (sort === "bestseller") {
    sortedProducts.sort((a, b) => (b.is_best_seller || 0) - (a.is_best_seller || 0));
  }
  // Nếu không có sort, mặc định đã là newest lên đầu do đã sort khi fetch

  // Phân trang: mỗi trang 20 sản phẩm (5 hàng × 4 sản phẩm)
  const productsToShow = sortedProducts.slice((page - 1) * pageSize, page * pageSize);
  const emptyBoxes = productsToShow.length % 4 === 0 ? 0 : 4 - (productsToShow.length % 4);

  // Loading state
  if (loading) {
    return (
      <div style={{ textAlign: 'center', padding: '40px' }}>
        <div style={{ fontSize: '18px', color: '#666' }}>Đang tải sản phẩm...</div>
      </div>
    );
  }

  // Error state
  if (error) {
    return (
      <div style={{ textAlign: 'center', padding: '40px' }}>
        <div style={{ fontSize: '18px', color: '#d70018' }}>{error}</div>
        <button 
          onClick={() => window.location.reload()} 
          style={{ 
            marginTop: '10px', 
            padding: '8px 16px', 
            background: '#0154b9', 
            color: 'white', 
            border: 'none', 
            borderRadius: '4px',
            cursor: 'pointer'
          }}
        >
          Thử lại
        </button>
      </div>
    );
  }

  if (!productsToShow || productsToShow.length === 0) {
    return <p className="product-list-empty">Không tìm thấy sản phẩm phù hợp.</p>;
  }

  const handleAddToCart = (product) => {
    if (product.variants && product.variants.length > 0) {
      const allVariantsOut = product.variants.every(v => Number(v.Quantity) <= 0);
      if (allVariantsOut) {
        alert("Tất cả biến thể đã hết hàng!");
        return;
      }
      setSelectedProduct(product);
      setShowVariantPopup(true);
    } else {
      if (Number(product.Quantity) <= 0) {
        alert("Sản phẩm đã hết hàng!");
        return;
      }
      addToCart(product);
    }
  };

  const addToCart = (product, variant = null) => {
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    let item;
    if (variant) {
      item = {
        ...product,
        selectedVariant: variant,
        SKU: variant.SKU,
        Price: variant.Price,
        Discount_price: variant.Discount_price,
        quantity: 1,
        variant_id: variant.Variant_ID,
      };
    } else {
      item = {
        ...product,
        Price: product.Discount_price || product.Price,
        Discount_price: product.Discount_price,
        quantity: 1,
      };
    }

    const exist = cart.find(
      (i) =>
        i.Product_ID === product.Product_ID &&
        (variant
          ? i.variant_id === variant.Variant_ID
          : !i.variant_id)
    );

    let updated;
    if (exist) {
      updated = cart.map((i) =>
        i.Product_ID === product.Product_ID &&
        (variant
          ? i.variant_id === variant.Variant_ID
          : !i.variant_id)
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
      {productsToShow.map((product) => {
        const hasVariant = product.variants && product.variants.length > 0;
        const hasStock = hasVariant
          ? product.variants.some(v => Number(v.Quantity) > 0)
          : Number(product.Quantity) > 0;

        return (
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
                  {product.ratingCount ? `(${product.ratingCount} đánh giá)` : "(0 đánh giá)"}
                </span>
              </div>
              <div className="product-list-actions">
                <button
                  className="product-list-cart-btn"
                  onClick={(e) => {
                    e.stopPropagation();
                    handleAddToCart(product);
                  }}
                  disabled={!product.Status || !hasStock}
                >
                  {!hasStock ? "Đã hết hàng" : "🛒 Thêm vào giỏ hàng"}
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
        );
      })}
      {/* Thêm box trống nếu hàng cuối chưa đủ 4 sản phẩm */}
      {Array.from({ length: emptyBoxes }).map((_, idx) => (
        <div
          key={`empty-${idx}`}
          className="product-list-item"
          style={{ visibility: "hidden" }}
        />
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
                disabled={!selectedVariant || Number(selectedVariant.Quantity) <= 0}
                onClick={() => {
                  if (Number(selectedVariant.Quantity) <= 0) {
                    alert("Biến thể đã hết hàng!");
                    return;
                  }
                  addToCart(selectedProduct, selectedVariant);
                }}
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
