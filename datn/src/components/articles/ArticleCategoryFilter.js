import React from "react";

const ArticleCategoryFilter = ({ categories, selected, onChange }) => (
  <div
    style={{
      display: "flex",
      gap: 12,
      marginBottom: 24,
      flexWrap: "wrap",
    }}
  >
    <button
      onClick={() => onChange("all")}
      style={{
        padding: "8px 20px",
        borderRadius: 20,
        border: "none",
        background: selected === "all" ? "#0154b9" : "#e3f2fd",
        color: selected === "all" ? "#fff" : "#0154b9",
        fontWeight: 600,
        fontSize: 16,
        cursor: "pointer",
        boxShadow:
          selected === "all"
            ? "0 2px 8px rgba(1,84,185,0.08)"
            : "none",
        transition: "background 0.2s",
      }}
    >
      Tất cả chuyên mục
    </button>
    {categories.map((cat, idx) => (
      <button
        key={cat.id ?? idx}
        onClick={() => onChange(cat.id)}
        style={{
          padding: "8px 20px",
          borderRadius: 20,
          border: "none",
          background: selected === cat.id ? "#0154b9" : "#e3f2fd",
          color: selected === cat.id ? "#fff" : "#0154b9",
          fontWeight: 600,
          fontSize: 16,
          cursor: "pointer",
          boxShadow:
            selected === cat.id
              ? "0 2px 8px rgba(1,84,185,0.08)"
              : "none",
          transition: "background 0.2s",
        }}
      >
        {cat.name}
      </button>
    ))}
  </div>
);

export default ArticleCategoryFilter;