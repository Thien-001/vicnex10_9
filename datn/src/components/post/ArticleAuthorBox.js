import React from "react";

function ArticleAuthorBox({ author }) {
  if (!author) return null;
  return (
    <div
      style={{
        display: "flex",
        alignItems: "center",
        background: "#f6f8fc",
        borderRadius: 12,
        padding: "20px 24px",
        margin: "32px 0",
        boxShadow: "0 2px 12px rgba(1,84,185,0.06)"
      }}
    >
      <img
        src={author.avatar}
        alt={author.name}
        style={{
          width: 56,
          height: 56,
          borderRadius: "50%",
          objectFit: "cover",
          marginRight: 20,
          border: "2px solid #0154b9",
          background: "#fff"
        }}
        onError={e => { e.target.src = "/default-avatar.png"; }}
      />
      <div>
        <div style={{ fontWeight: 700, fontSize: 18, color: "#0154b9" }}>{author.name}</div>
        {author.bio && (
          <div style={{ color: "#444", fontSize: 15, marginTop: 4 }}>{author.bio}</div>
        )}
        {author.profileUrl && (
          <a
            href={author.profileUrl}
            style={{
              color: "#fff",
              background: "#0154b9",
              borderRadius: 6,
              padding: "4px 14px",
              fontSize: 14,
              textDecoration: "none",
              marginTop: 8,
              display: "inline-block"
            }}
          >
            Xem trang cá nhân
          </a>
        )}
      </div>
    </div>
  );
}

export default ArticleAuthorBox;