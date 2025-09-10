import React, { useRef, useEffect } from 'react';
import { motion } from 'framer-motion';
import ProductCard from './ProductCard';

const RecentlyViewed = () => {
  const scrollRef = useRef(null);

  // Lấy danh sách sản phẩm đã xem từ localStorage
  const recentlyViewed =
    JSON.parse(localStorage.getItem("recentlyViewed") || "[]");

  useEffect(() => {
    const el = scrollRef.current;
    if (!el) return;

    const handleWheel = (e) => {
      if (e.deltaY === 0) return;
      e.preventDefault();
      el.scrollLeft += e.deltaY;
    };

    el.addEventListener('wheel', handleWheel, { passive: false });
    return () => el.removeEventListener('wheel', handleWheel);
  }, []);

  return (
    <section className="recently-viewed">
      <h2>Những Sản Phẩm Đã Xem</h2>
      <div className="recently-viewed-track" ref={scrollRef}>
        {recentlyViewed.length > 0 ? (
          recentlyViewed.map((product, index) => (
            <motion.div
              key={product.Product_ID || product.id}
              initial={{ opacity: 0, y: 50 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              viewport={{ once: true, amount: 0.2 }}
            >
              <ProductCard product={product} />
            </motion.div>
          ))
        ) : (
          <div style={{ padding: 32, color: "#888" }}>
            Bạn chưa xem sản phẩm nào.
          </div>
        )}
      </div>
    </section>
  );
};

export default RecentlyViewed;
