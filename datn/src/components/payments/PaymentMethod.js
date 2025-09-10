import React, { useState } from "react";

const paymentMethods = [
  { key: "cod", label: "Thanh toán khi nhận hàng (COD)" },
  { key: "bank", label: "Chuyển khoản ngân hàng" },
  { key: "momo", label: "Ví điện tử (Momo, ZaloPay...)" },
  { key: "installment", label: "Trả góp (thẻ tín dụng)" },
  { key: "card", label: "Thẻ Visa/MasterCard" },
];

export default function PaymentMethod({ value, onChange }) {
  const [selected, setSelected] = useState(value || "cod");

  const handleChange = (key) => {
    setSelected(key);
    if (onChange) onChange(key);
  };

  return (
    <div>
      <h3 style={{ marginBottom: 16, color: "#0154b9" }}>Phương thức thanh toán</h3>
      <div style={{ display: "flex", flexDirection: "column", gap: 12 }}>
        {paymentMethods.map((method) => (
          <label key={method.key} style={{
            display: "flex", alignItems: "center", cursor: "pointer", padding: "10px 0"
          }}>
            <input
              type="radio"
              name="payment"
              checked={selected === method.key}
              onChange={() => handleChange(method.key)}
              style={{ marginRight: 12 }}
            />
            <span style={{ fontSize: 16 }}>{method.label}</span>
          </label>
        ))}
      </div>
      {selected === "bank" && (
        <div style={{
          marginTop: 24,
          padding: 18,
          background: "#f6f8fc",
          borderRadius: 12,
          boxShadow: "0 2px 8px #e0e7ff"
        }}>
          <div style={{ fontWeight: 500, marginBottom: 10, color: "#0154b9" }}>Quét mã QR để chuyển khoản:</div>
          <img
            src="/img/qr-bank.png"
            alt="QR chuyển khoản"
            style={{ width: 180, height: 180, objectFit: "contain", borderRadius: 8, background: "#fff", border: "1px solid #e0e7ff" }}
          />
          <div style={{ marginTop: 12, color: "#333" }}>
            <b>Ngân hàng:</b> Vietcombank<br />
            <b>Số tài khoản:</b> 0123456789<br />
            <b>Chủ tài khoản:</b> Nguyễn Văn A
          </div>
        </div>
      )}
    </div>
  );
  }