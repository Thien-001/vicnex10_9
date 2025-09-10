import { useState, useEffect } from "react";
import axios from "axios";

function ProductDetailSection({ product }) {
  // State cho ProductDescription
  const [expanded, setExpanded] = useState(false);

  // State cho ExpertReviews
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);

  // Lấy review khi có productId
  useEffect(() => {
    if (!product?.id) return;
    setLoading(true);
    axios
      .get(`/api/expert-reviews?product_id=${product.id}`)
      .then((res) => {
        setReviews(res.data);
      })
      .finally(() => setLoading(false));
  }, [product?.id]);

  // CSS cho ExpertReviews (có thể chuyển sang file css ngoài nếu muốn)
  const expertStyles = `
    .expert-reviews {
      background: #f4f9fd;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(1,84,185,0.07);
      margin: 0;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .expert-reviews h4 {
      color: #0154b9;
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 18px;
    }
    .expert-reviews .review {
      display: flex;
      align-items: flex-start;
      gap: 18px;
      margin-bottom: 22px;
      border-bottom: 1px solid #e3e8f0;
      padding-bottom: 16px;
    }
    .expert-reviews .review:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    .expert-reviews .avatar {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e3e8f0;
      background: #fff;
    }
    .expert-reviews .info {
      flex: 1;
    }
    .expert-reviews .name {
      color: #0154b9;
      font-weight: 600;
      font-size: 17px;
    }
    .expert-reviews .title {
      display: block;
      color: #6b7280;
      font-size: 14px;
      margin-top: 2px;
    }
    .expert-reviews .rating {
      margin-top: 6px;
    }
    .expert-reviews .comment {
      color: #222;
      font-style: italic;
      font-size: 15px;
      display: block;
      margin-top: 4px;
    }
    @media (max-width: 900px) {
      .expert-reviews {
        max-width: 100vw;
        min-width: 0;
        padding: 12px;
        margin: 0 0 18px 0;
        align-self: unset;
      }
    }
  `;

  // Kiểm tra product có details không
  const hasDetails =
    product &&
    typeof product.details === "string" &&
    product.details.trim().length > 0;

  return (
    <div className="product-detail-section" style={{ display: "flex", gap: 32, alignItems: "stretch" }}>
      {/* Product Description */}
      <div
        className="product-description"
        style={{
          background: "#f5f9ff",
          borderRadius: 16,
          boxShadow: "0 4px 24px rgba(1,84,185,0.08)",
          padding: "32px 24px",
          flex: "2 1 700px",
          minWidth: 320,
          maxWidth: 900,
          display: "flex",
          flexDirection: "column",
          height: "100%",
        }}
      >
        <h2
          style={{
            color: "#0154b9",
            fontWeight: 700,
            fontSize: 24,
            marginBottom: 18,
            letterSpacing: 0.5,
          }}
        >
          Chi Tiết Sản Phẩm
        </h2>
        <div
          className="product-description-content"
          style={{
            maxHeight: expanded ? "none" : 400,
            overflow: expanded ? "visible" : "hidden",
            position: "relative",
            transition: "max-height 0.3s",
            fontSize: 17,
            color: "#222",
            lineHeight: 1.7,
          }}
        >
          {hasDetails ? (
            <div
              style={{ wordBreak: "break-word" }}
              dangerouslySetInnerHTML={{ __html: product.details }}
            />
          ) : (
            <p style={{ color: "#888", fontStyle: "italic" }}>
              Chưa có thông tin chi tiết sản phẩm.
            </p>
          )}
          {!expanded && hasDetails && (
            <div
              style={{
                position: "absolute",
                bottom: 0,
                left: 0,
                width: "100%",
                height: 80,
                background:
                  "linear-gradient(to bottom, rgba(255,255,255,0), #fff 90%)",
                pointerEvents: "none",
                borderRadius: "0 0 16px 16px",
              }}
            />
          )}
        </div>
        {hasDetails && (
          <button
            className="see-more-btn"
            style={{
              marginTop: 18,
              background: expanded
                ? "linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%)"
                : "#0154b9",
              color: "#fff",
              border: "none",
              borderRadius: 8,
              padding: "10px 32px",
              fontWeight: 700,
              fontSize: 16,
              cursor: "pointer",
              boxShadow: "0 2px 8px rgba(1,84,185,0.08)",
              transition: "background 0.2s",
            }}
            onClick={() => setExpanded(!expanded)}
          >
            {expanded ? "Rút gọn" : "Xem thêm"}
          </button>
        )}
      </div>

      {/* Expert Reviews */}
      <>
        <style>{expertStyles}</style>
        <aside className="expert-reviews" style={{
          flex: "1 1 320px",
          minWidth: 280,
          maxWidth: 420,
          margin: 0,
          height: "100%",
          display: "flex",
          flexDirection: "column",
        }}>
          {loading ? (
            <>Đang tải nhận xét chuyên gia...</>
          ) : !reviews.length ? (
            <>Chưa có nhận xét chuyên gia.</>
          ) : (
            <>
              <h4>Chuyên gia nói gì về sản phẩm?</h4>
              {reviews.slice(0, 5).map((review, idx) => (
                <div className="review" key={review.id || idx}>
                  <img
                    src={review.expert_image || "/img/product/default.png"}
                    alt={review.expert_name || "Chuyên gia"}
                    className="avatar"
                  />
                  <div className="info">
                    <strong className="name">{review.expert_name || "Chuyên gia"}</strong>
                    {review.position && (
                      <small className="title">{review.position}</small>
                    )}
                    {typeof review.rating === "number" && (
                      <div className="rating stars">
                        {Array.from({ length: review.rating }, (_, i) => (
                          <span key={i} style={{ color: "#ffd700", fontSize: 18 }}>★</span>
                        ))}
                      </div>
                    )}
                  </div>
                  <em className="comment">
                    "{review.content || "Không có nhận xét."}"
                  </em>
                </div>
              ))}
            </>
          )}
        </aside>
      </>
    </div>
  );
}

export default ProductDetailSection;
