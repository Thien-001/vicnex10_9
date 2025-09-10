import { Link, useNavigate } from "react-router-dom";
import { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import axios from "axios";

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
      console.log("Reload notification"); // Thêm dòng này ở đây
      const userStr = localStorage.getItem("user");
      if (!userStr) return;
      const user = JSON.parse(userStr);
      const res = await axios.get(`${API_URL}/api/notifications?user_id=${user.id}`);
      const notifications = res.data.data || res.data || [];
      setNotifications(notifications);

      // Đếm số lượng thông báo chưa đọc
      const unreadCount = notifications.filter(notification => !notification.is_read).length;
      setUnreadCount(unreadCount);
    };

    fetchNotifications();

    // Lắng nghe sự kiện notificationUpdated để reload thông báo khi có thay đổi
    window.addEventListener("notificationUpdated", fetchNotifications);
    return () => window.removeEventListener("notificationUpdated", fetchNotifications);
  }, []);

  const handleLogout = () => {
    // Xóa token đăng nhập
    localStorage.removeItem("token");
    // Xóa giỏ hàng ở localStorage
    localStorage.removeItem("cart");
    // Xóa thông tin user
    localStorage.removeItem("user");
    // Xóa giỏ hàng ở state/context nếu có
    setCartItems([]);
    // ...các thao tác khác như chuyển hướng...
    navigate("/login");
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
            <motion.div className="top-center" variants={fadeItemVariant}>
              <i className="fas fa-map-marker-alt"></i>
              <span>HỆ THỐNG CỬA HÀNG</span>
            </motion.div>

            {/* Tìm kiếm */}
            <motion.div
              className="top-search"
              variants={fadeItemVariant}
              animate={{
                boxShadow: isFocused
                  ? "0 0 12px rgba(1, 84, 185, 0.3)"
                  : "0 0 0 rgba(0,0,0,0)",
                scale: isFocused ? 1.05 : 1,
              }}
              transition={{
                scale: { type: "spring", stiffness: 260, damping: 20 },
                boxShadow: { duration: 0.3 },
              }}
            >
              <motion.input
                type="text"
                placeholder="Tìm sản phẩm..."
                onFocus={() => setIsFocused(true)}
                onBlur={() => setIsFocused(false)}
                initial={{ width: "100%" }}
                animate={{ width: "100%" }}
                transition={{ duration: 0.2 }}
              />
              <motion.button
                whileHover={{ rotate: 15, scale: 1.2 }}
                transition={{ type: "spring", stiffness: 300 }}
              >
                <i className="fas fa-search"></i>
              </motion.button>
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
                  onMouseLeave={() => setIsUserDropdownOpen(false)}
                  style={{ position: "relative", display: "flex", alignItems: "center", cursor: "pointer" }}
                  variants={fadeItemVariant}
                >
                  {(() => {
                    const userStr = localStorage.getItem("user");
                    if (!userStr) {
                      return (
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
                      );
                    }
                    try {
                      const user = JSON.parse(userStr);
                      return (
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
                          <span style={iconTextStyle}>{user.name || "TÀI KHOẢN"}</span>
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
                      );
                    } catch {
                      return (
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
                      );
                    }
                  })()}
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
                  onMouseEnter={() => setIsNotificationOpen(true)}
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
                  {/* Badge đỏ nếu có thông báo chưa đọc */}
                  {unreadCount > 0 && (
                    <span
                      style={{
                        position: "absolute",
                        top: 2,
                        right: 6,
                        background: "#e74c3c",
                        color: "#fff",
                        borderRadius: "50%",
                        width: 18,
                        height: 18,
                        fontSize: 12,
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "center",
                        fontWeight: 700,
                        zIndex: 30,
                        border: "2px solid #fff",
                      }}
                    >
                      {unreadCount}
                    </span>
                  )}
                  <span style={iconTextStyle}>THÔNG BÁO</span>
                  {/* Dropdown thông báo */}
                  <AnimatePresence>
                    {isNotificationOpen && (
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
                          zIndex: 20,
                          minWidth: "320px",
                          maxHeight: "400px",
                          overflowY: "auto",
                        }}
                      >
                        <h4 style={{ marginBottom: 10, color: "#0154b9" }}>Thông báo của bạn</h4>
                        {Array.isArray(notifications) && notifications.length > 0 ? (
                          notifications.map((item, idx) => (
                            <div key={idx} style={{ marginBottom: 12, borderBottom: "1px solid #eee", paddingBottom: 8 }}>
                              <div style={{ fontWeight: 600, color: "#0051ff", fontSize: 15 }}>{item.Title}</div>
                              <div style={{ fontSize: 13, color: "#777" }}>{item.Message}</div>
                              <div style={{ fontSize: 12, color: "#aaa" }}>{new Date(item.created_at).toLocaleString()}</div>
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
      <button
        className="mobile-menu-btn"
        onClick={() => setIsMobileMenuOpen(true)}
        style={{ display: "none" }}
      >
        <i className="fas fa-bars"></i>
      </button>

      {/* Menu dọc cho mobile */}
      {isMobileMenuOpen && (
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
      )}
    </header>
  );
};

export default Header;

/* CSS Code */
/* .nav-menu ul {
  gap: 36px;
}
@media (min-width: 901px) {
  .nav-menu ul {
    gap: 36px;
  }
} */
