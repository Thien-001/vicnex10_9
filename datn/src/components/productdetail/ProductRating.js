import React, { useState, useEffect } from "react";

function ProductRating({ productId, user }) {
  const [rating, setRating] = useState(0);
  const [hover, setHover] = useState(0);
  const [message, setMessage] = useState("");
  const [avg, setAvg] = useState(0);
  const [count, setCount] = useState(0);
  const [imageFiles, setImageFiles] = useState([]); // Thay đổi thành mảng để lưu nhiều ảnh
  const [reviews, setReviews] = useState([]); // Thêm state lưu danh sách đánh giá
  const [reviewText, setReviewText] = useState(""); // Thêm state lưu trữ đánh giá bằng văn bản

  useEffect(() => {
    fetch(`http://localhost:8000/api/products/${productId}/ratings`)
      .then(res => res.json())
      .then(data => {
        setAvg(Number(data.avg) || 0);
        setCount(Number(data.count) || 0);
        setReviews(Array.isArray(data.reviews) ? data.reviews : []); // Lưu danh sách đánh giá
      });
  }, [productId, message]);

  const handleImageChange = (e) => {
    const files = Array.from(e.target.files);
    if (files.length > 5) {
      setMessage("Bạn chỉ được chọn tối đa 5 ảnh.");
      return;
    }
    setImageFiles(files);
  };

  const handleSubmit = () => {
    if (!user) {
      setMessage("Bạn cần đăng nhập để đánh giá!");
      return;
    }
    if (!rating) {
      setMessage("Vui lòng chọn số sao!");
      return;
    }

    const formData = new FormData();
    formData.append("User_ID", user.ID);
    formData.append("Rating", rating);
    formData.append("text", reviewText); // Thêm trường đánh giá bằng văn bản
    imageFiles.forEach((file) => {
      formData.append("images[]", file); // ĐÚNG
    });

    fetch(`http://localhost:8000/api/products/${productId}/ratings`, {
      method: "POST",
      body: formData,
    })
      .then(async res => {
        const data = await res.json().catch(() => null);
        if (!res.ok) {
          setMessage(data?.message || "Đánh giá thất bại!");
          return;
        }
        setMessage("Cảm ơn bạn đã đánh giá!");
        setImageFiles([]);
        setReviewText(""); // Xóa nội dung đánh giá sau khi gửi
      })
      .catch(() => {
        setMessage("Đánh giá thất bại!");
      });
  };

  return (
    <div style={{
      background: "#fff",
      borderRadius: 8,
      padding: 20,
      margin: "24px 0",
      boxShadow: "0 2px 12px rgba(1, 84, 185, 0.06)",
      maxWidth: 1400,
      width: "100%",
      marginLeft: "auto",
      marginRight: "auto"
    }}>
      <h3 style={{ color: "#0154b9", marginBottom: 12 }}>Đánh giá sản phẩm</h3>
      <div style={{ fontSize: 22, marginBottom: 8 }}>
        {Array.from({ length: 5 }).map((_, i) => (
          <span
            key={i}
            style={{
              cursor: user ? "pointer" : "not-allowed",
              color: (hover || rating) > i ? "#FFD600" : "#ccc",
              transition: "color 0.2s"
            }}
            onMouseEnter={() => user && setHover(i + 1)}
            onMouseLeave={() => user && setHover(0)}
            onClick={() => user && setRating(i + 1)}
          >★</span>
        ))}
      </div>
      <label
        htmlFor="rating-images"
        style={{
          display: "inline-block",
          background: "#e3f0ff",
          color: "#0154b9",
          padding: "7px 18px",
          borderRadius: 8,
          fontWeight: 600,
          cursor: "pointer",
          marginBottom: 8,
          border: "1.5px solid #b6d4fe",
          fontSize: 15,
          transition: "background 0.18s"
        }}
      >
        📷 Chọn tối đa 5 ảnh
      </label>
      <input
        id="rating-images"
        type="file"
        accept="image/*"
        multiple
        onChange={handleImageChange}
        style={{ display: "none" }}
      />
      {imageFiles.length > 0 && (
        <div style={{ marginBottom: 8, display: "flex", gap: 8 }}>
          {imageFiles.map((file, index) => (
            <img
              key={index}
              src={URL.createObjectURL(file)}
              alt={`preview-${index}`}
              style={{ width: 120, borderRadius: 8, marginTop: 8, objectFit: "cover" }}
            />
          ))}
        </div>
      )}
      <textarea
        value={reviewText}
        onChange={e => setReviewText(e.target.value)}
        placeholder="Nhập nội dung đánh giá..."
        rows={3}
        style={{
          width: "100%",
          borderRadius: 8,
          border: "1.5px solid #b6d4fe",
          padding: 10,
          margin: "12px 0",
          fontSize: 15,
          resize: "vertical"
        }}
      />
      <button
        onClick={handleSubmit}
        disabled={!user || !rating}
        style={{
          background: "#0154b9",
          color: "#fff",
          border: "none",
          borderRadius: 8,
          padding: "8px 24px",
          fontWeight: 600,
          fontSize: "1rem",
          cursor: (!user || !rating) ? "not-allowed" : "pointer",
          marginBottom: 8
        }}
      >
        Gửi đánh giá
      </button>
      {message && (
        <div style={{
          color: message.includes("thất bại") ? "#d32f2f" : "#388e3c",
          marginTop: 8,
          fontWeight: 500
        }}>{message}</div>
      )}
      <div style={{ marginTop: 16, color: "#0154b9" }}>
        <b>Điểm trung bình:</b> {Number(avg).toFixed(1)} / 5 ({count} lượt đánh giá)
      </div>

      {/* HIỂN THỊ DANH SÁCH ĐÁNH GIÁ PHÍA DƯỚI */}
      <div style={{ marginTop: 32 }}>
        <h4 style={{ color: "#0154b9", marginBottom: 12 }}>Các đánh giá gần đây</h4>
        {reviews.length === 0 && <div style={{ color: "#888" }}>Chưa có đánh giá nào.</div>}
        {reviews.map((rv, idx) => (
          <div key={idx} style={{ display: "flex", alignItems: "center", gap: 16, marginBottom: 18 }}>
            <div>
              <b>{rv.user_name || `Người dùng #${rv.User_ID}`}</b>
              <div style={{ color: "#FFD600", fontSize: 18 }}>
                {Array.from({ length: rv.Rating }).map((_, i) => <span key={i}>★</span>)}
              </div>
              <div style={{ fontSize: 13, color: "#888" }}>{rv.created_at?.slice(0, 16).replace("T", " ")}</div>
              {rv.text && (
                <div style={{ fontSize: 15, color: "#333", margin: "4px 0 8px 0" }}>
                  {rv.text}
                </div>
              )}
            </div>
            {/* HIỂN THỊ ẢNH ĐÁNH GIÁ */}
            {rv.images && rv.images.length > 0 && (
              <div style={{ display: "flex", gap: 8, marginLeft: 12 }}>
                {rv.images.map((img, i) => (
                  <img
                    key={i}
                    src={`http://localhost:8000/storage/${img.replace(/^\/+/, "")}`}
                    alt="Ảnh đánh giá"
                    style={{ width: 90, borderRadius: 8, objectFit: "cover" }}
                  />
                ))}
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
}

export default ProductRating;