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
  const [imageError, setImageError] = useState(false);

  // Khi danh sách ảnh thay đổi, reset về ảnh đầu tiên
  useEffect(() => {
    if (images.length > 0) {
      setMainIndex(0);
      setImageError(false);
    }
  }, [images.length]);

  // Hàm lấy đường dẫn ảnh (ưu tiên trường Image)
  const getImagePath = (img) => img.Image || img.Image_path || img.image_path || "";

  // Hàm xử lý nút trái
  const handlePrev = () => {
    setMainIndex((prev) => (prev === 0 ? images.length - 1 : prev - 1));
    setImageError(false);
  };

  // Hàm xử lý nút phải
  const handleNext = () => {
    setMainIndex((prev) => (prev === images.length - 1 ? 0 : prev + 1));
    setImageError(false);
  };

  // Hàm xử lý khi ảnh lỗi
  const handleImageError = () => {
    setImageError(true);
  };

  // Nếu không có ảnh
  if (images.length === 0) {
    return (
      <div style={{
        width: "100%",
        height: "400px",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        background: "#f8f9fa",
        borderRadius: "8px",
        border: "2px dashed #dee2e6"
      }}>
        <div style={{ textAlign: "center", color: "#6c757d" }}>
          <div style={{ fontSize: "48px", marginBottom: "12px" }}>📷</div>
          <div style={{ fontSize: "16px" }}>Không có hình ảnh</div>
        </div>
      </div>
    );
  }

  return (
    <div>
      {/* Ảnh chính */}
      {images[mainIndex] && getImagePath(images[mainIndex]) && !imageError ? (
        <img
          src={imageBaseUrl + encodeURI(getImagePath(images[mainIndex]))}
          alt={product?.Name || product?.name || "Ảnh sản phẩm"}
          onError={handleImageError}
          style={{
            backgroundColor: "#fff",
            objectFit: "contain", // Thay đổi từ cover thành contain để hiển thị full ảnh
            display: "block",
            margin: "0 auto",
            borderRadius: "8px",
            boxShadow: "0 4px 12px rgba(0,0,0,0.1)", // Shadow đẹp hơn
            maxWidth: "100%",
            maxHeight: "500px", // Giới hạn chiều cao
          }}
        />
      ) : (
        // Placeholder khi ảnh lỗi
        <div style={{
          width: "100%",
          height: "400px",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          background: "#f8f9fa",
          borderRadius: "8px",
          border: "2px dashed #dee2e6",
          margin: "0 auto"
        }}>
          <div style={{ textAlign: "center", color: "#6c757d" }}>
            <div style={{ fontSize: "48px", marginBottom: "8px" }}>❌</div>
            <div style={{ fontSize: "14px" }}>Ảnh không tải được</div>
          </div>
        </div>
      )}

      {/* Slider ảnh phụ + nút chuyển */}
      <div style={{
        display: "flex",
        alignItems: "center",
        gap: "25px",
        marginTop: "16px",
        justifyContent: "center",
      }}>
        {/* Nút trước */}
        <button
          onClick={handlePrev}
          disabled={images.length <= 1}
          style={{
            padding: "8px 14px", // Tăng padding một chút
            backgroundColor: images.length <= 1 ? "#f5f5f5" : "#007bff",
            color: images.length <= 1 ? "#999" : "#fff",
            border: "none",
            borderRadius: "6px", // Bo tròn hơn
            cursor: images.length <= 1 ? "not-allowed" : "pointer",
            fontSize: "18px",
            fontWeight: "600",
            transition: "all 0.2s ease",
            boxShadow: images.length <= 1 ? "none" : "0 2px 4px rgba(0,123,255,0.2)"
          }}
          onMouseEnter={(e) => {
            if (images.length > 1) {
              e.target.style.backgroundColor = "#0056b3";
              e.target.style.transform = "scale(1.05)";
            }
          }}
          onMouseLeave={(e) => {
            if (images.length > 1) {
              e.target.style.backgroundColor = "#007bff";
              e.target.style.transform = "scale(1)";
            }
          }}
        >
          ‹
        </button>

        {/* Các ảnh phụ, chỉ tối đa 5 ảnh */}
        {(() => {
          const maxThumbs = 5;
          const thumbs = images.slice(0, maxThumbs);

          return thumbs.map((img, index) =>
            getImagePath(img) ? (
              <img
                key={img.Image_ID || img.image_id || index}
                src={imageBaseUrl + encodeURI(getImagePath(img))}
                alt={`Ảnh ${index + 1}`}
                onClick={() => {
                  setMainIndex(index);
                  setImageError(false);
                }}
                style={{
                  width: "100px",
                  height: "100px",
                  objectFit: "cover",
                  border: index === mainIndex ? "3px solid #007bff" : "2px solid #e9ecef", // Border đẹp hơn
                  borderRadius: "6px", // Bo tròn hơn
                  cursor: "pointer",
                  transition: "all 0.2s ease", // Smooth transition
                  backgroundColor: "#fff",
                  boxShadow: index === mainIndex
                    ? "0 4px 12px rgba(0,123,255,0.25)" // Shadow đẹp hơn khi active
                    : "0 2px 4px rgba(0,0,0,0.1)",
                  opacity: index === mainIndex ? 1 : 0.8, // Làm mờ ảnh không active
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.transform = "scale(1.08)";
                  e.currentTarget.style.opacity = "1";
                  e.currentTarget.style.boxShadow = "0 6px 16px rgba(0,123,255,0.3)";
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.transform = "scale(1)";
                  e.currentTarget.style.opacity = index === mainIndex ? "1" : "0.8";
                  e.currentTarget.style.boxShadow = index === mainIndex
                    ? "0 4px 12px rgba(0,123,255,0.25)"
                    : "0 2px 4px rgba(0,0,0,0.1)";
                }}
              />
            ) : null
          );
        })()}

        {/* Nút sau */}
        <button
          onClick={handleNext}
          disabled={images.length <= 1}
          style={{
            padding: "8px 14px", // Tăng padding một chút
            backgroundColor: images.length <= 1 ? "#f5f5f5" : "#007bff",
            color: images.length <= 1 ? "#999" : "#fff",
            border: "none",
            borderRadius: "6px", // Bo tròn hơn
            cursor: images.length <= 1 ? "not-allowed" : "pointer",
            fontSize: "18px",
            fontWeight: "600",
            transition: "all 0.2s ease",
            boxShadow: images.length <= 1 ? "none" : "0 2px 4px rgba(0,123,255,0.2)"
          }}
          onMouseEnter={(e) => {
            if (images.length > 1) {
              e.target.style.backgroundColor = "#0056b3";
              e.target.style.transform = "scale(1.05)";
            }
          }}
          onMouseLeave={(e) => {
            if (images.length > 1) {
              e.target.style.backgroundColor = "#007bff";
              e.target.style.transform = "scale(1)";
            }
          }}
        >
          ›
        </button>
      </div>

      {/* Thông tin ảnh hiện tại */}
      {images.length > 1 && (
        <div style={{
          textAlign: "center",
          marginTop: "12px",
          fontSize: "14px",
          color: "#6c757d"
        }}>
          {mainIndex + 1} / {images.length}
        </div>
      )}
    </div>
  );
}

export default ProductImageGallery;
