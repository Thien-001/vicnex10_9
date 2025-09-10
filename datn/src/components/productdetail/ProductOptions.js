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

function ProductOptions({ variants = [], onVariantChange }) {
  const weightOptions = getOptionsByPosition(variants, 0);
  const stiffnessOptions = getOptionsByPosition(variants, 1);
  const balanceOptions = getOptionsByPosition(variants, 2);
  const playStyleOptions = getOptionsByPosition(variants, 3);

  const [weight, setWeight] = useState(weightOptions[0]);
  const [stiffness, setStiffness] = useState(stiffnessOptions[0]);
  const [balance, setBalance] = useState(balanceOptions[0]);
  const [playStyle, setPlayStyle] = useState(playStyleOptions[0]);

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

  return (
    <div className="options">
      {weightOptions.length > 0 && (
        <div>
          <p>Trọng lượng:</p>
          {weightOptions.map(opt => (
            <button
              key={opt}
              className={weight === opt ? "selected" : ""}
              onClick={() => setWeight(opt)}
              type="button"
            >
              {opt}
            </button>
          ))}
        </div>
      )}
      {stiffnessOptions.length > 0 && (
        <div>
          <p>Độ cứng:</p>
          {stiffnessOptions.map(opt => (
            <button
              key={opt}
              className={stiffness === opt ? "selected" : ""}
              onClick={() => setStiffness(opt)}
              type="button"
            >
              {opt}
            </button>
          ))}
        </div>
      )}
      {balanceOptions.length > 0 && (
        <div>
          <p>Điểm cân bằng:</p>
          {balanceOptions.map(opt => (
            <button
              key={opt}
              className={balance === opt ? "selected" : ""}
              onClick={() => setBalance(opt)}
              type="button"
            >
              {opt}
            </button>
          ))}
        </div>
      )}
      {playStyleOptions.length > 0 && (
        <div>
          <p>Lối chơi:</p>
          {playStyleOptions.map(opt => (
            <button
              key={opt}
              className={playStyle === opt ? "selected" : ""}
              onClick={() => setPlayStyle(opt)}
              type="button"
            >
              {opt}
            </button>
          ))}
        </div>
      )}
    </div>
  );
}

export default ProductOptions;

