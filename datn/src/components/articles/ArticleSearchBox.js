import React, { useState } from "react";

function ArticleSearchBox({ onSearch }) {
  const [keyword, setKeyword] = useState("");
  const [isFocused, setIsFocused] = useState(false);

  const handleSubmit = e => {
    e.preventDefault();
    onSearch(keyword.trim());
  };

  return (
    <form
      onSubmit={handleSubmit}
      style={{
        display: "flex",
        alignItems: "center",
        background: isFocused 
          ? "linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%)"
          : "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
        borderRadius: 30,
        padding: "6px 8px 6px 20px",
        boxShadow: isFocused 
          ? "0 8px 32px rgba(1,84,185,0.15)" 
          : "0 4px 16px rgba(1,84,185,0.08)",
        marginBottom: 0, // REMOVE margin bottom
        width: 480,      // FIXED WIDTH
        height: 50,      // FIXED HEIGHT
        border: isFocused 
          ? "2px solid #0154b9" 
          : "2px solid transparent",
        transition: "all 0.3s ease",
        flexShrink: 0,    // PREVENT SHRINKING
        marginLeft: 430 // ALIGN RIGHT
      }}
      
    >
      <div style={{
        display: "flex",
        alignItems: "center",
        gap: "12px",
        flex: 1,
        height: "100%"
      }}>
        <span style={{
          fontSize: "20px",
          color: isFocused ? "#0154b9" : "#64748b",
          flexShrink: 0
        }}>
          🔍
        </span>
        <input
          type="text"
          value={keyword}
          onChange={e => setKeyword(e.target.value)}
          onFocus={() => setIsFocused(true)}
          onBlur={() => setIsFocused(false)}
          placeholder="Tìm kiếm bài viết..."
          style={{
            flex: 1,
            border: "none",
            outline: "none",
            background: "transparent",
            fontSize: 16,
            padding: "0",
            color: "#1a202c",
            fontWeight: 500,
            height: "100%",
            minWidth: 0 // ALLOW SHRINKING
          }}
        />
      </div>
      
      <button
        type="submit"
        style={{
          background: "linear-gradient(90deg, #0154b9, #3bb2ff)",
          color: "#fff",
          border: "none",
          borderRadius: 22,
          padding: "0 24px",
          fontWeight: 600,
          fontSize: 15,
          cursor: "pointer",
          boxShadow: "0 4px 12px rgba(1,84,185,0.3)",
          transition: "all 0.3s ease",
          display: "flex",
          alignItems: "center",
          gap: "6px",
          height: 38,      // FIXED BUTTON HEIGHT
          width: 120,      // FIXED BUTTON WIDTH
          justifyContent: "center",
          flexShrink: 0    // PREVENT SHRINKING
        }}
        onMouseEnter={(e) => {
          e.target.style.transform = "translateY(-2px)";
          e.target.style.boxShadow = "0 6px 20px rgba(1,84,185,0.4)";
        }}
        onMouseLeave={(e) => {
          e.target.style.transform = "translateY(0)";
          e.target.style.boxShadow = "0 4px 12px rgba(1,84,185,0.3)";
        }}
      >
        Tìm kiếm
      </button>
    </form>
  );
}

export default ArticleSearchBox;