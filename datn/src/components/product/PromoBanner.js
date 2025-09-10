import React from 'react';
import { motion } from 'framer-motion';

const promoVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: (i) => ({
    opacity: 1,
    y: 0,
    transition: {
      delay: i * 0.18,
      duration: 0.5,
      ease: 'easeOut'
    }
  })
};

const PromoBanner = () => {
  const promos = [
    {
      icon: '🚚',
      text: (
        <>
          <strong className="highlight">Miễn phí giao hàng</strong> toàn quốc cho đơn từ <strong>500.000₫</strong>
        </>
      )
    },
    {
      icon: '💳',
      text: (
        <>
          Thanh toán <strong className="highlight">an toàn</strong> – Hỗ trợ trả góp 0%
        </>
      )
    },
    {
      icon: '🎉',
      text: (
        <>
          Đăng ký thành viên nhận <strong className="highlight">voucher 50.000₫</strong>
        </>
      )
    }
  ];

  return (
    <div className="promo-banner">
      {promos.map((item, i) => (
        <motion.div
          className="promo-item"
          key={i}
          custom={i}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.4 }}
          variants={promoVariants}
          whileHover={{
            scale: 1.05,
            boxShadow: '0 8px 24px rgba(0,0,0,0.15)',
            transition: { duration: 0.3 }
          }}
        >
          <span className="icon">{item.icon}</span>
          <span className="text">{item.text}</span>
        </motion.div>
      ))}
    </div>
  );
};

export default PromoBanner;

<style>{`
  .promo-banner-mini {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 15px;
    color: #0154b9;
    margin: 18px 0;
    box-shadow: 0 2px 8px rgba(1,84,185,0.06);
  }
  .promo-icon {
    font-size: 22px;
    margin-right: 10px;
  }
  .promo-code {
    background: #0154b9;
    color: #fff;
    border-radius: 6px;
    padding: 2px 8px;
    margin-left: 8px;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 1px;
  }
`}</style>
