import React from "react";

function ArticleHeader({ article }) {
  return (
    <div style={{ marginBottom: 32 }}>
      {article.cover && (
        <img
          src={article.cover}
          alt={article.title}
          style={{
            width: "100%",
            maxHeight: 340,
            objectFit: "cover",
            borderRadius: 14,
            marginBottom: 24,
            boxShadow: "0 2px 12px rgba(1,84,185,0.08)"
          }}
        />
      )}
      <div style={{ color: "#0154b9", fontWeight: 600, fontSize: 16, marginBottom: 8 }}>
        {article.category}
      </div>
      <h1 style={{
        fontSize: 34,
        fontWeight: 800,
        margin: "0 0 12px",
        letterSpacing: 0.5,
        color: "#222"
      }}>{article.title}</h1>
      <div style={{ color: "#888", fontSize: 15 }}>{article.date}</div>
    </div>
  );
}

export default ArticleHeader;