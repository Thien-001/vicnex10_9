import React, { useState, useEffect } from "react";

function ProductImageGallery({ product }) {
  const imageBaseUrl = "http://localhost:8000/";

  // Gom cả ảnh đại diện và ảnh phụ thành một mảng
  const images = [
    ...(product?.Image ? [{ Image: product.Image }] : []),
    ...(Array.isArray(product?.images) ? product.images : []),
  ];

  // State lưu index ảnh chính hiện tại
  const [mainIndex, setMainIndex] = useState(0);

  // Khi danh sách ảnh thay đổi, reset về ảnh đầu tiên
  useEffect(() => {
    if (images.length > 0) {
      setMainIndex(0);
    }
  }, [images.length]);

  // Hàm lấy đường dẫn ảnh (ưu tiên trường Image)
  const getImagePath = (img) => img.Image || img.Image_path || img.image_path || "";

  // Hàm xử lý nút trái
  const handlePrev = () => {
    setMainIndex((prev) => (prev === 0 ? images.length - 1 : prev - 1));
  };

  // Hàm xử lý nút phải
  const handleNext = () => {
    setMainIndex((prev) => (prev === images.length - 1 ? 0 : prev + 1));
  };

  return (
    <div>
      {/* Ảnh chính */}
      {images[mainIndex] && getImagePath(images[mainIndex]) && (
        <img
          src={imageBaseUrl + encodeURI(getImagePath(images[mainIndex]))}
          alt={product?.Name || product?.name || "Ảnh sản phẩm"}
          style={{
            backgroundColor: "#fff",
            objectFit: "cover",
            display: "block",
            margin: "0 auto",
            borderRadius: "8px",
            boxShadow: "0 2px 8px rgba(0,0,0,0.08)",
          }}
        />
      )}

      {/* Slider ảnh phụ + nút chuyển */}
      <div
        style={{
          display: "flex",
          alignItems: "center",
          gap: "25px",
          marginTop: "16px",
          justifyContent: "center",
        }}
      >
        {/* Nút trước */}
        <button
          onClick={handlePrev}
          style={{
            padding: "6px 12px",
            backgroundColor: "#eee",
            border: "none",
            borderRadius: "4px",
            cursor: "pointer",
            fontSize: "18px",
          }}
        >
          &lt;
        </button>

        {/* Các ảnh phụ, chỉ tối đa 5 ảnh, không làm mờ và không có dấu "+" */}
        {(() => {
          const maxThumbs = 5;
          const thumbs = images.slice(0, maxThumbs);

          return thumbs.map((img, index) =>
            getImagePath(img) ? (
              <img
                key={img.Image_ID || img.image_id || index}
                src={imageBaseUrl + encodeURI(getImagePath(img))}
                alt={`Ảnh ${index + 1}`}
                onClick={() => setMainIndex(index)}
                style={{
                  width: "100px",
                  height: "100px",
                  objectFit: "cover",
                  border:
                    index === mainIndex ? "2px solid #007bff" : "1px solid #ccc",
                  borderRadius: "4px",
                  cursor: "pointer",
                  transition: "transform 0.2s",
                  backgroundColor: "#fff",
                  boxShadow:
                    index === mainIndex
                      ? "0 2px 8px rgba(0,123,255,0.15)"
                      : "none",
                }}
                onMouseOver={(e) =>
                  (e.currentTarget.style.transform = "scale(1.08)")
                }
                onMouseOut={(e) =>
                  (e.currentTarget.style.transform = "scale(1)")
                }
              />
            ) : null
          );
        })()}

        {/* Nút sau */}
        <button
          onClick={handleNext}
          style={{
            padding: "6px 12px",
            backgroundColor: "#eee",
            border: "none",
            borderRadius: "4px",
            cursor: "pointer",
            fontSize: "18px",
          }}
        >
          &gt;
        </button>
      </div>
    </div>
  );
}

export default ProductImageGallery;
