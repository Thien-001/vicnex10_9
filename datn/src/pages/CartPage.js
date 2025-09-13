// pages/CartPage.jstê
import React, { useEffect, useState } from "react";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import CartLeft from "../components/cart/CartLeft";
import CartRight from "../components/cart/CartRight";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import SectionHeading from "../components/home/SectionHeading";
import RecentlyViewed from "../components/product/RecentlyViewed";
import Hotmonthproduct from "../components/product/Hotmonthproduct";
import RecomendProduct from "../components/product/RecomendProduct";
import { CartProvider } from "../context/CartContext";
import { useLocation } from "react-router-dom";

function CartPage() {
  const location = useLocation();

  useEffect(() => {
    const params = new URLSearchParams(location.search);
    if (params.get("vnp_ResponseCode") === "24") {
      alert("Bạn đã hủy thanh toán!");
      // Hoặc dùng toast để thông báo đẹp hơn
    }
  }, [location.search]);

  const [cartItems, setCartItems] = useState([]);
  const [allVariants, setAllVariants] = useState([]);
  const [loadingVariants, setLoadingVariants] = useState(true);
  const [allProducts, setAllProducts] = useState([]);

  useEffect(() => {
    // Lấy cartItems từ localStorage
    const items = JSON.parse(localStorage.getItem("cart") || "[]");
    setCartItems(items);
  }, []);

  useEffect(() => {
    setLoadingVariants(true);
    // Lấy danh sách biến thể thực tế từ API
    fetch("http://localhost:8000/api/product-variants")
      .then((res) => res.json())
      .then((data) => {
        setAllVariants(data.data || []);
        setLoadingVariants(false);
      })
      .catch(() => {
        setAllVariants([]);
        setLoadingVariants(false);
      });
  }, []);

  const updateQuantity = (productId, sku, newQty) => {
    const updated = cartItems.map((item) =>
      String(item.Product_ID) === String(productId) && String(item.SKU) === String(sku)
        ? { ...item, qty: newQty } // Sửa lại thành qty
        : item
    );
    setCartItems(updated);
    localStorage.setItem("cart", JSON.stringify(updated));
  };

  const removeItem = (productId, sku) => {
    let cart = JSON.parse(localStorage.getItem("cart") || "[]");
    // Xóa sản phẩm theo Product_ID và SKU
    cart = cart.filter(item => !(item.Product_ID === productId && item.SKU === sku));
    localStorage.setItem("cart", JSON.stringify(cart));
    window.dispatchEvent(new Event("cartUpdated")); // Thêm dòng này!
    setCartItems(cart); // nếu bạn dùng useState cho cartItems
  };

  const [hotProducts, setHotProducts] = useState([]);
  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_hot=1")
      .then((res) => res.json())
      .then((data) => setHotProducts(data.data || []));
  }, []);

  useEffect(() => {
    fetch("http://localhost:8000/api/products")
      .then((res) => res.json())
      .then((data) => setAllProducts(data.data || []));
  }, []);

  return (
    <CartProvider>
      <>
        <Header />
        {/* Truyền prop current để BreadcrumbNav biết đang ở trang giỏ hàng */}
        <BreadcrumbNav current="Giỏ hàng" />
        <SectionHeading
          title="Giỏ Hàng"
          subtitle="Kiểm tra các sản phẩm đã chọn và thanh toán ngay"
        />
        
        <div className="cart-wrapper">
          <CartLeft
            cartItems={cartItems}
            updateQuantity={updateQuantity}
            removeItem={removeItem}
            allVariants={allVariants}
            loadingVariants={loadingVariants}
            allProducts={allProducts} // Thêm dòng này!
          />
          <CartRight cartItems={cartItems} />
        </div>
        <RecentlyViewed />
        <Hotmonthproduct products={hotProducts} />
        <RecomendProduct /> {/* Hiển thị sản phẩm bán chạy */}
        <Footer />
      </>
    </CartProvider>
  );
}

export default CartPage;

