import React from "react";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import ContactPage from "../components/contact/ContactPage";

const ContactPageWrapper = () => {
  const breadcrumb = [
    { label: "Trang chủ", link: "/" },
    { label: "Liên hệ", link: "/contact" }
  ];

  return (
    <div>
      <Header />
      <BreadcrumbNav items={breadcrumb} />
      <ContactPage />
      <Footer />
    </div>
  );
};

export default ContactPageWrapper;
