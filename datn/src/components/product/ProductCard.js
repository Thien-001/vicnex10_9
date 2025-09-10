import React from 'react';
import { useNavigate } from "react-router-dom";

const ProductCard = ({ product }) => {
  const navigate = useNavigate();

  // Định dạng giá tiền
  const formatPrice = (price) => {
    if (!price) return "";
    return Number(price).toLocaleString("vi-VN") + "₫";
  };

  const goToDetail = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    navigate(`/product/${product.slug}`);
  };

  // Thêm vào giỏ hàng nhưng không chuyển trang
  const handleAddToCart = (e) => {
    e.preventDefault();
    const user = localStorage.getItem("user");
    if (!user) {
      alert("Bạn cần đăng nhập để thêm vào giỏ hàng!");
      return;
    }
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const cartItem = {
      Product_ID: product.Product_ID,
      Name: product.Name,
      Image: product.Image || "/img/no-image.png",
      Price: product.Price,
      Discount_price: product.Discount_price || product.Price,
      quantity: 1,
    };
    const idx = cart.findIndex(item => item.Product_ID === cartItem.Product_ID);
    if (idx > -1) {
      cart[idx].quantity += 1;
    } else {
      cart.push(cartItem);
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    window.dispatchEvent(new Event("cartUpdated"));
  };

  // Mua ngay: thêm vào giỏ hàng và chuyển sang trang giỏ hàng
  const handleBuyNow = (e) => {
    e.stopPropagation();
    const user = localStorage.getItem("user");
    if (!user) {
      alert("Bạn cần đăng nhập để mua hàng!");
      return;
    }
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const cartItem = {
      Product_ID: product.Product_ID,
      Name: product.Name,
      Image: product.Image || "/img/no-image.png",
      Price: product.Price,
      Discount_price: product.Discount_price || product.Price,
      quantity: 1,
    };
    const idx = cart.findIndex(item => item.Product_ID === cartItem.Product_ID);
    if (idx > -1) {
      cart[idx].quantity += 1;
    } else {
      cart.push(cartItem);
    }
    localStorage.setItem("cart", JSON.stringify(cart));
    window.dispatchEvent(new Event("cartUpdated"));
    navigate("/cart");
  };

  return (
    <div
      className="product-card1"
      style={{ cursor: "pointer", position: "relative" }}
      onClick={goToDetail}
    >
      {/* Icon trái tim */}
      <div className="product-list-fav tooltip-parent" style={{ position: "absolute", top: 10, left: 10 }}>
        ♡
        <span className="tooltip">Yêu thích</span>
      </div>

      {/* Ribbon trạng thái */}
      <div className="product-list-ribbons" style={{ position: "absolute", top: 10, right: 10 }}>
        {product.is_hot && (
          <div className="product-list-ribbon hot">HOT</div>
        )}
        {product.is_best_seller && (
          <div className="product-list-ribbon best">BEST</div>
        )}
        {product.is_featured && (
          <div className="product-list-ribbon featured">FEATURED</div>
        )}
      </div>

      <img
        src={product.Image || "/img/no-image.png"}
        alt={product.Name}
        className="product-image"
        style={{
          width: "100%",
          height: 140,
          objectFit: "cover",
          borderRadius: 10,
          border: "1px solid #f0f0f0",
          marginBottom: 12,
          background: "#f8fafd",
          display: "block"
        }}
      />
      <h3 className="product-name1">{product.Name}</h3>
      {/* Danh mục */}
      <div className="product-list-category">
        {product?.category?.Name || ""}
      </div>
      {/* Thương hiệu */}
      <div className="product-list-brand">
        {product.Brand || ""}
      </div>
      <div className="product-price1">{formatPrice(product.Price)}</div>
      <div className="product-rating1">
        {'★★★★★'} <span>({product.rating || 0})</span>
      </div>
      <div className="product-actions1">
        <button
          className="add-cart-btn1"
          style={{
            padding: "7px 16px",
            borderRadius: 7,
            border: "1px solid #0154b9",
            fontWeight: 600,
            fontSize: "0.97rem",
            cursor: "pointer",
            background: "#0154b9",
            color: "#fff",
            transition: "background 0.18s, color 0.18s",
            outline: "none",
            marginRight: 8,
          }}
          onMouseEnter={e => {
            e.currentTarget.style.background = "#fff";
            e.currentTarget.style.color = "#0154b9";
            e.currentTarget.style.borderColor = "#0154b9";
          }}
          onMouseLeave={e => {
            e.currentTarget.style.background = "#0154b9";
            e.currentTarget.style.color = "#fff";
            e.currentTarget.style.borderColor = "#0154b9";
          }}
          onClick={handleAddToCart}
        >
          Thêm vào giỏ
        </button>
        <button
          className="buy-btn1"
          style={{
            padding: "7px 16px",
            borderRadius: 7,
            border: "1px solid #0154b9",
            fontWeight: 600,
            fontSize: "0.97rem",
            cursor: "pointer",
            background: "#fff",
            color: "#0154b9",
            transition: "background 0.18s, color 0.18s",
            outline: "none",
          }}
          onMouseEnter={e => {
            e.currentTarget.style.background = "#0154b9";
            e.currentTarget.style.color = "#fff";
            e.currentTarget.style.borderColor = "#0154b9";
          }}
          onMouseLeave={e => {
            e.currentTarget.style.background = "#fff";
            e.currentTarget.style.color = "#0154b9";
            e.currentTarget.style.borderColor = "#0154b9";
          }}
          onClick={handleBuyNow}
        >
          Mua Ngay
        </button>
      </div>
    </div>
  );
};

export default ProductCard;
