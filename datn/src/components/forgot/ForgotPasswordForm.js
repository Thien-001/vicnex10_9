import React, { useState } from "react";

const ForgotPasswordForm = () => {
  const [email, setEmail] = useState("");
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage("");
    setError("");
    setLoading(true);

    try {
      const res = await fetch("http://localhost:8000/api/forgot-password", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email }),
      });

      const data = await res.json();

      if (res.ok) {
        setMessage("✅ Vui lòng kiểm tra email để đặt lại mật khẩu.");
      } else if (res.status === 422) {
        // Laravel validation error
        const errorMsg =
          data?.errors?.email?.[0] || data.message || "Email không hợp lệ.";
        setError(`❌ ${errorMsg}`);
      } else {
        setError("❌ Đã xảy ra lỗi. Vui lòng thử lại.");
      }
    } catch (err) {
      console.error("Lỗi khi gửi yêu cầu:", err);
      setError("❌ Không thể kết nối đến máy chủ.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="forgot-password-container">
      <form className="forgot-password-form" onSubmit={handleSubmit}>
        <h2>Quên mật khẩu</h2>

        <input
          type="email"
          placeholder="Nhập email của bạn"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />

        <button type="submit" disabled={loading}>
          {loading ? "Đang gửi..." : "Gửi yêu cầu"}
        </button>

        {message && <div className="message success">{message}</div>}
        {error && <div className="message error">{error}</div>}
      </form>
    </div>
  );
};

export default ForgotPasswordForm;
