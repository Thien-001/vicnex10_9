import React from "react";

const CompareFloatingButton = ({ count, onClick, onEmptyCompare }) => {
  const handleClick = () => {
    if (count === 0) {
      onEmptyCompare && onEmptyCompare();
      return;
    }
    onClick();
  };

  return (
    <button className="compare-floating-btn" onClick={handleClick} title="So sánh sản phẩm">
      <span style={{ fontSize: 22 }}>⚖️</span>
      {count > 0 && <span className="compare-badge">{count}</span>}
    </button>
  );
};

export default CompareFloatingButton;