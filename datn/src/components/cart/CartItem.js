import React from "react";

export default function CartItem({ item, updateQuantity, removeItem, loadingVariants }) {
  const name = item.Name || item.name || "Không có tên";
  const image =
    item.Image
      ? "http://localhost:8000/" + encodeURI(item.Image)
      : item.images && item.images[0] && item.images[0].Image_path
      ? "http://localhost:8000/" + encodeURI(item.images[0].Image_path)
      : "/htm_css/img/product/font-size 18px;.png";
  const discountPrice = Number(item.Discount_price) || 0;
  const price = discountPrice > 0 ? discountPrice : Number(item.Price) || 0;
  const quantity = Number(item.quantity) || 1; // SỬA Ở ĐÂY

  const variantName = item.Variant_name || item.variant_name || "";
  const sku = item.SKU || item.sku || "";

  return (
    <tr>
      <td className="product-info">
        <img
          src={image}
          alt={name}
          style={{ width: "80px", height: "80px", objectFit: "cover", borderRadius: "6px" }}
        />
        <div className="product-detail">
          <p className="product-name">{name}</p>
          {variantName && (
            <div style={{ fontSize: 13, color: "#1976d2", marginTop: 2 }}>
              {variantName.split(" - ").map((v, idx, arr) => (
                <span key={idx}>
                  {v}
                  {idx < arr.length - 1 ? " | " : ""}
                </span>
              ))}
            </div>
          )}
          {sku && (
            <div style={{ fontSize: 12, color: "#888" }}>
              SKU: {sku}
            </div>
          )}
          {item.Category_Name && (
            <div style={{ fontSize: 12, color: "#888" }}>
              Danh mục: {item.Category_Name}
            </div>
          )}
        </div>
      </td>
      <td>
        <span className="new-price">₫{price.toLocaleString()}</span>
        {discountPrice > 0 && (
          <span style={{ textDecoration: "line-through", color: "#888", marginLeft: 6 }}>
            ₫{Number(item.Price).toLocaleString()}
          </span>
        )}
      </td>
      <td className="quantity">
        <button
          onClick={() => updateQuantity(item.Product_ID, sku, quantity - 1)}
          disabled={loadingVariants || quantity <= 1}
        >-</button>
        <input type="number" value={quantity} readOnly disabled={loadingVariants} />
        <button
          onClick={() => updateQuantity(item.Product_ID, sku, quantity + 1)}
          disabled={loadingVariants}
        >+</button>
      </td>
      {/* Bỏ cột thành tiền */}
      <td>
        <button className="delete-btn" onClick={() => removeItem(item.Product_ID, item.SKU)}>🗑️</button>
      </td>
    </tr>
  );
}
