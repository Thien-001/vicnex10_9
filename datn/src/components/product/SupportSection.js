import SupportCard from './SupportCard';

const supportData = [
  {
    image: '/img/product/hinh3.png',
    alt: 'Chọn vợt',
    title: 'Cần giúp chọn vợt phù hợp?',
    description: 'Trả lời 3 câu hỏi, hệ thống gợi ý cây vợt phù hợp với bạn.',
    buttonText: 'Chọn vợt ngay',
  },
  {
    image: '/img/product/hinh3.png',
    alt: 'Ưu đãi',
    title: 'Ưu đãi hôm nay dành cho bạn',
    description: 'Mua 2 tặng 1 / Miễn phí ship đơn từ 500k<br/>Chỉ áp dụng trong hôm nay.',
    buttonText: 'Xem ưu đãi',
  },
  {
    image: '/img/product/hinh3.png',
    alt: 'Đổi trả',
    title: 'Yên tâm đổi trả trong 7 ngày',
    description: 'Nếu không hài lòng, đổi trả miễn phí — khôn  cần lý do.',
    buttonText: 'Xem chi tiết',
  },
  {
    image: '/img/product/hinh3.png',
    alt: 'Chat với chuyên gia',
    title: 'Chat với chuyên gia cầu lông',
    description: 'Cần tư vấn? Đội ngũ vận động viên sẵn sàng hỗ trợ bạn.',
    buttonText: 'Chat ngay',
  },
];

const SupportSection = () => {
  return (
    <section className="support-section">
      <h2>Hỗ trợ khách hàng</h2>
      <div className="support-container">
        {supportData.map((item, index) => (
          <SupportCard key={index} {...item} />
        ))}
        <div className="support-form">
          <h3>Liên Hệ Ngay</h3>
          <p>Chúng tôi sẽ phản hồi trong vòng 24h</p>
          <form>
            <input type="text" placeholder="Họ và tên" required />
            <input type="tel" placeholder="Số điện thoại" required />
            <textarea placeholder="Nội dung cần hỗ trợ" required></textarea>
            <button type="submit">Liên hệ ngay</button>
          </form>
        </div>
      </div>
    </section>
  );
};

export default SupportSection;
