import React from "react";

const BookingDatePicker = ({ selectedDate, setSelectedDate }) => (
  <div className="booking-date-row">
    <label htmlFor="booking-date" className="booking-date-label">
      <i className="fas fa-calendar-alt" style={{ marginRight: 8, color: "#2563eb" }} />
      Chọn ngày:
    </label>
    <input
      id="booking-date"
      type="date"
      value={selectedDate}
      onChange={e => setSelectedDate(e.target.value)}
      className="booking-date-input"
      min={(() => {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = (today.getMonth() + 1).toString().padStart(2, '0');
        const dd = today.getDate().toString().padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
      })()}
    />
  </div>
);

export default BookingDatePicker;