import React, { useState } from "react";
import { Link } from "react-router-dom";

const OPEN_TIME = 6;
const CLOSE_TIME = 22;
const allSlots = [];
for (let h = OPEN_TIME; h < CLOSE_TIME; h++) {
  allSlots.push(`${h.toString().padStart(2, '0')}:00`);
}

export default function CourtCard({ court }) {
  const [showModal, setShowModal] = useState(false);
  const [bookings, setBookings] = useState([]);

  const openDetail = async () => {
    setShowModal(true);
    try {
      const res = await fetch(`/api/bookings?court_id=${court.Courts_ID}`);
      const data = await res.json();
      setBookings(data || []);
    } catch (err) {
      setBookings([]);
    }
  };

  const closeModal = () => {
    setShowModal(false);
    setBookings([]);
  };

  const bookedSlots = bookings.map(b => b.start_time.slice(0,5));
  const availableSlots = allSlots.filter(slot => !bookedSlots.includes(slot));

  return (
    <>
      <style>{`
        .court-card {
          background: #fff;
          border-radius: 16px;
          box-shadow: 0 4px 24px #0154b91a;
          overflow: hidden;
          display: flex;
          flex-direction: column;
          transition: box-shadow 0.18s, transform 0.18s;
          cursor: pointer;
          min-width: 260px;
          max-width: 340px;
          margin: 0 auto;
        }
        .court-card:hover {
          box-shadow: 0 8px 32px #0154b933;
          transform: translateY(-4px) scale(1.03);
        }
        .court-card-img-wrap {
          width: 100%;
          height: 180px;
          background: #f4f9fd;
          display: flex;
          align-items: center;
          justify-content: center;
        }
        .court-card-img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          border-radius: 16px 16px 0 0;
          transition: transform 0.18s;
        }
        .court-card:hover .court-card-img {
          transform: scale(1.04);
        }
        .court-card-info {
          padding: 18px 16px 14px 16px;
          display: flex;
          flex-direction: column;
          flex: 1;
        }
        .court-card-title {
          font-size: 19px;
          font-weight: 700;
          color: #1a237e;
          margin-bottom: 8px;
          min-height: 44px;
          display: -webkit-box;
          -webkit-line-clamp: 2;
          -webkit-box-orient: vertical;
          overflow: hidden;
        }
        .court-card-meta {
          font-size: 14px;
          color: #1976d2;
          margin-bottom: 6px;
        }
        .court-card-price {
          font-size: 16px;
          color: #e53935;
          font-weight: 700;
          margin-bottom: 10px;
        }
        .court-card-actions {
          display: flex;
          gap: 10px;
          margin-top: auto;
        }
        .court-card-detail-btn {
          background: #fff;
          color: #0154b9;
          border: 1.5px solid #0154b9;
          border-radius: 8px;
          padding: 7px 16px;
          font-weight: 600;
          font-size: 15px;
          cursor: pointer;
          text-decoration: none;
          transition: background 0.18s, color 0.18s;
        }
        .court-card-detail-btn:hover {
          background: #0154b9;
          color: #fff;
        }
        .court-card-book-btn {
          background: linear-gradient(90deg, #0154b9 0%, #3bb2ff 100%);
          color: #fff;
          border: none;
          border-radius: 8px;
          padding: 7px 18px;
          font-weight: 700;
          font-size: 15px;
          cursor: pointer;
          transition: background 0.18s, transform 0.18s;
          text-decoration: none;
          display: inline-block;
        }
        .court-card-book-btn:hover {
          background: linear-gradient(90deg, #3bb2ff 0%, #0154b9 100%);
          transform: scale(1.06);
        }
        /* Modal */
        .court-modal-overlay {
          position: fixed;
          top: 0; left: 0; right: 0; bottom: 0;
          background: rgba(0,0,0,0.5);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 999;
        }
        .court-modal-detail {
          background: #fff;
          border-radius: 18px;
          box-shadow: 0 8px 32px #0154b933;
          max-width: 700px;
          width: 96vw;
          max-height: 96vh;
          overflow-y: auto;
          position: relative;
          padding: 0 0 24px 0;
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        .modal-close-btn {
          position: absolute; top: 14px; right: 18px;
          background: #f4f9fd;
          border: none;
          font-size: 28px;
          color: #0154b9;
          border-radius: 50%;
          width: 38px; height: 38px;
          cursor: pointer;
          transition: background 0.18s;
          z-index: 2;
        }
        .modal-close-btn:hover {
          background: #e0e7ff;
          color: #e53935;
        }
        .court-modal-img-wrap {
          width: 100%;
          height: 240px;
          background: #f4f9fd;
          border-radius: 18px 18px 0 0;
          overflow: hidden;
          display: flex; align-items: center; justify-content: center;
        }
        .court-modal-img {
          width: 100%; height: 100%;
          object-fit: cover;
          border-radius: 18px 18px 0 0;
        }
        .court-modal-info {
          width: 100%;
          padding: 24px 28px 0 28px;
          display: flex; flex-direction: column; align-items: flex-start;
        }
        .court-modal-title {
          font-size: 26px;
          font-weight: 800;
          color: #0154b9;
          margin-bottom: 8px;
        }
        .court-modal-meta {
          font-size: 15px;
          color: #1976d2;
          margin-bottom: 8px;
          display: flex; gap: 18px;
          align-items: center;
        }
        .court-modal-meta i { margin-right: 4px; }
        .court-modal-price {
          font-size: 18px;
          color: #e53935;
          font-weight: 700;
          margin-bottom: 8px;
          display: flex; align-items: center; gap: 8px;
        }
        .court-modal-time {
          font-size: 15px;
          color: #0154b9;
          margin-bottom: 10px;
          display: flex; align-items: center; gap: 8px;
        }
        .court-modal-time i { margin-right: 4px; }
        .court-modal-desc {
          font-size: 15px;
          color: #374151;
          margin-bottom: 14px;
          margin-top: 6px;
        }
        .court-modal-slots h4 {
          font-size: 16px;
          font-weight: 700;
          margin-bottom: 8px;
          color: #0154b9;
        }
        .slot-box-list {
          display: flex;
          flex-wrap: wrap;
          gap: 10px;
          margin-top: 4px;
        }
        .slot-box {
          background: linear-gradient(90deg, #e0f2fe 0%, #bae6fd 100%);
          color: #0154b9;
          border-radius: 8px;
          padding: 8px 18px;
          font-weight: 600;
          font-size: 15px;
          box-shadow: 0 2px 8px #0154b91a;
          border: 1.5px solid #7dd3fc;
          margin-bottom: 4px;
          display: inline-flex;
          align-items: center;
          gap: 6px;
          transition: background 0.18s, color 0.18s;
          user-select: none;
        }
        .slot-box i { color: #2563eb; }
        .slot-box:hover {
          background: linear-gradient(90deg, #3bb2ff 0%, #0154b9 100%);
          color: #fff;
          cursor: pointer;
        }
        .slot-empty {
          color: #e53935;
          font-weight: 600;
          margin-bottom: 8px;
        }
        .court-modal-actions {
          margin-top: 18px;
          width: 100%;
          display: flex;
          justify-content: flex-end;
        }
        .court-modal-book-btn {
          background: linear-gradient(90deg, #0154b9 0%, #3bb2ff 100%);
          color: #fff;
          border: none;
          border-radius: 8px;
          padding: 10px 28px;
          font-weight: 700;
          font-size: 17px;
          box-shadow: 0 2px 8px rgba(1,84,185,0.12);
          transition: background 0.2s, transform 0.18s;
          text-decoration: none;
          margin-left: auto;
        }
        .court-modal-book-btn:hover {
          background: linear-gradient(90deg, #3bb2ff 0%, #0154b9 100%);
          transform: scale(1.06);
        }
        @media (max-width: 600px) {
          .court-card {
            min-width: 90vw;
            max-width: 99vw;
          }
          .court-card-img-wrap {
            height: 120px;
          }
          .court-modal-detail { padding: 0 0 12px 0; }
          .court-modal-info { padding: 18px 8px 0 8px; }
          .court-modal-title { font-size: 20px; }
          .court-modal-img-wrap { height: 140px; }
        }
      `}</style>

      <div className="court-card">
        <div className="court-card-img-wrap">
          <img src={court.Image} alt={court.Name} className="court-card-img" />
        </div>
        <div className="court-card-info">
          <h3 className="court-card-title">{court.Name}</h3>
          <div className="court-card-meta">
            {/* Không show địa điểm ở đây */}
            <span>🏸 {court.Court_type}</span>
          </div>
          <div className="court-card-price">
            <span>
              💵 {Number(court.Price_per_hour).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} / giờ
            </span>
          </div>
          <div style={{ color: "#0154b9", fontSize: 14, marginBottom: 10 }}>
            <span>
              <i className="fas fa-clock"></i> {court.open_time || "06:00"} - {court.close_time || "22:00"}
            </span>
          </div>
          <div className="court-card-actions">
            <button className="court-card-detail-btn" onClick={openDetail}>
              Xem chi tiết
            </button>
            <Link to={`/booking?court_id=${court.Courts_ID}`} className="court-card-book-btn">
              Đặt sân
            </Link>
          </div>
        </div>
      </div>

      {/* Modal chi tiết sân */}
      {showModal && (
        <div className="court-modal-overlay" onClick={closeModal}>
          <div className="court-modal-detail" onClick={e => e.stopPropagation()}>
            <button className="modal-close-btn" onClick={closeModal} title="Đóng">&times;</button>
            <div className="court-modal-img-wrap">
              <img src={court.Image} alt={court.Name} className="court-modal-img" />
            </div>
            <div className="court-modal-info">
              <h2 className="court-modal-title">{court.Name}</h2>
              <div className="court-modal-meta">
                <span><i className="fas fa-map-marker-alt"></i> {court.location?.name || '-'}</span>
                <span><i className="fas fa-bolt"></i> {court.Court_type}</span>
              </div>
              <div className="court-modal-price">
                <span>
                  <i className="fas fa-money-bill-wave"></i> 
                  {Number(court.Price_per_hour).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} / giờ
                </span>
              </div>
              <div className="court-modal-time">
                <span>
                  <i className="fas fa-clock"></i> 
                  {court.open_time || "06:00"} - {court.close_time || "22:00"}
                </span>
              </div>
              {court.Description && (
                <div className="court-modal-desc">
                  <strong>Mô tả:</strong>
                  <div style={{ whiteSpace: "pre-line", marginTop: 4 }}>{court.Description}</div>
                </div>
              )}
              <div className="court-modal-slots">
                <h4>Khung giờ còn trống hôm nay</h4>
                {availableSlots.length === 0 ? (
                  <div className="slot-empty">Hết chỗ trong ngày này.</div>
                ) : (
                  <div className="slot-box-list">
                    {availableSlots.map(slot => {
                      const now = new Date();
                      const isToday = true; // Modal này chỉ show hôm nay
                      const slotHour = parseInt(slot.split(":")[0]);
                      const isPast = isToday && slotHour <= now.getHours();
                      const isBooked = bookedSlots.includes(slot);
                      const disabled = isPast || isBooked;

                      return (
                        <span
                          key={slot}
                          className="slot-box"
                          style={{
                            background: isPast ? "#e5e7eb" : "#fff",
                            color: isPast ? "#9ca3af" : "#0154b9",
                            border: "1.5px solid #b6d4fa",
                            borderRadius: 8,
                            padding: "8px 18px",
                            margin: "0 8px 8px 0",
                            cursor: isPast ? "not-allowed" : "pointer",
                            textDecoration: isPast ? "line-through" : "none",
                            opacity: isPast ? 0.7 : 1,
                            fontWeight: 600,
                            fontSize: 15,
                            display: "inline-block",
                          }}
                          // Nếu muốn click thì thêm onClick, còn không thì bỏ
                          // onClick={() => !isPast && handleSlotClick(slot)}
                        >
                          <i className="far fa-clock"></i> {slot} - {parseInt(slot) + 1}:00
                        </span>
                      );
                    })}
                  </div>
                )}
              </div>
              <div className="court-modal-actions">
                <Link to={`/booking?court_id=${court.Courts_ID}`} className="court-modal-book-btn">
                  Đặt sân ngay
                </Link>
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
}