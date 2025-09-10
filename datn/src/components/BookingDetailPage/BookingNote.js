import React from "react";

const BookingNote = ({ note, setNote, loading }) => (
  <div className="booking-note-wrap">
    <label htmlFor="note">Ghi chú (tuỳ chọn):</label>
    <textarea
      id="note"
      value={note}
      onChange={e => setNote(e.target.value)}
      placeholder="Nhập ghi chú cho chủ sân nếu cần..."
      rows={2}
      style={{ width: "100%", borderRadius: 8, padding: 8, border: "1px solid #e0e7ff", marginTop: 4 }}
      disabled={loading}
    />
  </div>
);

export default BookingNote;