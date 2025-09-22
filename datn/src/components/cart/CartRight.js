import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";

function CartRight({ cartItems }) {
  const navigate = useNavigate();
  const [voucher, setVoucher] = useState("");
  const [voucherInfo, setVoucherInfo] = useState(null);
  const [voucherMsg, setVoucherMsg] = useState("");
  
  const subtotalForShipping = cartItems.reduce((sum, item) => {
    const price = Number(item.Discount_price) > 0 ? Number(item.Discount_price) : Number(item.Price) || 0;
    const qty = Number(item.quantity) || 1;
    return sum + price * qty;
  }, 0);
  
  const shippingFee = subtotalForShipping >= 500000 ? 0 : 30000;

  const subtotal = cartItems.reduce((sum, item) => {
    const price = Number(item.Discount_price) > 0 ? Number(item.Discount_price) : Number(item.Price) || 0;
    const qty = Number(item.quantity) || 1; // SỬA Ở ĐÂY
    return sum + price * qty;
  }, 0);

  // Lọc sản phẩm hợp lệ theo voucher (dùng Categories_ID)
  const eligibleItems = voucherInfo
    ? (voucherInfo.category_ids && voucherInfo.category_ids.length > 0
        ? cartItems.filter(item =>
            voucherInfo.category_ids.includes(item.category?.Categories_ID)
          )
        : cartItems // Nếu voucher áp dụng cho tất cả
      )
    : [];

  const eligibleSubtotal = eligibleItems.reduce((sum, item) => {
    const price = Number(item.Discount_price) > 0 ? Number(item.Discount_price) : Number(item.Price) || 0;
    const qty = Number(item.quantity) || 1; // SỬA Ở ĐÂY
    return sum + price * qty;
  }, 0);

  let discount = 0;
  if (voucherInfo) {
    if (voucherInfo.discount_type === "percentage") {
      discount = Math.round((eligibleSubtotal * voucherInfo.discount_value) / 100);
    } else if (voucherInfo.discount_type === "fixed") {
      discount = Math.min(Number(voucherInfo.discount_value), eligibleSubtotal);
    }
  }

  const total = subtotal + shippingFee - discount;

  // Hàm kiểm tra voucher
  const handleApplyVoucher = async () => {
    setVoucherMsg("");
    setVoucherInfo(null);
    if (!voucher) {
      setVoucherMsg("Vui lòng nhập mã giảm giá.");
      return;
    }

    const cartCategoryIds = cartItems.map(item => item.category?.Categories_ID).filter(Boolean);

    try {
      const res = await axios.post("http://localhost:8000/api/vouchers/check", {
        code: voucher,
        cart_category_ids: cartCategoryIds,
      });
      if (res.data.valid) {
        setVoucherInfo({
          ...res.data.voucher,
          category_ids: res.data.category_ids,
        });
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

  // Hàm đặt hàng
  const handleCheckout = () => {
    localStorage.removeItem('pendingBooking');
    navigate('/checkout', {
      state: {
        voucher,
        voucherInfo,
      }
    });
  };

  return (
    <div className="cart-right">
      <h3>Tóm Tắt Đơn Hàng</h3>
      <div style={{ fontSize: 15, color: "#0154b9", marginBottom: 8 }}>
        Giỏ hàng có <b>{cartItems.reduce((sum, item) => sum + (Number(item.quantity) || 1), 0)}</b> sản phẩm
      </div>
      <div className="summary-row">
        <span>Tổng tiền sản phẩm:</span>
        <strong>₫{subtotal.toLocaleString()}</strong>
      </div>
      <div className="summary-row">
        <span>Giảm giá:</span>
        <strong style={{ color: "red" }}>-₫{discount.toLocaleString()}</strong>
      </div>
      <div className="summary-row">
        <span>Phí vận chuyển:</span>
        <strong>₫{shippingFee.toLocaleString()}</strong>
      </div>
      {shippingFee === 0 && (
        <div style={{ color: "#10b981", fontSize: 13, marginTop: 2 }}>
          Đơn hàng trên 500.000đ được <b>miễn phí vận chuyển</b>
        </div>
      )}
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
      {voucherInfo && eligibleItems.length > 0 && (
        <div style={{ marginTop: 10, background: "#f6f8fc", borderRadius: 8, padding: "10px 16px" }}>
          <div style={{ fontWeight: 600, color: "#0154b9", marginBottom: 6 }}>
            Sản phẩm được áp dụng giảm giá:
          </div>
          <ul style={{ margin: 0, paddingLeft: 18 }}>
            {eligibleItems.map(item => {
              const price = Number(item.Discount_price) > 0 ? Number(item.Discount_price) : Number(item.Price) || 0;
              const qty = Number(item.qty) || 1;
              let itemDiscount = 0;
              if (voucherInfo.discount_type === "percentage") {
                itemDiscount = Math.round((price * qty * voucherInfo.discount_value) / 100);
              } else if (voucherInfo.discount_type === "fixed") {
                itemDiscount = Math.floor(Number(voucherInfo.discount_value) / eligibleItems.length);
              }
              return (
                <li key={item.Product_ID} style={{ color: "#222", fontSize: 15, marginBottom: 4 }}>
                  {item.Name} <span style={{ color: "#888" }}>({item.category?.Name})</span>
                  <span style={{ color: "#d32f2f", marginLeft: 8 }}>
                    -₫{itemDiscount.toLocaleString()}
                  </span>
                </li>
              );
            })}
          </ul>
        </div>
      )}
      
      <button
        className="checkout-btn"
        style={{ background: "#10b981", marginTop: 10 }}
        onClick={() => {
          navigate('/checkout', {
            state: {
              voucher,
              voucherInfo,
              fastPay: true
            }
          });
        }}
      >
        Mua Ngay
      </button>
    </div>
  );
}

export default CartRight;
