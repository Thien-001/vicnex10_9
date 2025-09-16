import React, { useState } from "react";

function ArticleSearchBox({ onSearch }) {
  const [keyword, setKeyword] = useState("");

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
        background: "#f6f8fc",
        borderRadius: 24,
        padding: "8px 18px",
        boxShadow: "0 2px 8px rgba(1,84,185,0.08)",
        marginBottom: 28,
        maxWidth: 420
      }}
    >
      <input
        type="text"
        value={keyword}
        onChange={e => setKeyword(e.target.value)}
        placeholder="Tìm kiếm bài viết..."
        style={{
          flex: 1,
          border: "none",
          outline: "none",
          background: "transparent",
          fontSize: 17,
          padding: "6px 0",
          color: "#222"
        }}
      />
      <button
        type="submit"
        style={{
          background: "#0154b9",
          color: "#fff",
          border: "none",
          borderRadius: 18,
          padding: "8px 22px",
          fontWeight: 600,
          fontSize: 16,
          cursor: "pointer",
          marginLeft: 10,
          boxShadow: "0 1px 4px rgba(1,84,185,0.10)",
          transition: "background 0.2s"
        }}
      >
        Tìm kiếm
      </button>
    </form>
  );
}

export default ArticleSearchBox;