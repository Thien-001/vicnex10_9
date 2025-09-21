import React, { useEffect, useState } from "react";
import { fetchProducts } from "../../api/productApi";
import { useNavigate } from "react-router-dom";

// Helper functions để parse variant name theo format thực tế
function parseVariantName(variantName) {
  // Format: "3U - Dẻo - Even Balance - 30 lbs - Tấn công"
  return variantName.split(" - ").map(part => part.trim());
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

function findVariant(variants, selectedOptions) {
  return variants.find(v => {
    const parts = parseVariantName(v.Variant_name);
    return (
      (!selectedOptions.weight || parts[0] === selectedOptions.weight) &&
      (!selectedOptions.stiffness || parts[1] === selectedOptions.stiffness) &&
      (!selectedOptions.balance || parts[2] === selectedOptions.balance) &&
      (!selectedOptions.tension || parts[3] === selectedOptions.tension) &&
      (!selectedOptions.playStyle || parts[4] === selectedOptions.playStyle)
    );
  });
}

// SỬA LẠI HÀM TÍNH STOCK - KIỂM TRA VARIANTS
function getStockInfo(product) {
  // Kiểm tra variants có tồn tại và hợp lệ
  const hasValidVariants = product.variants && 
                          Array.isArray(product.variants) && 
                          product.variants.length > 0;
  
  if (hasValidVariants) {
    // Lọc variants có số lượng > 0
    const availableVariants = product.variants.filter(v => {
      const quantity = parseInt(v.Quantity || 0, 10);
      return quantity > 0;
    });
    
    // Tính tổng số lượng từ tất cả variants
    const totalQuantity = product.variants.reduce((sum, v) => {
      return sum + (parseInt(v.Quantity || 0, 10));
    }, 0);
    
    return {
      hasVariants: true,
      hasStock: availableVariants.length > 0,
      totalQuantity,
      availableCount: availableVariants.length,
      totalCount: product.variants.length,
      displayText: availableVariants.length > 0 
        ? `${availableVariants.length}/${product.variants.length} biến thể có sẵn`
        : "Tất cả biến thể hết hàng"
    };
  } else {
    // Fallback về quantity chính của product
    const quantity = parseInt(product.Quantity || 0, 10);
    return {
      hasVariants: false,
      hasStock: quantity > 0,
      totalQuantity: quantity,
      availableCount: 0,
      totalCount: 0,
      displayText: quantity > 0 ? `Còn ${quantity} sản phẩm` : "Hết hàng"
    };
  }
}

function ProductList({ page, filters, onAddCompare, compareProducts = [], sort }) {
  const [products, setProducts] = useState([]);
  const [showVariantPopup, setShowVariantPopup] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);

  // State cho từng option theo format thực tế
  const [selectedOptions, setSelectedOptions] = useState({
    weight: "",        // 3U, 4U, 5U
    stiffness: "",     // Dẻo, Trung bình, Cứng
    balance: "",       // Even Balance, Head Heavy, Head Light
    tension: "",       // 28 lbs, 30 lbs, 32 lbs
    playStyle: ""      // Tấn công, Công thủ, Phòng thủ
  });
  const [selectedVariant, setSelectedVariant] = useState(null);

  const navigate = useNavigate();

  useEffect(() => {
    const fetchData = async () => {
      try {
        console.log("🚀 Fetching products with params:", { page, filters });
        const res = await fetchProducts(page, filters);
        
        console.log("📦 API Response:", res.data);
        let data = res.data.data;
        
        // Enhanced debug log
        console.log("🔍 Products received:", data.length);
        if (data.length > 0) {
          console.log("📋 First product sample:", {
            name: data[0].Name,
            id: data[0].Product_ID,
            mainQuantity: data[0].Quantity,
            variants: data[0].variants,
            variantsType: typeof data[0].variants,
            variantsLength: data[0].variants?.length,
            stockInfo: getStockInfo(data[0])
          });
        }
        
        // Sắp xếp sản phẩm mới nhất lên đầu nếu là trang 1
        if (page === 1) {
          data = [...data].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        }
        
        setProducts(data);
      } catch (err) {
        console.error("❌ Lỗi gọi API:", err);
        // Fallback - set empty array nếu có lỗi
        setProducts([]);
      }
    };
    fetchData();
  }, [page, filters]);

  // Reset options khi mở popup
  useEffect(() => {
    if (showVariantPopup && selectedProduct?.variants?.length > 0) {
      const variants = selectedProduct.variants;
      const defaultOptions = {
        weight: getOptionsByPosition(variants, 0)[0] || "",
        stiffness: getOptionsByPosition(variants, 1)[0] || "",
        balance: getOptionsByPosition(variants, 2)[0] || "",
        tension: getOptionsByPosition(variants, 3)[0] || "",
        playStyle: getOptionsByPosition(variants, 4)[0] || ""
      };
      setSelectedOptions(defaultOptions);
    }
  }, [showVariantPopup, selectedProduct]);

  // Tìm variant khi thay đổi options
  useEffect(() => {
    if (selectedProduct?.variants && Object.values(selectedOptions).some(opt => opt)) {
      const variant = findVariant(selectedProduct.variants, selectedOptions);
      setSelectedVariant(variant || null);
    } else {
      setSelectedVariant(null);
    }
  }, [selectedOptions, selectedProduct]);

  const handleProductClick = (slug) => {
    navigate(`/product/${slug}`);
  };

  // IMPROVED FILTERING - Kiểm tra dữ liệu trước khi filter
  let filteredProducts = Array.isArray(products) ? [...products] : [];
  
  // Filter by category
  if (filters?.category_id) {
    const categoryId = Number(filters.category_id);
    filteredProducts = filteredProducts.filter(product => 
      product.Categories_ID === categoryId
    );
  }
  
  // Filter by brand
  if (filters?.brand) {
    const brandArr = filters.brand.split(",").map((b) => b.trim().toLowerCase());
    filteredProducts = filteredProducts.filter(product => 
      product.Brand && brandArr.includes(product.Brand.toLowerCase())
    );
  }
  
  // Filter by price
  if (filters?.price) {
    const priceArr = filters.price.split(",");
    filteredProducts = filteredProducts.filter((product) => {
      return priceArr.some((priceRange) => {
        priceRange = priceRange.trim();
        const price = Number(product.Discount_price || product.Price);
        
        switch(priceRange) {
          case "Dưới 500.000đ":
            return price < 500000;
          case "500.000đ - 1.000.000đ":
            return price >= 500000 && price <= 1000000;
          case "1.000.000đ - 2.000.000đ":
            return price > 1000000 && price <= 2000000;
          case "Trên 2.000.000đ":
            return price > 2000000;
          default:
            return true;
        }
      });
    });
  }

  // IMPROVED SORTING
  let sortedProducts = [...filteredProducts];
  
  switch(sort) {
    case "price-asc":
      sortedProducts.sort((a, b) => {
        const priceA = Number(a.Discount_price || a.Price || 0);
        const priceB = Number(b.Discount_price || b.Price || 0);
        return priceA - priceB;
      });
      break;
    case "price-desc":
      sortedProducts.sort((a, b) => {
        const priceA = Number(a.Discount_price || a.Price || 0);
        const priceB = Number(b.Discount_price || b.Price || 0);
        return priceB - priceA;
      });
      break;
    case "bestseller":
      sortedProducts.sort((a, b) => {
        const bestA = Number(a.is_best_seller || 0);
        const bestB = Number(b.is_best_seller || 0);
        return bestB - bestA;
      });
      break;
    default:
      // Giữ nguyên thứ tự mặc định
      break;
  }

  // Early return nếu không có sản phẩm
  if (!sortedProducts || sortedProducts.length === 0) {
    return <p className="product-list-empty">Không tìm thấy sản phẩm phù hợp.</p>;
  }

  // IMPROVED ADD TO CART - Kiểm tra stock kỹ hơn
  const handleAddToCart = (product) => {
    // Validate product
    if (!product || !product.Product_ID) {
      alert("Thông tin sản phẩm không hợp lệ!");
      return;
    }

    // Check product status
    if (!product.Status) {
      alert("Sản phẩm hiện không khả dụng!");
      return;
    }

    const stockInfo = getStockInfo(product);
    console.log("🛒 Add to cart - Stock info:", stockInfo);
    
    if (!stockInfo.hasStock) {
      alert("Sản phẩm đã hết hàng!");
      return;
    }

    // Nếu có variants, mở popup chọn
    if (stockInfo.hasVariants) {
      setSelectedProduct(product);
      setShowVariantPopup(true);
    } else {
      // Thêm trực tiếp nếu không có variants
      addToCart(product);
    }
  };

  const addToCart = (product, variant = null) => {
    try {
      const cart = JSON.parse(localStorage.getItem("cart") || "[]");
      let item;
      
      if (variant) {
        // Kiểm tra variant có còn hàng không
        const variantQuantity = parseInt(variant.Quantity || 0, 10);
        if (variantQuantity <= 0) {
          alert("Biến thể này đã hết hàng!");
          return;
        }
        
        item = {
          ...product,
          selectedVariant: variant,
          SKU: variant.SKU,
          Price: Number(variant.Price || product.Price),
          Discount_price: Number(variant.Discount_price || variant.Price),
          quantity: 1,
          cartId: `${product.Product_ID}_${variant.Variant_ID}`
        };
      } else {
        // Sản phẩm không có variants
        const mainQuantity = parseInt(product.Quantity || 0, 10);
        if (mainQuantity <= 0) {
          alert("Sản phẩm đã hết hàng!");
          return;
        }
        
        item = {
          ...product,
          Price: Number(product.Discount_price || product.Price),
          Discount_price: Number(product.Discount_price),
          quantity: 1,
          cartId: `${product.Product_ID}_main`
        };
      }
      
      // Kiểm tra sản phẩm đã có trong giỏ chưa
      const existingItemIndex = cart.findIndex(i => i.cartId === item.cartId);
      
      let updatedCart;
      if (existingItemIndex !== -1) {
        // Cập nhật số lượng nếu đã có
        updatedCart = cart.map((cartItem, index) =>
          index === existingItemIndex
            ? { ...cartItem, quantity: (cartItem.quantity || 1) + 1 }
            : cartItem
        );
      } else {
        // Thêm mới nếu chưa có
        updatedCart = [...cart, item];
      }
      
      // Lưu vào localStorage
      localStorage.setItem("cart", JSON.stringify(updatedCart));
      
      // Dispatch event để update cart counter
      window.dispatchEvent(new Event("cartUpdated"));
      
      // Đóng popup và reset state
      setShowVariantPopup(false);
      setSelectedProduct(null);
      setSelectedVariant(null);
      setSelectedOptions({
        weight: "",
        stiffness: "",
        balance: "",
        tension: "",
        playStyle: ""
      });
      
      alert("✅ Đã thêm vào giỏ hàng!");
      
    } catch (error) {
      console.error("❌ Lỗi thêm vào giỏ hàng:", error);
      alert("Có lỗi xảy ra khi thêm vào giỏ hàng!");
    }
  };

  const updateSelectedOption = (key, value) => {
    setSelectedOptions(prev => ({
      ...prev,
      [key]: value
    }));
  };

  return (
    <main className="product-list-main">
      {sortedProducts.map((product) => {
        const stockInfo = getStockInfo(product);
        const isInCompare = compareProducts.some(p => p.Product_ID === product.Product_ID);
        const canAddToCompare = !isInCompare && compareProducts.length < 2;
        
        return (
          <div
            className="product-list-item"
            key={product.Product_ID}
            onClick={() => handleProductClick(product.slug)}
          >
            {/* Trái tim yêu thích */}
            <div className="product-list-fav tooltip-parent">
              ♡
              <span className="tooltip">Yêu thích</span>
            </div>
            
            {/* Ribbon trạng thái */}
            <div className="product-list-ribbons">
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
            
            {/* Ảnh sản phẩm */}
            <img
              src={`/${product.Image}`}
              alt={product.Name}
              className="product-list-image"
              onError={(e) => {
                e.target.src = '/images/placeholder.png'; // Fallback image
              }}
            />
            
            {/* Nội dung sản phẩm */}
            <div className="product-list-info">
              <h3 className="product-list-name">{product.Name}</h3>
              <div className="product-list-category">
                {product?.category?.Name || ""}
              </div>
              <div className="product-list-brand">
                {product.Brand || ""}
              </div>
              
              {/* IMPROVED STOCK DISPLAY */}
              <div className="product-list-stock" style={{ 
                margin: "4px 0", 
                fontSize: "0.85em",
                color: stockInfo.hasStock ? "#28a745" : "#dc3545",
                fontWeight: "500"
              }}>
                {stockInfo.displayText}
                {stockInfo.hasVariants && stockInfo.totalQuantity > 0 && (
                  <span style={{ 
                    color: "#666", 
                    fontSize: "0.9em", 
                    marginLeft: "5px" 
                  }}>
                    (Tổng: {stockInfo.totalQuantity})
                  </span>
                )}
              </div>
              
              {/* Giá sản phẩm */}
              <div className="product-list-price">
                {product.Discount_price && Number(product.Discount_price) < Number(product.Price) ? (
                  <>
                    <span className="product-list-price-sale">
                      {Number(product.Discount_price).toLocaleString("vi-VN")}₫
                    </span>
                    <del className="product-list-price-old">
                      {Number(product.Price).toLocaleString("vi-VN")}₫
                    </del>
                  </>
                ) : (
                  <span>{Number(product.Price || 0).toLocaleString("vi-VN")}₫</span>
                )}
              </div>
              
              {/* Rating */}
              <div className="product-list-rating">
                {Array.from({ length: 5 }).map((_, i) => {
                  const rating = Number(product.rating || 0);
                  if (rating >= i + 1) {
                    return <span key={i} style={{color:'#FFD700'}}>★</span>;
                  } else if (rating > i) {
                    return <span key={i} style={{color:'#FFD700'}}>☆</span>;
                  } else {
                    return <span key={i} style={{color:'#ddd'}}>★</span>;
                  }
                })}
                <span style={{marginLeft: 4, color: "#888", fontSize: "0.95em"}}>
                  ({product.rating ? Number(product.rating).toFixed(1) : "0"})
                </span>
              </div>
              
              {/* Actions */}
              <div className="product-list-actions">
                <button
                  className="product-list-cart-btn"
                  onClick={(e) => {
                    e.stopPropagation();
                    handleAddToCart(product);
                  }}
                  disabled={!product.Status || !stockInfo.hasStock}
                  style={{
                    opacity: (!product.Status || !stockInfo.hasStock) ? 0.6 : 1,
                    cursor: (!product.Status || !stockInfo.hasStock) ? 'not-allowed' : 'pointer'
                  }}
                >
                  {!stockInfo.hasStock
                    ? "Đã hết hàng"
                    : stockInfo.hasVariants
                      ? "Chọn biến thể"
                      : "🛒 Thêm vào giỏ hàng"}
                </button>
                
                <button
                  className="product-list-compare-btn tooltip-parent"
                  onClick={(e) => {
                    e.stopPropagation();
                    if (onAddCompare && canAddToCompare) {
                      onAddCompare(product);
                    }
                  }}
                  disabled={!canAddToCompare}
                  style={{
                    opacity: canAddToCompare ? 1 : 0.6,
                    cursor: canAddToCompare ? 'pointer' : 'not-allowed'
                  }}
                >
                  {isInCompare ? "Đã chọn" : "So sánh"}
                  <span className="tooltip">So sánh sản phẩm</span>
                </button>
              </div>
            </div>
          </div>
        );
      })}

      {/* IMPROVED VARIANT POPUP */}
      {showVariantPopup && selectedProduct && (
        <div className="variant-popup-overlay" style={{
          position: "fixed",
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          backgroundColor: "rgba(0,0,0,0.5)",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          zIndex: 1000,
          marginTop: "50"
        }}>
          <div className="variant-popup" style={{
            backgroundColor: "white",
            padding: "20px",
            borderRadius: "8px",
            maxWidth: "600px",
            width: "90%",
            maxHeight: "80vh",
            overflow: "auto"
          }}>
            <h3>Chọn biến thể cho {selectedProduct.Name}</h3>
            
            {/* Hiển thị tất cả biến thể có sẵn */}
            <div style={{ 
              marginBottom: "16px", 
              padding: "8px", 
              backgroundColor: "#f8f9fa", 
              borderRadius: "4px" 
            }}>
              <h4 style={{ 
                margin: "0 0 8px 0", 
                fontSize: "0.9em", 
                color: "#666" 
              }}>
                Biến thể có sẵn ({
                  selectedProduct.variants?.filter(v => parseInt(v.Quantity || 0, 10) > 0).length || 0
                }/{selectedProduct.variants?.length || 0}):
              </h4>
              {selectedProduct.variants?.length > 0 ? (
                <div style={{ fontSize: "0.8em", color: "#555" }}>
                  {selectedProduct.variants.map((variant, index) => {
                    const quantity = parseInt(variant.Quantity || 0, 10);
                    return (
                      <div key={index} style={{ 
                        display: "flex", 
                        justifyContent: "space-between", 
                        padding: "2px 0",
                        color: quantity > 0 ? "#28a745" : "#dc3545"
                      }}>
                        <span>{variant.Variant_name}</span>
                        <span>SL: {quantity}</span>
                      </div>
                    );
                  })}
                </div>
              ) : (
                <span style={{ fontSize: "0.8em", color: "#999" }}>
                  Không có biến thể
                </span>
              )}
            </div>
            
            {/* Render các options nếu có variants */}
            {selectedProduct.variants?.length > 0 && (
              <>
                {/* Trọng lượng */}
                {getOptionsByPosition(selectedProduct.variants, 0).length > 0 && (
                  <div style={{ marginBottom: "12px" }}>
                    <p style={{ marginBottom: "8px", fontWeight: "500" }}>
                      Trọng lượng:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(selectedProduct.variants, 0).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('weight', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.weight === opt ? "#007bff" : "white",
                            color: selectedOptions.weight === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Độ cứng */}
                {getOptionsByPosition(selectedProduct.variants, 1).length > 0 && (
                  <div style={{ marginBottom: "12px" }}>
                    <p style={{ marginBottom: "8px", fontWeight: "500" }}>
                      Độ cứng:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(selectedProduct.variants, 1).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('stiffness', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.stiffness === opt ? "#007bff" : "white",
                            color: selectedOptions.stiffness === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Điểm cân bằng */}
                {getOptionsByPosition(selectedProduct.variants, 2).length > 0 && (
                  <div style={{ marginBottom: "12px" }}>
                    <p style={{ marginBottom: "8px", fontWeight: "500" }}>
                      Điểm cân bằng:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(selectedProduct.variants, 2).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('balance', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.balance === opt ? "#007bff" : "white",
                            color: selectedOptions.balance === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Lực căng */}
                {getOptionsByPosition(selectedProduct.variants, 3).length > 0 && (
                  <div style={{ marginBottom: "12px" }}>
                    <p style={{ marginBottom: "8px", fontWeight: "500" }}>
                      Lực căng:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(selectedProduct.variants, 3).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('tension', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.tension === opt ? "#007bff" : "white",
                            color: selectedOptions.tension === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Lối chơi */}
                {getOptionsByPosition(selectedProduct.variants, 4).length > 0 && (
                  <div style={{ marginBottom: "12px" }}>
                    <p style={{ marginBottom: "8px", fontWeight: "500" }}>
                      Lối chơi:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(selectedProduct.variants, 4).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('playStyle', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.playStyle === opt ? "#007bff" : "white",
                            color: selectedOptions.playStyle === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}
              </>
            )}
            
            {/* Hiển thị thông tin variant đã chọn */}
            {selectedVariant ? (
              <div style={{ 
                margin: "16px 0", 
                padding: "12px", 
                backgroundColor: "#e8f5e8", 
                borderRadius: "4px",
                border: "1px solid #28a745"
              }}>
                <div style={{ color: "#0154b9", fontWeight: "500" }}>
                  Biến thể đã chọn: {selectedVariant.Variant_name}
                </div>
                <div style={{ marginTop: "8px" }}>
                  Giá: <strong>
                    {Number(selectedVariant.Discount_price || selectedVariant.Price || 0).toLocaleString("vi-VN")}₫
                  </strong>
                </div>
                <div style={{ 
                  color: parseInt(selectedVariant.Quantity || 0, 10) > 0 ? "#28a745" : "#dc3545",
                  fontWeight: "bold"
                }}>
                  Số lượng còn lại: {selectedVariant.Quantity || 0}
                </div>
                <div style={{ color: "#666", fontSize: "0.9em" }}>
                  SKU: {selectedVariant.SKU || 'N/A'}
                </div>
              </div>
            ) : (
              <div style={{ 
                margin: "16px 0", 
                padding: "12px", 
                backgroundColor: "#fff3cd", 
                borderRadius: "4px",
                border: "1px solid #ffc107",
                color: "#856404"
              }}>
                Vui lòng chọn các thông số để xem biến thể phù hợp
              </div>
            )}
            
            {/* Buttons */}
            <div style={{ 
              marginTop: "20px", 
              display: "flex", 
              gap: "10px", 
              justifyContent: "flex-end" 
            }}>
              <button
                onClick={() => {
                  setShowVariantPopup(false);
                  setSelectedProduct(null);
                  setSelectedVariant(null);
                  setSelectedOptions({
                    weight: "",
                    stiffness: "",
                    balance: "",
                    tension: "",
                    playStyle: ""
                  });
                }}
                style={{
                  padding: "10px 20px",
                  border: "1px solid #6c757d",
                  borderRadius: "4px",
                  backgroundColor: "white",
                  color: "#6c757d",
                  cursor: "pointer"
                }}
              >
                Hủy
              </button>
              <button
                disabled={!selectedVariant || parseInt(selectedVariant.Quantity || 0, 10) <= 0}
                onClick={() => {
                  if (!selectedVariant) {
                    alert("Vui lòng chọn biến thể!");
                    return;
                  }
                  
                  const quantity = parseInt(selectedVariant.Quantity || 0, 10);
                  if (quantity <= 0) {
                    alert("Biến thể này đã hết hàng!");
                    return;
                  }
                  
                  addToCart(selectedProduct, selectedVariant);
                }}
                style={{
                  padding: "10px 20px",
                  border: "none",
                  borderRadius: "4px",
                  backgroundColor: (!selectedVariant || parseInt(selectedVariant.Quantity || 0, 10) <= 0) 
                    ? "#6c757d" 
                    : "#28a745",
                  color: "white",
                  cursor: (!selectedVariant || parseInt(selectedVariant.Quantity || 0, 10) <= 0) 
                    ? "not-allowed" 
                    : "pointer"
                }}
              >
                Thêm vào giỏ hàng
              </button>
            </div>
          </div>
        </div>
      )}
    </main>
  );
}

export default ProductList;