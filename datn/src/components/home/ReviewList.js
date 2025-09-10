import React, { useEffect, useState } from "react";
import axios from "axios";

// Gộp ReviewItem vào cùng file
const ReviewItem = ({ avatar, name, rating, text, images, product_name }) => (
    <div
        className="review-item"
        style={{
            display: "flex",
            alignItems: "flex-start",
            background: "#fafdff",
            borderRadius: 14,
            boxShadow: "0 1px 6px rgba(1,84,185,0.06)",
            padding: 16,
            marginBottom: 18,
            border: "1.5px solid #e3f0ff",
        }}
    >
        <img
            src={avatar || "/img/product/lcw.png"}
            alt={name}
            style={{
                width: 48,
                height: 48,
                borderRadius: "50%",
                marginRight: 16,
                border: "2px solid #b6d4fe",
            }}
        />
        <div style={{ flex: 1 }}>
            <div style={{ fontWeight: 600, color: "#0154b9" }}>{name}</div>
            <div style={{ color: "#f7b500", margin: "2px 0 6px 0" }}>
                {"★".repeat(rating)}{"☆".repeat(5 - rating)}
            </div>
            {product_name && (
                <div style={{ fontSize: 13, color: "#888", marginBottom: 4 }}>
                    Đánh giá cho: <b>{product_name}</b>
                </div>
            )}
            <div
                style={{
                    fontSize: 15,
                    color: "#333",
                    marginBottom: 8,
                }}
            >
                {text}
            </div>
            {images && images.length > 0 && (
                <div style={{ display: "flex", gap: 8 }}>
                    {images.map((img, idx) => (
                        <img
                            key={idx}
                            src={img}
                            alt=""
                            style={{
                                width: 56,
                                height: 56,
                                borderRadius: 8,
                                objectFit: "cover",
                                border: "1.5px solid #e3f0ff",
                            }}
                        />
                    ))}
                </div>
            )}
        </div>
    </div>
);

const ReviewList = () => {
    const [reviews, setReviews] = useState([]);

    useEffect(() => {
        axios.get("/api/top-reviews")
            .then(res => {
                setReviews(res.data.map(r => ({
                    ...r,
                    avatar: "/img/product/lcw.png", // hoặc lấy từ user nếu có
                    images: r.image ? [r.image] : [],
                    rating: Number(r.Rating),
                    text: r.product_ratings.review || r.comment || "",
                    name: r.user_name || r.name || "Ẩn danh",
                    product_name: r.product_name || "",
                })));
            });
    }, []);

    return (
        <div className="reviews-wrapper">
            <div className="reviews-grid">
                {reviews.map((review, index) => (
                    <ReviewItem key={index} {...review} />
                ))}
            </div>
            <div className="see-more">
                <a href="/#">Xem tất cả đánh giá.</a>
            </div>
        </div>
    );
};

export default ReviewList;
