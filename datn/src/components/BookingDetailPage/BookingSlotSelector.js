import React from "react";

const BookingSlotSelector = ({
  allSlots,
  bookedSlots,
  selectedStartSlot,
  setSelectedStartSlot,
  selectedEndSlot,
  setSelectedEndSlot,
  loading,
  selectedDate
}) => {
  const now = new Date();
  const isToday = selectedDate === `${now.getFullYear()}-${(now.getMonth()+1).toString().padStart(2,'0')}-${now.getDate().toString().padStart(2,'0')}`;

  const handleSlotClick = (slot) => {
    if (!selectedStartSlot) {
      setSelectedStartSlot(slot);
      setSelectedEndSlot(slot);
    } else {
      if (slot === selectedStartSlot && slot === selectedEndSlot) {
        setSelectedStartSlot('');
        setSelectedEndSlot('');
      } else if (slot < selectedStartSlot) {
        setSelectedStartSlot(slot);
      } else if (slot > selectedEndSlot) {
        setSelectedEndSlot(slot);
      } else {
        setSelectedStartSlot(slot);
        setSelectedEndSlot(slot);
      }
    }
  };

  return (
    <div className="booking-slots-wrap">
      <h4>Khung giờ còn trống</h4>
      <div className="slot-box-list">
        {allSlots.length === 0 ? (
          <span className="slot-empty">Hết chỗ trong ngày này.</span>
        ) : (
          allSlots.map(slot => {
            const slotHour = parseInt(slot.split(":")[0]);
            const isPast = isToday && slotHour <= now.getHours();
            const isBooked = bookedSlots.includes(slot);
            const isSelected = selectedStartSlot && selectedEndSlot &&
              slot >= selectedStartSlot && slot <= selectedEndSlot;
            const disabled = isPast || isBooked;

            return (
              <button
                key={slot}
                className={
                  `slot-box-btn${isSelected ? ' selected' : ''}${isPast ? ' slot-past' : ''}${isBooked ? ' slot-booked' : ''}`
                }
                disabled={loading || disabled}
                style={{
                  background: disabled
                    ? "#e5e7eb"
                    : isSelected
                      ? "#0154b9"
                      : "#fff",
                  color: disabled
                    ? "#9ca3af"
                    : isSelected
                      ? "#fff"
                      : "#0154b9",
                  borderColor: isSelected ? "#0154b9" : "#e5e7eb",
                  cursor: disabled ? "not-allowed" : "pointer",
                  textDecoration: disabled ? "line-through" : "none",
                  opacity: disabled ? 0.7 : 1,
                  fontWeight: isSelected ? 700 : 600,
                }}
                onClick={() => !disabled && handleSlotClick(slot)}
              >
                <i className="far fa-clock"></i> {slot} - {parseInt(slot) + 1}:00
              </button>
            );
          })
        )}
      </div>
    </div>
  );
};

export default BookingSlotSelector;