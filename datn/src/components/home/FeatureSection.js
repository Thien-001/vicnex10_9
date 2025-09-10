import React from "react";

const features = [
  {
    icon: "🏸",
    title: "SẢN PHẨM CHÍNH HÃNG",
    desc: "Cam kết cung cấp sản phẩm cầu lông chính hãng, bảo hành đầy đủ.",
  },
  {
    icon: "🚚",
    title: "GIAO HÀNG NHANH",
    desc: "Giao hàng toàn quốc, kiểm tra trước khi thanh toán.",
  },
  {
    icon: "💰",
    title: "GIÁ TỐT NHẤT",
    desc: "Giá cạnh tranh, nhiều ưu đãi hấp dẫn mỗi tháng.",
  },
  {
    icon: "⭐",
    title: "ĐỔI TRẢ LINH HOẠT",
    desc: "Đổi trả trong 7 ngày nếu sản phẩm lỗi hoặc không đúng mô tả.",
  },
];

const FeatureSection = () => (
  <>
    <div className="feature-section">
      {features.map((item, idx) => (
        <div className="feature-box" key={idx}>
          <div className="feature-icon">{item.icon}</div>
          <div className="feature-content">
            <div className="feature-title">{item.title}</div>
            <div className="feature-desc">{item.desc}</div>
          </div>
        </div>
      ))}
    </div>
    
  </>
);

export default FeatureSection;
