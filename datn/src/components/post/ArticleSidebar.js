import React from "react";
import ArticleCategoryList from "./ArticleCategoryList";

function ArticleSidebar({ onCategorySelect }) {
  return (
    <aside
      style={{
        background: "#f5f9ff",
        borderRadius: 12,
        padding: 28,
        marginBottom: 32,
        boxShadow: "0 2px 12px rgba(1,84,185,0.06)",

      }}
    >
      <ArticleCategoryList onSelect={onCategorySelect} />
      <h3 style={{ fontSize: 18, fontWeight: 700, marginBottom: 16, color: "#0154b9" }}>Bài nổi bật</h3>
      <ul style={{ paddingLeft: 18, margin: 0 }}>
        <li><a href="#" style={{ color: "#0154b9", fontSize: 15, textDecoration: "none" }}>Cách chọn vợt cầu lông phù hợp</a></li>
        <li><a href="#" style={{ color: "#0154b9", fontSize: 15, textDecoration: "none" }}>5 bài tập tăng sức bền</a></li>
        <li><a href="#" style={{ color: "#0154b9", fontSize: 15, textDecoration: "none" }}>Mẹo phòng thủ hiệu quả</a></li>
      </ul>
    </aside>
  );
}

export default ArticleSidebar;