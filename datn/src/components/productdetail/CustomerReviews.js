const reviewData = [
  {
    name: "Lee Chong Wei",
    rating: 4.8,
    avatar: "/img/product/lcw.png",
    comment:
      "Vợt này rất chắc chắn, smash cầu đi nhanh, kiểm soát tốt. Rất phù hợp với người chơi có lối đánh tấn công. Shop giao hàng nhanh, đóng gói cẩn thận.",
    images: [
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
    ],
    likes: 18,
    comments: 18,
  },{
    name: "Lee Chong Wei",
    rating: 4.8,
    avatar: "/img/product/lcw.png",
    comment:
      "Vợt này rất chắc chắn, smash cầu đi nhanh, kiểm soát tốt. Rất phù hợp với người chơi có lối đánh tấn công. Shop giao hàng nhanh, đóng gói cẩn thận.",
    images: [
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
    ],
    likes: 18,
    comments: 18,
  },{
    name: "Lee Chong Wei",
    rating: 4.8,
    avatar: "/img/product/lcw.png",
    comment:
      "Vợt này rất chắc chắn, smash cầu đi nhanh, kiểm soát tốt. Rất phù hợp với người chơi có lối đánh tấn công. Shop giao hàng nhanh, đóng gói cẩn thận.",
    images: [
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
    ],
    likes: 18,
    comments: 18,
  },{
    name: "Lee Chong Wei",
    rating: 4.8,
    avatar: "/img/product/lcw.png",
    comment:
      "Vợt này rất chắc chắn, smash cầu đi nhanh, kiểm soát tốt. Rất phù hợp với người chơi có lối đánh tấn công. Shop giao hàng nhanh, đóng gói cẩn thận.",
    images: [
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
    ],
    likes: 18,
    comments: 18,
  },{
    name: "Lee Chong Wei",
    rating: 4.8,
    avatar: "/img/product/lcw.png",
    comment:
      "Vợt này rất chắc chắn, smash cầu đi nhanh, kiểm soát tốt. Rất phù hợp với người chơi có lối đánh tấn công. Shop giao hàng nhanh, đóng gói cẩn thận.",
    images: [
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
      "/img/product/tesst.png",
    ],
    likes: 18,
    comments: 18,
  },
  // bạn có thể thêm các review khác tại đây
];

function CustomerReviews() {
  return (
    <section className="customer-reviews">
      <h3>Khách hàng nói gì về sản phẩm?</h3>
      <p>⭐️⭐️⭐️⭐️⭐️ 4.8/5 | 120 đánh giá</p>

      {reviewData.map((review, index) => (
        <div className="review-item" key={index}>
          <div className="review-header">
            <img src={review.avatar} alt="Avatar" />
            <div>
              <strong>{review.name}</strong> ⭐ {review.rating}/5
              <p>{review.comment}</p>
            </div>
          </div>

          <div className="review-images">
            {review.images.map((img, idx) => (
              <img key={idx} src={img} alt="Ảnh đánh giá" />
            ))}
          </div>

          <div className="review-actions">
            <span>👍 {review.likes}</span>
            <span>💬 {review.comments}</span>
          </div>
        </div>
      ))}

      <div className="more-reviews">
        <a href="/#">Xem thêm đánh giá</a>
      </div>
    </section>
  );
}

export default CustomerReviews;
