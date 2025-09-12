import React, { useEffect, useState, useCallback } from "react";
import { fetchProducts } from "../../api/productApi";
import { useNavigate } from "react-router-dom";

function ProductList({ page, filters, onAddCompare, compareProducts = [], sort }) {
  const [products, setProducts] = useState([]);
  const [meta, setMeta] = useState({});
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  // Debounce function
  const debounce = useCallback((func, delay) => {
    let timeoutId;
    return (...args) => {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => func.apply(null, args), delay);
    };
  }, []);

  // Debounced fetch function
  const debouncedFetch = useCallback(
    debounce(async (page, filters) => {
      setLoading(true);
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
      } finally {
        setLoading(false);
      }
    }, 300),
    [debounce]
  );

  useEffect(() => {
    debouncedFetch(page, filters);
  }, [page, filters, debouncedFetch]);

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

  if (loading) {
    return (
      <div style={{ textAlign: 'center', padding: '40px' }}>
        <div style={{ 
          display: 'inline-block',
          width: '40px',
          height: '40px',
          border: '4px solid #f3f3f3',
          borderTop: '4px solid #0154b9',
          borderRadius: '50%',
          animation: 'spin 1s linear infinite'
        }}></div>
        <p style={{ marginTop: '16px', color: '#666' }}>Đang tải sản phẩm...</p>
        <style>{`
          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        `}</style>
      </div>
    );
  }

  if (!sortedProducts || sortedProducts.length === 0) {
    return <p className="product-list-empty">Không tìm thấy sản phẩm phù hợp.</p>;
  }

  return (
    <main className="product-list-main">
      {sortedProducts.map((product) => (
        <div
          className="product-list-item"
          key={product.Product_ID}
          onClick={() => handleProductClick(product.slug)}
        >
          <img
            src={`/${product.Image}`}
            alt={product.Name}
            className="product-list-image"
          />
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
                </span>{" "}
                <del className="product-list-price-old">
                  {Number(product.Price).toLocaleString("vi-VN")}₫
                </del>
              </>
            ) : (
              <span>{Number(product.Price).toLocaleString("vi-VN")}₫</span>
            )}
          </div>
          <div className="product-list-rating">
            ★★★★☆ <span>(24)</span>
          </div>
          <div className="product-list-actions">
            <button
              className="product-list-cart-btn"
              onClick={(e) => {
                e.stopPropagation();
                const cart = JSON.parse(
                  localStorage.getItem("cart") || "[]"
                );
                const exist = cart.find(
                  (item) => item.Product_ID === product.Product_ID
                );
                let updated;
                if (exist) {
                  updated = cart.map((item) =>
                    item.Product_ID === product.Product_ID
                      ? { ...item, quantity: (item.quantity || 1) + 1 }
                      : item
                  );
                } else {
                  updated = [...cart, { ...product, quantity: 1 }];
                }
                localStorage.setItem("cart", JSON.stringify(updated));
                window.dispatchEvent(new Event("cartUpdated"));
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
              🛒 Thêm vào giỏ hàng
            </button>
            <button
              className="product-list-compare-btn"
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
            </button>
          </div>
          <div className="product-list-fav">♡</div>
        </div>
      ))}
    </main>
  );
}

export default ProductList;
