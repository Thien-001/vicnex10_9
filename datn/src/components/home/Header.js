import React, { useState, useEffect, useRef } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import { motion, AnimatePresence } from "framer-motion";
// API URL
const API_URL = "http://localhost:8000";

// Style dùng chung cho icon
const iconStyle = {
  fontSize: 22,
  color: "#0154b9", // <-- màu trắng
  background: "#fff", // nền xanh đậm cho nổi bật
  borderRadius: "50%",
  width: 38,
  height: 38,
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  marginRight: 8,
  transition: "background 0.2s, color 0.2s",
  boxShadow: "0 1px 4px #0154b91a",
  cursor: "pointer",
};

// Text style for icons
const iconTextStyle = {
  fontWeight: 600,
  color: "#0154b9",
  fontSize: 15,
  letterSpacing: 0.2,
  display: "flex",
  alignItems: "center",
  height: 38,
  lineHeight: "38px",
  userSelect: "none",
};

// Animation variants
const dropdownVariants = {
  hidden: { opacity: 0, y: -10, pointerEvents: "none" },
  visible: {
    opacity: 1,
    y: 0,
    pointerEvents: "auto",
    transition: { duration: 0.3 },
  },
};

const fadeItemVariant = {
  hidden: { opacity: 0, y: -10 },
  visible: (i) => ({
    opacity: 1,
    y: 0,
    transition: { delay: i * 0.05 },
  }),
};

const containerMotion = {
  initial: { opacity: 0, y: 20 },
  animate: { opacity: 1, y: 0 },
  transition: { duration: 0.6, ease: "easeOut" },
};

const brandList = [
  "Yonex", "Lining", "Victor", "Apacs", "Flypower", "Kawasaki", "Mizuno",
  "FZ Forza", "Babolat", "RSL", "Fleet", "Gosen", "Wilson", "Prince",
  "Head", "Yang Yang", "ProAce", "Carlton", "Talbot Torro", "Bonny",
  "Ashaway", "Tronex"
];

const mainMenuItems = [
  { to: "/promotions", icon: "fas fa-gift", label: "Khuyến Mãi" },
  { to: "/article", icon: "fas fa-newspaper", label: "Bài viết" },
  { to: "/faq", icon: "fas fa-store", label: "FAQ" },
  { to: "/contact", icon: "fas fa-phone", label: "Liên hệ" },
];

const categories = [
  { title: "Giày cầu lông", links: brandList, type: "giay" },
  { title: "Vợt cầu lông", links: brandList, type: "vot" },
  { title: "Áo cầu lông", links: brandList, type: "ao" },
  { title: "Váy cầu lông", links: brandList, type: "vay" },
  { title: "Quần cầu lông", links: brandList, type: "quan" },
  { title: "Túi vợt cầu lông", links: brandList, type: "tui-vot" },
  { title: "Balo cầu lông", links: brandList, type: "balo" },
  { title: "Phụ kiện cầu lông", links: brandList, type: "phu-kien" },
];

const storeList = [
  { name: "Vicnex Hà Nội", address: "Số 1 Tràng Tiền, Hoàn Kiếm, Hà Nội" },
  { name: "Vicnex Hồ Gươm", address: "Số 5 Đinh Tiên Hoàng, Hoàn Kiếm, Hà Nội" },
  { name: "Vicnex Vincom Bà Triệu", address: "191 Bà Triệu, Hai Bà Trưng, Hà Nội" },
  { name: "Vicnex Landmark 81", address: "720A Điện Biên Phủ, Bình Thạnh, TP.HCM" },
  { name: "Vicnex Bitexco", address: "2 Hải Triều, Quận 1, TP.HCM" },
  { name: "Vicnex Crescent Mall", address: "101 Tôn Dật Tiên, Quận 7, TP.HCM" },
  { name: "Vicnex Đà Nẵng", address: "36 Bạch Đằng, Hải Châu, Đà Nẵng" },
  { name: "Vicnex Vincom Hải Phòng", address: "4 Lê Thánh Tông, Ngô Quyền, Hải Phòng" },
  { name: "Vicnex Nha Trang", address: "44 Trần Phú, Nha Trang, Khánh Hòa" },
  { name: "Vicnex Cần Thơ", address: "1 Hòa Bình, Ninh Kiều, Cần Thơ" },
];

const Header = ({ cartItems }) => {
  const navigate = useNavigate();
  // State
  const [isFocused, setIsFocused] = useState(false);
  const [isUserDropdownOpen, setIsUserDropdownOpen] = useState(false);
  const [isProductOpen, setIsProductOpen] = useState(false);
  const [isCartDropdownOpen, setIsCartDropdownOpen] = useState(false);
  const [isNotificationOpen, setIsNotificationOpen] = useState(false);
  const [cartItemsState, setCartItems] = useState([]);
  const [cartCount, setCartCount] = useState(0);
  const [notifications, setNotifications] = useState([]);
  const [unreadCount, setUnreadCount] = useState(0);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [searchValue, setSearchValue] = useState("");
  const [suggestions, setSuggestions] = useState([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const searchRef = useRef();
  const [isStoreDropdownOpen, setIsStoreDropdownOpen] = useState(false);


  // Lấy user từ localStorage
  const userStr = localStorage.getItem("user");
  const user = userStr ? JSON.parse(userStr) : null;

  // Đếm số lượng sản phẩm trong giỏ hàng
  useEffect(() => {
    const updateCartCount = () => {
      const cartItems = JSON.parse(localStorage.getItem("cart") || "[]");
      setCartCount(cartItems.reduce((total, item) => total + (item.quantity || 1), 0));
    };
    updateCartCount();
    window.addEventListener("cartUpdated", updateCartCount);
    return () => window.removeEventListener("cartUpdated", updateCartCount);
  }, []);

  // Lấy danh sách sản phẩm trong giỏ hàng
  useEffect(() => {
    const items = JSON.parse(localStorage.getItem("cart") || "[]");
    setCartItems(items);
  }, [isCartDropdownOpen, cartCount]);

  // Fetch notifications
  useEffect(() => {
    const fetchNotifications = async () => {
      if (!user || !user.ID) return;
      const res = await axios.get(`${API_URL}/api/notifications?user_id=${user.ID}`);
      const notificationsData = res.data.data || [];
      setNotifications(notificationsData);

      const unreadCount = notificationsData.filter(notification => !notification.is_read).length;
      setUnreadCount(unreadCount);
    };

    fetchNotifications();

    window.addEventListener("notificationUpdated", fetchNotifications);
    return () => window.removeEventListener("notificationUpdated", fetchNotifications);
  }, [user]);

  // Gợi ý sản phẩm khi nhập
  useEffect(() => {
    if (searchValue.trim().length < 1) {
      setSuggestions([]);
      setShowSuggestions(false);
      return;
    }
    axios
      .get(`${API_URL}/api/products?search=${encodeURIComponent(searchValue.trim())}`)
      .then(res => {
        setSuggestions(res.data.data || []);
        setShowSuggestions(true);
      });
  }, [searchValue]);

  // Đóng dropdown khi click ra ngoài
  useEffect(() => {
    const handleClick = (e) => {
      if (searchRef.current && !searchRef.current.contains(e.target)) {
        setShowSuggestions(false);
      }
    };
    document.addEventListener("mousedown", handleClick);
    return () => document.removeEventListener("mousedown", handleClick);
  }, []);

  const handleLogout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("cart");
    localStorage.removeItem("user");
    setCartItems([]);
    navigate("/login");
  };

  const handleNotificationDropdownOpen = async () => {
    setIsNotificationOpen(true);
    if (unreadCount > 0 && user && user.ID) {
      // Gọi API đánh dấu tất cả thông báo là đã đọc
      await axios.post(`${API_URL}/api/notifications/read-all`, { user_id: user.ID });
      setUnreadCount(0);
      // Optionally, cập nhật lại danh sách thông báo
      const res = await axios.get(`${API_URL}/api/notifications?user_id=${user.ID}`);
      setNotifications(res.data.data || []);
    }
  };

  const handleSearch = (e) => {
    e.preventDefault();
    if (searchValue.trim()) {
      setShowSuggestions(false);
      navigate(`/product?search=${encodeURIComponent(searchValue.trim())}`);
    }
  };

  // Render
  return (
    <header className="header">
      <div className="container1">
        <nav className="nav-menu1">
          <motion.div
            className="top-bar"
            variants={containerMotion}
            initial="hidden"
            animate="visible"
          >
            {/* Logo */}
            <Link to="/">
              <motion.img
                src="/img/logo/Logo_vicnec.png"
                alt="Logo"
                initial={{ opacity: 0, y: -20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, type: "spring", stiffness: 100 }}
                whileHover={{
                  scale: 1.1,
                  rotate: 3,
                  filter: "brightness(1.15) contrast(1.05)",
                }}
                whileTap={{ scale: 0.95 }}
                style={{ cursor: "pointer" }}
              />
            </Link>

            {/* Hotline */}
            <motion.div className="top-left" variants={fadeItemVariant}>
              <i className="fas fa-headset"></i>
              <span>HOTLINE:</span>
              <a href="tel:0977508430">0977508430</a> |{" "}
              <a href="tel:0338000308">0338000308</a>
            </motion.div>

            {/* Cửa hàng */}
            <motion.div className="top-center" variants={fadeItemVariant}
              onMouseEnter={() => setIsStoreDropdownOpen(true)}
              onMouseLeave={() => setIsStoreDropdownOpen(false)}
              style={{ position: "relative", cursor: "pointer" }}
            >
              <i className="fas fa-map-marker-alt"></i>
              <span>HỆ THỐNG CỬA HÀNG</span>
              <AnimatePresence>
                {isStoreDropdownOpen && (
                  <motion.div
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -10 }}
                    transition={{ duration: 0.2 }}
                    style={{
                      position: "absolute",
                      top: "120%",
                      left: 0,
                      background: "#fff",
                      border: "1px solid #e3eafc",
                      borderRadius: "12px",
                      boxShadow: "0 8px 32px #0154b922",
                      zIndex: 9999,
                      minWidth: "320px",
                      padding: "12px 0",
                    }}
                  >
                    {storeList.map((store, idx) => (
                      <div
                        key={idx}
                        style={{
                          padding: "10px 22px",
                          borderBottom: idx < storeList.length - 1 ? "1px solid #f3f6fa" : "none",
                          fontWeight: 600,
                          color: "#0154b9",
                          transition: "background 0.18s",
                          cursor: "pointer",
                        }}
                        onMouseEnter={e => e.currentTarget.style.background = "#f6f8fc"}
                        onMouseLeave={e => e.currentTarget.style.background = "none"}
                      >
                        <div style={{ fontSize: "1.05rem" }}>{store.name}</div>
                        <div style={{ fontSize: "0.97rem", color: "#555", fontWeight: 400 }}>{store.address}</div>
                      </div>
                    ))}
                  </motion.div>
                )}
              </AnimatePresence>
            </motion.div>

            {/* Tìm kiếm */}
            <motion.div
              className="top-search"
              variants={fadeItemVariant}
              ref={searchRef}
              style={{ position: "relative" }}
            >
              <form onSubmit={handleSearch} style={{ display: "flex", width: "100%" }}>
                <motion.input
                  type="text"
                  placeholder="Tìm sản phẩm..."
                  value={searchValue}
                  onChange={e => {
                    setSearchValue(e.target.value);
                    setShowSuggestions(true); // Luôn mở dropdown khi nhập
                  }}
                  autoComplete="off"
                  style={{ flex: 1 }}
                />
                <motion.button
                  type="submit"
                  whileHover={{ rotate: 15, scale: 1.2 }}
                  transition={{ type: "spring", stiffness: 300 }}
                  style={{ marginLeft: 6, background: "none", border: "none", cursor: "pointer" }}
                >
                  <i className="fas fa-search"></i>
                </motion.button>
              </form>
              {/* Dropdown gợi ý sản phẩm */}
              {showSuggestions && (
                <div
                  style={{
                    position: "absolute",
                    top: "110%",
                    left: 0,
                    right: 0,
                    background: "#fff",
                    border: "1px solid #e3eafc",
                    borderRadius: "14px",
                    boxShadow: "0 8px 32px #0154b922",
                    zIndex: 99999,
                    minWidth: "320px",
                    padding: "8px 0",
                    maxHeight: "340px",
                    overflowY: "auto",
                    marginTop: 4,
                    fontSize: "1rem",
                    animation: "fadeIn 0.18s",
                  }}
                >
                  {suggestions.length > 0 ? (
                    suggestions.map(product => (
                      <Link
                        key={product.Product_ID}
                        to={`/product/${product.slug}`}
                        style={{
                          display: "flex",
                          alignItems: "center",
                          gap: 14,
                          padding: "10px 18px",
                          color: "#0154b9",
                          textDecoration: "none",
                          borderBottom: "1px solid #f3f6fa",
                          fontWeight: 600,
                          transition: "background 0.18s, color 0.18s",
                          cursor: "pointer",
                        }}
                        onMouseEnter={e => e.currentTarget.style.background = "#f6f8fc"}
                        onMouseLeave={e => e.currentTarget.style.background = "none"}
                        onClick={() => setShowSuggestions(false)}
                      >
                        <img
                          src={product.Image ? `${API_URL}/${product.Image}` : "/img/no-image.png"}
                          alt={product.Name}
                          style={{
                            width: 40,
                            height: 40,
                            borderRadius: 10,
                            objectFit: "cover",
                            border: "1px solid #e3eafc",
                            background: "#f8fbff",
                            boxShadow: "0 2px 8px #0154b911",
                          }}
                        />
                        <span style={{
                          flex: 1,
                          whiteSpace: "nowrap",
                          overflow: "hidden",
                          textOverflow: "ellipsis",
                          fontWeight: 600,
                          fontSize: "1rem",
                        }}>
                          {product.Name}
                        </span>
                      </Link>
                    ))
                  ) : (
                    <div style={{
                      color: "#888",
                      padding: "16px 18px",
                      textAlign: "center",
                      fontSize: "1.05rem",
                      fontWeight: 500,
                    }}>
                      Không có sản phẩm phù hợp
                    </div>
                  )}
                </div>
              )}
            </motion.div>

            {/* ICON NHÓM PHẢI */}
            <motion.div className="top-right" variants={fadeItemVariant}>
              <motion.div
                className="icon-group"
                initial="hidden"
                animate="visible"
                variants={{
                  visible: { transition: { staggerChildren: 0.2 } },
                }}
                style={{ display: "flex", alignItems: "center", gap: 18 }}
              >
                {/* TÀI KHOẢN + DROPDOWN */}
                <motion.div
                  className="icon-item"
                  onMouseEnter={() => setIsUserDropdownOpen(true)}
                  onMouseLeave={() => setIsUserDropdownOpen(false)
                  }
                  style={{ position: "relative", display: "flex", alignItems: "center", cursor: "pointer" }}
                  variants={fadeItemVariant}
                >
                  {!user ? (
                    <>
                      <motion.i
                        className="fas fa-user"
                        style={iconStyle}
                        whileHover={{
                          backgroundColor: "#0154b9",
                          color: "#fff",
                        }}
                        transition={{ duration: 0.3 }}
                      />
                      <span style={iconTextStyle}>TÀI KHOẢN</span>
                      <AnimatePresence>
                        {isUserDropdownOpen && (
                          <motion.div
                            className="user-dropdown"
                            initial={{ opacity: 0, y: -10 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -10 }}
                            transition={{ duration: 0.2 }}
                            style={{
                              position: "absolute",
                              top: "120%",
                              right: 0,
                              background: "#fff",
                              border: "1px solid #ccc",
                              borderRadius: "10px",
                              padding: "18px 0",
                              boxShadow: "0 4px 16px rgba(1,84,185,0.10)",
                              zIndex: 10,
                              display: "flex",
                              flexDirection: "column",
                              gap: "0",
                              minWidth: "220px",
                            }}
                          >
                            <Link
                              to="/login"
                              style={{
                                padding: "10px 24px",
                                color: "#0154b9",
                                fontWeight: 600,
                                textDecoration: "none",
                                border: "none",
                                background: "none",
                                textAlign: "left",
                                borderRadius: 0,
                                transition: "background 0.2s",
                              }}
                              onMouseEnter={e => e.currentTarget.style.background = "#f6f8fc"}
                              onMouseLeave={e => e.currentTarget.style.background = "none"}
                            >
                              Đăng nhập
                            </Link>
                            <Link
                              to="/register"
                              style={{
                                padding: "10px 24px",
                                color: "#0154b9",
                                fontWeight: 600,
                                textDecoration: "none",
                                border: "none",
                                background: "none",
                                textAlign: "left",
                                borderRadius: 0,
                                transition: "background 0.2s",
                              }}
                              onMouseEnter={e => e.currentTarget.style.background = "#f6f8fc"}
                              onMouseLeave={e => e.currentTarget.style.background = "none"}
                            >
                              Đăng ký
                            </Link>
                          </motion.div>
                        )}
                      </AnimatePresence>
                    </>
                  ) : (
                    <>
                      {user.avatar ? (
                        <img
                          src={user.avatar}
                          alt="Avatar"
                          style={{
                            width: 38,
                            height: 38,
                            borderRadius: "50%",
                            objectFit: "cover",
                            marginRight: 8,
                            background: "#e3f0ff",
                            border: "2px solid #0154b9",
                          }}
                        />
                      ) : (
                        <motion.i
                          className="fas fa-user"
                          style={iconStyle}
                          whileHover={{
                            backgroundColor: "#0154b9",
                            color: "#fff",
                          }}
                          transition={{ duration: 0.3 }}
                        />
                      )}
                      <span style={iconTextStyle}>
                        {user.Name ? user.Name.toUpperCase() : "TÀI KHOẢN"}
                      </span>
                      <AnimatePresence>
                        {isUserDropdownOpen && (
                          <motion.div
                            className="user-dropdown"
                            initial={{ opacity: 0, y: -10 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -10 }}
                            transition={{ duration: 0.2 }}
                            style={{
                              position: "absolute",
                              top: "120%",
                              right: 0,
                              background: "#fff",
                              border: "1px solid #ccc",
                              borderRadius: "10px",
                              padding: "18px 0",
                              boxShadow: "0 4px 16px rgba(1,84,185,0.10)",
                              zIndex: 10,
                              display: "flex",
                              flexDirection: "column",
                              gap: "0",
                              minWidth: "220px",
                            }}
                          >
                            <Link
                              to="/profile"
                              style={{
                                padding: "10px 24px",
                                color: "#0154b9",
                                fontWeight: 600,
                                textDecoration: "none",
                                border: "none",
                                background: "none",
                                textAlign: "left",
                                borderRadius: 0,
                                transition: "background 0.2s",
                              }}
                              onMouseEnter={e => e.currentTarget.style.background = "#f6f8fc"}
                              onMouseLeave={e => e.currentTarget.style.background = "none"}
                            >
                              <i className="fas fa-user" style={{ marginRight: 8 }}></i> Trang cá nhân
                            </Link>
                            {(user.Role_ID === 1 || user.Role_ID === 2) && (
                              <a
                                href="http://127.0.0.1:8000/admin"
                                style={{
                                  padding: "10px 24px",
                                  color: "#0154b9",
                                  fontWeight: 600,
                                  textDecoration: "none",
                                  border: "none",
                                  background: "none",
                                  textAlign: "left",
                                  borderRadius: 0,
                                  transition: "background 0.2s",
                                }}
                                onMouseEnter={e => e.currentTarget.style.background = "#f6f8fc"}
                                onMouseLeave={e => e.currentTarget.style.background = "none"}
                              >
                                <i className="fas fa-cogs" style={{ marginRight: 8 }}></i> Quản lý
                              </a>
                            )}
                            <button
                              onClick={handleLogout}
                              style={{
                                padding: "10px 24px",
                                color: "#e74c3c",
                                fontWeight: 600,
                                background: "none",
                                border: "none",
                                textAlign: "left",
                                borderRadius: 0,
                                cursor: "pointer",
                                transition: "background 0.2s",
                              }}
                              onMouseEnter={e => e.currentTarget.style.background = "#fbe9e7"}
                              onMouseLeave={e => e.currentTarget.style.background = "none"}
                            >
                              <i className="fas fa-sign-out-alt" style={{ marginRight: 8 }}></i> Đăng xuất
                            </button>
                          </motion.div>
                        )}
                      </AnimatePresence>
                    </>
                  )}
                </motion.div>

                {/* GIỎ HÀNG */}
                <Link to="/cart" style={{ textDecoration: "none" }}>
                  <motion.div
                    className="icon-item cart"
                    onMouseEnter={() => setIsCartDropdownOpen(true)}
                    onMouseLeave={() => setIsCartDropdownOpen(false)}
                    style={{ position: "relative", display: "flex", alignItems: "center" }}
                    whileHover={{ scale: 1.1, rotate: 2 }}
                    whileTap={{ scale: 0.95 }}
                    variants={fadeItemVariant}
                    transition={{ type: "spring", stiffness: 300 }}
                  >
                    <motion.i
                      className="fas fa-shopping-cart"
                      style={iconStyle}
                      whileHover={{
                        backgroundColor: "#0154b9",
                        color: "#fff",
                      }}
                      transition={{ duration: 0.3 }}
                    />
                    <span className="cart-count">{cartCount}</span>
                    <span style={iconTextStyle}>GIỎ HÀNG</span>
                    {/* Dropdown giỏ hàng */}
                    <AnimatePresence>
                      {isCartDropdownOpen && (
                        <motion.div
                          initial={{ opacity: 0, y: -10 }}
                          animate={{ opacity: 1, y: 0 }}
                          exit={{ opacity: 0, y: -10 }}
                          transition={{ duration: 0.2 }}
                          style={{
                            position: "absolute",
                            top: "120%",
                            right: 0,
                            background: "#fff",
                            border: "1px solid #ddd",
                            borderRadius: "8px",
                            padding: "16px 18px",
                            boxShadow: "0 4px 16px rgba(0,0,0,0.12)",
                            zIndex: 9999, // <-- tăng zIndex lên cao
                            minWidth: "480px",
                            maxHeight: "400px",
                            overflowY: "auto",
                          }}
                        >
                          <h4 style={{ marginBottom: 10, color: "#0154b9" }}>Sản phẩm trong giỏ hàng</h4>
                          {Array.isArray(cartItemsState) && cartItemsState.length > 0 ? (
                            cartItemsState.map((item, idx) => (
                              <div key={idx} style={{ display: "flex", alignItems: "center", gap: 10, marginBottom: 12 }}>
                                <img src={item.Image || "/img/no-image.png"} alt={item.Name} style={{ width: 48, height: 48, borderRadius: 8, objectFit: "cover", border: "1px solid #eee" }} />
                                <div style={{ flex: 1 }}>
                                  <div style={{ fontWeight: 600, color: "#0051ff", fontSize: 15 }}>{item.Name}</div>
                                  <div style={{ fontSize: 13, color: "#777" }}>x{item.quantity}</div>
                                </div>
                                <div style={{ fontWeight: 600, color: "#d32f2f", fontSize: 15 }}>
                                  {Number(item.Discount_price > 0 ? item.Discount_price : item.Price || 0).toLocaleString("vi-VN", {
                                    style: "currency",
                                    currency: "VND",
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0,
                                  })}
                                </div>
                              </div>
                            ))
                          ) : (
                            <div style={{ color: "#888", fontSize: 15 }}>Giỏ hàng đang trống.</div>
                          )}
                          <div style={{ textAlign: "right", marginTop: 10 }}>
                            <Link to="/cart" style={{ color: "#0154b9", fontWeight: 600 }}>Xem giỏ hàng</Link>
                          </div>
                        </motion.div>
                      )}
                    </AnimatePresence>
                  </motion.div>
                </Link>

                {/* THÔNG BÁO */}
                <motion.div
                  className="icon-item"
                  style={{ position: "relative", display: "flex", alignItems: "center" }}
                  whileHover={{ scale: 1.1, rotate: 2 }}
                  whileTap={{ scale: 0.95 }}
                  variants={fadeItemVariant}
                  transition={{ type: "spring", stiffness: 300 }}
                  onMouseEnter={handleNotificationDropdownOpen}
                  onMouseLeave={() => setIsNotificationOpen(false)}
                >
                  <motion.i
                    className="fas fa-bell"
                    style={iconStyle}
                    whileHover={{
                      backgroundColor: "#0154b9",
                      color: "#fff",
                    }}
                    transition={{ duration: 0.3 }}
                  />
                  {/* Badge đỏ giống giỏ hàng */}
                  {unreadCount > 0 && (
                    <span className="cart-count">{unreadCount}</span>
                  )}
                  <span style={iconTextStyle}>THÔNG BÁO</span>
                  {/* Dropdown thông báo */}
                  <AnimatePresence>
                    {isNotificationOpen && (
                      <motion.div
                        className="notify-dropdown"
                        initial={{ opacity: 0, y: -10 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -10 }}
                        transition={{ duration: 0.2 }}
                      >
                        <h4 style={{ marginBottom: 10, color: "#0154b9" }}>Thông báo của bạn</h4>
                        {Array.isArray(notifications) && notifications.length > 0 ? (
                          notifications.map((item, idx) => (
                            <div
                              key={idx}
                              style={{
                                marginBottom: 12,
                                borderBottom: "1px solid #eee",
                                paddingBottom: 8,
                                fontWeight: item.is_read ? 400 : 700,
                                background: item.is_read ? "#fff" : "#e0e7ff",
                                borderRadius: 6,
                                transition: "background 0.2s",
                                display: "flex",
                                flexDirection: "column",
                              }}
                            >
                              <div style={{ color: "#0051ff", fontSize: 15 }}>{item.Title}</div>
                              <div style={{ fontSize: 13, color: "#777" }}>{item.Message}</div>
                              <div style={{ fontSize: 12, color: "#aaa" }}>
                                {item.Created_at
                                  ? new Date(item.Created_at).toLocaleString()
                                  : (item.created_at ? new Date(item.created_at).toLocaleString() : "")}
                              </div>
                            </div>
                          ))
                        ) : (
                          <div style={{ color: "#888", fontSize: 15 }}>Chưa có thông báo nào.</div>
                        )}
                      </motion.div>
                    )}
                  </AnimatePresence>
                </motion.div>
              </motion.div>
            </motion.div>
          </motion.div>
        </nav>
      </div>

      {/* MENU CHÍNH */}
      <motion.div className="container" {...containerMotion}>
        <nav className="nav-menu">
          <ul>
            {/* Sản phẩm dropdown */}
            <motion.li
              className="dropdown-wrapper"
              onMouseEnter={() => setIsProductOpen(true)}
              onMouseLeave={() => setIsProductOpen(false)}
              whileHover={{ scale: 1.03 }}
              style={{
                borderRadius: 6,
                padding: "4px 8px",
                position: "relative",
              }}
            >
              <Link to="/product">
                <i className="fas fa-cart-shopping"></i> Sản phẩm
              </Link>
              <AnimatePresence>
                {isProductOpen && (
                  <motion.div
                    className="dropdown-mega"
                    variants={dropdownVariants}
                    initial="hidden"
                    animate="visible"
                    exit="hidden"
                    style={{
                      position: "absolute",
                      top: 40,
                      left: "-327px",
                      transform: "translateX(-50%)",
                      background: "#fff",
                      border: "1px solid #ddd",
                      borderRadius: "8px",
                      padding: 20,
                      zIndex: 999,
                      boxShadow: "0 8px 16px rgba(0,0,0,0.1)",
                      display: "grid",
                      gridTemplateColumns: "repeat(4, minmax(180px, 1fr))",
                      gap: "16px",
                      maxWidth: "1849px",
                      maxHeight: "400px",
                      overflowY: "auto",
                    }}
                  >
                    {categories.map((cat, idx) => (
                      <div className="mega-column" key={idx}>
                        <h4>{cat.title}</h4>
                        {cat.links.map((brand, i) => (
                          <Link key={i} to={`/product/${cat.type}/${brand}`}>
                            {cat.title.split(" ")[0]}{" "}
                            {brand.charAt(0).toUpperCase() + brand.slice(1)}
                          </Link>
                        ))}
                      </div>
                    ))}
                  </motion.div>
                )}
              </AnimatePresence>
            </motion.li>

            {/* Đặt sân */}
            <motion.li
              className="dropdown-wrapper"
              whileHover={{ scale: 1.03 }}
              style={{ borderRadius: 6, padding: "4px 8px", position: "relative" }}
            >
              <Link to="/courts" style={{ display: "inline-flex", alignItems: "center", gap: "6px" }}>
                <i className="fas fa-tag"></i> Đặt Sân
              </Link>
            </motion.li>

            {/* Các menu khác */}
            {mainMenuItems.map((item, index) => (
              <motion.li
                key={index}
                variants={fadeItemVariant}
                initial="hidden"
                animate="visible"
                custom={index}
                whileHover={{ scale: 1.05 }}
                style={{ borderRadius: 6, padding: "4px 8px" }}
              >
                <Link to={item.to}>
                  <i className={item.icon}></i> {item.label}
                </Link>
              </motion.li>
            ))}
          </ul>
          <motion.div
            className="menu-header"
            whileHover={{ scale: 1.1 }}
            transition={{ type: "spring", stiffness: 200 }}
          >
            <i className="fas fa-bars"></i> MENU
          </motion.div>
        </nav>
      </motion.div>

      {/* Nút hamburger menu (chỉ hiện trên mobile) */}
      {/* <button
        className="mobile-menu-btn"
        onClick={() => setIsMobileMenuOpen(true)}
      >
        <i className="fas fa-bars" style={{ fontSize: 18 }}></i>
      </button> */}

      {/* Menu dọc cho mobile */}
      {/* {isMobileMenuOpen && (
        <div className="mobile-nav-menu" onClick={() => setIsMobileMenuOpen(false)}>
          <div className="mobile-nav-content" onClick={e => e.stopPropagation()}>
            <button className="mobile-nav-close" onClick={() => setIsMobileMenuOpen(false)}>
              <i className="fas fa-times"></i>
            </button>
            <ul style={{ listStyle: "none", padding: 0, margin: "40px 0 0 0" }}>
              <li>
                <Link to="/product" onClick={() => setIsMobileMenuOpen(false)}>
                  <i className="fas fa-cart-shopping"></i> Sản phẩm
                </Link>
              </li>
              <li>
                <Link to="/courts" onClick={() => setIsMobileMenuOpen(false)}>
                  <i className="fas fa-tag"></i> Đặt Sân
                </Link>
              </li>
              {mainMenuItems.map((item, idx) => (
                <li key={idx}>
                  <Link to={item.to} onClick={() => setIsMobileMenuOpen(false)}>
                    <i className={item.icon}></i> {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </div>
      )} */}
    </header>
  );
};

export default Header;

/* CSS Code */
/* .nav-menu ul {
  gap: 36px;
}
@media (min-width: 901px) {
 
    gap: 36px;
  }
} */
