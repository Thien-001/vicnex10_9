import React from "react";
import ArticleCategoryList from "./ArticleCategoryList";

function ArticleSidebar({ onCategorySelect }) {
  return (
    <aside
      style={{
        maxWidth: 340,
        marginLeft: "auto",
        marginRight: 0,
        padding: "0 0 0 16px",
        background: "transparent",
        minHeight: 400,
      }}
    >
      <div style={{ marginBottom: 32 }}>
        <ArticleCategoryList onSelect={onCategorySelect} />
      </div>
      <div>
        <h3 style={{ fontSize: 18, fontWeight: 700, marginBottom: 16, color: "#0154b9" }}>Bài nổi bật</h3>
        <ul style={{ paddingLeft: 18, margin: 0 }}>
          <li><a href="#" style={{ color: "#0154b9", fontSize: 15 }}>Cách chọn vợt cầu lông phù hợp</a></li>
          <li><a href="#" style={{ color: "#0154b9", fontSize: 15 }}>5 bài tập tăng sức bền</a></li>
          <li><a href="#" style={{ color: "#0154b9", fontSize: 15 }}>Mẹo phòng thủ hiệu quả</a></li>
        </ul>
      </div>
      {/* Thêm các box khác nếu muốn, mỗi box bọc trong <div> */}
    </aside>
  );
}

export default ArticleSidebar;