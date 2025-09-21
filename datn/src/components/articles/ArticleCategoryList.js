import React, { useEffect, useState } from "react";

function ArticleCategoryList({ onSelect }) {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedId, setSelectedId] = useState(null);

  useEffect(() => {
    fetch("/api/post_categories")
      .then((res) => res.json())
      .then((data) => {
        setCategories(data);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, []);

  const handleCategoryClick = (cat) => {
    setSelectedId(cat.id);
    if (onSelect) onSelect(cat);
  };

  const handleAllClick = () => {
    setSelectedId(null);
    if (onSelect) onSelect(null);
  };

  if (loading) {
    return (
      <div
        style={{
          marginBottom: 28,
          background: "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
          borderRadius: 14,
          padding: "24px 20px",
          boxShadow: "0 8px 24px rgba(1,84,185,0.12)",
          marginLeft: "auto",
          maxWidth: 320,
          marginTop: 35,
          border: "1px solid rgba(1,84,185,0.08)",
        }}
      >
        <div
          style={{
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            gap: 8,
            color: "#64748b",
            fontSize: 16,
            fontWeight: 500,
          }}
        >
          <div
            style={{
              width: 16,
              height: 16,
              border: "2px solid #e2e8f0",
              borderTop: "2px solid #0154b9",
              borderRadius: "50%",
              animation: "spin 1s linear infinite",
            }}
          />
          Đang tải danh mục...
        </div>
        <style>
          {`@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }`}
        </style>
      </div>
    );
  }

  if (!categories.length) {
    return (
      <div
        style={{
          marginBottom: 28,
          background: "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
          borderRadius: 14,
          padding: "24px 20px",
          boxShadow: "0 8px 24px rgba(1,84,185,0.12)",
          marginLeft: "auto",
          maxWidth: 320,
          marginTop: 35,
          border: "1px solid rgba(1,84,185,0.08)",
          textAlign: "center",
        }}
      >
        <div
          style={{
            fontSize: "32px",
            marginBottom: 8,
            opacity: 0.6,
          }}
        >
          📂
        </div>
        <div
          style={{
            color: "#64748b",
            fontSize: 16,
            fontWeight: 500,
          }}
        >
          Không có danh mục nào.
        </div>
      </div>
    );
  }

  return (
    <div
      style={{
        marginBottom: 28,
        background: "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
        borderRadius: 14,
        padding: "24px 20px",
        boxShadow: "0 8px 24px rgba(1,84,185,0.12)",
        marginLeft: "auto",
        maxWidth: 320,
        marginTop: 35,
        border: "1px solid rgba(1,84,185,0.08)",
      }}
    >
      <h3
        style={{
          fontSize: 20,
          fontWeight: 700,
          marginBottom: 18,
          color: "#0154b9",
          letterSpacing: 0.2,
          display: "flex",
          alignItems: "center",
          gap: "8px",
        }}
      >
        <span style={{ fontSize: "18px" }}>📚</span>
        Danh mục bài viết
      </h3>

      <ul style={{ listStyle: "none", padding: 0, margin: 0 }}>
        {/* "Tất cả" button */}
        <li style={{ marginBottom: 10 }}>
          <button
            style={{
              width: "100%",
              background:
                selectedId === null
                  ? "linear-gradient(90deg, #0154b9, #3bb2ff)"
                  : "#fff",
              color: selectedId === null ? "#fff" : "#0154b9",
              border: selectedId === null ? "none" : "1.5px solid #b6d4fa",
              borderRadius: 8,
              padding: "8px 20px",
              cursor: "pointer",
              fontSize: 16,
              fontWeight: 600,
              transition: "all 0.3s ease",
              boxShadow:
                selectedId === null
                  ? "0 4px 16px rgba(1,84,185,0.3)"
                  : "0 1px 4px rgba(1,84,185,0.04)",
              textAlign: "left",
              display: "flex",
              alignItems: "center",
              gap: "6px",
            }}
            onMouseOver={(e) => {
              if (selectedId !== null) {
                e.currentTarget.style.background = "#e3f2fd";
                e.currentTarget.style.color = "#003c7e";
                e.currentTarget.style.borderColor = "#0154b9";
                e.currentTarget.style.transform = "translateY(-1px)";
                e.currentTarget.style.boxShadow = "0 3px 12px rgba(1,84,185,0.15)";
              }
            }}
            onMouseOut={(e) => {
              if (selectedId !== null) {
                e.currentTarget.style.background = "#fff";
                e.currentTarget.style.color = "#0154b9";
                e.currentTarget.style.borderColor = "#b6d4fa";
                e.currentTarget.style.transform = "translateY(0)";
                e.currentTarget.style.boxShadow = "0 1px 4px rgba(1,84,185,0.04)";
              }
            }}
            onClick={handleAllClick}
          >
            <span>📄</span>
            Tất cả bài viết
            {selectedId === null && (
              <span style={{ marginLeft: "auto", fontSize: "12px" }}>✓</span>
            )}
          </button>
        </li>

        {/* Category buttons */}
        {categories.map((cat) => (
          <li key={cat.id} style={{ marginBottom: 10 }}>
            <button
              style={{
                width: "100%",
                background:
                  selectedId === cat.id
                    ? "linear-gradient(90deg, #0154b9, #3bb2ff)"
                    : "#fff",
                color: selectedId === cat.id ? "#fff" : "#0154b9",
                border: selectedId === cat.id ? "none" : "1.5px solid #b6d4fa",
                borderRadius: 8,
                padding: "8px 20px",
                cursor: "pointer",
                fontSize: 16,
                fontWeight: 600,
                transition: "all 0.3s ease",
                boxShadow:
                  selectedId === cat.id
                    ? "0 4px 16px rgba(1,84,185,0.3)"
                    : "0 1px 4px rgba(1,84,185,0.04)",
                textAlign: "left",
                display: "flex",
                alignItems: "center",
                gap: "6px",
              }}
              onMouseOver={(e) => {
                if (selectedId !== cat.id) {
                  e.currentTarget.style.background = "#e3f2fd";
                  e.currentTarget.style.color = "#003c7e";
                  e.currentTarget.style.borderColor = "#0154b9";
                  e.currentTarget.style.transform = "translateY(-1px)";
                  e.currentTarget.style.boxShadow = "0 3px 12px rgba(1,84,185,0.15)";
                }
              }}
              onMouseOut={(e) => {
                if (selectedId !== cat.id) {
                  e.currentTarget.style.background = "#fff";
                  e.currentTarget.style.color = "#0154b9";
                  e.currentTarget.style.borderColor = "#b6d4fa";
                  e.currentTarget.style.transform = "translateY(0)";
                  e.currentTarget.style.boxShadow = "0 1px 4px rgba(1,84,185,0.04)";
                }
              }}
              onClick={() => handleCategoryClick(cat)}
            >
              <span>📁</span>
              {cat.Name}
              {selectedId === cat.id && (
                <span style={{ marginLeft: "auto", fontSize: "12px" }}>✓</span>
              )}
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ArticleCategoryList;