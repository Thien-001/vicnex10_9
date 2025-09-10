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

  // Lấy số lượng tồn kho đúng của biến thể hoặc sản phẩm gốc
  const displayQuantity =
    product.variants && product.variants.length > 0
      ? selectedVariant
        ? selectedVariant.Quantity
        : "Sản phẩm bạn chọn đã hết"
      : product.Quantity ?? 0;

  // Lấy số lượng tối đa để nhập
  const maxQuantity =
    product.variants && product.variants.length > 0
      ? selectedVariant?.Quantity || 0
      : product.Quantity ?? 0;

  // Xử lý thay đổi số lượng
  const handleQuantityChange = (val) => {
    if (val < 1) return;
    if (val > maxQuantity) return;
    setQuantity(val);
  };

  // Hàm xử lý khi chọn biến thể
  const handleVariantChange = (variant) => {
    setSelectedVariant(variant);
    if (variant && Number(variant.Quantity) < 1) {
      setShowOutOfStockBox(true);
      setQuantity(1); // reset về 1 nếu hết hàng
    } else {
      // Nếu số lượng hiện tại lớn hơn số lượng biến thể, set lại cho đúng
      if (variant && quantity > Number(variant.Quantity)) {
        setQuantity(Number(variant.Quantity) > 0 ? Number(variant.Quantity) : 1);
      }
    }
  };

  // Hàm show box truyền xuống ProductActions
  const showOutOfStock = () => setShowOutOfStockBox(true);

  return (
    <div className="product-details">
      <h1>{product.name || product.Name}</h1>
      <div className="price">
        <span className="current" style={{ fontWeight: "bold", fontSize: 24, color: "#d32f2f" }}>
          {Number(price).toLocaleString("vi-VN")}₫
        </span>
        {originalPrice && (
          <span className="original" style={{ textDecoration: "line-through", marginLeft: 10, color: "#888" }}>
            {Number(originalPrice).toLocaleString("vi-VN")}₫
          </span>
        )}
      </div>
      <ul className="highlights" style={{ listStyle: "none", paddingLeft: 0 }}>
        <li>🏷️ Thương hiệu: {brandName}</li>
        <li>
          ✅ Số lượng: {displayQuantity}
        </li>
        <li>📦 Tình trạng: {stockStatus}</li>
        <li>SKU: {sku}</li>
      </ul>
      <div className="description" style={{ marginTop: 20, maxWidth: 500 }}>
        <strong>Mô tả sản phẩm:</strong>
        <pre
          style={{
            whiteSpace: "pre-wrap",
            fontFamily: "inherit",
            maxHeight: showFullDesc ? "none" : 250,
            overflow: showFullDesc ? "visible" : "hidden",
            position: "relative",
            transition: "max-height 0.3s",
          }}
        >
          {showFullDesc ? description : shortDesc}
        </pre>
        {isLongDesc && !showFullDesc && (
          <button
            style={{
              background: "none",
              border: "none",
              color: "#1976d2",
              cursor: "pointer",
              padding: 0,
              fontSize: 16,
              marginTop: 5,
            }}
            onClick={() => setShowFullDesc(true)}
          >
            Xem thêm
          </button>
        )}
        {isLongDesc && showFullDesc && (
          <button
            style={{
              background: "none",
              border: "none",
              color: "#1976d2",
              cursor: "pointer",
              padding: 0,
              fontSize: 16,
              marginTop: 5,
            }}
            onClick={() => setShowFullDesc(false)}
          >
            Thu gọn
          </button>
        )}
      </div>
      <ProductOptions
        variants={product.variants}
        onVariantChange={handleVariantChange}
      />
      <ProductActions
        product={product}
        selectedVariant={selectedVariant}
        quantity={quantity}
        showOutOfStock={setShowOutOfStockBox}
      />
      {/* Chọn số lượng */}
      <div className="quantity-info" style={{ marginTop: 10, fontSize: 18 }}>
        <p>
          Số lượng còn lại: {displayQuantity}
        </p>
        <button
          onClick={() => handleQuantityChange(quantity - 1)}
          disabled={quantity <= 1 || maxQuantity < 1}
        >-</button>
        <input
          type="number"
          value={quantity}
          min="1"
          max={maxQuantity}
          onChange={e => handleQuantityChange(Number(e.target.value))}
          disabled={maxQuantity < 1}
        />
        <button
          onClick={() => handleQuantityChange(quantity + 1)}
          disabled={quantity >= maxQuantity || maxQuantity < 1}
        >+</button>
        <span style={{ marginLeft: 10 }}>
          {maxQuantity > 5
            ? `(Còn ${maxQuantity} sản phẩm!)`
            : maxQuantity > 1
              ? `(Số lượng có hạn!)`
              : maxQuantity === 1
                ? `(Sắp hết hàng!)`
                : `(Hết hàng)`}
        </span>
      </div>
      {/* Modal thông báo hết hàng */}
      {showOutOfStockBox && (
        <div style={{
          position: "fixed",
          top: 0, left: 0, width: "100vw", height: "100vh",
          background: "rgba(0,0,0,0.25)",
          zIndex: 9999,
          display: "flex", alignItems: "center", justifyContent: "center"
        }}>
          <div style={{
            background: "#fff",
            border: "2px solid #d70018",
            borderRadius: 12,
            padding: "32px 36px",
            minWidth: 320,
            boxShadow: "0 4px 24px #bdbdbd",
            textAlign: "center"
          }}>
            <div style={{fontSize: 22, fontWeight: 700, color: "#d70018", marginBottom: 12}}>
              Sản phẩm bạn chọn đã hết hàng!
            </div>
            <button
              style={{
                marginTop: 10,
                padding: "8px 22px",
                borderRadius: 7,
                border: "none",
                background: "#0154b9",
                color: "#fff",
                fontWeight: 600,
                fontSize: 15,
                cursor: "pointer"
              }}
              onClick={() => setShowOutOfStockBox(false)}
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
