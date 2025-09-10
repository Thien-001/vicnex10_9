import React from "react";

const Pagination = ({ page, totalPages, onChange }) => {
  if (totalPages <= 1) return null;
  const pages = [];
  for (let i = 1; i <= totalPages; i++) pages.push(i);

  return (
    <div style={{ display: "flex", gap: 8, justifyContent: "center", margin: "32px 0" }}>
      <button
        disabled={page === 1}
        onClick={() => onChange(page - 1)}
        style={{
          padding: "8px 16px",
          borderRadius: 6,
          border: "1.5px solid #b6d4fa",
          background: "#fff",
          color: "#0154b9",
          fontWeight: 600,
          cursor: page === 1 ? "not-allowed" : "pointer",
          minWidth: 36,
        }}
      >
        &lt;
      </button>
      {pages.map(p => (
        <button
          key={p}
          onClick={() => onChange(p)}
          style={{
            padding: "8px 16px",
            borderRadius: 6,
            border: "1.5px solid #b6d4fa",
            background: p === page ? "#0154b9" : "#fff",
            color: p === page ? "#fff" : "#0154b9",
            fontWeight: p === page ? 700 : 500,
            cursor: "pointer",
            minWidth: 36,
            transition: "all 0.18s",
          }}
        >
          {p}
        </button>
      ))}
      <button
        disabled={page === totalPages}
        onClick={() => onChange(page + 1)}
        style={{
          padding: "8px 16px",
          borderRadius: 6,
          border: "1.5px solid #b6d4fa",
          background: "#fff",
          color: "#0154b9",
          fontWeight: 600,
          cursor: page === totalPages ? "not-allowed" : "pointer",
          minWidth: 36,
        }}
      >
        &gt;
      </button>
    </div>
  );
};

export default Pagination;