import React from "react";

function ArticleShare({ article }) {
  return (
    <div style={{ margin: "32px 0 24px", display: "flex", alignItems: "center" }}>
      <span style={{ fontWeight: 600, color: "#0154b9", marginRight: 12 }}>Chia sẻ:</span>
      <a
        href={`https://www.facebook.com/sharer/sharer.php?u=${window.location.href}`}
        target="_blank"
        rel="noopener noreferrer"
        style={{
          background: "#1877f2",
          color: "#fff",
          borderRadius: "50%",
          width: 32,
          height: 32,
          display: "inline-flex",
          alignItems: "center",
          justifyContent: "center",
          marginRight: 8,
          textDecoration: "none",
          fontSize: 18,
          boxShadow: "0 1px 4px rgba(24,119,242,0.12)"
        }}
        title="Chia sẻ Facebook"
      >
        <i className="bx bxl-facebook"></i>
      </a>
      {/* Thêm các nút chia sẻ khác nếu muốn */}
    </div>
  );
}

export default ArticleShare;