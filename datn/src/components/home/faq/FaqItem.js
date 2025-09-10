import React, { useRef, useEffect, useState } from "react";
import { motion, useAnimation, useInView } from "framer-motion";

const FaqItem = ({ question, answer }) => {
  const ref = useRef(null);
  const inView = useInView(ref, { once: true });
  const controls = useAnimation();
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (inView) {
      controls.start({
        opacity: 1,
        y: 0,
        transition: { duration: 0.6, ease: "easeOut" },
      });
    }
  }, [inView, controls]);

  // Hàm chuyển \n thành <br />
  const renderAnswer = (text) =>
    text.split("\n").map((line, idx) => (
      <React.Fragment key={idx}>
        {line}
        <br />
      </React.Fragment>
    ));

  return (
    <motion.div
      ref={ref}
      initial={{ opacity: 0, y: 40 }}
      animate={controls}
      className="faq-item"
      style={{
        background: "#fff",
        padding: "16px",
        borderRadius: "8px",
        boxShadow: "0 2px 8px rgba(0,0,0,0.1)",
        marginBottom: "16px",
        cursor: "pointer",
        transition: "box-shadow 0.2s",
        boxShadow: open
          ? "0 4px 16px rgba(1,84,185,0.13)"
          : "0 2px 8px rgba(0,0,0,0.1)",
      }}
      onClick={() => setOpen((prev) => !prev)}
      tabIndex={0}
      aria-expanded={open}
    >
      <div
        className="faq-title"
        style={{
          fontWeight: "bold",
          marginBottom: open ? "8px" : 0,
          fontSize: "18px",
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
        }}
      >
        <span>✅ {question}</span>
        <span
          className="faq-icon"
          style={{
            opacity: 0.7,
            fontSize: "18px",
            transform: open ? "rotate(90deg)" : "rotate(0deg)",
            transition: "transform 0.2s",
          }}
        >
          ▶
        </span>
      </div>
      {open && (
        <motion.p
          className="faq-desc"
          style={{ fontSize: "16px", color: "#333", marginTop: 8, whiteSpace: "pre-line" }}
          initial={{ opacity: 0, height: 0 }}
          animate={{ opacity: 1, height: "auto" }}
          transition={{ duration: 0.3 }}
        >
          {renderAnswer(answer)}
        </motion.p>
      )}
    </motion.div>
  );
};

export default FaqItem;
