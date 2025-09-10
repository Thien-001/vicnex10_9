import { useNavigate } from "react-router-dom";
import { useState } from "react";
import axios from "axios";

function CartRight({ cartItems }) {
  const navigate = useNavigate();
  const [voucher, setVoucher] = useState("");
  const [voucherInfo, setVoucherInfo] = useState(null);
  const [voucherMsg, setVoucherMsg] = useState("");
  const shippingFee = 30000;

  const hasProduct = cartItems && cartItems.length > 0;

  const subtotal = hasProduct
    ? cartItems.reduce((sum, item) => {
        const price =
          Number(item.Discount_price) > 0
            ? Number(item.Discount_price)
            : Number(item.Price) || 0;
        const qty = Number(item.quantity) || 1;
        return sum + price * qty;
      }, 0)
    : 0;

  // Tính giảm giá dựa trên voucher
  // Xác định danh mục voucher áp dụng (giả sử backend trả về voucherInfo.category_name)
  let discount = 0;
  if (voucherInfo) {
    // Lọc các sản phẩm thuộc đúng danh mục
    const eligibleItems = cartItems.filter(
      item => item.category?.Name === voucherInfo.category_name
    );
    const eligibleSubtotal = eligibleItems.reduce((sum, item) => {
      const price =
        Number(item.Discount_price) > 0
          ? Number(item.Discount_price)
          : Number(item.Price) || 0;
      const qty = Number(item.quantity) || 1;
      return sum + price * qty;
    }, 0);

    if (voucherInfo.discount_type === "percentage") {
      discount = Math.round((eligibleSubtotal * voucherInfo.discount_value) / 100);
    } else if (voucherInfo.discount_type === "fixed") {
      discount = Math.min(Number(voucherInfo.discount_value), eligibleSubtotal);
    }
  }

  const total = hasProduct ? subtotal + shippingFee - discount : 0;

  const handleCheckout = () => {
    localStorage.removeItem('pendingBooking');
    navigate('/checkout', {
      state: {
        voucher,
        voucherInfo,
      }
    });
  };

  // Hàm kiểm tra voucher
  const handleApplyVoucher = async () => {
    setVoucherMsg("");
    setVoucherInfo(null);
    if (!voucher) {
      setVoucherMsg("Vui lòng nhập mã giảm giá.");
      return;
    }

    // Log toàn bộ cartItems để xem cấu trúc thực tế
    console.log("cartItems:", cartItems);

    // Log thử từng item để xem có trường category không
    cartItems.forEach((item, idx) => {
      console.log(`item[${idx}]:`, item);
      if (item.category) {
        console.log(`item[${idx}].category:`, item.category);
        console.log(`item[${idx}].category.Name:`, item.category.Name);
      }
    });

    // Lấy đúng trường tên danh mục
    const cartCategories = cartItems.map(
      item => item.category?.Name || ""
    );

    // Log kết quả cartCategories
    console.log("cartCategories:", cartCategories);

    try {
      const res = await axios.post("http://localhost:8000/api/vouchers/check", {
        code: voucher,
        is_booking: false,
        cart_categories: cartCategories,
      });
      if (res.data.valid) {
        setVoucherInfo(res.data.voucher);
        setVoucherMsg("Áp dụng mã thành công!");
      } else {
        setVoucherInfo(null);
        setVoucherMsg(res.data.message || "Mã không hợp lệ.");
      }
    } catch (err) {
      setVoucherInfo(null);
      setVoucherMsg("Có lỗi khi kiểm tra mã.");
    }
  };

  return (
    <div className="cart-right">
      <h3>Tóm Tắt Đơn Hàng</h3>
      <div className="summary-row">
        <span>Tổng tiền sản phẩm:</span>
        <strong>₫{subtotal.toLocaleString()}</strong>
      </div>
      {hasProduct && (
        <>
          <div className="summary-row">
            <span>Giảm giá:</span>
            <strong style={{ color: "red" }}>-₫{discount.toLocaleString()}</strong>
          </div>
          <div className="summary-row">
            <span>Phí vận chuyển:</span>
            <strong>₫{shippingFee.toLocaleString()}</strong>
          </div>
          <div className="summary-row total-row">
            <span>Tổng thanh toán:</span>
            <strong>₫{total.toLocaleString()}</strong>
          </div>
          <div className="voucher">
            <input
              type="text"
              placeholder="Nhập mã giảm giá"
              value={voucher}
              onChange={e => setVoucher(e.target.value)}
              disabled={!!voucherInfo}
            />
            <button type="button" onClick={handleApplyVoucher} disabled={!!voucherInfo}>
              {voucherInfo ? "Đã áp dụng" : "Áp dụng"}
            </button>
            {voucherMsg && (
              <div style={{ color: voucherInfo ? "green" : "red", marginTop: 4 }}>{voucherMsg}</div>
            )}
            {voucherInfo && (
              <div style={{ color: "#10b981", fontSize: 13, marginTop: 2 }}>
                Mã: <b>{voucherInfo.code}</b> - {voucherInfo.discount_type === "percentage"
                  ? `Giảm ${voucherInfo.discount_value}%`
                  : `Giảm ${Number(voucherInfo.discount_value).toLocaleString()}₫`}
                <br />
                <span>
                  Số tiền đã giảm: <b>-₫{discount.toLocaleString()}</b>
                </span>
              </div>
            )}
          </div>
          <button className="checkout-btn" onClick={handleCheckout}>
            Đặt Hàng
          </button>
        </>
      )}
      {!hasProduct && (
        <div
          style={{
            textAlign: "center",
            padding: "48px 0",
            background: "linear-gradient(120deg, #e0e7ff 0%, #f0fdfa 100%)",
            borderRadius: "16px",
            margin: "32px 0",
            boxShadow: "0 4px 24px rgba(1,84,185,0.08)",
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center"
          }}
        >
          <div style={{ fontSize: 54, color: "#0154b9", marginBottom: 16 }}>
            <i className="fas fa-shopping-basket"></i>
          </div>
          <h3 style={{ color: "#d32f2f", marginBottom: 10, fontWeight: 700, fontSize: 22 }}>
            Giỏ hàng của bạn đang trống!
          </h3>
          <p style={{ fontSize: 16, marginBottom: 18, color: "#222" }}>
            Hãy khám phá các sản phẩm nổi bật và ưu đãi hấp dẫn của chúng tôi!
          </p>
          <a
            href="/"
            style={{
              display: "inline-block",
              padding: "10px 28px",
              background: "#0154b9",
              color: "#fff",
              borderRadius: 8,
              textDecoration: "none",
              fontWeight: 600,
              fontSize: 16,
              boxShadow: "0 2px 8px rgba(1,84,185,0.10)",
              transition: "background 0.2s"
            }}
            onMouseEnter={e => e.currentTarget.style.background = "#0a3570"}
            onMouseLeave={e => e.currentTarget.style.background = "#0154b9"}
          >
            ← Quay lại mua sắm
          </a>
        </div>
      )}
    </div>
  );
}

export default CartRight;
