import React, { useState } from "react";
import { motion } from "framer-motion";
import { FiEye, FiEyeOff } from "react-icons/fi";
import axios from "axios";
import { toast, ToastContainer } from "react-toastify";
import { useNavigate } from "react-router-dom";
import "react-toastify/dist/ReactToastify.css";

const LoginForm = () => {
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });
  const [showPassword, setShowPassword] = useState(false);
  const navigate = useNavigate();

  const handleChange = (e) => {
    setFormData((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const res = await axios.post("http://localhost:8000/api/login", {
        email: formData.email,
        password: formData.password,
      });

      // ✅ Lưu token và user vào localStorage
      localStorage.setItem("token", res.data.token);
      localStorage.setItem("user", JSON.stringify(res.data.user));

      toast.success("Đăng nhập thành công!");

      // ✅ Delay chuyển trang để người dùng kịp thấy thông báo
      setTimeout(() => {
        const user = res.data.user;
        if (user.Role_ID === 1) {
          window.location.href = "http://127.0.0.1:8000/admin"; // chuyển sang admin Laravel
        } else {
          navigate("/"); // chuyển về trang chủ React
        }
      }, 1500);
    } catch (err) {
      if (err.response?.data?.errors) {
        const firstError = Object.values(err.response.data.errors)[0][0];
        toast.error(`❌ ${firstError}`);
      } else if (err.response?.data?.message) {
        toast.error(`❌ ${err.response.data.message}`);
      } else {
        toast.error("❌ Đã xảy ra lỗi, thử lại sau.");
      }
    }
  };

  return (
    <div className="login-wrapper">
      <ToastContainer />
      <motion.form
        className="login-form"
        onSubmit={handleSubmit}
        initial={{ opacity: 0, y: 40 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6, ease: "easeOut" }}
      >
        <h2>Đăng Nhập</h2>

        <motion.input
          type="email"
          name="email"
          placeholder="Vui lòng nhập email"
          value={formData.email}
          onChange={handleChange}
          whileFocus={{ scale: 1.02 }}
          transition={{ type: "spring", stiffness: 300 }}
          required
        />

        <motion.div
          className="input-group"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
        >
          <input
            type={showPassword ? "text" : "password"}
            name="password"
            placeholder="Vui lòng nhập mật khẩu"
            value={formData.password}
            onChange={handleChange}
            required
          />
          <motion.span
            className="eye-icon"
            onClick={() => setShowPassword(!showPassword)}
            whileHover={{ scale: 1.2, y: "-50%" }}
            transition={{ type: "spring", stiffness: 300 }}
          >
            {showPassword ? <FiEyeOff size={20} /> : <FiEye size={20} />}
          </motion.span>
        </motion.div>
z
        <div className="social-login">
          <span className="social-label">Đăng nhập với</span>
          <div className="social-icons">
            <motion.img
              src="https://cdn-icons-png.flaticon.com/512/145/145802.png"
              alt="Facebook"
              className="social-icon"
              whileHover={{ scale: 1.2, rotate: 5 }}
              transition={{ type: "spring", stiffness: 200 }}
            />
            <motion.img
              src="https://cdn-icons-png.flaticon.com/512/281/281764.png"
              alt="Google"
              className="social-icon"
              whileHover={{ scale: 1.2, rotate: -5 }}
              transition={{ type: "spring", stiffness: 200 }}
            />
          </div>
          <a href="/#" className="forgot-password">Quên mật khẩu?</a>
        </div>

        <motion.button
          type="submit"
          whileHover={{
            scale: 1.05,
            boxShadow: "0px 8px 20px rgba(0,123,255,0.3)",
          }}
          whileTap={{ scale: 0.97 }}
          transition={{ type: "spring", stiffness: 300 }}
        >
          Đăng Nhập
        </motion.button>
      </motion.form>
    </div>
  );
};

export default LoginForm;
