import React from "react";
import ArticleCard from "./ArticleCard";

function ArticleList({ articles }) {
  if (!articles || !articles.length) {
    return (
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          padding: "80px 20px",
          background:
            "linear-gradient(135deg, #f8faff 0%, #e8f4fd 100%)",
          borderRadius: "20px",
          margin: "40px auto",
          maxWidth: "600px",
          border: "2px dashed #cbd5e0",
          boxShadow: "0 4px 16px rgba(1,84,185,0.08)",
        }}
      >
        <div
          style={{
            fontSize: "64px",
            marginBottom: "16px",
            opacity: 0.6,
          }}
        >
          📝
        </div>
        <h3
          style={{
            color: "#4a5568",
            fontSize: "24px",
            fontWeight: "600",
            marginBottom: "8px",
            textAlign: "center",
          }}
        >
          Không có bài viết nào
        </h3>
        <p
          style={{
            color: "#718096",
            fontSize: "16px",
            textAlign: "center",
            maxWidth: "400px",
            lineHeight: "1.5",
          }}
        >
          Hiện tại chưa có bài viết nào trong danh mục này. Vui lòng quay lại sau
          hoặc chọn danh mục khác.
        </p>
      </div>
    );
  }

  return (
    <div
      style={{
        display: "grid",
        gridTemplateColumns: "repeat(4, 1fr)",
        columnGap: 24, // SỬA: riêng biệt column gap
        rowGap: 85, // SỬA: riêng biệt row gap - TĂNG LÊN
        marginBottom: 40,
        padding: "0 12px",
        maxWidth: 1640,
        marginLeft: "auto",
        marginRight: "auto",
        alignItems: "start", // SỬA: align items to start
        // REMOVE: gridAutoRows và overflow
      }}
    >
      {articles.map((article, index) => (
        <div
          key={article.Post_ID || article.id || index}
          style={{
            display: "flex",
            flexDirection: "column", // THÊM: column direction
            width: "100%",
            height: "fit-content", // THÊM: height fit content
            // REMOVE: justifyContent và gridColumn
          }}
        >
          <ArticleCard article={article} />
        </div>
      ))}
    </div>
  );
}

export default ArticleList;