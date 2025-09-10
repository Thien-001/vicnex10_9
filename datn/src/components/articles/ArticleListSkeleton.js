import React from "react";

const ArticleListSkeleton = () => (
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
    {[...Array(4)].map((_, i) => (
      <div
        key={i}
        style={{
          background: "#f3f4f6",
          borderRadius: 14,
          minHeight: 320,
          padding: 24,
          animation: "pulse 1.2s infinite",
        }}
      >
        <div
          style={{
            width: "60%",
            height: 22,
            background: "#e5e7eb",
            borderRadius: 6,
            marginBottom: 18,
          }}
        />
        <div
          style={{
            width: "100%",
            height: 14,
            background: "#e5e7eb",
            borderRadius: 6,
            marginBottom: 10,
          }}
        />
        <div
          style={{
            width: "80%",
            height: 14,
            background: "#e5e7eb",
            borderRadius: 6,
            marginBottom: 10,
          }}
        />
        <div
          style={{
            width: "40%",
            height: 14,
            background: "#e5e7eb",
            borderRadius: 6,
          }}
        />
      </div>
    ))}
    <style>
      {`
        @keyframes pulse {
          0% { opacity: 1; }
          50% { opacity: 0.5; }
          100% { opacity: 1; }
        }
      `}
    </style>
  </div>
);

export default ArticleListSkeleton;