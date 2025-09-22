import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function ProductRating({ productId, user }) {
  const [rating, setRating] = useState(0);
  const [hover, setHover] = useState(0);
  const [message, setMessage] = useState("");
  const [avg, setAvg] = useState(0);
  const [count, setCount] = useState(0);
  const [imageFiles, setImageFiles] = useState([]);
  const [reviews, setReviews] = useState([]);
  const [reviewText, setReviewText] = useState("");
  const [hasPurchased, setHasPurchased] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    fetch(`http://localhost:8000/api/products/${productId}/ratings`)
      .then(res => res.json())
      .then(data => {
        setAvg(Number(data.avg) || 0);
        setCount(Number(data.count) || 0);
        setReviews(Array.isArray(data.reviews) ? data.reviews : []);
      });
  }, [productId, message]);

  useEffect(() => {
    if (user && productId) {
      console.log("Check purchased:", user.ID, productId); // Log giá trị truyền vào API
      fetch(`http://localhost:8000/api/orders/check-purchased?user_id=${user.ID}&product_id=${productId}`)
        .then(res => res.json())
        .then(data => {
          console.log("API response:", data); // Log kết quả trả về từ API
          setHasPurchased(!!data.purchased);
        })
        .catch((err) => {
          console.log("API error:", err); // Log lỗi nếu có
          setHasPurchased(false);
        });
    } else {
      setHasPurchased(false);
    }
  }, [user, productId]);

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
    formData.append("text", reviewText);
    imageFiles.forEach((file) => {
      formData.append("images[]", file);
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
        setReviewText("");
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
      <h3 style={{ color: "#000", marginBottom: 12, fontWeight: "700" }}>Đánh giá sản phẩm</h3>
      {hasPurchased ? (
        <>
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
        </>
      ) : (
        <div style={{textAlign: "center", margin: "24px 0"}}>
          <p style={{fontWeight: 600, color: "#000", marginBottom: 12}}>
            Mua hàng ngay để trải nghiệm sản phẩm của chúng mình!
          </p>
          <button
            onClick={() => window.scrollTo({ top: 0, behavior: "smooth" })}
            style={{
              background: "#e74c3c",
              color: "#fff",
              border: "none",
              borderRadius: 6,
              padding: "10px 24px",
              fontWeight: 700,
              fontSize: 16,
              cursor: "pointer"
            }}
          >
            Mua hàng ngay
          </button>
        </div>
      )}
      <div style={{ marginTop: 16, color: "#F7DC6F", fontSize: 18, fontWeight: 500 }}>
        <b style={{color: "#000" }}>Điểm trung bình:</b> {Number(avg).toFixed(1)} / 5 ({count} lượt đánh giá)
      </div>
      <div style={{ marginTop: 32 }}>
        <h4 style={{ color: "#000", marginBottom: 12 }}>Các đánh giá gần đây</h4>
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