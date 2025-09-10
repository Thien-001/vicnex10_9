import React from "react";
import FaqItem from "./FaqItem";

const faqData = [
  {
    question: "Làm thế nào để đặt hàng trên website?",
    answer:
      "Bạn chỉ cần chọn sản phẩm muốn mua, nhấn 'Thêm vào giỏ hàng', sau đó vào giỏ hàng để kiểm tra và tiến hành thanh toán theo hướng dẫn.",
  },
  {
    question: "Tôi có thể kiểm tra tình trạng đơn hàng như thế nào?",
    answer:
      "Sau khi đặt hàng, bạn sẽ nhận được email xác nhận cùng mã đơn hàng. Bạn có thể kiểm tra trạng thái đơn hàng tại mục 'Đơn hàng của tôi' hoặc liên hệ bộ phận CSKH để được hỗ trợ.",
  },
  {
    question: "Chính sách bảo hành sản phẩm ra sao?",
    answer:
      "Tất cả sản phẩm chính hãng đều được bảo hành từ 6 đến 24 tháng tùy theo từng loại. Vui lòng giữ hóa đơn mua hàng để được hỗ trợ bảo hành nhanh chóng.",
  },
  {
    question: "Tôi muốn đổi trả sản phẩm thì phải làm gì?",
    answer:
      "Bạn có thể đổi trả sản phẩm trong vòng 7 ngày kể từ khi nhận hàng nếu sản phẩm bị lỗi kỹ thuật hoặc không đúng mô tả. Vui lòng liên hệ CSKH để được hướng dẫn chi tiết.",
  },
  {
    question: "Các hình thức thanh toán nào được chấp nhận?",
    answer:
      "Chúng tôi hỗ trợ thanh toán khi nhận hàng (COD), chuyển khoản ngân hàng và thanh toán qua ví điện tử. Bạn có thể lựa chọn hình thức phù hợp khi đặt hàng.",
  },
];

const FaqSection = () => {
  return (
    <>
      <style>{`
        .faq-section {
          max-width: 1000px;
          margin: 48px auto 0 auto;
          background: #fff;
          border-radius: 18px;
          box-shadow: 0 4px 32px rgba(1,84,185,0.08);
          padding: 36px 28px 32px 28px;
        }
        .faq-title {
          text-align: center;
          font-size: 2rem;
          font-weight: 700;
          color: #0154b9;
          margin-bottom: 32px;
          letter-spacing: 1px;
        }
        .faq-box {
          margin-bottom: 18px;
        }
        .faq-link {
          display: block;
          text-align: center;
          margin-top: 18px;
          color: #0154b9;
          font-weight: 600;
          text-decoration: none;
          font-size: 1.08rem;
          transition: color 0.18s;
        }
        .faq-link:hover {
          color: #013a7c;
          text-decoration: underline;
        }
        @media (max-width: 800px) {
          .faq-section {
            padding: 18px 6px 18px 6px;
          }
        }
      `}</style>
      <div className="faq-section">
        <div className="faq-title">
          <i className="fa-regular fa-circle-question" style={{marginRight: 10}}></i>
          Câu hỏi thường gặp
        </div>
        <div className="faq-box">
          {faqData.map((faq, index) => (
            <FaqItem key={index} question={faq.question} answer={faq.answer} />
          ))}
        </div>
        <a className="faq-link" href="/faq">
          Xem tất cả câu hỏi &gt;
        </a>
      </div>
    </>
  );
};

export default FaqSection;
