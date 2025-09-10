import React from "react";
import { Link } from "react-router-dom";

export default function Thankyou({ products = [], booking }) {
  const isBooking = !!booking;

  return (
    <div
      className="thankyou-page"
      style={{
        minHeight: "70vh",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        background: "linear-gradient(135deg,#e0e7ff 0%,#fff 100%)",
      }}
    >
      <div
        style={{
          background: "#fff",
          borderRadius: 24,
          boxShadow: "0 12px 48px rgba(1,84,185,0.15)",
          padding: "56px 36px 48px 36px",
          textAlign: "center",
          minWidth: 340,
          maxWidth: 500,
          border: "1.5px solid #e0e7ff",
          position: "relative",
          margin: "30px",
        }}
      >
        <div
          style={{
            fontSize: 68,
            color: "#fff",
            marginBottom: 18,
            width: 90,
            height: 90,
            borderRadius: "50%",
            background: "linear-gradient(135deg,#3bb2ff 0%,#0154b9 100%)",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            margin: "0 auto 18px auto",
            boxShadow: "0 4px 24px #0154b92a",
          }}
        >
          <span role="img" aria-label="success">🎉</span>
        </div>
        <h2
          style={{
            color: "#0154b9",
            marginBottom: 12,
            fontWeight: 800,
            fontSize: 28,
            letterSpacing: 0.5,
          }}
        >
          {isBooking ? "Đặt sân thành công!" : "Cảm ơn bạn đã đặt hàng!"}
        </h2>
        <p style={{ fontSize: 18, color: "#374151", marginBottom: 22, lineHeight: 1.7 }}>
          {isBooking ? (
            <>
              Yêu cầu đặt sân của bạn đã được ghi nhận.<br />
              Chúng tôi sẽ liên hệ xác nhận và hỗ trợ bạn sớm nhất.<br />
              <span style={{ color: "#0154b9", fontWeight: 700, fontSize: 17 }}>
                Thông tin đặt sân:
              </span>
              <div
                style={{
                  background: "linear-gradient(90deg,#e0f2fe 0%,#bae6fd 100%)",
                  borderRadius: 12,
                  padding: "18px 16px",
                  margin: "18px 0",
                  color: "#0154b9",
                  fontWeight: 600,
                  textAlign: "left",
                  fontSize: 16,
                  boxShadow: "0 2px 8px #bae6fd44",
                }}
              >
                <div>🏸 <b>Tên sân:</b> {booking.CourtName}</div>
                <div><b>Loại sân:</b> {booking.Court_type}</div>
                <div><b>Địa điểm:</b> {booking.Location}</div>
                <div><b>Ngày:</b> {booking.Booking_date}</div>
                <div>
                  <b>Khung giờ:</b> {booking.Start_time?.slice(0,5)} - {booking.End_time?.slice(0,5)}
                  &nbsp;|&nbsp; <b>Số giờ:</b> {booking.Duration_hours}
                </div>
                <div><b>Tổng tiền:</b> {Number(booking.Total_price).toLocaleString()}₫</div>
                {booking.Note && <div><b>Ghi chú:</b> {booking.Note}</div>}
              </div>
              Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!
            </>
          ) : (
            <>
              Đơn hàng của bạn đã được ghi nhận.<br />
              Chúng tôi sẽ liên hệ xác nhận và giao hàng sớm nhất.
            </>
          )}
        </p>
        {!isBooking && products.length > 0 && (
          <div style={{ margin: "28px 0" }}>
            <h3 style={{ color: "#0154b9", fontSize: 18, marginBottom: 14, fontWeight: 700 }}>Sản phẩm bạn vừa mua:</h3>
            <ul style={{ listStyle: "none", padding: 0, margin: 0 }}>
              {products.map((p, idx) => (
                <li
                  key={idx}
                  style={{
                    marginBottom: 14,
                    display: "flex",
                    alignItems: "center",
                    gap: 14,
                    background: "#f6f8fc",
                    borderRadius: 8,
                    padding: "8px 12px",
                    boxShadow: "0 1px 4px #e0e7ff",
                  }}
                >
                  <img
                    src={p.Image?.startsWith("http") ? p.Image : `/${p.Image}`}
                    alt={p.Name}
                    style={{
                      width: 48,
                      height: 48,
                      objectFit: "cover",
                      borderRadius: 8,
                      boxShadow: "0 1px 4px #e0e7ff",
                      border: "1.5px solid #e0e7ff",
                    }}
                  />
                  <span style={{ fontWeight: 600, color: "#0154b9", fontSize: 15 }}>{p.Name}</span>
                  <span style={{ color: "#e53935", fontWeight: 700, fontSize: 15 }}>{p.quantity ? `x${p.quantity}` : ""}</span>
                </li>
              ))}
            </ul>
          </div>
        )}
        <Link
          to="/"
          className="back-link"
          style={{
            marginTop: 28,
            padding: "13px 36px",
            background: "linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%)",
            color: "#fff",
            borderRadius: 12,
            textDecoration: "none",
            fontWeight: 800,
            fontSize: 18,
            boxShadow: "0 2px 12px rgba(1,84,185,0.13)",
            transition: "background 0.2s, box-shadow 0.2s",
            display: "inline-block",
            letterSpacing: 0.2,
          }}
        >
          Quay về trang chủ
        </Link>
      </div>
    </div>
  );
}