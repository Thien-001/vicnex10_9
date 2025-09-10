import React, { useRef, useEffect } from "react";
import { motion, useAnimation, useInView } from "framer-motion";

const SpecialOfferBox = () => {
  const ref = useRef(null);
  const inView = useInView(ref, { once: true });
  const controls = useAnimation();

  useEffect(() => {
    if (inView) {
      controls.start({
        opacity: 1,
        scale: 1,
        y: 0,
        transition: { duration: 0.7, ease: "easeOut" },
      });
    }
  }, [inView, controls]);

  return (
    <motion.div
      ref={ref}
      initial={{ opacity: 0, scale: 0.95, y: 30 }}
      animate={controls}
      className="full-box"
    >
      <div className="box-1">
        <div className="box-1-content">
          <h2>Ưu đãi đặc biệt hôm nay!</h2>
          <p>Chỉ áp dụng trong 24h. Đừng bỏ lỡ!</p>
          <button>Mua Ngay</button>
        </div>
      </div>
      <div className="box-2"></div>
    </motion.div>
  );
};

export default SpecialOfferBox;
