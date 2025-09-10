import React from "react";

function ArticleComments({ postId }) {
  // ...giả sử bạn đã có logic lấy comments...
  return (
    <div
      style={{
        background: "#f6f8fc",
        borderRadius: 12,
        padding: "24px 28px",
        marginTop: 40,
        boxShadow: "0 2px 12px rgba(1,84,185,0.06)"
      }}
    >
      <h3 style={{ fontSize: 20, fontWeight: 700, color: "#0154b9", marginBottom: 18 }}>
        Bình luận
      </h3>
      {/* ...render comments ở đây... */}
      <div style={{ color: "#888", fontStyle: "italic" }}>Chức năng bình luận sẽ sớm ra mắt!</div>
    </div>
  );
}

export default ArticleComments;