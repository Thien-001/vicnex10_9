import React from "react";

function ArticleRelated({ related }) {
  if (!related || !related.length) return null;
  return (
    <div
      style={{
        background: "#f6f8fc",
        borderRadius: 12,
        padding: "20px 24px",
        margin: "40px 0 0",
        boxShadow: "0 2px 12px rgba(1,84,185,0.06)"
      }}
    >
      <h3 style={{ fontSize: 18, fontWeight: 700, color: "#0154b9", marginBottom: 14 }}>
        Bài viết liên quan
      </h3>
      <ul style={{ paddingLeft: 18, margin: 0 }}>
        {related.map(item => (
          <li key={item.id} style={{ marginBottom: 8 }}>
            <a href={`/article/${item.id}`} style={{ color: "#0154b9", fontSize: 16, textDecoration: "none" }}>
              {item.title}
            </a>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ArticleRelated;