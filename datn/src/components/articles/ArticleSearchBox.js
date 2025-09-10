import React from "react";

const ArticleSearchBox = ({ value, onChange }) => (
  <input
    type="text"
    placeholder="Tìm kiếm bài viết..."
    value={value}
    onChange={e => onChange(e.target.value)}
    style={{
      padding: "10px 18px",
      borderRadius: 8,
      border: "1.5px solid #b6d4fa",
      fontSize: 16,
      minWidth: 220,
      background: "#fff",
      color: "#222",
      fontWeight: 500,
      boxShadow: "0 1px 4px rgba(1,84,185,0.04)",
    }}
  />
);

export default ArticleSearchBox;