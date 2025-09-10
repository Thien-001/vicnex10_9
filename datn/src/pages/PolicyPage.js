import React from "react";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import PrivacyPolicy from "../components/Policy/PrivacyPolicy";
import OperationPolicy from "../components/Policy/OperationPolicy";
import ShippingPolicy from "../components/Policy/ShippingPolicy";
import ReturnRefundPolicy from "../components/Policy/ReturnRefundPolicy";

const menuItems = [
  { id: "privacy", label: "Chính sách bảo mật" },
  { id: "operation", label: "Quy chế hoạt động" },
  { id: "shipping", label: "Chính sách vận chuyển" },
  { id: "returnrefund", label: "Chính sách trả hàng & hoàn tiền" },
];

export default function PolicyPage() {
  return (
    <>
      <Header />
      <div
        className="policy-page"
        style={{
          maxWidth: 1100,
          margin: "40px auto",
          padding: 24,
          background: "#fff",
          borderRadius: 16,
          boxShadow: "0 2px 16px #e0e7ff44",
          display: "flex",
          gap: 32,
        }}
      >
        {/* Sidebar menu */}
        <nav
          className="policy-menu"
          style={{
            minWidth: 220,
            borderRight: "1.5px solid #e0e7ff",
            paddingRight: 24,
            marginRight: 16,
            position: "sticky",
            top: 32,
            height: "fit-content",
          }}
        >
          <h2 style={{ fontSize: "1.15rem", color: "#0154b9", fontWeight: 700, marginBottom: 18 }}>
            Danh mục chính sách
          </h2>
          <ul style={{ listStyle: "none", padding: 0, margin: 0 }}>
            {menuItems.map(item => (
              <li key={item.id} style={{ marginBottom: 14 }}>
                <a
                  href={`#${item.id}`}
                  style={{
                    color: "#2563eb",
                    textDecoration: "none",
                    fontWeight: 600,
                    fontSize: "1.05rem",
                    transition: "color 0.2s",
                  }}
                  onClick={e => {
                    // Đợi anchor nhảy xong rồi scroll lên đầu trang
                    setTimeout(() => {
                      window.scrollTo({ top: 0, behavior: "smooth" });
                    }, 0);
                  }}
                  onMouseOver={e => (e.target.style.color = "#0154b9")}
                  onMouseOut={e => (e.target.style.color = "#2563eb")}
                >
                  {item.label}
                </a>
              </li>
            ))}
          </ul>
        </nav>

        {/* Nội dung chính sách */}
        <div style={{ flex: 1 }}>
          <h1
            style={{
              textAlign: "center",
              color: "#0154b9",
              fontWeight: 800,
              fontSize: "2.2rem",
              marginBottom: 32,
            }}
          >
            Chính Sách & Quy Định
          </h1>
          <div id="privacy"><PrivacyPolicy /></div>
          <div id="operation"><OperationPolicy /></div>
          <div id="shipping"><ShippingPolicy /></div>
          <div id="returnrefund"><ReturnRefundPolicy /></div>
        </div>
      </div>
      <Footer />
    </>
  );
}