import { useEffect, useState } from "react";
import axios from "axios";

function ExpertReviews({ productId }) {
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!productId) return;
    setLoading(true);
    axios
      .get(`/api/expert-reviews?product_id=${productId}`)
      .then((res) => {
        setReviews(res.data);
      })
      .finally(() => setLoading(false));
  }, [productId]);

  // CSS trực tiếp
  const styles = `
    .expert-reviews {
      background: #f4f9fd;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(1,84,185,0.07);
      margin: 0;

      height: fit-content;

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

  if (!productId)
    return null;
  if (loading)
    return (
      <>
        <style>{styles}</style>
        <aside className="expert-reviews">
          Đang tải nhận xét chuyên gia...
        </aside>
      </>
    );
  if (!reviews.length)
    return (
      <>
        <style>{styles}</style>
        <aside className="expert-reviews">
          Chưa có nhận xét chuyên gia.
        </aside>
      </>
    );

  return (
    <>
      <style>{styles}</style>
      <aside className="expert-reviews">
        <h4>Chuyên gia nói gì về sản phẩm?</h4>
        {reviews.slice(0, 5).map((review, idx) => (
          <div className="review" key={review.id || idx}>
            <img
              src={review.expert_image || "/img/product/default.png"}
              alt={review.expert_name}
              className="avatar"
            />
            <div className="info">
              <strong className="name">{review.expert_name}</strong>
              {review.position && (
                <small className="title">{review.position}</small>
              )}
            </div>
            <div className="rating">
              {review.rating && <div className="stars">{review.rating}</div>}
              <em className="comment">"{review.content}"</em>
            </div>
          </div>
        ))}
      </aside>
    </>
  );
}

export default ExpertReviews;
