import QuickSupportCard from './QuickSupportCard';

const QuickSupportSection = () => {
  const cards = [
    {
      icon: '🛒',
      title: 'Hỗ trợ đặt hàng',
      description: 'Hotline tư vấn, hướng dẫn mua hàng',
      note: '“Gọi ngay: 1800 1234”'
    },
    {
      icon: '🧊',
      title: 'Chính sách đổi trả',
      description: 'Hỗ trợ đổi trả trong 7 ngày',
      note: '“Xem Chi Tiết”'
    },
    {
      icon: '🚀',
      title: 'Giao hàng nhanh',
      description: 'Nhận hàng trong 24–48h',
      note: '“Miễn phí vận chuyển đơn từ 500K”'
    },
    {
      icon: '📞',
      title: 'Hỗ trợ khách hàng 24/7',
      description: 'Live chat, hotline, email',
      note: '“Liên Hệ Ngay”'
    },
  ];

  return (
    <section className="support-cards">
      {cards.map((card, index) => (
        <QuickSupportCard
          key={index}
          icon={card.icon}
          title={card.title}
          description={card.description}
          note={card.note}
        />
      ))}
    </section>
  );
};

export default QuickSupportSection;
