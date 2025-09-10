import React, { useEffect, useState } from "react";

function ArticleCategoryList({ onSelect }) {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch("/api/post_categories")
      .then((res) => res.json())
      .then((data) => {
        setCategories(data);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, []);

  if (loading) return <div>Đang tải danh mục...</div>;
  if (!categories.length) return <div>Không có danh mục nào.</div>;

  return (
    <div
      style={{
        marginBottom: 28,
        background: "#f6f8fc",
        borderRadius: 14,
        padding: "24px 20px",
        boxShadow: "0 2px 12px rgba(1,84,185,0.06)",
        marginLeft: "auto", // căn sang phải
        maxWidth: 320, // giới hạn chiều rộng để không quá to
      }}
    >
      <h3
        style={{
          fontSize: 20,
          fontWeight: 700,
          marginBottom: 18,
          color: "#0154b9",
          letterSpacing: 0.2,
        }}
      >
        Danh mục bài viết
      </h3>
      <ul style={{ listStyle: "none", padding: 0, margin: 0 }}>
        {categories.map((cat) => (
          <li key={cat.id} style={{ marginBottom: 10 }}>
            <button
              style={{
                background: "#fff",
                color: "#0154b9",
                border: "1.5px solid #b6d4fa",
                borderRadius: 8,
                padding: "8px 20px",
                cursor: "pointer",
                fontSize: 16,
                fontWeight: 600,
                transition: "all 0.2s",
                boxShadow: "0 1px 4px rgba(1,84,185,0.04)",
              }}
              onMouseOver={(e) => {
                e.currentTarget.style.background = "#e3f2fd";
                e.currentTarget.style.color = "#003c7e";
                e.currentTarget.style.borderColor = "#0154b9";
              }}
              onMouseOut={(e) => {
                e.currentTarget.style.background = "#fff";
                e.currentTarget.style.color = "#0154b9";
                e.currentTarget.style.borderColor = "#b6d4fa";
              }}
              onClick={() => onSelect && onSelect(cat)}
            >
              {cat.Name}
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ArticleCategoryList;