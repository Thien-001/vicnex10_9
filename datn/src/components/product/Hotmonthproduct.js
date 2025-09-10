import React, { useRef, useEffect } from 'react';
import { motion } from 'framer-motion';
import ProductCard from './ProductCard';

const Hotmonthproduct = ({ products }) => {
  const scrollRef = useRef(null);

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
      <h2>Sản Phẩm HOT Tháng</h2>
      <div className="recently-viewed-track" ref={scrollRef}>
        {products && products.length > 0 ? (
          products.map((product, index) => (
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
          <div style={{ padding: 32, color: "#888" }}>Không có sản phẩm HOT tháng nào.</div>
        )}
      </div>
    </section>
  );
};

export default Hotmonthproduct;
