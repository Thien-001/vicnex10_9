// src/App.js
import React from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import HomePage from './pages/HomePage';
import ProductsPage from "./pages/ProductsPage";
import ProductPage from "./pages/ProductPage";
import ProductDetailPage from "./pages/ProductDetailPage";
import CartPage from "./pages/CartPage";
import CheckoutPage from "./pages/CheckoutPage";
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import BookingPage from "./pages/BookingPage";
import BookingDetailPage from "./pages/BookingDetailPage";
import CourtDetailPage from "./pages/CourtPage";
import UserProfile from "./pages/UserProfile";
import ThanhyouPage from "./pages/ThanhyouPage";
import ContactPage from "./pages/ContactPage";
import { CartProvider } from "./context/CartContext";
import ArticlePage from "./pages/ArticlePage";
import ArticleListPage from "./pages/ArticleListPage";
import FaqPage from "./pages/FaqPage";
import PolicyPage from "./pages/PolicyPage";
import VnpayCallback from "./components/VnpayCallback";

function App() {
  return (
    <CartProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/products" element={<ProductsPage />} />
          <Route path="/product" element={<ProductPage />} />
          <Route path="/product/:slug" element={<ProductDetailPage />} />
          <Route path="/cart" element={<CartPage />} />
          <Route path="/checkout" element={<CheckoutPage />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route path="/booking" element={<BookingPage />} />
          <Route path="/booking/:courtId" element={<BookingDetailPage />} />
          <Route path="/courts" element={<CourtDetailPage />} />
          <Route path="/profile" element={<UserProfile />} />
          <Route path="/thankyou" element={<ThanhyouPage />} />
          <Route path="/contact" element={<ContactPage />} />
          <Route path="/article" element={<ArticleListPage />} />
          <Route path="/article/:id" element={<ArticlePage />} />
          <Route path="/faq" element={<FaqPage />} />
          <Route path="/policy" element={<PolicyPage />} />
          <Route path="/vnpay-callback" element={<VnpayCallback />} />
        </Routes>
      </BrowserRouter>
    </CartProvider>
  );
}

export default App;

