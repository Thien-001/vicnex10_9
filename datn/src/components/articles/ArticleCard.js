import React from "react";
import { Link } from "react-router-dom";

function ArticleCard({ article }) {
  return (
    <div style={{
      background: "#f6f8fc",
      borderRadius: 10,
      padding: 16,
      width: 320,
      height: 420,
      boxShadow: "0 2px 8px rgba(0,0,0,0.03)",
      display: "flex",
      flexDirection: "column",
      justifyContent: "space-between",
      overflow: "hidden"
    }}>
      <img
        src={article.Thumbnail}
        alt={article.Title}
        style={{
          width: "100%",
          height: 160,
          objectFit: "cover",
          borderRadius: 8,
          marginBottom: 8
        }}
      />
      <h2 style={{
        fontSize: 20,
        fontWeight: 600,
        margin: "8px 0",
        overflow: "hidden",
        textOverflow: "ellipsis",
        whiteSpace: "nowrap"
      }}>
        <Link to={`/article/${article.Post_ID}`} style={{ color: "#0154b9", textDecoration: "none" }}>
          {article.Title}
        </Link>
      </h2>
      <div style={{ color: "#888", fontSize: 14, marginBottom: 8 }}>
        {article.Created_at && new Date(article.Created_at).toLocaleDateString()}
      </div>
      <div style={{
        color: "#222",
        fontSize: 16,
        marginBottom: 12,
        overflow: "hidden",
        textOverflow: "ellipsis",
        display: "-webkit-box",
        WebkitLineClamp: 3,
        WebkitBoxOrient: "vertical",
        minHeight: 60,
        maxHeight: 60
      }}>
        {article.Excerpt}
      </div>
      <div style={{ marginTop: "auto" }}>
        <Link to={`/article/${article.Post_ID}`} style={{
          color: "#fff",
          background: "#0154b9",
          padding: "6px 18px",
          borderRadius: 6,
          textDecoration: "none",
          fontWeight: 500,
          fontSize: 15,
          display: "inline-block"
        }}>Đọc tiếp</Link>
      </div>
    </div>
  );
}

export default ArticleCard;