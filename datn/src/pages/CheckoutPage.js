import React, { useState, useEffect } from "react";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import CheckoutLeft from "../components/checkout/CheckoutLeft";
import CheckoutRight from "../components/checkout/CheckoutRight";

export default function CheckoutPage() {
  const [form, setForm] = useState({
    full_name: "",
    phone: "",
    email: "",
    province_code: "",
    district_code: "",
    ward_code: "",
    address: "",
    note: "",
  });
  const [cartItems, setCartItems] = useState([]);

  useEffect(() => {
    // Ưu tiên pendingBooking nếu có
    const pending = JSON.parse(localStorage.getItem('pendingBooking') || 'null');
    if (pending) {
      setCartItems([pending]);
    } else {
      const items = JSON.parse(localStorage.getItem("cart") || "[]");
      setCartItems(items);
    }
  }, []);

  return (
    <>
      <Header />
      <BreadcrumbNav current="Thanh toán" />
      <div className="checkout-wrapper" style={{ display: "flex", gap: 32 }}>
        <CheckoutLeft form={form} setForm={setForm} cartItems={cartItems} />
        <CheckoutRight cartItems={cartItems} setCartItems={setCartItems} form={form} />
      </div>
      <Footer />
    </>
  );
}
