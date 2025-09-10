import React from "react";
import ArticleCard from "./ArticleCard";

function ArticleList({ articles }) {
  if (!articles || !articles.length) {
    return (
      <div
        style={{
          color: "#888",
          fontSize: 18,
          textAlign: "center",
          margin: "40px 0",
        }}
      >
        Không có bài viết nào.
      </div>
    );
  }

  return (
    <div
      style={{
        display: "grid",
        gridTemplateColumns: "repeat(auto-fit, minmax(280px, 1fr))",
        gap: 32,
        marginBottom: 40,
        padding: "0 12px",
        maxWidth: 1640,
        marginLeft: "auto",
        marginRight: "auto",
      }}
    >
      {articles.map((article) => (
        <ArticleCard key={article.id} article={article} />
      ))}
    </div>
  );
}

export default ArticleList;