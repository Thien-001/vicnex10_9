import React, { useRef, useEffect } from 'react';
import { motion } from 'framer-motion';
import ProductCard from './ProductCard';

const mockProducts = Array.from({ length: 10 }, (_, i) => ({
  id: i + 1,
  name: 'Vợt Cầu Lông Yonex Astrox 100ZZ – Smash Cực Mạnh',
  image: '/img/product/product-test.png',
  price: '3.890.000₫',
  rating: 5,
  reviews: 24
}));

const RecentlyViewed = () => {
  const scrollRef = useRef(null);

  useEffect(() => {
    const el = scrollRef.current;
    if (!el) return;

    const handleWheel = (e) => {
      if (e.deltaY === 0) return;
      e.preventDefault(); // Ngăn cuộn dọc mặc định
      el.scrollLeft += e.deltaY;
    };

    el.addEventListener('wheel', handleWheel, { passive: false });
    return () => el.removeEventListener('wheel', handleWheel);
  }, []);

  return (
    <section className="recently-viewed">
      <h2>Những Sản Phẩm Gợi Ý Dành Cho Bạn</h2>
      <div className="recently-viewed-track" ref={scrollRef}>
        {mockProducts.map((product, index) => (
          <motion.div
            key={product.id}
            initial={{ opacity: 0, y: 50 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
            viewport={{ once: true, amount: 0.2 }}
          >
            <ProductCard product={product} />
          </motion.div>
        ))}
      </div>
    </section>
  );
};

export default RecentlyViewed;
