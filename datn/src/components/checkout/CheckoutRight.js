import React, { useState, useEffect } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import axios from "axios";
import "./Checkout.css";

const CheckoutRight = ({ cartItems, setCartItems, form }) => {
  const navigate = useNavigate();
  const location = useLocation();
  const [voucherInfo, setVoucherInfo] = useState(location.state?.voucherInfo || null);
  const [voucher, setVoucher] = useState(location.state?.voucher || "");
  const [voucherMsg, setVoucherMsg] = useState("");
  const [paymentMethod, setPaymentMethod] = useState("cod");
  const [loading, setLoading] = useState(false);
  const [shippingFee, setShippingFee] = useState(30000);
  const [showWarning, setShowWarning] = useState(false);
  const [warningMsg, setWarningMsg] = useState("");

  const hasProduct = cartItems && cartItems.length > 0;

  const subtotal = hasProduct
    ? cartItems.reduce((sum, item) => {
        const price =
          Number(item.Discount_price) > 0
            ? Number(item.Discount_price)
            : Number(item.Price) || 0;
        const qty = Number(item.qty) || 1;
        return sum + price * qty;
      }, 0)
    : 0;

  // Lọc sản phẩm hợp lệ theo voucher (dùng Categories_ID)
  const eligibleItems = voucherInfo
    ? cartItems.filter(item =>
        item.category?.Categories_ID === voucherInfo.category_id ||
        (Array.isArray(voucherInfo.category_ids) && voucherInfo.category_ids.includes(item.category?.Categories_ID))
      )
    : [];

  const eligibleSubtotal = eligibleItems.reduce((sum, item) => {
    const price =
      Number(item.Discount_price) > 0
        ? Number(item.Discount_price)
        : Number(item.Price) || 0;
    const qty = Number(item.qty) || 1;
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

    const isBooking = cartItems.length === 1 && cartItems[0].Courts_ID;
    let cartCategoryIds = [];
    if (!isBooking && cartItems.length > 0) {
      cartCategoryIds = cartItems.map(item => item.category?.Categories_ID);
    }

    try {
      const res = await axios.post("http://localhost:8000/api/vouchers/check", {
        code: voucher,
        is_booking: isBooking,
        cart_category_ids: cartCategoryIds,
      });
      if (res.data.valid) {
        setVoucherInfo({
          ...res.data.voucher,
          category_id: res.data.category_id,
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

  const handleCheckout = async () => {
    const isBooking = cartItems.length === 1 && cartItems[0].Courts_ID;

    if (isBooking) {
      // Đặt sân chỉ cần các trường cơ bản
      if (
        !form.full_name ||
        !form.phone ||
        !form.email
      ) {
        setWarningMsg("Vui lòng điền đầy đủ thông tin để đặt sân!");
        setShowWarning(true);
        return;
      }
      setLoading(true);
      try {
        const booking = cartItems[0];
        const user = JSON.parse(localStorage.getItem("user"));
        const bookingData = {
          User_ID: user?.ID,
          Courts_ID: booking.Courts_ID,
          CourtName: booking.CourtName,         // Thêm dòng này
          Court_type: booking.Court_type,       // Thêm dòng này
          Location: booking.Location,           // Thêm dòng này
          Booking_date: booking.Booking_date,
          Start_time: booking.Start_time,
          End_time: booking.End_time,
          Duration_hours: booking.Duration_hours,
          Price_per_hour: booking.Price_per_hour,
          Total_price: booking.Total_price,
          Note: booking.Note || form.note,
        };
        await axios.post("http://localhost:8000/api/court_bookings", bookingData, {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });
        localStorage.removeItem("pendingBooking");
        // XÓA GIỎ HÀNG nếu là đặt sân
        setCartItems([]);
        localStorage.removeItem("cart");
        navigate("/thankyou", { state: { booking: bookingData } });
      } catch (err) {
        alert("Có lỗi xảy ra khi đặt sân. Vui lòng thử lại!");
      }
      setLoading(false);
      return;
    }

    // Mua sản phẩm cần đủ địa chỉ
    if (
      !form.full_name ||
      !form.phone ||
      !form.email ||
      !form.address ||
      !form.province_code ||
      !form.district_code ||
      !form.ward_code ||
      !cartItems ||
      cartItems.length === 0
    ) {
      alert("Vui lòng điền đầy đủ thông tin và chọn sản phẩm!");
      return;
    }

    setLoading(true);

    // Log từng sản phẩm trong giỏ hàng
    console.log("cartItems chi tiết:", cartItems);

    // Log chi tiết từng sản phẩm trong giỏ hàng
    cartItems.forEach((item, idx) => {
      console.log(`Sản phẩm ${idx}:`, item);
    });

    const order_details = cartItems.map((item) => {
  const obj = {
    Product_ID: Number(item.Product_ID),
    quantity: Number(item.quantity),
    price: Number(item.Price),
    discount_price: Number(item.discount_price || 0),
    total_price: Number((Number(item.Price) - Number(item.discount_price || 0)) * Number(item.quantity)),
  };
  // SỬA: Thêm variant_id nếu tồn tại (kể cả giá trị 0)
  if ('variant_id' in item) {
    obj.variant_id = Number(item.variant_id);
  }
  console.log("order_details obj:", obj);
  return obj;
});

    const user = JSON.parse(localStorage.getItem("user"));
    const orderData = {
      ...form,
      user_id: user?.ID,
      status: "pending",
      total_price: total,
      shipping_fee: shippingFee,
      voucher_id: voucherInfo ? voucherInfo.id : null,
      payment_method: paymentMethod,
      order_details,
    };

    console.log("orderData gửi lên:", orderData);
    console.log("order_details gửi lên:", orderData.order_details);
    orderData.order_details.forEach((item, idx) => {
      console.log(`Sản phẩm ${idx}:`, item);
    });

    try {
      // Gửi đơn hàng lên backend
      await axios.post("http://localhost:8000/api/orders", orderData, {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
      });
      // XÓA GIỎ HÀNG khi đặt hàng thành công
      setCartItems([]);
      localStorage.removeItem("cart");

      // THÊM DÒNG NÀY để cập nhật thông báo ở header
      if (window.dispatchEvent) {
        window.dispatchEvent(new Event("notificationUpdated"));
      }

      navigate("/thankyou", { state: { products: cartItems } });
    } catch (err) {
      alert("Có lỗi xảy ra. Vui lòng thử lại!");
    }
    setLoading(false);

    // Sau khi đặt hàng thành công (trước hoặc sau navigate)
    if (window.dispatchEvent) {
      window.dispatchEvent(new Event("notificationUpdated"));
    }
  };

  // SỬA: KHÔNG xóa giỏ hàng khi tạo đơn hàng và chuyển sang VNPAY
  const handleVnpayPay = async () => {
    const isBooking = cartItems.length === 1 && cartItems[0].Courts_ID;

    if (isBooking) {
      if (
        !form.full_name ||
        !form.phone ||
        !form.email
      ) {
        alert("Vui lòng điền đầy đủ thông tin đặt sân!");
        return;
      }
    } else {
      if (
        !form.full_name ||
        !form.phone ||
        !form.email ||
        !form.address ||
        !form.province_code ||
        !form.district_code ||
        !form.ward_code ||
        !cartItems ||
        cartItems.length === 0
      ) {
        alert("Vui lòng điền đầy đủ thông tin và chọn sản phẩm!");
        return;
      }
    }

    setLoading(true);

    const order_details = cartItems.map((item) => {
      const obj = {
        Product_ID: item.Product_ID,
        quantity: item.quantity,
        price: item.Price,
        discount_price: item.discount_price || 0,
        total_price:
          (Number(item.Price) - (item.discount_price || 0)) * item.quantity,
      };
      if (item.variant_id) obj.variant_id = item.variant_id;
      return obj;
    });

    const user = JSON.parse(localStorage.getItem("user"));
    const orderData = {
      ...form,
      user_id: user?.ID,
      status: "pending",
      total_price: total,
      shipping_fee: shippingFee,
      voucher_id: voucherInfo ? voucherInfo.id : null,
      payment_method: "vnpay",
      order_details,
    };

    try {
      // Tạo đơn hàng trước
      const res = await axios.post("http://localhost:8000/api/orders", orderData, {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
      });

      // KHÔNG xóa giỏ hàng ở đây!

      // Gọi API tạo link VNPAY
      const vnpayRes = await axios.post("http://localhost:8000/api/vnpay/create", {
        total: total,
        orderId: res.data.id || Math.floor(Math.random() * 100000),
      });
      if (!vnpayRes.data.paymentUrl) {
        alert("Không nhận được link thanh toán VNPAY từ server!");
        setLoading(false);
        return;
      }
      window.location.href = vnpayRes.data.paymentUrl;
    } catch (err) {
      alert("Có lỗi xảy ra khi tạo thanh toán VNPAY. Vui lòng thử lại!");
    }
    setLoading(false);
  };

  useEffect(() => {
    const area = form.area || "ngoaithanh";
    const distanceKm = form.distance_km || 10;
    setShippingFee(calculateShippingFee(subtotal, area, distanceKm));
  }, [form, subtotal]);

  return (
    <div className="checkout-right">
      <h3>Tóm Tắt Đơn Hàng</h3>
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
                // Chia đều cho các sản phẩm hợp lệ
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

      <div className="payment-method">
        <p>Phương Thức Thanh Toán</p>
        <label>
          <input
            type="radio"
            name="payment"
            value="cod"
            checked={paymentMethod === "cod"}
            onChange={() => setPaymentMethod("cod")}
          />{" "}
          Thanh toán khi nhận hàng (COD)
        </label>
        <label>
          <input
            type="radio"
            name="payment"
            value="vnpay"
            checked={paymentMethod === "vnpay"}
            onChange={() => setPaymentMethod("vnpay")}
          />{" "}
          Thanh toán qua VNPAY
        </label>
      </div>

      <button
        className="checkout-btn"
        onClick={
          paymentMethod === "vnpay"
            ? handleVnpayPay
            : handleCheckout
        }
        disabled={loading}
      >
        {loading ? "Đang xử lý..." : "Thanh Toán"}
      </button>

      {/* Warning Modal */}
      {showWarning && (
        <div className="modal-overlay" onClick={() => setShowWarning(false)}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <h3>⚠️ Thông báo</h3>
            <p>{warningMsg}</p>
            <button onClick={() => setShowWarning(false)}>Đóng</button>
          </div>
        </div>
      )}
    </div>
  );
};

function calculateShippingFee(subtotal, area, distanceKm) {
  if (subtotal >= 500000) return 0;
  let areaFee = 40000;
  if (area === "noithanh") areaFee = 20000;
  else if (area === "ngoaithanh") areaFee = 40000;
  else if (area === "tinhxa") areaFee = 60000;

  let distanceFee = 40000;
  if (distanceKm <= 5) distanceFee = 15000;
  else if (distanceKm <= 10) distanceFee = 25000;

  return Math.max(areaFee, distanceFee);
}

export default CheckoutRight;