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
    const fetchExpertReviews = async () => {
      if (!product?.Product_ID) {
        setLoading(false);
        return;
      }
      
      try {
        setLoading(true);
        console.log("🔍 Fetching expert reviews for product:", product.Product_ID);
        
        const response = await axios.get(`http://localhost:8000/api/expert-reviews?product_id=${product.Product_ID}`);
        console.log("📊 Expert reviews response:", response.data);
        
        if (response.data) {
          // Nếu response.data là array
          if (Array.isArray(response.data)) {
            setReviews(response.data);
          }
          // Nếu response.data có property data
          else if (response.data.data && Array.isArray(response.data.data)) {
            setReviews(response.data.data);
          }
          // Nếu response.data có property reviews
          else if (response.data.reviews && Array.isArray(response.data.reviews)) {
            setReviews(response.data.reviews);
          }
          else {
            console.log("⚠️ Unexpected expert reviews data structure");
            setReviews([]);
          }
        } else {
          setReviews([]);
        }
        
      } catch (error) {
        console.error("❌ Error fetching expert reviews:", error);
        setReviews([]);
      } finally {
        setLoading(false);
      }
    };

    fetchExpertReviews();
  }, [product?.Product_ID]);

  // CSS cho ExpertReviews
  const expertStyles = `
    .expert-reviews {
      background: #f4f9fd;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(1,84,185,0.07);
      margin: 0;
      height: fit-content;
      display: flex;
      flex-direction: column;
      padding: 20px;
      border: 1px solid #e3e8f0;
    }
    .expert-reviews h4 {
      color: #0154b9;
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .expert-reviews .review {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 16px;
      padding: 16px;
      background: #fff;
      border-radius: 8px;
      border: 1px solid #e8f2ff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .expert-reviews .review:last-child {
      margin-bottom: 0;
    }
    .expert-reviews .avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e3e8f0;
      background: #f8f9fa;
      flex-shrink: 0;
    }
    .expert-reviews .info {
      flex: 1;
      min-width: 0;
    }
    .expert-reviews .name {
      color: #0154b9;
      font-weight: 600;
      font-size: 15px;
      margin-bottom: 2px;
    }
    .expert-reviews .title {
      color: #6b7280;
      font-size: 12px;
      margin-bottom: 6px;
      display: block;
    }
    .expert-reviews .rating {
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 2px;
    }
    .expert-reviews .comment {
      color: #374151;
      font-style: italic;
      font-size: 13px;
      line-height: 1.4;
      margin: 0;
    }
    .expert-reviews .no-reviews {
      text-align: center;
      color: #6b7280;
      font-style: italic;
      padding: 20px;
    }
    .expert-reviews .loading {
      text-align: center;
      color: #6b7280;
      padding: 20px;
    }
    @media (max-width: 900px) {
      .expert-reviews {
        max-width: 100%;
        min-width: 0;
        margin: 20px 0 0 0;
      }
    }
  `;

  // Kiểm tra product có details không
  const hasDetails =
    product &&
    (
      (typeof product.details === "string" && product.details.trim().length > 0) ||
      (typeof product.Description === "string" && product.Description.trim().length > 0) ||
      (typeof product.description === "string" && product.description.trim().length > 0)
    );

  const getProductDetails = () => {
    return product?.details || product?.Description || product?.description || "";
  };

  return (
    <div className="product-detail-section" style={{ 
      display: "flex", 
      gap: 50, 
      alignItems: "stretch",
      margin: "32px 0",
      maxWidth: "1900px",
      marginLeft: "200px",
      marginRight: "auto",
      padding: "0 20px"
    }}>
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
          height: "fit-content",
        }}
      >
        <h2
          style={{
            color: "#0154b9",
            fontWeight: 700,
            fontSize: 24,
            marginBottom: 18,
            letterSpacing: 0.5,
            display: "flex",
            alignItems: "center",
            gap: 8
          }}
        >
          📋 Chi Tiết Sản Phẩm
        </h2>
        <div
          className="product-description-content"
          style={{
            maxHeight: expanded ? "none" : 400,
            overflow: expanded ? "visible" : "hidden",
            position: "relative",
            transition: "max-height 0.3s",
            fontSize: 16,
            color: "#374151",
            lineHeight: 1.6,
          }}
        >
          {hasDetails ? (
            <div
              style={{ wordBreak: "break-word" }}
              dangerouslySetInnerHTML={{ __html: getProductDetails() }}
            />
          ) : (
            <p style={{ color: "#6b7280", fontStyle: "italic", textAlign: "center", padding: "40px 0" }}>
              Chưa có thông tin chi tiết sản phẩm.
            </p>
          )}
          {!expanded && hasDetails && getProductDetails().length > 800 && (
            <div
              style={{
                position: "absolute",
                bottom: 0,
                left: 0,
                width: "100%",
                height: 80,
                background:
                  "linear-gradient(to bottom, rgba(245,249,255,0), #f5f9ff 90%)",
                pointerEvents: "none",
                borderRadius: "0 0 16px 16px",
              }}
            />
          )}
        </div>
        {hasDetails && getProductDetails().length > 800 && (
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
              fontWeight: 600,
              fontSize: 14,
              cursor: "pointer",
              boxShadow: "0 2px 8px rgba(1,84,185,0.2)",
              transition: "all 0.2s",
              alignSelf: "flex-start"
            }}
            onClick={() => setExpanded(!expanded)}
            onMouseEnter={(e) => {
              e.target.style.transform = "translateY(-1px)";
              e.target.style.boxShadow = "0 4px 12px rgba(1,84,185,0.3)";
            }}
            onMouseLeave={(e) => {
              e.target.style.transform = "translateY(0)";
              e.target.style.boxShadow = "0 2px 8px rgba(1,84,185,0.2)";
            }}
          >
            {expanded ? "Thu gọn ▲" : "Xem thêm ▼"}
          </button>
        )}
      </div>

      {/* Expert Reviews */}
      <>
        <style>{expertStyles}</style>
        <aside className="expert-reviews" style={{
          flex: "1 1 450px",         // TĂNG TỪ 350px LÊN 450px
          minWidth: 400,             // TĂNG TỪ 300px LÊN 400px
          maxWidth: 550,             // TĂNG TỪ 420px LÊN 550px
          margin: 0,
          height: "fit-content",
          position: "sticky",
          top: "20px"
        }}>
          <h4>
            👨‍💼 Chuyên gia nói gì?
          </h4>
          
          {loading ? (
            <div className="loading">
              <div style={{ marginBottom: "8px" }}>🔄</div>
              Đang tải nhận xét chuyên gia...
            </div>
          ) : !reviews.length ? (
            <div className="no-reviews">
              <div style={{ fontSize: "32px", marginBottom: "8px" }}>💬</div>
              Chưa có nhận xét từ chuyên gia
            </div>
          ) : (
            <>
              {reviews.slice(0, 3).map((review, idx) => (
                <div className="review" key={review.id || idx}>
                  <img
                    src={review.expert_image || review.avatar || "/img/product/default-avatar.png"}
                    alt={review.expert_name || "Chuyên gia"}
                    className="avatar"
                    onError={(e) => {
                      e.target.src = "/img/product/default-avatar.png";
                    }}
                  />
                  <div className="info">
                    <div className="name">
                      {review.expert_name || review.name || "Chuyên gia"}
                    </div>
                    {(review.position || review.title) && (
                      <small className="title">
                        {review.position || review.title}
                      </small>
                    )}
                    {typeof review.rating === "number" && review.rating > 0 && (
                      <div className="rating">
                        {Array.from({ length: 5 }, (_, i) => (
                          <span 
                            key={i} 
                            style={{ 
                              color: i < review.rating ? "#ffd700" : "#e5e7eb", 
                              fontSize: "14px" 
                            }}
                          >
                            ★
                          </span>
                        ))}
                        <span style={{ marginLeft: "4px", fontSize: "12px", color: "#6b7280" }}>
                          ({review.rating}/5)
                        </span>
                      </div>
                    )}
                    <p className="comment">
                      "{review.content || review.comment || review.review || "Sản phẩm tốt, đáng tin cậy."}"
                    </p>
                  </div>
                </div>
              ))}
              
              {reviews.length > 3 && (
                <div style={{
                  textAlign: "center",
                  marginTop: "12px",
                  fontSize: "12px",
                  color: "#6b7280"
                }}>
                  +{reviews.length - 3} nhận xét khác
                </div>
              )}
            </>
          )}
        </aside>
      </>
    </div>
  );
}

export default ProductDetailSection;
