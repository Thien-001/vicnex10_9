import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { fetchProductBySlug } from "../../api/productApi";
import ProductOptions from "./ProductOptions";
import ProductActions from "./ProductActions";

function ProductInfo() {
  const { slug } = useParams();
  const [product, setProduct] = useState(null);
  const [showFullDesc, setShowFullDesc] = useState(false);
  const [selectedVariant, setSelectedVariant] = useState(null);
  const [quantity, setQuantity] = useState(1);
  const [showOutOfStockBox, setShowOutOfStockBox] = useState(false);
  const [userClosedPopup, setUserClosedPopup] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetchProductBySlug(slug);
        const data = Array.isArray(res.data) ? res.data[0] : res.data;
        setProduct(data);
      } catch (err) {
        setProduct(null);
      }
    };
    fetchData();
  }, [slug]);

  if (!product) return <p>Đang tải...</p>;

  const description = product.description || product.Description || "Chưa có mô tả sản phẩm";
  const isLongDesc = description.length > 250;
  const shortDesc = isLongDesc ? description.slice(0, 250) + "..." : description;
  const brandName = product.Brand || product.brand || "Chưa cập nhật";
  const quantityFromRating = product.rating ?? product.Rating ?? 0;

  let stockStatus = "Hết hàng";
  if (quantityFromRating > 5) {
    stockStatus = "Còn hàng";
  } else if (quantityFromRating >= 2 && quantityFromRating <= 5) {
    stockStatus = "Số lượng có hạn";
  } else if (quantityFromRating > 0 && quantityFromRating < 2) {
    stockStatus = "Sắp hết hàng, đặt nhanh kẻo lỡ";
  }

  const price = selectedVariant ? selectedVariant.Discount_price : product.Discount_price;
  const originalPrice = selectedVariant ? selectedVariant.Price : product.Price;
  const sku = selectedVariant ? selectedVariant.SKU : product.SKU;

  const displayQuantity =
    product.variants && product.variants.length > 0
      ? selectedVariant
        ? selectedVariant.Quantity
        : "Sản phẩm bạn chọn đã hết"
      : product.Quantity ?? 0;

  const maxQuantity =
    product.variants && product.variants.length > 0
      ? selectedVariant?.Quantity || 0
      : product.Quantity ?? 0;

  const handleQuantityChange = (val) => {
    if (val < 1) return;
    if (val > maxQuantity) return;
    setQuantity(val);
  };

  const handleVariantChange = (variant) => {
    setSelectedVariant(variant);
    
    if (variant && Number(variant.Quantity) < 1 && !userClosedPopup && !showOutOfStockBox) {
      setShowOutOfStockBox(true);
      setQuantity(1);
    } else if (variant && Number(variant.Quantity) > 0) {
      setUserClosedPopup(false);
      setShowOutOfStockBox(false);
      
      if (quantity > Number(variant.Quantity)) {
        setQuantity(Number(variant.Quantity) > 0 ? Number(variant.Quantity) : 1);
      }
    }
  };

  const closeOutOfStockBox = () => {
    setShowOutOfStockBox(false);
    setUserClosedPopup(true);
  };

  const handleOverlayClick = (e) => {
    if (e.target === e.currentTarget) {
      closeOutOfStockBox();
    }
  };

  const handleCloseButtonClick = (e) => {
    e.preventDefault();
    e.stopPropagation();
    closeOutOfStockBox();
  };

  return (
    <div className="product-details">
      {/* TITLE */}
      <h1 style={{
        fontSize: "28px",
        fontWeight: "700",
        color: "#0154b9",
        marginBottom: "16px",
        lineHeight: "1.3"
      }}>
        {product.name || product.Name}
      </h1>

      {/* PRICE */}
      <div className="price" style={{ marginBottom: "20px" }}>
        <span className="current" style={{ 
          fontWeight: "700", 
          fontSize: "32px", 
          color: "#d70018" 
        }}>
          {Number(price).toLocaleString("vi-VN")}₫
        </span>
        {originalPrice && (
          <span className="original" style={{ 
            textDecoration: "line-through", 
            marginLeft: "12px", 
            color: "#999",
            fontSize: "20px"
          }}>
            {Number(originalPrice).toLocaleString("vi-VN")}₫
          </span>
        )}
      </div>

      {/* HIGHLIGHTS */}
      <ul className="highlights" style={{ 
        listStyle: "none", 
        paddingLeft: 0,
        marginBottom: "24px"
      }}>
        <li style={{ 
          marginBottom: "10px",
          fontSize: "16px",
          display: "flex",
          alignItems: "center",
          color: "#495057"
        }}>
          <span style={{ marginRight: "10px", fontSize: "18px" }}>🏷️</span>
          <strong style={{ marginRight: "8px" }}>Thương hiệu:</strong> 
          <span style={{ color: "#0154b9", fontWeight: "600" }}>{brandName}</span>
        </li>
        <li style={{ 
          marginBottom: "10px",
          fontSize: "16px", 
          display: "flex",
          alignItems: "center",
          color: "#495057"
        }}>
          <span style={{ marginRight: "10px", fontSize: "18px" }}>📦</span>
          <strong style={{ marginRight: "8px" }}>Số lượng:</strong> 
          <span style={{ color: "#28a745", fontWeight: "600" }}>{displayQuantity}</span>
        </li>
        <li style={{ 
          marginBottom: "10px",
          fontSize: "16px",
          display: "flex", 
          alignItems: "center",
          color: "#495057"
        }}>
          <span style={{ marginRight: "10px", fontSize: "18px" }}>✅</span>
          <strong style={{ marginRight: "8px" }}>Tình trạng:</strong> 
          <span style={{ color: "#28a745", fontWeight: "600" }}>{stockStatus}</span>
        </li>
        <li style={{ 
          fontSize: "16px",
          display: "flex",
          alignItems: "center", 
          color: "#495057"
        }}>
          <span style={{ marginRight: "10px", fontSize: "18px" }}>🔖</span>
          <strong style={{ marginRight: "8px" }}>SKU:</strong> 
          <span style={{ color: "#6c757d" }}>{sku}</span>
        </li>
      </ul>

      {/* DESCRIPTION */}
      <div className="description" style={{ 
        marginTop: "20px", 
        marginBottom: "24px",
        maxWidth: "600px"
      }}>
        <strong style={{
          fontSize: "18px",
          color: "#495057",
          marginBottom: "12px",
          display: "block"
        }}>
          📝 Mô tả sản phẩm:
        </strong>
        <pre style={{
          whiteSpace: "pre-wrap",
          fontFamily: "inherit",
          maxHeight: showFullDesc ? "none" : 250,
          overflow: showFullDesc ? "visible" : "hidden",
          position: "relative",
          transition: "max-height 0.3s",
          fontSize: "15px",
          lineHeight: "1.6",
          color: "#6c757d",
          margin: "0",
          paddingTop: "8px"
        }}>
          {showFullDesc ? description : shortDesc}
        </pre>
        {isLongDesc && (
          <button style={{
            background: "none",
            border: "none",
            color: "#0154b9",
            cursor: "pointer",
            padding: "8px 0 0 0",
            fontSize: "15px",
            fontWeight: "600",
            textDecoration: "underline"
          }}
          onClick={() => setShowFullDesc(!showFullDesc)}
          >
            {showFullDesc ? "Thu gọn ↑" : "Xem thêm ↓"}
          </button>
        )}
      </div>

      {/* PRODUCT OPTIONS */}
      <ProductOptions
        variants={product.variants}
        onVariantChange={handleVariantChange}
      />

      {/* PRODUCT ACTIONS */}
      <ProductActions
        product={product}
        selectedVariant={selectedVariant}
        quantity={quantity}
        showOutOfStock={setShowOutOfStockBox}
      />

      {/* QUANTITY */}
      <div className="quantity-info" style={{ 
        marginTop: "20px", 
        fontSize: "16px"
      }}>
        <p style={{
          fontWeight: "600",
          color: "#495057",
          marginBottom: "12px",
          fontSize: "17px"
        }}>
          📊 Số lượng còn lại: <span style={{ color: "#28a745" }}>{displayQuantity}</span>
        </p>
        
        <div style={{ display: "flex", alignItems: "center", gap: "10px", flexWrap: "wrap" }}>
          <button
            onClick={() => handleQuantityChange(quantity - 1)}
            disabled={quantity <= 1 || maxQuantity < 1}
            style={{
              width: "40px",
              height: "40px",
              border: "1px solid #ddd",
              background: "#fff",
              borderRadius: "6px",
              cursor: quantity <= 1 || maxQuantity < 1 ? "not-allowed" : "pointer",
              fontSize: "20px",
              fontWeight: "600",
              display: "flex",
              alignItems: "center",
              justifyContent: "center"
            }}
          >
            −
          </button>
          
          <input
            type="number"
            value={quantity}
            min="1"
            max={maxQuantity}
            onChange={e => handleQuantityChange(Number(e.target.value))}
            disabled={maxQuantity < 1}
            style={{
              width: "70px",
              height: "40px",
              textAlign: "center",
              border: "1px solid #ddd",
              borderRadius: "6px",
              fontSize: "16px",
              fontWeight: "600"
            }}
          />
          
          <button
            onClick={() => handleQuantityChange(quantity + 1)}
            disabled={quantity >= maxQuantity || maxQuantity < 1}
            style={{
              width: "40px",
              height: "40px",
              border: "1px solid #ddd",
              background: "#fff",
              borderRadius: "6px",
              cursor: quantity >= maxQuantity || maxQuantity < 1 ? "not-allowed" : "pointer",
              fontSize: "20px",
              fontWeight: "600",
              display: "flex",
              alignItems: "center",
              justifyContent: "center"
            }}
          >
            +
          </button>
          
          <span style={{ 
            marginLeft: "10px",
            fontSize: "14px",
            color: "#6c757d",
            fontStyle: "italic"
          }}>
            {maxQuantity > 5
              ? `(Còn ${maxQuantity} sản phẩm!)`
              : maxQuantity > 1
                ? `(Số lượng có hạn!)`
                : maxQuantity === 1
                  ? `(Sắp hết hàng!)`
                  : `(Hết hàng)`}
          </span>
        </div>
      </div>

      {/* Modal thông báo hết hàng */}
      {showOutOfStockBox && (
        <div 
          style={{
            position: "fixed",
            top: 0, 
            left: 0, 
            width: "100vw", 
            height: "100vh",
            background: "rgba(0,0,0,0.6)",
            zIndex: 999999,
            display: "flex", 
            alignItems: "center", 
            justifyContent: "center",
            backdropFilter: "blur(2px)"
          }}
          onClick={handleOverlayClick}
        >
          <div 
            style={{
              background: "#fff",
              border: "2px solid #d70018",
              borderRadius: 12,
              padding: "32px 36px",
              minWidth: 320,
              maxWidth: 400,
              boxShadow: "0 10px 30px rgba(0,0,0,0.3)",
              textAlign: "center",
              position: "relative"
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <button
              style={{
                position: "absolute",
                top: "10px",
                right: "10px",
                background: "transparent",
                border: "none",
                fontSize: "20px",
                cursor: "pointer",
                color: "#d70018",
                width: "30px",
                height: "30px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center"
              }}
              onClick={handleCloseButtonClick}
            >
              ✕
            </button>

            <div style={{
              fontSize: 22, 
              fontWeight: 700, 
              color: "#d70018", 
              marginBottom: 12
            }}>
              ⚠️ Sản phẩm bạn chọn đã hết hàng!
            </div>
            
            <p style={{
              color: "#666",
              marginBottom: 20,
              fontSize: 16
            }}>
              Vui lòng chọn tùy chọn khác hoặc liên hệ với chúng tôi để được hỗ trợ.
            </p>
            
            <button
              style={{
                marginTop: 10,
                padding: "12px 24px",
                borderRadius: 8,
                border: "none",
                background: "#d70018",
                color: "#fff",
                fontWeight: 600,
                fontSize: 16,
                cursor: "pointer",
                transition: "background-color 0.2s ease",
                width: "100%"
              }}
              onClick={handleCloseButtonClick}
              onMouseEnter={(e) => e.target.style.backgroundColor = "#b8001a"}
              onMouseLeave={(e) => e.target.style.backgroundColor = "#d70018"}
            >
              Đóng
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default ProductInfo;
