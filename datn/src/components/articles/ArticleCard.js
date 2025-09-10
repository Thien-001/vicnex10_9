import React from "react";
import { Link } from "react-router-dom";

function ArticleCard({ article }) {
  return (
    <div style={{
      background: "#f6f8fc",
      borderRadius: 10,
      padding: 16,
      width: 320,
      boxShadow: "0 2px 8px rgba(0,0,0,0.03)"
    }}>
      <img
        src={article.Thumbnail}
        alt={article.Title}
        style={{ width: "100%", height: 160, objectFit: "cover", borderRadius: 8 }}
      />
      <h2 style={{ fontSize: 20, fontWeight: 600, margin: "12px 0 8px" }}>
        <Link to={`/article/${article.Post_ID}`} style={{ color: "#0154b9", textDecoration: "none" }}>
          {article.Title}
        </Link>
      </h2>
      <div style={{ color: "#888", fontSize: 14, marginBottom: 8 }}>
        {article.Created_at && new Date(article.Created_at).toLocaleDateString()}
        {/* Nếu muốn hiển thị tên tác giả, cần truyền thêm từ API */}
      </div>
      <div style={{ color: "#222", fontSize: 16, marginBottom: 12 }}>
        {article.Excerpt}
      </div>
      <Link to={`/article/${article.Post_ID}`} style={{
        color: "#fff",
        background: "#0154b9",
        padding: "6px 18px",
        borderRadius: 6,
        textDecoration: "none",
        fontWeight: 500,
        fontSize: 15
      }}>Đọc tiếp</Link>
    </div>
  );
}

export default ArticleCard;