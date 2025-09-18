// components/cart/CartLeft.jsx
import React, { useState, useEffect } from "react";
import CartItem from "./CartItem";

function CartLeft({ cartItems, updateQuantity, removeItem, allVariants, loadingVariants, allProducts }) {
  const [showStockAlert, setShowStockAlert] = useState(false);
  const [stockMessage, setStockMessage] = useState("");

  // Log allVariants mỗi lần render để kiểm tra dữ liệu truyền vào
  useEffect(() => {
    console.log("allVariants nhận được từ props:", allVariants);
  }, [allVariants]);

  // Hàm chuẩn hóa để so sánh SKU tuyệt đối
  const normalize = (val) => String(val || "").trim().toLowerCase();

  const handleUpdateQuantity = (productId, sku, newQty) => {
    // Nếu có SKU và tìm thấy biến thể thì lấy tồn kho từ biến thể
    let variant = allVariants.find(
      (v) => Number(v.Product_ID) === Number(productId) && normalize(v.SKU) === normalize(sku)
    );
    let maxQty = variant ? Number(variant.Quantity) : 0;

    // Nếu không tìm thấy biến thể (sản phẩm gốc), lấy tồn kho từ bảng products
    if (!variant) {
      const product = allProducts.find((p) => Number(p.Product_ID) === Number(productId));
      maxQty = product ? Number(product.Quantity) : 0;
      variant = product;
    }

    console.log("Variant/Sản phẩm gốc tìm được:", variant);

    if (newQty > maxQty) {
      setStockMessage(`Số lượng không đủ. Chỉ còn ${maxQty} sản phẩm trong kho!`);
      setShowStockAlert(true);
      return;
    }
    updateQuantity(productId, sku, newQty);
  };

  const handleRemoveItem = (productId, sku) => {
    removeItem(productId, sku);
    // Gọi sự kiện để Header cập nhật số lượng giỏ hàng
    window.dispatchEvent(new Event("cartUpdated"));
  };

  const handleCloseAlert = () => {
    setShowStockAlert(false);
    setStockMessage("");
  };

  const isEmpty = !cartItems || cartItems.length === 0;

  return (
    <div className="cart-left">
      <h2>Giỏ Hàng</h2>
      <p className="sub-heading">Danh sách sản phẩm bạn đã thêm vào giỏ</p>
      {isEmpty ? (
        <div
          className="cart-empty-box"
          style={{
            textAlign: "center",
            padding: "56px 0",
            background: "linear-gradient(120deg, #e0e7ff 0%, #f0fdfa 100%)",
            borderRadius: "16px",
            margin: "40px 0",
            boxShadow: "0 4px 24px rgba(1,84,185,0.08)",
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <div style={{ fontSize: 64, color: "#0154b9", marginBottom: 18 }}>
            <i className="fas fa-shopping-basket"></i>
          </div>
          <h3
            style={{
              color: "#d32f2f",
              marginBottom: 10,
              fontWeight: 700,
              fontSize: 24,
            }}
          >
            Giỏ hàng của bạn đang trống!
          </h3>
          <p
            style={{
              fontSize: 17,
              marginBottom: 22,
              color: "#222",
            }}
          >
            Bạn chưa thêm sản phẩm nào vào giỏ.
            <br />
            Khám phá ngay các sản phẩm nổi bật và ưu đãi hấp dẫn!
          </p>
          <a
            href="/"
            className="back-link"
            style={{
              display: "inline-block",
              padding: "12px 32px",
              background: "#0154b9",
              color: "#fff",
              borderRadius: 10,
              textDecoration: "none",
              fontWeight: 600,
              fontSize: 18,
              boxShadow: "0 2px 8px rgba(1,84,185,0.10)",
              transition: "background 0.2s",
            }}
            onMouseEnter={(e) => (e.currentTarget.style.background = "#0a3570")}
            onMouseLeave={(e) => (e.currentTarget.style.background = "#0154b9")}
          >
            ← Quay lại mua sắm
          </a>
        </div>
      ) : (
        <>
          <div
            style={{
              maxHeight: "720px",
              overflowY: cartItems.length > 5 ? "auto" : "visible",
            }}
          >
            <table className="cart-table">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Đơn giá</th>
                  <th>Số lượng</th>
                  <th>Xoá</th>
                </tr>
              </thead>
              <tbody>
                {cartItems.map((item) => (
                  <CartItem
                    key={item.Product_ID + "-" + item.SKU}
                    item={item}
                    updateQuantity={handleUpdateQuantity}
                    removeItem={handleRemoveItem}
                    allVariants={allVariants}
                    allProducts={allProducts} // Thêm dòng này!
                  />
                ))}
              </tbody>
            </table>
          </div>
          <a href="/" className="back-link">
            ← Quay lại trang chủ
          </a>
        </>
      )}
      {/* Box thông báo tồn kho */}
      {showStockAlert && (
        <div
          style={{
            position: "fixed",
            top: 0,
            left: 0,
            width: "100vw",
            height: "100vh",
            background: "rgba(0,0,0,0.25)",
            zIndex: 9999,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <div
            style={{
              background: "#fff",
              border: "2px solid #d70018",
              borderRadius: 12,
              padding: "32px 36px",
              minWidth: 320,
              boxShadow: "0 4px 24px #bdbdbd",
              textAlign: "center",
            }}
          >
            <div
              style={{
                fontSize: 18,
                fontWeight: 700,
                color: "#d70018",
                marginBottom: 12,
              }}
            >
              {stockMessage}
            </div>
            <button
              style={{
                marginTop: 10,
                padding: "8px 22px",
                borderRadius: 7,
                border: "none",
                background: "#0154b9",
                color: "#fff",
                fontWeight: 600,
                fontSize: 15,
                cursor: "pointer",
              }}
              onClick={handleCloseAlert}
            >
              Đóng
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default CartLeft;
