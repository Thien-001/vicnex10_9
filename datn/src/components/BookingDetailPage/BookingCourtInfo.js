import React from "react";

// Hàm lấy URL ảnh giống LocationList
const getImageUrl = (img) => {
  if (!img) return "/no-image.png";
  if (img.startsWith("http")) return img;
  if (img.startsWith("uploads/")) return `http://localhost:8000/${img}`;
  return `http://localhost:8000/uploads/${img}`;
};

/**
 * BookingCourtInfo
 * Hiển thị ảnh sân bên trái, thông tin bên phải (giống trang sản phẩm)
 */
const BookingCourtInfo = ({ court }) => (
  <div className="booking-detail-container">
    {/* Ảnh sân bên trái */}
    <div className="booking-detail-image">
      <img
        src={getImageUrl(court.Image)}
        alt={court.Name}
        style={{ width: "100%", borderRadius: 14, objectFit: "cover" }}
      />
    </div>
    {/* Thông tin sân bên phải */}
    <div className="booking-detail-info-box">
      <h2>Đặt sân: {court.Name}</h2>
      <div className="booking-detail-meta">
        <div><b>Địa điểm:</b> {court.location?.name}</div>
        <div><b>Loại sân:</b> {court.Court_type}</div>
        <div><b>Giá:</b> {Number(court.Price_per_hour).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} / giờ</div>
        <div><b>Giờ mở cửa:</b> {court.open_time || "06:00"} - {court.close_time || "22:00"}</div>
      </div>
      {court.Description && (
        <div className="booking-detail-description">
          {court.Description}
        </div>
      )}
    </div>
  </div>
);

export default BookingCourtInfo;