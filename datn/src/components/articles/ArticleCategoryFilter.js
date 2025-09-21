import React, { useState, useEffect, useRef } from "react";

const ArticleCategoryFilter = ({ categories, selected, onChange }) => {
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef(null);

  // Đóng dropdown khi click bên ngoài
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Tìm tên category được chọn
  const getSelectedCategoryName = () => {
    if (selected === "all") return "Tất cả chuyên mục";
    const category = categories.find(cat => cat.id === selected);
    return category ? (category.name || category.Name) : "Tất cả chuyên mục";
  };

  const handleCategorySelect = (categoryId) => {
    onChange(categoryId);
    setIsOpen(false);
  };

  return (
    <div 
      ref={dropdownRef}
      style={{
        position: "relative",
        marginBottom: 32,
      }}
    >
      {/* DROPDOWN BUTTON */}
      <button
        onClick={() => setIsOpen(!isOpen)}
        style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
          gap: 12,
          padding: "16px 24px",
          background: isOpen 
            ? "linear-gradient(135deg, #f0f8ff 0%, #e8f4fd 100%)"
            : "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
          border: isOpen ? "2px solid #0154b9" : "2px solid #e2e8f0",
          borderRadius: 14,
          cursor: "pointer",
          fontSize: 16,
          fontWeight: 600,
          color: "#0154b9",
          boxShadow: isOpen 
            ? "0 8px 32px rgba(1,84,185,0.15)" 
            : "0 4px 16px rgba(1,84,185,0.08)",
          transition: "all 0.3s ease",
          minWidth: 300,
          maxWidth: 400,
          width: "100%"
        }}
        onMouseEnter={(e) => {
          if (!isOpen) {
            e.target.style.borderColor = "#0154b9";
            e.target.style.boxShadow = "0 6px 24px rgba(1,84,185,0.12)";
            e.target.style.transform = "translateY(-1px)";
          }
        }}
        onMouseLeave={(e) => {
          if (!isOpen) {
            e.target.style.borderColor = "#e2e8f0";
            e.target.style.boxShadow = "0 4px 16px rgba(1,84,185,0.08)";
            e.target.style.transform = "translateY(0)";
          }
        }}
      >
        <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
          <span style={{ fontSize: "18px" }}>📁</span>
          <span>{getSelectedCategoryName()}</span>
        </div>
        <span style={{ 
          fontSize: "14px",
          transform: isOpen ? "rotate(180deg)" : "rotate(0deg)",
          transition: "transform 0.3s ease",
          color: isOpen ? "#0154b9" : "#64748b"
        }}>
          ▼
        </span>
      </button>

      {/* DROPDOWN LIST */}
      {isOpen && (
        <div style={{
          position: "absolute",
          top: "100%",
          left: 0,
          right: 0,
          background: "#ffffff",
          border: "2px solid #e2e8f0",
          borderTop: "1px solid #e2e8f0",
          borderRadius: "0 0 14px 14px",
          boxShadow: "0 12px 40px rgba(1,84,185,0.15)",
          zIndex: 1000,
          maxHeight: 320,
          overflowY: "auto",
          marginTop: "-2px"
        }}>
          {/* All Categories Option */}
          <button
            onClick={() => handleCategorySelect("all")}
            style={{
              width: "100%",
              padding: "14px 24px",
              border: "none",
              background: selected === "all" 
                ? "linear-gradient(90deg, #0154b9, #3bb2ff)" 
                : "transparent",
              color: selected === "all" ? "#fff" : "#0154b9",
              textAlign: "left",
              cursor: "pointer",
              fontSize: 15,
              fontWeight: selected === "all" ? 600 : 500,
              transition: "all 0.2s ease",
              display: "flex",
              alignItems: "center",
              gap: 10,
              borderBottom: "1px solid #f1f5f9"
            }}
            onMouseEnter={(e) => {
              if (selected !== "all") {
                e.target.style.background = "linear-gradient(135deg, #f0f8ff 0%, #e8f4fd 100%)";
                e.target.style.transform = "translateX(4px)";
              }
            }}
            onMouseLeave={(e) => {
              if (selected !== "all") {
                e.target.style.background = "transparent";
                e.target.style.transform = "translateX(0)";
              }
            }}
          >
            <span style={{ fontSize: "16px" }}>📄</span>
            <span>Tất cả chuyên mục</span>
            {selected === "all" && (
              <span style={{ marginLeft: "auto", fontSize: "14px" }}>✓</span>
            )}
          </button>

          {/* Category Options */}
          {categories.map((category, index) => (
            <button
              key={category.id || index}
              onClick={() => handleCategorySelect(category.id)}
              style={{
                width: "100%",
                padding: "14px 24px",
                border: "none",
                background: selected === category.id 
                  ? "linear-gradient(90deg, #0154b9, #3bb2ff)" 
                  : "transparent",
                color: selected === category.id ? "#fff" : "#0154b9",
                textAlign: "left",
                cursor: "pointer",
                fontSize: 15,
                fontWeight: selected === category.id ? 600 : 500,
                transition: "all 0.2s ease",
                display: "flex",
                alignItems: "center",
                gap: 10,
                borderBottom: index < categories.length - 1 ? "1px solid #f1f5f9" : "none"
              }}
              onMouseEnter={(e) => {
                if (selected !== category.id) {
                  e.target.style.background = "linear-gradient(135deg, #f0f8ff 0%, #e8f4fd 100%)";
                  e.target.style.transform = "translateX(4px)";
                }
              }}
              onMouseLeave={(e) => {
                if (selected !== category.id) {
                  e.target.style.background = "transparent";
                  e.target.style.transform = "translateX(0)";
                }
              }}
            >
              <span style={{ fontSize: "16px" }}>📁</span>
              <span>{category.name || category.Name}</span>
              {selected === category.id && (
                <span style={{ marginLeft: "auto", fontSize: "14px" }}>✓</span>
              )}
            </button>
          ))}
        </div>
      )}

      {/* OVERLAY KHI DROPDOWN MỞ */}
      {isOpen && (
        <div
          style={{
            position: "fixed",
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            zIndex: 999,
            background: "rgba(0,0,0,0.1)"
          }}
          onClick={() => setIsOpen(false)}
        />
      )}
    </div>
  );
};

export default ArticleCategoryFilter;