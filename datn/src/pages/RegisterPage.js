import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import "../App.css";
import React from "react";
import RegisterForm from "../components/register/RegisterForm";

function RegisterPage() {
  return (
    <div>
      <Header />
        <div>
      <RegisterForm />
    </div>
      <Footer />
    </div>
  );
}

export default RegisterPage;