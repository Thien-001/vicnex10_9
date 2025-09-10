import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import "../App.css";
import React from "react";
import LoginForm from "../components/login/LoginForm";

function LoginPage() {
  return (
    <div>
      <Header />
          <div>
      <LoginForm />
    </div>
      <Footer />
    </div>
  );
}

export default LoginPage;