import React, { useState, useEffect } from "react";

function parseVariantName(variantName) {
  return variantName.split(" - ");
}

function getOptionsByPosition(variants, pos) {
  return Array.from(
    new Set(
      variants
        .map(v => {
          const parts = parseVariantName(v.Variant_name);
          return parts[pos];
        })
        .filter(Boolean)
    )
  );
}

// Tìm variant có hàng đầu tiên
function findFirstAvailableVariant(variants) {
  return variants.find(v => parseInt(v.Quantity || 0, 10) > 0);
}

function ProductOptions({ variants = [], onVariantChange }) {
  const weightOptions = getOptionsByPosition(variants, 0);
  const stiffnessOptions = getOptionsByPosition(variants, 1);
  const balanceOptions = getOptionsByPosition(variants, 2);
  const playStyleOptions = getOptionsByPosition(variants, 3);

  // Khởi tạo với variant có hàng đầu tiên
  const initializeOptions = () => {
    if (variants.length === 0) {
      return {
        weight: weightOptions[0] || "",
        stiffness: stiffnessOptions[0] || "",
        balance: balanceOptions[0] || "",
        playStyle: playStyleOptions[0] || ""
      };
    }

    const availableVariant = findFirstAvailableVariant(variants);
    if (availableVariant) {
      const parts = parseVariantName(availableVariant.Variant_name);
      return {
        weight: parts[0] || weightOptions[0] || "",
        stiffness: parts[1] || stiffnessOptions[0] || "",
        balance: parts[2] || balanceOptions[0] || "",
        playStyle: parts[3] || playStyleOptions[0] || ""
      };
    }

    return {
      weight: weightOptions[0] || "",
      stiffness: stiffnessOptions[0] || "",
      balance: balanceOptions[0] || "",
      playStyle: playStyleOptions[0] || ""
    };
  };

  const defaultOptions = initializeOptions();
  const [weight, setWeight] = useState(defaultOptions.weight);
  const [stiffness, setStiffness] = useState(defaultOptions.stiffness);
  const [balance, setBalance] = useState(defaultOptions.balance);
  const [playStyle, setPlayStyle] = useState(defaultOptions.playStyle);

  // Reset khi variants thay đổi
  useEffect(() => {
    if (variants.length > 0) {
      const newOptions = initializeOptions();
      setWeight(newOptions.weight);
      setStiffness(newOptions.stiffness);
      setBalance(newOptions.balance);
      setPlayStyle(newOptions.playStyle);
    }
  }, [variants]);

  // Tìm variant và thông báo cho parent
  useEffect(() => {
    const variant = variants.find(v => {
      const parts = parseVariantName(v.Variant_name);
      return (
        parts[0] === weight &&
        parts[1] === stiffness &&
        parts[2] === balance &&
        parts[3] === playStyle
      );
    });
    if (onVariantChange) onVariantChange(variant);
  }, [weight, stiffness, balance, playStyle, variants, onVariantChange]);

  // Tìm variant hiện tại
  const currentVariant = variants.find(v => {
    const parts = parseVariantName(v.Variant_name);
    return (
      parts[0] === weight &&
      parts[1] === stiffness &&
      parts[2] === balance &&
      parts[3] === playStyle
    );
  });

  return (
    <div className="options">
      {/* CHỈ HIỂN THỊ CÁC OPTIONS CÓ DỮ LIỆU */}
      
      {/* Trọng lượng */}
      {weightOptions.length > 0 && (
        <div style={{ marginBottom: "12px" }}>
          <p style={{ fontWeight: "600", marginBottom: "6px", color: "#495057", fontSize: "14px" }}>
            Trọng lượng:
          </p>
          <div style={{ display: "flex", flexWrap: "wrap", gap: "6px" }}>
            {weightOptions.map(opt => (
              <button
                key={opt}
                className={weight === opt ? "selected" : ""}
                onClick={() => setWeight(opt)}
                type="button"
                style={{
                  padding: "6px 12px",
                  border: "1px solid",
                  borderColor: weight === opt ? "#007bff" : "#dee2e6",
                  borderRadius: "4px",
                  backgroundColor: weight === opt ? "#007bff" : "white",
                  color: weight === opt ? "white" : "#495057",
                  cursor: "pointer",
                  fontWeight: weight === opt ? "600" : "500",
                  fontSize: "13px",
                  transition: "all 0.15s ease"
                }}
              >
                {opt}
              </button>
            ))}
          </div>
        </div>
      )}

      {/* Độ cứng */}
      {stiffnessOptions.length > 0 && (
        <div style={{ marginBottom: "12px" }}>
          <p style={{ fontWeight: "600", marginBottom: "6px", color: "#495057", fontSize: "14px" }}>
            Độ cứng:
          </p>
          <div style={{ display: "flex", flexWrap: "wrap", gap: "6px" }}>
            {stiffnessOptions.map(opt => (
              <button
                key={opt}
                className={stiffness === opt ? "selected" : ""}
                onClick={() => setStiffness(opt)}
                type="button"
                style={{
                  padding: "6px 12px",
                  border: "1px solid",
                  borderColor: stiffness === opt ? "#007bff" : "#dee2e6",
                  borderRadius: "4px",
                  backgroundColor: stiffness === opt ? "#007bff" : "white",
                  color: stiffness === opt ? "white" : "#495057",
                  cursor: "pointer",
                  fontWeight: stiffness === opt ? "600" : "500",
                  fontSize: "13px",
                  transition: "all 0.15s ease"
                }}
              >
                {opt}
              </button>
            ))}
          </div>
        </div>
      )}

      {/* Điểm cân bằng */}
      {balanceOptions.length > 0 && (
        <div style={{ marginBottom: "12px" }}>
          <p style={{ fontWeight: "600", marginBottom: "6px", color: "#495057", fontSize: "14px" }}>
            Điểm cân bằng:
          </p>
          <div style={{ display: "flex", flexWrap: "wrap", gap: "6px" }}>
            {balanceOptions.map(opt => (
              <button
                key={opt}
                className={balance === opt ? "selected" : ""}
                onClick={() => setBalance(opt)}
                type="button"
                style={{
                  padding: "6px 12px",
                  border: "1px solid",
                  borderColor: balance === opt ? "#007bff" : "#dee2e6",
                  borderRadius: "4px",
                  backgroundColor: balance === opt ? "#007bff" : "white",
                  color: balance === opt ? "white" : "#495057",
                  cursor: "pointer",
                  fontWeight: balance === opt ? "600" : "500",
                  fontSize: "13px",
                  transition: "all 0.15s ease"
                }}
              >
                {opt}
              </button>
            ))}
          </div>
        </div>
      )}

      {/* Lối chơi */}
      {playStyleOptions.length > 0 && (
        <div style={{ marginBottom: "12px" }}>
          <p style={{ fontWeight: "600", marginBottom: "6px", color: "#495057", fontSize: "14px" }}>
            Lối chơi:
          </p>
          <div style={{ display: "flex", flexWrap: "wrap", gap: "6px" }}>
            {playStyleOptions.map(opt => (
              <button
                key={opt}
                className={playStyle === opt ? "selected" : ""}
                onClick={() => setPlayStyle(opt)}
                type="button"
                style={{
                  padding: "6px 12px",
                  border: "1px solid",
                  borderColor: playStyle === opt ? "#007bff" : "#dee2e6",
                  borderRadius: "4px",
                  backgroundColor: playStyle === opt ? "#007bff" : "white",
                  color: playStyle === opt ? "white" : "#495057",
                  cursor: "pointer",
                  fontWeight: playStyle === opt ? "600" : "500",
                  fontSize: "13px",
                  transition: "all 0.15s ease"
                }}
              >
                {opt}
              </button>
            ))}
          </div>
        </div>
      )}

      {/* THÔNG TIN VARIANT ĐƯỢC RÚT GỌN */}
      {currentVariant && (
        <div style={{ 
          marginTop: "16px",
          padding: "12px", 
          backgroundColor: parseInt(currentVariant.Quantity || 0, 10) > 0 ? "#e8f5e8" : "#fee2e2",
          borderRadius: "6px",
          border: parseInt(currentVariant.Quantity || 0, 10) > 0 ? "1px solid #28a745" : "1px solid #dc3545"
        }}>
          <div style={{ 
            fontWeight: "600", 
            color: parseInt(currentVariant.Quantity || 0, 10) > 0 ? "#28a745" : "#dc3545",
            fontSize: "14px",
            marginBottom: "4px"
          }}>
            {parseInt(currentVariant.Quantity || 0, 10) > 0 ? "✅" : "⚠️"} 
            {currentVariant.Variant_name}
          </div>
          
          <div style={{ fontSize: "16px", fontWeight: "600", color: "#d93025" }}>
            {Number(currentVariant.Discount_price || currentVariant.Price || 0).toLocaleString("vi-VN")}₫
          </div>
          
          <div style={{ 
            fontSize: "12px",
            color: "#6c757d",
            marginTop: "2px"
          }}>
            Số lượng: {currentVariant.Quantity || 0}
            {parseInt(currentVariant.Quantity || 0, 10) <= 0 && " - Hết hàng"}
          </div>
        </div>
      )}
    </div>
  );
}

export default ProductOptions;

