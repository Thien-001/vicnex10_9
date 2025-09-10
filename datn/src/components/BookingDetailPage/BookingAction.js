import React from "react";

const BookingAction = ({ onBooking, loading, disabled }) => (
  <div className="booking-action-row">
    <button
      className="booking-submit-btn"
      onClick={onBooking}
      disabled={loading || disabled}
    >
      {loading ? "Đang đặt..." : "Xác nhận đặt sân"}
    </button>
  </div>
);

export default BookingAction;