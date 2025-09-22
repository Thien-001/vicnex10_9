import React, { useState, useEffect } from "react";
import "./Checkout.css";

function CheckoutLeft({ cartItems, form, setForm }) {
  // Lấy user từ API (giống UserProfile)
  const [user, setUser] = useState(null);

  // Lấy user ID từ localStorage (giả sử đã lưu khi đăng nhập)
  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (!storedUser) return;
    let userData;
    try {
      userData = JSON.parse(storedUser);
    } catch (e) {
      return;
    }
    if (!userData.ID) return;
    fetch(`http://localhost:8000/api/users/${userData.ID}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
    })
      .then((res) => res.json())
      .then((data) => {
        setUser({
          id: userData.ID,
          name: data.Name || "",
          email: data.Email || "",
          phone: data.Phone || "",
          address: data.Address || "",
          province_code: data.province_code || "",
          district_code: data.district_code || "",
          ward_code: data.ward_code || "",
          province: data.province || "",
          district: data.district || "",
          ward: data.ward || "",
        });
      });
  }, []);

  const [provinces, setProvinces] = useState([]);
  const [districts, setDistricts] = useState([]);
  const [wards, setWards] = useState([]);

  // Lấy tỉnh thành khi mount
  useEffect(() => {
    fetch("https://esgoo.net/api-tinhthanh/1/0.htm")
      .then((res) => res.json())
      .then((data) => {
        if (data.error === 0) setProvinces(data.data);
        else setProvinces([]);
      })
      .catch(() => setProvinces([]));
  }, []);

  // Lấy quận huyện khi chọn tỉnh
  useEffect(() => {
    if (form.province_code) {
      fetch(`https://esgoo.net/api-tinhthanh/2/${form.province_code}.htm`)
        .then((res) => res.json())
        .then((data) => {
          if (data.error === 0) setDistricts(data.data);
          else setDistricts([]);
          setWards([]);
        })
        .catch(() => {
          setDistricts([]);
          setWards([]);
        });
    } else {
      setDistricts([]);
      setWards([]);
    }
  }, [form.province_code]);

  // Lấy phường xã khi chọn huyện
  useEffect(() => {
    if (form.district_code) {
      fetch(`https://esgoo.net/api-tinhthanh/3/${form.district_code}.htm`)
        .then((res) => res.json())
        .then((data) => {
          if (data.error === 0) setWards(data.data);
          else setWards([]);
        })
        .catch(() => setWards([]));
    } else {
      setWards([]);
    }
  }, [form.district_code]);

  // Tự động điền các trường cơ bản (họ tên, sđt, email, địa chỉ)
  useEffect(() => {
    if (user && user.name && !form.full_name) {
      setForm((prev) => ({
        ...prev,
        full_name: user.name || "",
        phone: user.phone || "",
        email: user.email || "",
        address: user.address || "",
      }));
    }
  }, [user, form.full_name, setForm]);

  // Tự động điền province_code nếu có
  useEffect(() => {
    if (
      user &&
      user.province &&
      provinces.length > 0 &&
      !form.province_code
    ) {
      const provinceObj = provinces.find(
        (p) => p.full_name.trim().toLowerCase() === user.province.trim().toLowerCase()
      );
      if (provinceObj) {
        setForm((prev) => ({
          ...prev,
          province_code: provinceObj.id,
        }));
      }
    }
    // Nếu backend trả về code thì ưu tiên code
    if (user && user.province_code && !form.province_code) {
      setForm((prev) => ({
        ...prev,
        province_code: user.province_code,
      }));
    }
  }, [user, provinces, form.province_code, setForm]);

  // Tự động điền district_code nếu có
  useEffect(() => {
    if (
      user &&
      user.district &&
      districts.length > 0 &&
      !form.district_code
    ) {
      const districtObj = districts.find(
        (d) => d.full_name.trim().toLowerCase() === user.district.trim().toLowerCase()
      );
      if (districtObj) {
        setForm((prev) => ({
          ...prev,
          district_code: districtObj.id,
        }));
      }
    }
    if (user && user.district_code && !form.district_code) {
      setForm((prev) => ({
        ...prev,
        district_code: user.district_code,
      }));
    }
  }, [user, districts, form.district_code, setForm]);

  // Tự động điền ward_code nếu có
  useEffect(() => {
    if (
      user &&
      user.ward &&
      wards.length > 0 &&
      !form.ward_code
    ) {
      const wardObj = wards.find(
        (w) => w.full_name.trim().toLowerCase() === user.ward.trim().toLowerCase()
      );
      if (wardObj) {
        setForm((prev) => ({
          ...prev,
          ward_code: wardObj.id,
        }));
      }
    }
    if (user && user.ward_code && !form.ward_code) {
      setForm((prev) => ({
        ...prev,
        ward_code: user.ward_code,
      }));
    }
  }, [user, wards, form.ward_code, setForm]);

  // Xử lý thay đổi form
  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name === "province_code") {
      setForm({
        ...form,
        province_code: value,
        district_code: "",
        ward_code: "",
      });
    } else if (name === "district_code") {
      setForm({
        ...form,
        district_code: value,
        ward_code: "",
      });
    } else {
      setForm({
        ...form,
        [name]: value,
      });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const total_price = Number(localStorage.getItem("cartTotal")) || 0;
    const shipping_fee = 30000;
    const voucher_id = null;

    if (!form.province_code || !form.district_code || !form.ward_code || !form.address || cartItems.length === 0) {
      alert("Vui lòng điền đầy đủ thông tin và chọn sản phẩm!");
      return;
    }

    try {
      const res = await fetch("http://localhost:8000/api/orders", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          ...form,
          status: "pending",
          total_price,
          shipping_fee,
          voucher_id,
          cart: cartItems,
        }),
      });

      const result = await res.text();
      if (!res.ok) {
        alert("Đặt hàng thất bại: " + (result || "Lỗi không xác định!"));
      } else {
        alert("Đặt hàng thành công!");
      }
    } catch (err) {
      alert("Lỗi kết nối: " + err.message);
    }
  };

  const isBooking = cartItems.length === 1 && cartItems[0].Courts_ID;

  return (
    <div style={{ marginTop: "20px", marginBottom: "20px"}} className="checkout-left">
      <h3 style={{ marginLeft: "20px", fontWeight:"700", fontSize: "25px" }}>Thông Tin Giao Hàng</h3>
      {(!cartItems || cartItems.length === 0) ? (
        <div
          style={{
            color: "#0154b9",
            fontWeight: "bold",
            marginBottom: 18,
            background: "#e0e7ff",
            borderRadius: 10,
            padding: "18px 12px",
            textAlign: "center",
            boxShadow: "0 2px 8px rgba(1,84,185,0.08)",
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
          }}
        >
          <span style={{ fontSize: 38, marginBottom: 8 }}>🛒</span>
          <span style={{ fontSize: 17, fontWeight: 600 }}>
            Giỏ hàng của bạn đang trống!
          </span>
          <span style={{ fontSize: 15, color: "#333", marginTop: 4 }}>
            Hãy chọn sản phẩm yêu thích để tiếp tục mua sắm nhé 💙
          </span>
          <a
            href="/"
            style={{
              marginTop: 14,
              background: "#0154b9",
              color: "#fff",
              borderRadius: 8,
              padding: "8px 22px",
              fontWeight: 600,
              textDecoration: "none",
              boxShadow: "0 2px 8px rgba(1,84,185,0.08)",
              display: "inline-block",
            }}
          >
            Quay lại trang chủ
          </a>
        </div>
      ) : isBooking ? (
        // FORM ĐẶT SÂN (tùy ý bạn, ví dụ đơn giản bên dưới)
        <form className="booking-form" onSubmit={handleSubmit}>
          <input
            name="full_name"
            type="text"
            placeholder="Họ và tên người đặt sân"
            required
            value={form.full_name}
            onChange={handleChange}
          />
          <input
            name="phone"
            type="text"
            placeholder="Số điện thoại liên hệ"
            required
            value={form.phone}
            onChange={handleChange}
          />
          <input
            name="email"
            type="email"
            placeholder="Email"
            required
            value={form.email}
            onChange={handleChange}
          />
          <textarea
            name="note"
            placeholder="Ghi chú cho đặt sân (nếu có)"
            value={form.note}
            onChange={handleChange}
          ></textarea>
          
        </form>
      ) : (
        // FORM MUA SẢN PHẨM (giữ nguyên như hiện tại)
        <form className="shipping-form" onSubmit={handleSubmit}>
          <input
            name="full_name"
            type="text"
            placeholder="Họ và tên"
            required
            value={form.full_name}
            onChange={handleChange}
          />
          <input
            name="phone"
            type="text"
            placeholder="Số điện thoại"
            required
            value={form.phone}
            onChange={handleChange}
          />
          <input
            name="email"
            type="email"
            placeholder="Email"
            required
            value={form.email}
            onChange={handleChange}
          />

          {/* Chọn tỉnh */}
          <select
            name="province_code"
            required
            value={form.province_code}
            onChange={handleChange}
          >
            <option value="">Chọn tỉnh/thành phố</option>
            {provinces.map((p) => (
              <option key={p.id} value={p.id}>
                {p.full_name}
              </option>
            ))}
          </select>

          {/* Chọn huyện */}
          <select
            name="district_code"
            required
            value={form.district_code}
            onChange={handleChange}
            disabled={!form.province_code}
          >
            <option value="">Chọn quận/huyện</option>
            {districts.map((d) => (
              <option key={d.id} value={d.id}>
                {d.full_name}
              </option>
            ))}
          </select>

          {/* Chọn xã */}
          <select
            name="ward_code"
            required
            value={form.ward_code}
            onChange={handleChange}
            disabled={!form.district_code}
          >
            <option value="">Chọn phường/xã</option>
            {wards.map((w) => (
              <option key={w.id} value={w.id}>
                {w.full_name}
              </option>
            ))}
          </select>

          <input
            name="address"
            type="text"
            placeholder="Địa chỉ nhận hàng chi tiết"
            value={form.address}
            onChange={handleChange}
          />
          <textarea
            name="note"
            placeholder="Ghi chú cho đơn hàng"
            value={form.note}
            onChange={handleChange}
          ></textarea>

          <div className="checkout-links">
            <a href="/" className="back-link">
              Quay lại trang chủ
            </a>
            <a href="/" className="continue-link">
              Tiếp tục mua sắm
            </a>
          </div>
        </form>
      )}
    </div>
  );
}

export default CheckoutLeft;
