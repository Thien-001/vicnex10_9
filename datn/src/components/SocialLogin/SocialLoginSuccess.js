import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

function SocialLoginSuccess() {
  const navigate = useNavigate();
  const [success, setSuccess] = useState(false);

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const token = params.get("token");
    const userId = params.get("user_id");

    if (token && userId) {
      // 👉 Lưu token và user_id vào localStorage
      localStorage.setItem("token", token);
      localStorage.setItem("user_id", userId);
      setSuccess(true);
      setTimeout(() => {
        // ✅ Chuyển hướng về trang chính sau 2 giây
        navigate("/");
      }, 2000);
    } else {
      // ❌ Nếu thiếu token hoặc user_id -> quay về login
      navigate("/login");
    }
  }, [navigate]);

  return (
    <div
      style={{
        minHeight: "100vh",
        background: "linear-gradient(135deg,#e0e7ff 0%,#fff 100%)",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
      }}
    >
      <div
        style={{
          background: "#fff",
          borderRadius: 16,
          boxShadow: "0 4px 24px rgba(1,84,185,0.12)",
          padding: "48px 32px",
          textAlign: "center",
          minWidth: 340,
        }}
      >
        {success ? (
          <>
            <div style={{ fontSize: 48, color: "#0154b9", marginBottom: 12 }}>
              <span role="img" aria-label="success">
                ✅
              </span>
            </div>
            <h2 style={{ color: "#0154b9", marginBottom: 8 }}>
              Đăng nhập thành công!
            </h2>
            <div style={{ fontSize: 16, color: "#333", marginBottom: 18 }}>
              Chào mừng bạn quay trở lại hệ thống.
            </div>
            <button
              style={{
                background: "#0154b9",
                color: "#fff",
                border: "none",
                borderRadius: 8,
                padding: "10px 32px",
                fontWeight: 600,
                fontSize: 16,
                cursor: "pointer",
                boxShadow: "0 2px 8px rgba(1,84,185,0.08)",
              }}
              onClick={() => navigate("/")}
            >
              Về trang chủ
            </button>
            <div style={{ marginTop: 12, fontSize: 13, color: "#888" }}>
              Bạn sẽ được chuyển về trang chủ sau vài giây...
            </div>
          </>
        ) : (
          <>
            <div style={{ fontSize: 40, color: "#0154b9", marginBottom: 12 }}>
              <span role="img" aria-label="loading">
                🔄
              </span>
            </div>
            <h2 style={{ color: "#0154b9", marginBottom: 8 }}>
              Đang xác thực đăng nhập...
            </h2>
          </>
        )}
      </div>
    </div>
  );
}

export default SocialLoginSuccess;
