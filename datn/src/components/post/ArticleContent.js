import React from "react";

function ArticleContent({ content }) {
  return (
    <div
      style={{
        fontSize: 18,
        lineHeight: 1.8,
        color: "#222",
        marginBottom: 40,
        background: "#fff",
        borderRadius: 12,
        boxShadow: "0 2px 12px rgba(1,84,185,0.06)",
        padding: "32px 28px",
        wordBreak: "break-word",
        letterSpacing: 0.1,
        minHeight: 200
      }}
      dangerouslySetInnerHTML={{ __html: content }}
    />
  );
}

export default ArticleContent;