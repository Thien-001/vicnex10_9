import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";

function UserProfile() {
  const [user, setUser] = useState(null);
  const [orders, setOrders] = useState([]);
  const [bookings, setBookings] = useState([]);
  const [activeTab, setActiveTab] = useState(1);
  const [editUser, setEditUser] = useState({});
  const [passwords, setPasswords] = useState({
    currentPassword: "",
    newPassword: "",
    confirmNewPassword: "",
  });
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);
  const [selectedOrder, setSelectedOrder] = useState(null);
  const [selectedBooking, setSelectedBooking] = useState(null);
  const [showSuccessModal, setShowSuccessModal] = useState(false);

  const navigate = useNavigate();

  // Thêm state cho tỉnh/huyện/xã
  const [provinces, setProvinces] = useState([]);
  const [districts, setDistricts] = useState([]);
  const [wards, setWards] = useState([]);

  // Lấy danh sách tỉnh
  useEffect(() => {
    fetch("https://esgoo.net/api-tinhthanh/1/0.htm")
      .then((res) => res.json())
      .then((data) => {
        if (data.error === 0) setProvinces(data.data);
        else setProvinces([]);
      })
      .catch(() => setProvinces([]));
  }, []);

  // Lấy danh sách huyện khi chọn tỉnh
  useEffect(() => {
    if (editUser.province_code) {
      fetch(`https://esgoo.net/api-tinhthanh/2/${editUser.province_code}.htm`)
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
  }, [editUser.province_code]);

  // Lấy danh sách xã khi chọn huyện
  useEffect(() => {
    if (editUser.district_code) {
      fetch(`https://esgoo.net/api-tinhthanh/3/${editUser.district_code}.htm`)
        .then((res) => res.json())
        .then((data) => {
          if (data.error === 0) setWards(data.data);
          else setWards([]);
        })
        .catch(() => setWards([]));
    } else {
      setWards([]);
    }
  }, [editUser.district_code]);

  // Lấy thông tin user
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
    axios.get(`http://localhost:8000/api/users/${userData.ID}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
    }).then(res => {
      setUser({
        id: userData.ID,
        name: res.data.Name || "",
        email: res.data.Email || "",
        phone: res.data.Phone || "",
        gender: res.data.Gender || "",
        dob: formatDate(res.data.Date_of_birth),
        address: res.data.Address || "",
        avatar: res.data.Avatar || res.data.avatar || "",
        province_code: res.data.province_code || "",
        district_code: res.data.district_code || "",
        ward_code: res.data.ward_code || "",
        province: res.data.province || "",
        district: res.data.district || "",
        ward: res.data.ward || "",
      });
      setEditUser({
        name: res.data.Name || "",
        phone: res.data.Phone || "",
        gender: res.data.Gender || "",
        dob: formatDate(res.data.Date_of_birth),
        address: res.data.Address || "",
        province_code: res.data.province_code || "",
        district_code: res.data.district_code || "",
        ward_code: res.data.ward_code || "",
        province: res.data.province || "",
        district: res.data.district || "",
        ward: res.data.ward || "",
      });
    });
  }, []);

  // Lấy lịch sử đơn hàng (có phân trang)
  const [orderPage, setOrderPage] = useState(1);
  const [orderPagination, setOrderPagination] = useState({});

  // Lấy danh sách sản phẩm để map slug nếu cần
  const [productSlugMap, setProductSlugMap] = useState({});
  useEffect(() => {
    axios.get("http://localhost:8000/api/products").then(res => {
      const map = {};
      (res.data.data || []).forEach(p => {
        map[p.Product_ID] = p.slug;
      });
      setProductSlugMap(map);
    });
  }, []);

  // Lấy lịch sử đơn hàng, bổ sung slug cho từng sản phẩm
  useEffect(() => {
    if (!user || !user.id) return;
    axios.get(`http://localhost:8000/api/orders/user/${user.id}?page=${orderPage}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
    }).then(res => {
      const ordersWithSlug = (res.data.data || []).map(order => ({
        ...order,
        order_details: (order.order_details || []).map(item => ({
          ...item,
          slug: item.slug || productSlugMap[item.Product_ID] || "",
        }))
      }));
      setOrders(ordersWithSlug);
      setOrderPagination({
        current: res.data.current_page,
        last: res.data.last_page,
        total: res.data.total,
      });
    });
  }, [user, orderPage, productSlugMap]);

  // Lấy lịch sử đặt sân (có phân trang)
  const [bookingPage, setBookingPage] = useState(1);
  const [bookingPagination, setBookingPagination] = useState({});

  useEffect(() => {
    if (!user || !user.id) return;
    axios.get(`http://localhost:8000/api/court_bookings/user/${user.id}?page=${bookingPage}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
    }).then(res => {
      setBookings(res.data.data || []);
      setBookingPagination({
        current: res.data.current_page,
        last: res.data.last_page,
        total: res.data.total,
      });
    });
  }, [user, bookingPage]);

  function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    if (isNaN(d)) return "";
    const month = (d.getMonth() + 1).toString().padStart(2, "0");
    const day = d.getDate().toString().padStart(2, "0");
    const year = d.getFullYear();
    return `${year}-${month}-${day}`;
  }

  // Sửa hàm để reset huyện/xã khi đổi tỉnh/huyện
  function handleEditChange(e) {
    const { name, value } = e.target;
    if (name === "province_code") {
      setEditUser(prev => ({
        ...prev,
        province_code: value,
        district_code: "",
        ward_code: "",
      }));
    } else if (name === "district_code") {
      setEditUser(prev => ({
        ...prev,
        district_code: value,
        ward_code: "",
      }));
    } else {
      setEditUser(prev => ({
        ...prev,
        [name]: value,
      }));
    }
  }

  function handlePasswordChange(e) {
    setPasswords(prev => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  }

  async function handleUpdate(e) {
    e.preventDefault();
    setMessage("");
    setLoading(true);

    if (
      !editUser.name ||
      !editUser.phone ||
      !editUser.gender ||
      !editUser.dob ||
      !editUser.address ||
      !editUser.province_code ||
      !editUser.district_code ||
      !editUser.ward_code
    ) {
      setMessage("Vui lòng nhập đầy đủ thông tin.");
      setLoading(false);
      return;
    }
    if (
      passwords.newPassword ||
      passwords.confirmNewPassword ||
      passwords.currentPassword
    ) {
      if (!passwords.currentPassword) {
        setMessage("Nhập mật khẩu hiện tại để đổi mật khẩu.");
        setLoading(false);
        return;
      }
      if (!passwords.newPassword || !passwords.confirmNewPassword) {
        setMessage("Nhập đầy đủ mật khẩu mới và xác nhận.");
        setLoading(false);
        return;
      }
      if (passwords.newPassword !== passwords.confirmNewPassword) {
        setMessage("Mật khẩu mới và xác nhận không khớp.");
        setLoading(false);
        return;
      }
      if (passwords.newPassword.length < 6) {
        setMessage("Mật khẩu mới phải có ít nhất 6 ký tự.");
        setLoading(false);
        return;
      }
    }

    // Lấy tên tỉnh/huyện/xã từ code
    const provinceObj = provinces.find((p) => p.id === editUser.province_code);
    const districtObj = districts.find((d) => d.id === editUser.district_code);
    const wardObj = wards.find((w) => w.id === editUser.ward_code);

    try {
      const formData = new FormData();
      formData.append("Name", editUser.name);
      formData.append("Phone", editUser.phone);
      formData.append("Gender", editUser.gender);
      formData.append("Date_of_birth", editUser.dob);
      formData.append("Address", editUser.address);
      formData.append("province", provinceObj ? provinceObj.full_name : "");
      formData.append("district", districtObj ? districtObj.full_name : "");
      formData.append("ward", wardObj ? wardObj.full_name : "");
      // Không gửi code lên backend nữa
      if (editUser.avatarFile) {
        formData.append("Avatar", editUser.avatarFile);
      }
      if (passwords.currentPassword)
        formData.append("currentPassword", passwords.currentPassword);
      if (passwords.newPassword)
        formData.append("newPassword", passwords.newPassword);

      // Gửi request cập nhật, nhận lại user mới từ backend (có avatar mới nếu có)
      const res = await axios.post(
        `http://localhost:8000/api/users/${user.id}`,
        formData,
        { headers: { "Content-Type": "multipart/form-data" } }
      );

      // Cập nhật lại user với dữ liệu mới nhất từ backend (có avatar mới nếu có)
      setUser((prev) => ({
        ...prev,
        name: res.data.Name || "",
        email: res.data.Email || "",
        phone: res.data.Phone || "",
        gender: res.data.Gender || "",
        dob: formatDate(res.data.Date_of_birth),
        address: res.data.Address || "",
        avatar: res.data.Avatar || res.data.avatar || prev.avatar,
        province_code: editUser.province_code,
        district_code: editUser.district_code,
        ward_code: editUser.ward_code,
        province: res.data.province || (provinceObj ? provinceObj.full_name : ""),
        district: res.data.district || (districtObj ? districtObj.full_name : ""),
        ward: res.data.ward || (wardObj ? wardObj.full_name : ""),
      }));

      setEditUser((prev) => ({
        ...prev,
        avatarPreview: undefined,
        avatarFile: undefined,
      }));
      setPasswords({
        currentPassword: "",
        newPassword: "",
        confirmNewPassword: "",
      });
      setMessage("Cập nhật thành công!");
      setShowSuccessModal(true);
    } catch (err) {
      setMessage("Có lỗi xảy ra khi cập nhật.");
    } finally {
      setLoading(false);
    }
  }

  function BookingHistoryBox({ order, onCancel }) {
    return (
      <div
        style={{
          background: "#fff",
          borderRadius: 8,
          padding: 20,
          margin: "24px 0",
          boxShadow: "0 2px 12px rgba(1, 84, 185, 0.06)",
          maxWidth: 1400,
          width: "100%",
          marginLeft: "auto",
          marginRight: "auto"
        }}
      >
        <h3 style={{ color: "#0154b9", marginBottom: 12 }}>
          Đơn đặt sân #{order.id}
        </h3>
        <div><b>Ngày đặt:</b> {order.date}</div>
        <div><b>Trạng thái:</b> {order.status}</div>
        {/* Thêm các thông tin khác nếu cần */}
        {order.status !== "cancelled" && (
          <button
            onClick={() => onCancel(order)}
            style={{
              background: "#0154b9",
              color: "#fff",
              border: "none",
              borderRadius: 8,
              padding: "8px 24px",
              fontWeight: 600,
              fontSize: "1rem",
              cursor: "pointer",
              marginTop: 8
            }}
          >
            Hủy đơn hàng
          </button>
        )}
      </div>
    );
  }

  // Thêm hàm xử lý hủy đặt sân
  const handleCancelBooking = async (booking) => {
    if (!window.confirm("Bạn chắc chắn muốn hủy đặt sân này?")) return;
    try {
      await axios.post(
        `http://localhost:8000/api/court-bookings/${booking.Court_booking_ID}/cancel`,
        {},
        { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
      );
      alert("Đã hủy đặt sân!");
      setBookings(prev =>
        prev.map(b =>
          b.Court_booking_ID === booking.Court_booking_ID
            ? { ...b, Status: -1 }
            : b
        )
      );
      setSelectedBooking(null);
    } catch (err) {
      // Log lỗi chi tiết ra console
      if (err.response) {
        console.error("Lỗi backend:", err.response.data);
        alert("Hủy đặt sân thất bại: " + (err.response.data?.message || "Lỗi không xác định"));
      } else {
        console.error("Lỗi không xác định:", err);
        alert("Hủy đặt sân thất bại!");
      }
    }
  };

  // Map tên tỉnh sang code nếu chưa có code
  useEffect(() => {
    if (
      user &&
      user.province &&
      provinces.length > 0 &&
      !user.province_code
    ) {
      const provinceObj = provinces.find(
        (p) => p.full_name.trim().toLowerCase() === user.province.trim().toLowerCase()
      );
      if (provinceObj) {
        setUser(prev => ({ ...prev, province_code: provinceObj.id }));
        setEditUser(prev => ({ ...prev, province_code: provinceObj.id }));
      }
    }
    // eslint-disable-next-line
  }, [user, provinces]);

  // Map tên huyện sang code nếu chưa có code
  useEffect(() => {
    if (
      user &&
      user.district &&
      districts.length > 0 &&
      !user.district_code
    ) {
      const districtObj = districts.find(
        (d) => d.full_name.trim().toLowerCase() === user.district.trim().toLowerCase()
      );
      if (districtObj) {
        setUser(prev => ({ ...prev, district_code: districtObj.id }));
        setEditUser(prev => ({ ...prev, district_code: districtObj.id }));
      }
    }
    // eslint-disable-next-line
  }, [user, districts]);

  // Map tên xã sang code nếu chưa có code
  useEffect(() => {
    if (
      user &&
      user.ward &&
      wards.length > 0 &&
      !user.ward_code
    ) {
      const wardObj = wards.find(
        (w) => w.full_name.trim().toLowerCase() === user.ward.trim().toLowerCase()
      );
      if (wardObj) {
        setUser(prev => ({ ...prev, ward_code: wardObj.id }));
        setEditUser(prev => ({ ...prev, ward_code: wardObj.id }));
      }
    }
    // eslint-disable-next-line
  }, [user, wards]);

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, []);

  if (!user) return (
    <>
      <Header />
      <div style={{ textAlign: "center", margin: "80px 0", fontSize: 20, color: "#0154b9" }}>
        Đang tải thông tin người dùng...
      </div>
      <Footer />
    </>
  );

  return (
    <>
      <Header />
      <div
        style={{
          display: "flex",
          maxWidth: 1600,
          margin: "48px auto",
          background: "linear-gradient(120deg, #f6f8fc 60%, #e0e7ff 100%)",
          borderRadius: 32,
          boxShadow: "0 8px 40px rgba(1,84,185,0.13)",
          minHeight: 600,
          padding: 0,
          position: "relative",
          border: "1.5px solid #e0e7ff",
          overflow: "hidden",
        }}
      >
        {/* Menu bên trái */}
        <div
          style={{
            width: 240,
            borderRight: "1.5px solid #e5e7eb",
            background: "rgba(246,248,252,0.98)",
            borderRadius: "32px 0 0 32px",
            padding: "40px 0",
            boxShadow: "2px 0 16px rgba(1,84,185,0.04)",
            minHeight: 600,
            display: "flex",
            flexDirection: "column",
            gap: 0,
          }}
        >
          <div
            style={{
              padding: "16px 32px",
              cursor: "pointer",
              background: activeTab === 1 ? "#e0e7ff" : "none",
              color: activeTab === 1 ? "#0154b9" : "#333",
              fontWeight: activeTab === 1 ? 700 : 500,
              borderRadius: "0 24px 24px 0",
              marginBottom: 8,
            }}
            onClick={() => setActiveTab(1)}
            className="profile-menu-item"
          >
            <i className="fas fa-user" style={{ marginRight: 10 }} /> Thông tin cá nhân
          </div>
          <div
            style={{
              padding: "16px 32px",
              cursor: "pointer",
              background: activeTab === 2 ? "#e0e7ff" : "none",
              color: activeTab === 2 ? "#0154b9" : "#333",
              fontWeight: activeTab === 2 ? 700 : 500,
              borderRadius: "0 24px 24px 0",
              marginBottom: 8,
            }}
            onClick={() => setActiveTab(2)}
            className="profile-menu-item"
          >
            <i className="fas fa-shopping-bag" style={{ marginRight: 10 }} /> Lịch sử đặt hàng
          </div>
          <div
            style={{
              padding: "16px 32px",
              cursor: "pointer",
              background: activeTab === 3 ? "#e0e7ff" : "none",
              color: activeTab === 3 ? "#0154b9" : "#333",
              fontWeight: activeTab === 3 ? 700 : 500,
              borderRadius: "0 24px 24px 0",
              marginBottom: 8,
            }}
            onClick={() => setActiveTab(3)}
            className="profile-menu-item"
          >
            <i className="fas fa-edit" style={{ marginRight: 10 }} /> Thay đổi thông tin
          </div>
          <div
            style={{
              padding: "16px 32px",
              cursor: "pointer",
              background: activeTab === 4 ? "#e0e7ff" : "none",
              color: activeTab === 4 ? "#0154b9" : "#333",
              fontWeight: activeTab === 4 ? 700 : 500,
              borderRadius: "0 24px 24px 0",
              marginBottom: 8,
            }}
            onClick={() => setActiveTab(4)}
            className="profile-menu-item"
          >
            <i className="fas fa-calendar-check" style={{ marginRight: 10 }} /> Lịch sử đặt sân
          </div>
        </div>

        {/* Nội dung bên phải */}
        <div
          style={{
            flex: 1,
            padding: "48px 56px",
            minHeight: 600,
            background: "#fff",
            borderRadius: "0 32px 32px 0",
            boxShadow: "none",
            position: "relative",
          }}
        >
          {activeTab === 1 && (
            <div>
              <h2 style={{ color: "#0154b9", fontWeight: 700, marginBottom: 24 }}>Thông tin cá nhân</h2>
              <div style={{ display: "flex", alignItems: "center", marginBottom: 24 }}>
                <img
                  src={
                    user.avatar
                      ? (user.avatar.startsWith("http") ? user.avatar : `/${user.avatar}`)
                      : user.gender === "female"
                        ? "/img/avt/default-avatar-female.png"
                        : user.gender === "male"
                          ? "/img/avt/default-avatar-male.png"
                          : "/img/avt/lgpt.png"
                  }
                  alt="Avatar"
                  style={{
                    width: 300,
                    height: 300,
                    borderRadius: "50%",
                    objectFit: "cover",
                    boxShadow: "0 2px 8px #e0e7ff",
                    marginRight: 100,
                    border: "2px solid #e0e7ff",
                    background: "#f6f8fc"
                  }}
                />
                <div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}><b>Họ tên:</b> {user.name}</div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}><b>Email:</b> {user.email}</div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}><b>Số điện thoại:</b> {user.phone}</div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}><b>Giới tính:</b> {user.gender === "male" ? "Nam" : user.gender === "female" ? "Nữ" : "Khác"}</div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}><b>Ngày sinh:</b> {user.dob}</div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}><b>Địa chỉ:</b> {user.address}</div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}>
                    <b>Tỉnh/Thành phố:</b> {provinces.find(p => p.id === user.province_code)?.full_name || ""}
                  </div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}>
                    <b>Quận/Huyện:</b> {districts.find(d => d.id === user.district_code)?.full_name || ""}
                  </div>
                  <div style={{ fontSize: 17, marginBottom: 12 }}>
                    <b>Phường/Xã:</b> {wards.find(w => w.id === user.ward_code)?.full_name || ""}
                  </div>
                </div>
              </div>
            </div>
          )}
          {activeTab === 2 && (
            <div>
              <h2 style={{ color: "#0154b9", fontWeight: 700, marginBottom: 24 }}>Lịch sử đặt hàng</h2>
              {orders.length === 0 ? (
                <div style={{ color: "#888", fontSize: 16 }}>Bạn chưa có đơn hàng nào.</div>
              ) : (
                <>
                  <table style={{ width: "100%", borderCollapse: "collapse", background: "#fff", borderRadius: 8, minWidth: 900 }}>
                    <thead>
                      <tr style={{ background: "#e0e7ff" }}>
                        <th style={{ padding: "8px 12px", borderRadius: 6 }}>Mã đơn</th>
                        <th style={{ padding: "8px 12px" }}>Ngày đặt</th>
                        <th style={{ padding: "8px 12px", minWidth: 200 }}>Tổng tiền</th>
                        <th style={{ padding: "8px 12px" }}>Trạng thái</th>
                        <th style={{ padding: "8px 12px" }}>Sản phẩm</th>
                      </tr>
                    </thead>
                    <tbody>
                      {orders.map(order => (
                        <tr
                          key={order.id}
                          style={{ background: "#fff", cursor: "pointer" }}
                          onClick={() => setSelectedOrder(order)}
                        >
                          <td style={{ padding: "8px 12px" }}>{order.id}</td>
                          <td style={{ padding: "8px 12px" }}>
                            {new Date(order.created_at).toLocaleDateString("vi-VN")}
                          </td>
                          <td style={{ padding: "8px 12px", color: "#d32f2f", fontWeight: 600, minWidth: 200 }}>
                            {order.total_price ? Number(order.total_price).toLocaleString() : 0}₫
                            {order.shipping_fee &&
                              <span style={{ color: "#888", fontSize: 13, marginLeft: 8 }}>
                                + Phí ship: {Number(order.shipping_fee).toLocaleString()}₫
                              </span>
                            }
                          </td>
                          <td style={{ padding: "8px 12px" }}>
                            <span style={{
                              padding: "4px 14px",
                              borderRadius: 14,
                              fontWeight: 700,
                              fontSize: 15,
                              background:
                                order.status === "completed" ? "#e0f7ef" :
                                order.status === "cancelled" ? "#ffeaea" :
                                order.status === "shipping" ? "#e3f0ff" :
                                order.status === "confirmed" ? "#e0e7ff" :
                                "#fffbe7",
                              color:
                                order.status === "completed" ? "#10b981" :
                                order.status === "cancelled" ? "#e11d48" :
                                order.status === "shipping" ? "#0154b9" :
                                order.status === "confirmed" ? "#0154b9" :
                                "#f59e42",
                              display: "inline-block"
                            }}>
                              {order.status === "pending" && "Chờ xác nhận"}
                              {order.status === "confirmed" && "Đã xác nhận"}
                              {order.status === "shipping" && "Đang giao"}
                              {order.status === "completed" && "Hoàn thành"}
                              {order.status === "cancelled" && "Đã huỷ"}
                              {!["pending", "confirmed", "shipping", "completed", "cancelled"].includes(order.status) && order.status}
                            </span>
                          </td>
                          <td style={{ padding: "8px 12px" }}>
                            {order.order_details && order.order_details.length > 0
                              ? `${order.order_details.reduce((sum, item) => sum + (item.quantity || 0), 0)} sản phẩm`
                              : <span style={{ color: "#888", fontSize: 14 }}>Không có sản phẩm nào.</span>
                            }
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                  {/* Phân trang */}
                  {orderPagination.last > 1 && (
                    <div style={{ textAlign: "center", marginTop: 24 }}>
                      <button
                        disabled={orderPagination.current === 1}
                        onClick={() => setOrderPage(orderPagination.current - 1)}
                        style={{ marginRight: 8, padding: "6px 16px", borderRadius: 6, border: "1px solid #e5e7eb", background: "#fff", cursor: orderPagination.current === 1 ? "not-allowed" : "pointer" }}
                      >Trước</button>
                      <span style={{ fontWeight: 500, color: "#0154b9" }}>
                        Trang {orderPagination.current} / {orderPagination.last}
                      </span>
                      <button
                        disabled={orderPagination.current === orderPagination.last}
                        onClick={() => setOrderPage(orderPagination.current + 1)}
                        style={{ marginLeft: 8, padding: "6px 16px", borderRadius: 6, border: "1px solid #e5e7eb", background: "#fff", cursor: orderPagination.current === orderPagination.last ? "not-allowed" : "pointer" }}
                      >Sau</button>
                    </div>
                  )}
                </>
              )}
            </div>
          )}
          {activeTab === 3 && (
            <div>
              <h2 style={{ color: "#0154b9", fontWeight: 700, marginBottom: 24 }}>Thay đổi thông tin</h2>
              <form onSubmit={handleUpdate}>
                <div className="profile-field">
                  <label>Họ tên:</label>
                  <input name="name" value={editUser.name} onChange={handleEditChange} className="profile-input" />
                </div>
                <div className="profile-field">
                  <label>Số điện thoại:</label>
                  <input name="phone" value={editUser.phone} onChange={handleEditChange} className="profile-input" />
                </div>
                <div className="profile-field">
                  <label>Giới tính:</label>
                  <select name="gender" value={editUser.gender} onChange={handleEditChange} className="profile-input">
                    <option value="">Chọn giới tính</option>
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                    <option value="other">Khác</option>
                  </select>
                </div>
                <div className="profile-field">
                  <label>Ngày sinh:</label>
                  <input type="date" name="dob" value={editUser.dob} onChange={handleEditChange} className="profile-input" />
                </div>
                <div className="profile-field">
                  <label>Địa chỉ:</label>
                  <input name="address" value={editUser.address} onChange={handleEditChange} className="profile-input" />
                </div>
                <div className="profile-field">
                  <label>Tỉnh/Thành phố:</label>
                  <select
                    name="province_code"
                    value={editUser.province_code || ""}
                    onChange={handleEditChange}
                    className="profile-input"
                    required
                  >
                    <option value="">Chọn tỉnh/thành phố</option>
                    {provinces.map((p) => (
                      <option key={p.id} value={p.id}>{p.full_name}</option>
                    ))}
                  </select>
                </div>
                <div className="profile-field">
                  <label>Quận/Huyện:</label>
                  <select
                    name="district_code"
                    value={editUser.district_code || ""}
                    onChange={handleEditChange}
                    className="profile-input"
                    required
                    disabled={!editUser.province_code}
                  >
                    <option value="">Chọn quận/huyện</option>
                    {districts.map((d) => (
                      <option key={d.id} value={d.id}>{d.full_name}</option>
                    ))}
                  </select>
                </div>
                <div className="profile-field">
                  <label>Phường/Xã:</label>
                  <select
                    name="ward_code"
                    value={editUser.ward_code || ""}
                    onChange={handleEditChange}
                    className="profile-input"
                    required
                    disabled={!editUser.district_code}
                  >
                    <option value="">Chọn phường/xã</option>
                    {wards.map((w) => (
                      <option key={w.id} value={w.id}>{w.full_name}</option>
                    ))}
                  </select>
                </div>
                <div className="profile-field">
                  <label>Ảnh đại diện:</label>
                  <input
                    type="file"
                    accept="image/*"
                    onChange={e => {
                      if (e.target.files && e.target.files[0]) {
                        setEditUser(prev => ({
                          ...prev,
                          avatarFile: e.target.files[0],
                          avatarPreview: URL.createObjectURL(e.target.files[0]),
                        }));
                      }
                    }}
                    className="profile-input"
                    style={{ background: "#fff" }}
                  />
                  {editUser.avatarPreview ? (
                    <img
                      src={editUser.avatarPreview}
                      alt="Avatar preview"
                      style={{ width: 120, height: 120, borderRadius: "50%", marginTop: 10, objectFit: "cover" }}
                    />
                  ) : user.avatar ? (
                    <img
                      src={user.avatar.startsWith("http") ? user.avatar : `/${user.avatar}`}
                      alt="Avatar"
                      style={{ width: 120, height: 120, borderRadius: "50%", marginTop: 10, objectFit: "cover" }}
                    />
                  ) : null}
                </div>
                <div style={{ marginTop: 32 }}>
                  <h3 style={{ fontSize: 18, fontWeight: 600, color: "#0154b9", marginBottom: 18 }}>Đổi mật khẩu (tuỳ chọn)</h3>
                  <input type="password" name="currentPassword" placeholder="Mật khẩu hiện tại" value={passwords.currentPassword} onChange={handlePasswordChange} className="profile-input" style={{ marginBottom: 12 }} />
                  <input type="password" name="newPassword" placeholder="Mật khẩu mới" value={passwords.newPassword} onChange={handlePasswordChange} className="profile-input" style={{ marginBottom: 12 }} />
                  <input type="password" name="confirmNewPassword" placeholder="Xác nhận mật khẩu mới" value={passwords.confirmNewPassword} onChange={handlePasswordChange} className="profile-input" style={{ marginBottom: 18 }} />
                </div>
                {message && (
                  <div style={{ color: "#e11d48", marginBottom: 18, textAlign: "center" }}>{message}</div>
                )}
                <div style={{ textAlign: "center", marginTop: 32 }}>
                  <button type="submit" disabled={loading} style={{
                    padding: "10px 32px",
                    fontSize: 16,
                    background: "#10b981",
                    color: "#fff",
                    border: "none",
                    borderRadius: 8,
                    fontWeight: 600,
                    cursor: loading ? "not-allowed" : "pointer",
                    boxShadow: "0 2px 8px rgba(16,185,129,0.08)"
                  }}>{loading ? "Đang lưu..." : "Lưu thay đổi"}</button>
                </div>
              </form>
            </div>
          )}
          {activeTab === 4 && (
            <div>
              <h2 style={{ color: "#0154b9", fontWeight: 700, marginBottom: 24 }}>Lịch sử đặt sân</h2>
              {bookings.length === 0 ? (
                <div style={{ color: "#888", fontSize: 16 }}>Bạn chưa có lịch sử đặt sân nào.</div>
              ) : (
                <>
                  <table style={{ width: "100%", borderCollapse: "collapse", background: "#fff", borderRadius: 8, minWidth: 900 }}>
                    <thead>
                      <tr style={{ background: "#e0e7ff" }}>
                        <th style={{ padding: "8px 12px", borderRadius: 6 }}>Mã đặt sân</th>
                        <th style={{ padding: "8px 12px" }}>Tên sân</th>
                        <th style={{ padding: "8px 12px" }}>Loại sân</th>
                        <th style={{ padding: "8px 12px" }}>Địa điểm</th>
                        <th style={{ padding: "8px 12px" }}>Ngày</th>
                        <th style={{ padding: "8px 12px" }}>Khung giờ</th>
                        <th style={{ padding: "8px 12px" }}>Tổng tiền</th>
                        <th style={{ padding: "8px 12px" }}>Trạng thái</th>
                      </tr>
                    </thead>
                    <tbody>
                      {bookings.map(booking => (
                        <tr
                          key={booking.Court_booking_ID}
                          style={{ cursor: "pointer" }}
                          onClick={() => setSelectedBooking(booking)}
                        >
                          <td>{booking.Court_booking_ID}</td>
                          <td style={{ padding: "8px 12px" }}>{booking.CourtName || booking.court_name}</td>
                          <td style={{ padding: "8px 12px" }}>{booking.Court_type || booking.court_type}</td>
                          <td style={{ padding: "8px 12px" }}>{booking.Location || booking.location}</td>
                          <td style={{ padding: "8px 12px" }}>{booking.Booking_date}</td>
                          <td style={{ padding: "8px 12px" }}>
                            {booking.Start_time?.slice(0,5)} - {booking.End_time?.slice(0,5)}
                          </td>
                          <td style={{ padding: "8px 12px", color: "#d32f2f", fontWeight: 600 }}>
                            {Number(booking.Total_price).toLocaleString()}₫
                          </td>
                          <td style={{
                            padding: "8px 12px",
                            color:
                              booking.Status === -1 ? "#e11d48" :
                              booking.Status === 1 ? "#10b981" :
                              "#f59e42",
                            fontWeight: 600
                          }}>
                            {booking.Status === 1 && "Đã xác nhận"}
                            {booking.Status === 0 && "Chờ xác nhận"}
                            {booking.Status === -1 && "Đã huỷ"}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                  {/* Phân trang nếu cần */}
                  {bookingPagination.last > 1 && (
                    <div style={{ textAlign: "center", marginTop: 24 }}>
                      <button
                        disabled={bookingPagination.current === 1}
                        onClick={() => setBookingPage(bookingPagination.current - 1)}
                        style={{ marginRight: 8, padding: "6px 16px", borderRadius: 6, border: "1px solid #e5e7eb", background: "#fff", cursor: bookingPagination.current === 1 ? "not-allowed" : "pointer" }}
                      >Trước</button>
                      <span style={{ fontWeight: 500, color: "#0154b9" }}>
                        Trang {bookingPagination.current} / {bookingPagination.last}
                      </span>
                      <button
                        disabled={bookingPagination.current === bookingPagination.last}
                        onClick={() => setBookingPage(bookingPagination.current + 1)}
                        style={{ marginLeft: 8, padding: "6px 16px", borderRadius: 6, border: "1px solid #e5e7eb", background: "#fff", cursor: bookingPagination.current === bookingPagination.last ? "not-allowed" : "pointer" }}
                      >Sau</button>
                    </div>
                  )}
                </>
              )}
              {/* Modal chi tiết đặt sân */}
              {selectedBooking && (
                <div
                  style={{
                    position: "fixed",
                    top: 0, left: 0, right: 0, bottom: 0,
                    background: "rgba(0,0,0,0.25)",
                    zIndex: 1000,
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center"
                  }}
                  onClick={() => setSelectedBooking(null)}
                >
                  <div
                    style={{
                      background: "#fff",
                      borderRadius: 18,
                      padding: 36,
                      minWidth: 420,
                      maxWidth: 600,
                      boxShadow: "0 8px 32px rgba(1,84,185,0.18)",
                      position: "relative",
                      border: "2px solid #e0e7ff"
                    }}
                    onClick={e => e.stopPropagation()}
                  >
                    <button
                      onClick={() => setSelectedBooking(null)}
                      style={{
                        position: "absolute",
                        top: 14,
                        right: 18,
                        background: "#e0e7ff",
                        border: "none",
                        fontSize: 28,
                        color: "#0154b9",
                        cursor: "pointer",
                        borderRadius: "50%",
                        width: 38,
                        height: 38,
                        lineHeight: "38px",
                        textAlign: "center",
                        fontWeight: 700,
                        boxShadow: "0 2px 8px #e0e7ff"
                      }}
                      title="Đóng"
                    >×</button>
                    <h2 style={{ color: "#0154b9", marginBottom: 18, fontWeight: 700, textAlign: "center" }}>
                      <i className="fas fa-file-invoice" style={{ marginRight: 10 }} />
                      Đơn hàng #{selectedOrder.id}
                    </h2>
                    <div style={{ marginBottom: 10, textAlign: "center", fontWeight: 600 }}>
                      <span style={{
                        padding: "4px 18px",
                        borderRadius: 16,
                        background:
                          selectedOrder.status === "completed" ? "#e0f7ef" :
                          selectedOrder.status === "cancelled" ? "#ffeaea" :
                          selectedOrder.status === "shipping" ? "#e3f0ff" :
                          "#fffbe7",
                        color:
                          selectedOrder.status === "completed" ? "#10b981" :
                          selectedOrder.status === "cancelled" ? "#e11d48" :
                          selectedOrder.status === "shipping" ? "#0154b9" :
                          "#f59e42",
                        fontWeight: 700,
                        fontSize: 16,
                        display: "inline-block"
                      }}>
                        {selectedOrder.status === "pending" && "Chờ xác nhận"}
                        {selectedOrder.status === "confirmed" && "Đã xác nhận"}
                        {selectedOrder.status === "shipping" && "Đang giao"}
                        {selectedOrder.status === "completed" && "Hoàn thành"}
                        {selectedOrder.status === "cancelled" && "Đã huỷ"}
                        {!["pending", "confirmed", "shipping", "completed", "cancelled"].includes(selectedOrder.status) && selectedOrder.status}
                      </span>
                    </div>
                    <div style={{ margin: "18px 0 10px 0", fontWeight: 600, fontSize: 17 }}>
                      <i className="fas fa-user" style={{ marginRight: 8, color: "#0154b9" }} />
                      Người nhận: <span style={{ fontWeight: 500 }}>{selectedOrder.full_name || selectedOrder.customer_name}</span>
                    </div>
                    <div style={{ marginBottom: 10 }}>
                      <i className="fas fa-phone" style={{ marginRight: 8, color: "#0154b9" }} />
                      Số điện thoại: <span style={{ fontWeight: 500 }}>{selectedOrder.phone}</span>
                    </div>
                    <div style={{ marginBottom: 10 }}>
                      <i className="fas fa-map-marker-alt" style={{ marginRight: 8, color: "#0154b9" }} />
                      Địa chỉ nhận: <span style={{ fontWeight: 500 }}>{selectedOrder.address}</span>
                    </div>
                    <div style={{ marginBottom: 10 }}>
                      <i className="fas fa-calendar-alt" style={{ marginRight: 8, color: "#0154b9" }} />
                      Ngày đặt: <span style={{ fontWeight: 500 }}>{new Date(selectedOrder.created_at).toLocaleString("vi-VN")}</span>
                    </div>
                    <div style={{ marginBottom: 10 }}>
                      <i className="fas fa-credit-card" style={{ marginRight: 8, color: "#0154b9" }} />
                      Phương thức thanh toán: <span style={{ fontWeight: 500 }}>
                        {selectedOrder.payment_method === "cod" ? "Thanh toán khi nhận hàng" : selectedOrder.payment_method}
                      </span>
                    </div>
                    <div style={{ marginBottom: 10 }}>
                      <i className="fas fa-ticket-alt" style={{ marginRight: 8, color: "#0154b9" }} />
                      Voucher áp dụng:{" "}
                      {selectedOrder.voucher_code
                        ? <span style={{ color: "#f59e42", fontWeight: 600 }}>{selectedOrder.voucher_code}</span>
                        : <span style={{ color: "#888" }}>Không</span>
                      }
                    </div>
                    <div style={{ margin: "18px 0 10px 0", fontWeight: 600, fontSize: 17 }}>
                      <i className="fas fa-box-open" style={{ marginRight: 8, color: "#0154b9" }} />
                      Danh sách sản phẩm:
                    </div>
                    <table style={{ width: "100%", borderCollapse: "collapse", marginBottom: 18 }}>
                      <thead>
                        <tr style={{ background: "#e0e7ff" }}>
                          <th style={{ padding: 6, fontWeight: 600 }}>Ảnh</th>
                          <th style={{ padding: 6, fontWeight: 600 }}>Tên sản phẩm</th>
                          <th style={{ padding: 6, fontWeight: 600 }}>SL</th>
                          <th style={{ padding: 6, fontWeight: 600 }}>Giá</th>
                          <th style={{ padding: 6, fontWeight: 600 }}>Thành tiền</th>
                        </tr>
                      </thead>
                      <tbody>
                        {selectedOrder.order_details && selectedOrder.order_details.length > 0 ? (
                          selectedOrder.order_details.map((item, idx) => (
                            <tr key={idx} style={{ background: idx % 2 === 0 ? "#f9fafb" : "#fff" }}>
                              <td style={{ padding: 6 }}>
                                <img
                                  src={
                                    item.product && item.product.Image
                                      ? `http://localhost:8000/${item.product.Image}`
                                      : "/img/no-image.png"
                                  }
                                  alt={item.product_name || item.product?.Name || "Sản phẩm"}
                                  style={{ width: 48, height: 48, objectFit: "cover", borderRadius: 6, border: "1px solid #eee" }}
                                />
                              </td>
                              <td style={{ padding: 6 }}>{item.product_name || item.product?.Name || "Sản phẩm"}</td>
                              <td style={{ padding: 6, textAlign: "center" }}>{item.quantity}</td>
                              <td style={{ padding: 6 }}>
                                {Number(item.price).toLocaleString()}₫
                              </td>
                              <td style={{ padding: 6 }}>
                                {(Number(item.price) * Number(item.quantity)).toLocaleString()}₫
                              </td>
                              <td style={{ padding: 6 }}>
                                {/* Hiển thị nút đánh giá nếu đơn hàng đã hoàn thành */}
                                {selectedOrder.status === "completed" && (
                                  <button
                                    style={{
                                      background: "#FFD600",
                                      color: "#0154b9",
                                      border: "none",
                                      borderRadius: 6,
                                      padding: "6px 18px",
                                      fontWeight: 600,
                                      cursor: "pointer"
                                    }}
                                    onClick={() => {
                                      // Chuyển đến trang chi tiết sản phẩm theo slug
                                      navigate(`/product/${item.slug}`);
                                    }}
                                  >
                                    Đánh giá sản phẩm
                                  </button>
                                )}
                              </td>
                            </tr>
                          ))
                        ) : (
                          <tr>
                            <td colSpan={6} style={{ textAlign: "center", color: "#888" }}>Không có sản phẩm.</td>
                          </tr>
                        )}
                      </tbody>
                    </table>
                    <div style={{ textAlign: "right", fontSize: 16 }}>
                      <div><b>Tổng tiền sản phẩm:</b> {Number(selectedOrder.total_price).toLocaleString()}₫</div>
                      <div><b>Phí vận chuyển:</b> {Number(selectedOrder.shipping_fee).toLocaleString()}₫</div>
                      {selectedOrder.discount && (
                        <div style={{ color: "#e11d48" }}>
                          <b>Giảm giá:</b> -{Number(selectedOrder.discount).toLocaleString()}₫
                        </div>
                      )}
                      <div style={{ fontSize: 18, fontWeight: 700, marginTop: 8 }}>
                        Tổng thanh toán: <span style={{ color: "#0154b9" }}>
                          {(Number(selectedOrder.total_price) + Number(selectedOrder.shipping_fee) - (Number(selectedOrder.discount) || 0)).toLocaleString()}₫
                        </span>
                      </div>
                    </div>
                    {selectedOrder.status === "pending" && (
                      <div style={{ textAlign: "center", marginTop: 18 }}>
                        <button
                          style={{
                            background: "#e11d48",
                            color: "#fff",
                            border: "none",
                            borderRadius: 8,
                            padding: "10px 32px",
                            fontWeight: 600,
                            fontSize: "1rem",
                            cursor: "pointer",
                            marginBottom: 8,
                            boxShadow: "0 2px 8px #e0e7ff"
                          }}
                          onClick={async () => {
                            if (!window.confirm("Bạn chắc chắn muốn hủy đơn hàng này?")) return;
                            try {
                              await axios.post(
                                `http://localhost:8000/api/orders/${selectedOrder.id}/cancel`,
                                {},
                                { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
                              );
                              alert("Đã hủy đơn hàng!");
                              setOrders(prev =>
                                prev.map(order =>
                                  order.id === selectedOrder.id
                                    ? { ...order, status: "cancelled" }
                                    : order
                                )
                              );
                              setSelectedOrder(null);
                            } catch (err) {
                              alert("Hủy đơn hàng thất bại!");
                            }
                          }}
                        >
                          <i className="fas fa-times-circle" style={{ marginRight: 8 }} />
                          Hủy đơn hàng
                        </button>
                      </div>
                    )}
                  </div>
                </div>
              )}
            </div>
          )}
        </div>
      </div>
      <Footer />
      {selectedOrder && (
        <div
          style={{
            position: "fixed",
            top: 0, left: 0, right: 0, bottom: 0,
            background: "rgba(0,0,0,0.25)",
            zIndex: 1000,
            display: "flex",
            alignItems: "center",
            justifyContent: "center"
          }}
          onClick={() => setSelectedOrder(null)}
        >
          <div
            style={{
              background: "#fff",
              borderRadius: 18,
              padding: 36,
              minWidth: 420,
              maxWidth: 600,
              boxShadow: "0 8px 32px rgba(1,84,185,0.18)",
              position: "relative",
              border: "2px solid #e0e7ff"
            }}
            onClick={e => e.stopPropagation()}
          >
            {/* Thêm dòng này để kiểm tra dữ liệu */}
            {console.log("selectedOrder:", selectedOrder)}
            {console.log("selectedOrder.order_details:", selectedOrder?.order_details)}
            <button
              onClick={() => setSelectedOrder(null)}
              style={{
                position: "absolute",
                top: 14,
                right: 18,
                background: "#e0e7ff",
                border: "none",
                fontSize: 28,
                color: "#0154b9",
                cursor: "pointer",
                borderRadius: "50%",
                width: 38,
                height: 38,
                lineHeight: "38px",
                textAlign: "center",
                fontWeight: 700,
                boxShadow: "0 2px 8px #e0e7ff"
              }}
              title="Đóng"
            >×</button>
            <h2 style={{ color: "#0154b9", marginBottom: 18, fontWeight: 700, textAlign: "center" }}>
              <i className="fas fa-file-invoice" style={{ marginRight: 10 }} />
              Đơn hàng #{selectedOrder.id}
            </h2>
            <div style={{ marginBottom: 10, textAlign: "center", fontWeight: 600 }}>
              <span style={{
                padding: "4px 18px",
                borderRadius: 16,
                background:
                  selectedOrder.status === "completed" ? "#e0f7ef" :
                  selectedOrder.status === "cancelled" ? "#ffeaea" :
                  selectedOrder.status === "shipping" ? "#e3f0ff" :
                  "#fffbe7",
                color:
                  selectedOrder.status === "completed" ? "#10b981" :
                  selectedOrder.status === "cancelled" ? "#e11d48" :
                  selectedOrder.status === "shipping" ? "#0154b9" :
                  "#f59e42",
                fontWeight: 700,
                fontSize: 16,
                display: "inline-block"
              }}>
                {selectedOrder.status === "pending" && "Chờ xác nhận"}
                {selectedOrder.status === "confirmed" && "Đã xác nhận"}
                {selectedOrder.status === "shipping" && "Đang giao"}
                {selectedOrder.status === "completed" && "Hoàn thành"}
                {selectedOrder.status === "cancelled" && "Đã huỷ"}
                {!["pending", "confirmed", "shipping", "completed", "cancelled"].includes(selectedOrder.status) && selectedOrder.status}
              </span>
            </div>
            <div style={{ margin: "18px 0 10px 0", fontWeight: 600, fontSize: 17 }}>
              <i className="fas fa-user" style={{ marginRight: 8, color: "#0154b9" }} />
              Người nhận: <span style={{ fontWeight: 500 }}>{selectedOrder.full_name || selectedOrder.customer_name}</span>
            </div>
            <div style={{ marginBottom: 10 }}>
              <i className="fas fa-phone" style={{ marginRight: 8, color: "#0154b9" }} />
              Số điện thoại: <span style={{ fontWeight: 500 }}>{selectedOrder.phone}</span>
            </div>
            <div style={{ marginBottom: 10 }}>
              <i className="fas fa-map-marker-alt" style={{ marginRight: 8, color: "#0154b9" }} />
              Địa chỉ nhận: <span style={{ fontWeight: 500 }}>{selectedOrder.address}</span>
            </div>
            <div style={{ marginBottom: 10 }}>
              <i className="fas fa-calendar-alt" style={{ marginRight: 8, color: "#0154b9" }} />
              Ngày đặt: <span style={{ fontWeight: 500 }}>{new Date(selectedOrder.created_at).toLocaleString("vi-VN")}</span>
            </div>
            <div style={{ marginBottom: 10 }}>
              <i className="fas fa-credit-card" style={{ marginRight: 8, color: "#0154b9" }} />
              Phương thức thanh toán: <span style={{ fontWeight: 500 }}>
                {selectedOrder.payment_method === "cod" ? "Thanh toán khi nhận hàng" : selectedOrder.payment_method}
              </span>
            </div>
            <div style={{ marginBottom: 10 }}>
              <i className="fas fa-ticket-alt" style={{ marginRight: 8, color: "#0154b9" }} />
              Voucher áp dụng:{" "}
              {selectedOrder.voucher_code
                ? <span style={{ color: "#f59e42", fontWeight: 600 }}>{selectedOrder.voucher_code}</span>
                : <span style={{ color: "#888" }}>Không</span>
              }
            </div>
            <div style={{ margin: "18px 0 10px 0", fontWeight: 600, fontSize: 17 }}>
              <i className="fas fa-box-open" style={{ marginRight: 8, color: "#0154b9" }} />
              Danh sách sản phẩm:
            </div>
            <table style={{ width: "100%", borderCollapse: "collapse", marginBottom: 18 }}>
              <thead>
                <tr style={{ background: "#e0e7ff" }}>
                  <th style={{ padding: 6, fontWeight: 600 }}>Ảnh</th>
                  <th style={{ padding: 6, fontWeight: 600 }}>Tên sản phẩm</th>
                  <th style={{ padding: 6, fontWeight: 600 }}>SL</th>
                  <th style={{ padding: 6, fontWeight: 600 }}>Giá</th>
                  <th style={{ padding: 6, fontWeight: 600 }}>Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                {selectedOrder.order_details && selectedOrder.order_details.length > 0 ? (
                  selectedOrder.order_details.map((item, idx) => (
                    <tr key={idx} style={{ background: idx % 2 === 0 ? "#f9fafb" : "#fff" }}>
                      <td style={{ padding: 6 }}>
                        <img
                          src={
                            item.product && item.product.Image
                              ? `http://localhost:8000/${item.product.Image}`
                              : "/img/no-image.png"
                          }
                          alt={item.product_name || item.product?.Name || "Sản phẩm"}
                          style={{ width: 48, height: 48, objectFit: "cover", borderRadius: 6, border: "1px solid #eee" }}
                        />
                      </td>
                      <td style={{ padding: 6 }}>{item.product_name || item.product?.Name || "Sản phẩm"}</td>
                      <td style={{ padding: 6, textAlign: "center" }}>{item.quantity}</td>
                      <td style={{ padding: 6 }}>
                        {Number(item.price).toLocaleString()}₫
                      </td>
                      <td style={{ padding: 6 }}>
                        {(Number(item.price) * Number(item.quantity)).toLocaleString()}₫
                      </td>
                      <td style={{ padding: 6 }}>
                        {/* Hiển thị nút đánh giá nếu đơn hàng đã hoàn thành */}
                        {selectedOrder.status === "completed" && (
                          <button
                            style={{
                              background: "#FFD600",
                              color: "#0154b9",
                              border: "none",
                              borderRadius: 6,
                              padding: "6px 18px",
                              fontWeight: 600,
                              cursor: "pointer"
                            }}
                            onClick={() => {
                              // Chuyển đến trang chi tiết sản phẩm theo slug
                              navigate(`/product/${item.slug}`);
                            }}
                          >
                            Đánh giá sản phẩm
                          </button>
                        )}
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={6} style={{ textAlign: "center", color: "#888" }}>Không có sản phẩm.</td>
                  </tr>
                )}
              </tbody>
            </table>
            <div style={{ textAlign: "right", fontSize: 16 }}>
              <div><b>Tổng tiền sản phẩm:</b> {Number(selectedOrder.total_price).toLocaleString()}₫</div>
              <div><b>Phí vận chuyển:</b> {Number(selectedOrder.shipping_fee).toLocaleString()}₫</div>
              {selectedOrder.discount && (
                <div style={{ color: "#e11d48" }}>
                  <b>Giảm giá:</b> -{Number(selectedOrder.discount).toLocaleString()}₫
                </div>
              )}
              <div style={{ fontSize: 18, fontWeight: 700, marginTop: 8 }}>
                Tổng thanh toán: <span style={{ color: "#0154b9" }}>
                  {(Number(selectedOrder.total_price) + Number(selectedOrder.shipping_fee) - (Number(selectedOrder.discount) || 0)).toLocaleString()}₫
                </span>
              </div>
            </div>
            {selectedOrder.status === "pending" && (
              <div style={{ textAlign: "center", marginTop: 18 }}>
                <button
                  style={{
                    background: "#e11d48",
                    color: "#fff",
                    border: "none",
                    borderRadius: 8,
                    padding: "10px 32px",
                    fontWeight: 600,
                    fontSize: "1rem",
                    cursor: "pointer",
                    marginBottom: 8,
                    boxShadow: "0 2px 8px #e0e7ff"
                  }}
                  onClick={async () => {
                    if (!window.confirm("Bạn chắc chắn muốn hủy đơn hàng này?")) return;
                    try {
                      await axios.post(
                        `http://localhost:8000/api/orders/${selectedOrder.id}/cancel`,
                        {},
                        { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
                      );
                      alert("Đã hủy đơn hàng!");
                      setOrders(prev =>
                        prev.map(order =>
                          order.id === selectedOrder.id
                            ? { ...order, status: "cancelled" }
                            : order
                        )
                      );
                      setSelectedOrder(null);
                    } catch (err) {
                      alert("Hủy đơn hàng thất bại!");
                    }
                  }}
                >
                  <i className="fas fa-times-circle" style={{ marginRight: 8 }} />
                  Hủy đơn hàng
                </button>
              </div>
            )}
          </div>
        </div>
      )}
      {showSuccessModal && (
        <div className="modal-overlay">
          <div className="modal-box">
            <h3>Cập nhật thành công!</h3>
            <button onClick={() => setShowSuccessModal(false)}>Đóng</button>
          </div>
        </div>
      )}
      <style>{`
        .profile-field {
          margin-bottom: 18px;
        }
        .profile-input {
          width: 100%;
          padding: 10px 12px;
          border: 1px solid #e5e7eb;
          border-radius: 6px;
          font-size: 15px;
          background: #f9fafb;
          margin-top: 4px;
          transition: border 0.2s;
        }
        .profile-input:focus {
          border-color: #0154b9;
          outline: none;
        }
        /* Menu trái hover đẹp */
        .profile-menu-item {
          transition: background 0.18s, color 0.18s, font-weight 0.18s;
          cursor: pointer;
        }
        .profile-menu-item:hover {
          background: #e0e7ff !important;
          color: #0154b9 !important;
          font-weight: 700 !important;
        }
        .modal-overlay {
          position: fixed;
          top: 0; left: 0; right: 0; bottom: 0;
          background: rgba(0,0,0,0.35);
          z-index: 9999;
          display: flex;
          align-items: center;
          justify-content: center;
        }
        .modal-box {
          background: #fff;
          border-radius: 14px;
          padding: 36px 32px 24px 32px;
          box-shadow: 0 8px 32px rgba(1,84,185,0.18);
          text-align: center;
          min-width: 320px;
        }
        .modal-box h3 {
          color: #10b981;
          font-size: 22px;
          margin-bottom: 18px;
        }
        .modal-box button {
          background: #0154b9;
          color: #fff;
          border: none;
          border-radius: 8px;
          padding: 8px 28px;
          font-size: 16px;
          font-weight: 600;
          cursor: pointer;
          margin-top: 12px;
        }
      `}</style>
    </>
  );
}

export default UserProfile;
