import { motion } from "framer-motion";
import { useInView } from "react-intersection-observer";

const SectionHeading = ({ title, subtitle }) => {
  const { ref, inView } = useInView({ triggerOnce: true, threshold: 0.33 });

  return (
    <motion.div
      ref={ref}
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.6 }}
      style={{
        textAlign: "center",
        margin: "64px 0 36px 0",
        padding: "0 8px",
        position: "relative",
      }}
    >
      {/* Hiệu ứng ánh sáng nền */}
      <div
        style={{
          position: "absolute",
          left: "50%",
          top: "50%",
          width: 320,
          height: 120,
          background:
            "radial-gradient(circle, #e3f0ff 0%, #fafdff 70%, transparent 100%)",
          opacity: 0.55,
          transform: "translate(-50%, -50%)",
          zIndex: 0,
          pointerEvents: "none",
        }}
      />
      {/* Tiêu đề nổi bật */}
      <h2
        style={{
          fontSize: 48,
          fontWeight: 900,
          letterSpacing: 2.5,
          textTransform: "uppercase",
          background:
            "linear-gradient(90deg, #0154b9 0%, #3bb2ff 60%, #ffd700 100%)",
          WebkitBackgroundClip: "text",
          WebkitTextFillColor: "transparent",
          backgroundClip: "text",
          marginBottom: 18,
          textShadow:
            "0 8px 32px rgba(1,84,185,0.13), 0 2px 8px #ffd70044",
          fontFamily: "Montserrat, Arial, sans-serif",
          position: "relative",
          zIndex: 1,
          lineHeight: 1.08,
        }}
      >
        {title}
      </h2>
      {/* Thanh nhấn dưới tiêu đề */}
      <div
        style={{
          width: 80,
          height: 6,
          borderRadius: 4,
          margin: "0 auto 22px auto",
          background:
            "linear-gradient(90deg, #0154b9 0%, #3bb2ff 80%, #ffd700 100%)",
          boxShadow: "0 2px 12px #0154b944",
          zIndex: 1,
          position: "relative",
        }}
      />
      {/* Subtitle chuyên nghiệp */}
      <p
        style={{
          fontSize: "1.22rem",
          color: "#0154b9",
          fontWeight: 600,
          marginBottom: 0,
          letterSpacing: "0.35px",
          lineHeight: 1.7,
          textShadow: "0 2px 8px #e3f0ff99",
          position: "relative",
          zIndex: 1,
          maxWidth: 600,
          marginLeft: "auto",
          marginRight: "auto",
        }}
      >
        {subtitle}
      </p>
    </motion.div>
  );
};

export default SectionHeading;
