import React, { useState, useEffect } from "react";
import CheckoutLeft from "./CheckoutLeft";
import CheckoutRight from "./CheckoutRight";
import "./Checkout.css";

function Checkout() {
  const [cartItems, setCartItems] = useState([]);
  const [form, setForm] = useState({
    full_name: "",
    phone: "",
    email: "",
    address: "",
    province_code: "",
    district_code: "",
    ward_code: "",
    note: "",
    area: "ngoaithanh", // default
    distance_km: 10, // default
  });

  // Load cart từ localStorage
  useEffect(() => {
    const savedCart = localStorage.getItem("cart");
    if (savedCart) {
      try {
        const cart = JSON.parse(savedCart);
        setCartItems(Array.isArray(cart) ? cart : []);
      } catch (e) {
        console.error("Error parsing cart from localStorage:", e);
        setCartItems([]);
      }
    }
  }, []);

  return (
    <div className="checkout-container">
      <CheckoutLeft 
        cartItems={cartItems}
        form={form}
        setForm={setForm}
      />
      <CheckoutRight 
        cartItems={cartItems}
        setCartItems={setCartItems}
        form={form}
      />
    </div>
  );
}

export default Checkout;