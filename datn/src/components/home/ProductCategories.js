// ProductCategories.jsx
import { motion } from "framer-motion";
import { useInView } from "react-intersection-observer";
import { useEffect, useState } from "react";
import { fetchCategories } from "../../api/categoryApi";
import { useNavigate } from "react-router-dom";

// Gộp ProductBox vào đây
function ProductBox({ imgSrc, altText, title, description, buttonText }) {
  return (
    <div className="product-box">
      <img src={imgSrc} alt={altText} />
      <h3>{title}</h3>
      <p>{description}</p>
      <button>{buttonText}</button>
    </div>
  );
}

const ProductCategories = () => {
  const { ref, inView } = useInView({ triggerOnce: true, threshold: 0.33 });
  const [categories, setCategories] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    fetchCategories()
      .then((res) => {
        setCategories(res.data);
      })
      .catch((err) => {
        console.error("Lỗi khi gọi API category:", err);
      });
  }, []);

  const handleCategoryClick = (cat) => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    navigate(`/products?category=${cat.Slug}`);
  };

  return (
    <motion.div
      className="product-categories"
      ref={ref}
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.6, ease: "easeOut" }}
    >
      {categories
        .filter((cat) => cat.Status === 1)
        .map((cat, idx) => (
          <motion.div
            key={cat.Categories_ID || idx}
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.97 }}
            onClick={() => handleCategoryClick(cat)}
            style={{ cursor: "pointer" }}
          >
            <ProductBox
              imgSrc={`http://localhost:8000/${cat.Image}?v=${Date.now()}`}
              altText={cat.Name}
              title={cat.Name}
              description={cat.Description}
              buttonText="Xem thêm"
            />
          </motion.div>
        ))}
    </motion.div>
  );
};

export default ProductCategories;
