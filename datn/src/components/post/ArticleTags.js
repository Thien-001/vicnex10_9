import React from "react";

function ArticleTags({ tags }) {
  if (!tags || !tags.length) return null;
  return (
    <div style={{ marginBottom: 18 }}>
      {tags.map(tag => (
        <span
          key={tag}
          style={{
            display: "inline-block",
            background: "#e3f2fd",
            color: "#0154b9",
            borderRadius: 8,
            padding: "4px 14px",
            marginRight: 10,
            fontSize: 15,
            fontWeight: 500,
            marginBottom: 6,
            boxShadow: "0 1px 4px rgba(1,84,185,0.08)"
          }}
        >
          #{tag}
        </span>
      ))}
    </div>
  );
}

export default ArticleTags;