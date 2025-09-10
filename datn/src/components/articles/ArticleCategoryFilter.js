import React from "react";

const ArticleCategoryFilter = ({ categories, selected, onChange }) => (
  <select
    value={selected}
    onChange={e => onChange(e.target.value)}
    style={{
      padding: "10px 18px",
      borderRadius: 8,
      border: "1.5px solid #b6d4fa",
      fontSize: 16,
      minWidth: 180,
      background: "#fff",
      color: "#0154b9",
      fontWeight: 600,
      marginRight: 12,
      boxShadow: "0 1px 4px rgba(1,84,185,0.04)",
    }}
  >
    <option value="all">Tất cả chuyên mục</option>
    {categories.map((cat, idx) => (
      <option key={cat.id ?? idx} value={cat.id}>
        {cat.name}
      </option>
    ))}
  </select>
);

export default ArticleCategoryFilter;