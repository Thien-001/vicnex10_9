import React, { useRef, useEffect, useState } from 'react';
import { motion } from 'framer-motion';
import ProductCard from './ProductCard';

const RecomendProduct = () => {
  const scrollRef = useRef(null);
  const [bestSellerProducts, setBestSellerProducts] = useState([]);

  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_best_seller=1")
      .then(res => res.json())
      .then(data => setBestSellerProducts(data.data || []));
  }, []);

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
      <h2>Những Sản Phẩm Dành Cho Bạn</h2>
      <div className="recently-viewed-track" ref={scrollRef}>
        {bestSellerProducts.length > 0 ? (
          bestSellerProducts.map((product, index) => (
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
          <div style={{ padding: 32, color: "#888" }}>Không có sản phẩm bán chạy nào.</div>
        )}
      </div>
    </section>
  );
};

export default RecomendProduct;
