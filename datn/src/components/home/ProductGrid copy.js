import { useEffect, useState } from "react";
import { motion } from "framer-motion";
import { useInView } from "react-intersection-observer";
import { Link } from "react-router-dom";
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

  useEffect(() => {
    fetch(`http://localhost:8000/api/products?${type}=1`)
      .then((res) => res.json())
      .then((data) => setProducts(getRandomProducts(data.data || [], 8)));
  }, [type]);

  // Gom ProductItem vào đây luôn
  const renderProductItem = (product, index) => {
    const priceSale =
      product.Discount_price > 0
        ? Number(product.Discount_price).toLocaleString("vi-VN", {
            style: "currency",
            currency: "VND",
          })
        : Number(product.Price).toLocaleString("vi-VN", {
            style: "currency",
            currency: "VND",
          });

    const priceOld =
      product.Discount_price > 0
        ? Number(product.Price).toLocaleString("vi-VN", {
            style: "currency",
            currency: "VND",
          })
        : "";

    const handleAddToCart = (e) => {
      e.preventDefault();
      const item = {
        id: product.Product_ID,
        imgSrc: product.Image,
        title: product.Name,
        priceSale,
      };
      addToCart(item);
    };

    return (
      <motion.div
        key={product.Product_ID || index}
        className="product-grid-item"
        whileHover={{ scale: 1.03 }}
        whileTap={{ scale: 0.97 }}
        transition={{ type: "spring", stiffness: 300 }}
      >
        <div className="product-content">
          <Link
            to={`/product/${product.slug}`}
            className="product-link"
            onClick={() => window.scrollTo(0, 0)}
          >
            <img src={product.Image} alt={product.Name} />
            <div className="product-info">
              <h4>{product.Name}</h4>
              <p className="price-sale">Giá bán: {priceSale}</p>
              {priceOld && <p className="price-old">Giá gốc: {priceOld}</p>}
              <div className="stars">{product.stars || "★★★★★"}</div>
            </div>
          </Link>
          {/* <div className="cart-overlay">
            <button className="cart-center-btn" onClick={handleAddToCart}>🛒</button>
          </div> */}
        </div>
        <div className="product-actions">
          <button className="buy-btn">Mua Ngay</button>
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
    );
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
          products.map(renderProductItem)
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