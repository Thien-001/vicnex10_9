import React from "react";

function ProductSortDropdown({ sort, setSort }) {
  return (
    <div style={{ display: "flex", justifyContent: "flex-end", alignItems: "center", marginBottom: 20 }}>
      <label
        style={{
          fontWeight: 600,
          fontSize: 15,
          marginRight: 10,
          color: "#0154b9",
          letterSpacing: 0.2,
        }}
      >
        Sắp xếp:
      </label>
      <select
        value={sort}
        onChange={e => setSort(e.target.value)}
        style={{
          padding: "7px 18px",
          borderRadius: 6,
          border: "1.5px solid #0154b9",
          fontSize: 15,
          outline: "none",
          minWidth: 160,
          background: "#f7fbff",
          color: "#0154b9",
          fontWeight: 600,
          boxShadow: "0 2px 8px rgba(1,84,185,0.07)",
          transition: "border 0.2s, box-shadow 0.2s",
          cursor: "pointer",
        }}
        onFocus={e => (e.target.style.border = "1.5px solid #003c7e")}
        onBlur={e => (e.target.style.border = "1.5px solid #0154b9")}
      >
        <option value="default">Mặc định</option>
        <option value="price-asc">Giá tăng dần</option>
        <option value="price-desc">Giá giảm dần</option>
        <option value="newest">Mới nhất</option>
        <option value="bestseller">Bán chạy</option>
      </select>
    </div>
  );
}

export default ProductSortDropdown;