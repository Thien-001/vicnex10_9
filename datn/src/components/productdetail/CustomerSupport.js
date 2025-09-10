import SupportCard from "./SupportCard";
import SupportForm from "./SupportForm";

const CustomerSupport = () => (
  <section className="support-section">
    <h2>Hỗ trợ khách hàng</h2>
    <div className="support-container">
      <SupportCard
        imgSrc="payment.jpg"
        alt="Hướng dẫn thanh toán"
        title="Hướng dẫn thanh toán"
        description="Chi tiết các phương thức thanh toán: COD, chuyển khoản, ví điện tử."
        buttonText="Xem hướng dẫn"
      />
      <SupportCard
        imgSrc="shipping.jpg"
        alt="Vận chuyển"
        title="Thông tin giao hàng"
        description="Giao hàng toàn quốc, theo dõi đơn dễ dàng, thời gian từ 1–3 ngày."
        buttonText="Xem chi tiết"
      />
      <SupportCard
        imgSrc="return.jpg"
        alt="Đổi trả"
        title="Chính sách đổi trả"
        description="Đổi trả trong 7 ngày với sản phẩm còn nguyên tem, miễn phí hoàn toàn."
        buttonText="Xem chính sách"
      />
      <SupportCard
        imgSrc="support.jpg"
        alt="Hỗ trợ đơn hàng"
        title="Vấn đề với đơn hàng?"
        description="Chúng tôi sẵn sàng hỗ trợ nếu bạn gặp lỗi sản phẩm, chậm giao, thiếu hàng…"
        buttonText="Liên hệ hỗ trợ"
      />
      <SupportForm />
    </div>
  </section>
);

export default CustomerSupport;
