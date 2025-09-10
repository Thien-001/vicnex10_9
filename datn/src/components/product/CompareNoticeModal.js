import React from "react";

const CompareNoticeModal = ({ message, onClose }) => (
  <div className="notice-modal-overlay">
    <div className="notice-modal-content">
      <p>{message}</p>
      <button onClick={onClose}>Đóng</button>
    </div>
  </div>
);

export default CompareNoticeModal;