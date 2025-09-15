import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

function ProductActions({ product, selectedVariant, quantity, showOutOfStock }) {
  const navigate = useNavigate();
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [showLimitModal, setShowLimitModal] = useState(false); // Thêm state mới

  if (!product) return null;

  const hasVariants = Array.isArray(product.variants) && product.variants.length > 0;
  const hasStock = hasVariants
    ? product.variants.some(v => Number(v.Quantity) > 0)
    : Number(product.Quantity) > 0;

  const getCurrentStock = () => {
    if (selectedVariant && typeof selectedVariant.Quantity !== "undefined") {
      return Number(selectedVariant.Quantity) || 0;
    }
    return Number(product.Quantity) || 0;
  };

  const getCartItem = () => {
    return {
      Product_ID: product.Product_ID,
      Name: product.Name,
      Image: product.Image || product.image || "/img/no-image.png",
      Price: selectedVariant ? selectedVariant.Discount_price : product.Discount_price,
      Discount_price: selectedVariant ? selectedVariant.Discount_price : product.Discount_price,
      SKU: selectedVariant ? (selectedVariant.SKU || selectedVariant.sku) : product.SKU,
      Variant_name: selectedVariant ? selectedVariant.Variant_name : "",
      quantity: quantity,
    };
  };

  const handleAddToCart = () => {
    const token = localStorage.getItem("token");
    if (!token) {
      setShowLoginModal(true);
      return;
    }
    // Nếu có biến thể mà chưa chọn biến thể thì báo hết hàng
    if (hasVariants && !selectedVariant) {
      if (showOutOfStock) showOutOfStock(true);
      return;
    }
    const stock = getCurrentStock();
    if (stock < 1 || quantity > stock) {
      if (showOutOfStock) showOutOfStock(true);
      return;
    }
    const cartItem = getCartItem();
    if (!cartItem) return;

    const cartItems = JSON.parse(localStorage.getItem("cart") || "[]");
    const existing = cartItems.find(
      (item) =>
        item.Product_ID === cartItem.Product_ID &&
        item.SKU === cartItem.SKU
    );
    if (existing) {
      const total = (existing.quantity || 1) + quantity;
      if (total > stock) {
        setShowLimitModal(true); // Hiện modal thông báo
        return;
      }
      existing.quantity = total;
    } else {
      if (quantity > stock) {
        setShowLimitModal(true); // Hiện modal thông báo
        return;
      }
      cartItems.push(cartItem);
    }
    localStorage.setItem("cart", JSON.stringify(cartItems));
    window.dispatchEvent(new Event("cartUpdated"));
  };

  const handleBuyNow = () => {
    // Xóa thông tin đặt sân cũ nếu có
    localStorage.removeItem('pendingBooking');

    const token = localStorage.getItem("token");
    if (!token) {
      setShowLoginModal(true);
      return;
    }
    // Nếu có biến thể mà chưa chọn biến thể thì báo hết hàng
    if (hasVariants && !selectedVariant) {
      if (showOutOfStock) showOutOfStock(true);
      return;
    }
    const stock = getCurrentStock();
    if (stock < 1 || quantity > stock) {
      if (showOutOfStock) showOutOfStock(true);
      return;
    }
    const cartItem = getCartItem();
    if (!cartItem) return;

    localStorage.setItem("cart", JSON.stringify([cartItem]));
    window.dispatchEvent(new Event("cartUpdated"));
    navigate("/cart");
  };

  const handleCloseModal = () => {
    setShowLoginModal(false);
  };

  const handleGoLogin = () => {
    setShowLoginModal(false);
    navigate("/login");
  };

  return (
    <div className="actions">
      <button
        onClick={handleAddToCart}
        disabled={!hasStock}
        style={{
          padding: "10px 24px",
          background: "#0154b9",
          color: "#fff",
          border: "none",
          borderRadius: 6,
          fontWeight: 600,
          fontSize: 16,
          marginRight: 12,
          cursor: !hasStock ? "not-allowed" : "pointer",
          opacity: !hasStock ? 0.6 : 1,
        }}
      >
        🛒 Thêm Vào Giỏ Hàng
      </button>
      <button
        onClick={handleBuyNow}
        disabled={!hasStock}
        style={{
          padding: "10px 24px",
          background: "#d70018",
          color: "#fff",
          border: "none",
          borderRadius: 6,
          fontWeight: 600,
          fontSize: 16,
          cursor: !hasStock ? "not-allowed" : "pointer",
          opacity: !hasStock ? 0.6 : 1,
        }}
      >
        Mua Ngay
      </button>
      {showLoginModal && (
        <div
          style={{
            position: "fixed",
            top: 0,
            left: 0,
            width: "100vw",
            height: "100vh",
            background: "rgba(0, 128, 255, 0.15)",
            zIndex: 9999,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <div
            style={{
              background: "#fff",
              borderRadius: 12,
              padding: "32px 24px",
              boxShadow: "0 4px 24px rgba(0,0,0,0.12)",
              minWidth: 320,
              textAlign: "center",
            }}
          >
            <h3 style={{ color: "#0154b9", marginBottom: 12 }}>
              Bạn cần đăng nhập để thực hiện thao tác này!
            </h3>
            <button
              style={{
                background: "#0154b9",
                color: "#fff",
                border: "none",
                borderRadius: 6,
                padding: "8px 24px",
                marginRight: 12,
                fontWeight: 600,
                cursor: "pointer",
              }}
              onClick={handleGoLogin}
            >
              Đăng nhập ngay
            </button>
            <button
              style={{
                background: "#eee",
                color: "#333",
                border: "none",
                borderRadius: 6,
                padding: "8px 24px",
                fontWeight: 600,
                cursor: "pointer",
              }}
              onClick={handleCloseModal}
            >
              Đóng
            </button>
          </div>
        </div>
      )}
      {showLimitModal && (
        <div
          style={{
            position: "fixed",
            top: 0,
            left: 0,
            width: "100vw",
            height: "100vh",
            background: "rgba(0,0,0,0.25)", // Nền mờ tối
            zIndex: 9999,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <div
            style={{
              background: "#fff",
              borderRadius: 12,
              padding: "32px 24px",
              boxShadow: "0 4px 24px rgba(0,0,0,0.12)",
              minWidth: 320,
              textAlign: "center",
            }}
          >
            <h3 style={{ color: "#d70018", marginBottom: 12 }}>
              Bạn đã thêm tối đa số lượng sản phẩm còn lại!
            </h3>
            <button
              style={{
                background: "#0154b9",
                color: "#fff",
                border: "none",
                borderRadius: 6,
                padding: "8px 24px",
                fontWeight: 600,
                cursor: "pointer",
              }}
              onClick={() => setShowLimitModal(false)}
            >
              Đóng
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default ProductActions;