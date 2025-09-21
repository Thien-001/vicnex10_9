import { useEffect, useState } from "react";
import { motion } from "framer-motion";
import { useInView } from "react-intersection-observer";
import { useNavigate } from "react-router-dom";
import { useCart } from "../../context/CartContext";

function getRandomProducts(arr, max = 8) {
  if (!Array.isArray(arr)) return [];
  const shuffled = arr.slice().sort(() => Math.random() - 0.5);
  return shuffled.slice(0, max);
}

const ProductGrid = ({ type = "is_hot" }) => {
  const { ref, inView } = useInView({ triggerOnce: true, threshold: 0.33 });
  const [products, setProducts] = useState([]);
  const { addToCart } = useCart();
  const navigate = useNavigate();

  useEffect(() => {
    fetch(`http://localhost:8000/api/products?${type}=1`)
      .then((res) => res.json())
      .then((data) => setProducts(getRandomProducts(data.data || [], 8)));
  }, [type]);

  const handleAddToCart = (product, e) => {
    e.stopPropagation();
    const user = localStorage.getItem("user");
    if (!user) {
      alert("Bạn cần đăng nhập để thêm vào giỏ hàng!");
      return;
    }
    const item = {
      id: product.Product_ID,
      imgSrc: product.Image,
      title: product.Name,
      priceSale: product.Discount_price > 0 ? product.Discount_price : product.Price,
    };
    addToCart(item);
  };

  return (
    <motion.div
      className="product-wrapper"
      ref={ref}
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.6, ease: "easeOut" }}
    >
      <div className="product-grid">
        {products.length > 0 ? (
          products.map((product, index) => (
            <motion.div
              key={product.Product_ID || index}
              className="product-grid-item"
              whileHover={{ scale: 1.03 }}
              whileTap={{ scale: 0.97 }}
              transition={{ type: "spring", stiffness: 300 }}
              onClick={() => navigate(`/product/${product.slug}`)}
              style={{ cursor: "pointer" }}
            >
              <div className="product-content">
                <img src={product.Image} alt={product.Name} className="product-list-image" />
                <div className="product-list-info">
                  <h3 className="product-list-name">{product.Name}</h3>
                  <div className="product-list-category">{product?.category?.Name || ""}</div>
                  <div className="product-list-brand">{product.Brand || ""}</div>
                  <div className="product-list-price">
                    {product.Discount_price ? (
                      <>
                        <span className="product-list-price-sale">
                          {Number(product.Discount_price).toLocaleString("vi-VN")}₫
                        </span>
                        <del className="product-list-price-old">
                          {Number(product.Price).toLocaleString("vi-VN")}₫
                        </del>
                      </>
                    ) : (
                      <span>{Number(product.Price).toLocaleString("vi-VN")}₫</span>
                    )}
                  </div>
                  <div className="product-list-rating">
                    {Array.from({ length: 5 }).map((_, i) => {
                      if (product.rating >= i + 1) return <span key={i} style={{color:'#FFD700'}}>★</span>;
                      if (product.rating > i) return <span key={i} style={{color:'#FFD700'}}>☆</span>;
                      return <span key={i} style={{color:'#ddd'}}>★</span>;
                    })}
                    <span style={{marginLeft: 4, color: "#888", fontSize: "0.95em"}}>
                      {product.ratingCount ? `(${product.ratingCount} đánh giá)` : "(0 đánh giá)"}
                    </span>
                  </div>
                </div>
              </div>
              <div className="product-actions">
                <button
                  className="buy-btn"
                  onClick={(e) => {
                    e.stopPropagation();
                    navigate(`/product/${product.slug}`);
                  }}
                >
                  Mua Ngay
                </button>
              </div>
              <div className="product-grid-ribbons">
                {product.is_hot && (
                  <div className="product-grid-ribbon hot">HOT</div>
                )}
                {product.is_best_seller && (
                  <div className="product-grid-ribbon best">BEST</div>
                )}
                {product.is_featured && (
                  <div className="product-grid-ribbon featured">FEATURED</div>
                )}
                {product.is_recommend && (
                  <div className="product-grid-ribbon recommend">RECOMMEND</div>
                )}
              </div>
            </motion.div>
          ))
        ) : (
          <div style={{ padding: 32, color: "#888", textAlign: "center" }}>
            Không có sản phẩm phù hợp.
          </div>
        )}
      </div>
    </motion.div>
  );
};

export default ProductGrid;