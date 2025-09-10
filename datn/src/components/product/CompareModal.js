import React from "react";

const CompareModal = ({ products, onClose, onRemove }) => {
  if (!products || products.length === 0) return null;

  // Lấy 2 sản phẩm để so sánh
  const [a, b] = products;

  // Hàm hiển thị giá (có giảm giá)
  const renderPrice = (product) =>
    product.Discount_price ? (
      <>
        <span style={{ color: "#d70018", fontWeight: 700 }}>
          {Number(product.Discount_price).toLocaleString("vi-VN")}₫
        </span>
        {" "}
        <del style={{ color: "#888" }}>
          {Number(product.Price).toLocaleString("vi-VN")}₫
        </del>
      </>
    ) : (
      <span style={{ color: "#222", fontWeight: 600 }}>
        {Number(product.Price).toLocaleString("vi-VN")}₫
      </span>
    );

  return (
    <div className="compare-modal-overlay">
      <div className="compare-modal-content">
        <button className="compare-modal-close" onClick={onClose} title="Đóng so sánh">
          &times;
        </button>
        <h3>So sánh sản phẩm</h3>
        <table className="compare-table-full">
          <tbody>
            <tr>
              <th></th>
              <td>
                {a && (
                  <>
                    <img src={a.Image} alt={a.Name} style={{ width: 100, borderRadius: 8 }} />
                    <div style={{ margin: "8px 0", fontWeight: 600 }}>{a.Name}</div>
                    <button onClick={() => onRemove(a.Product_ID)} style={{marginTop: 4}}>Xóa</button>
                  </>
                )}
              </td>
              <td>
                {b ? (
                  <>
                    <img src={b.Image} alt={b.Name} style={{ width: 100, borderRadius: 8 }} />
                    <div style={{ margin: "8px 0", fontWeight: 600 }}>{b.Name}</div>
                    <button onClick={() => onRemove(b.Product_ID)} style={{marginTop: 4}}>Xóa</button>
                  </>
                ) : (
                  <div style={{ color: "#888", fontStyle: "italic" }}>Chọn thêm sản phẩm để so sánh</div>
                )}
              </td>
            </tr>
            <tr>
              <th>Giá</th>
              <td>{a && renderPrice(a)}</td>
              <td>{b && renderPrice(b)}</td>
            </tr>
            <tr>
              <th>Thương hiệu</th>
              <td>{a?.Brand || "-"}</td>
              <td>{b?.Brand || "-"}</td>
            </tr>
            <tr>
              <th>Đánh giá</th>
              <td>{a?.rating ? `★ ${a.rating}` : "-"}</td>
              <td>{b?.rating ? `★ ${b.rating}` : "-"}</td>
            </tr>
            <tr>
              <th>Tồn kho</th>
              <td>{a?.Status ? "✅ Còn hàng" : "❌ Hết hàng"}</td>
              <td>{b?.Status ? "✅ Còn hàng" : "❌ Hết hàng"}</td>
            </tr>
            <tr>
              <th>Mô tả</th>
              <td>{a?.Description || "-"}</td>
              <td>{b?.Description || "-"}</td>
            </tr>
            <tr>
              <th>Chi tiết kỹ thuật</th>
              <td>
                <div className="compare-detail-box" dangerouslySetInnerHTML={{ __html: a?.details || "-" }} />
              </td>
              <td>
                <div className="compare-detail-box" dangerouslySetInnerHTML={{ __html: b?.details || "-" }} />
              </td>
            </tr>
            <tr>
              <th>SKU</th>
              <td>{a?.SKU || "-"}</td>
              <td>{b?.SKU || "-"}</td>
            </tr>
            <tr>
              <th>Nổi bật/Bán chạy</th>
              <td>
                {a?.is_featured ? "🌟 Nổi bật " : ""}
                {a?.is_hot ? "🔥 Hot " : ""}
                {a?.is_best_seller ? "🏆 Bán chạy" : ""}
              </td>
              <td>
                {b?.is_featured ? "🌟 Nổi bật " : ""}
                {b?.is_hot ? "🔥 Hot " : ""}
                {b?.is_best_seller ? "🏆 Bán chạy" : ""}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default CompareModal;