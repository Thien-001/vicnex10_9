import React, { useState } from "react";
import axios from "axios";

const ContactPage = () => {
  const [form, setForm] = useState({
    name: "",
    phone: "",
    email: "",
    subject: "",
    message: "",
  });
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
    setError("");
    setSuccess("");
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    // Validate cơ bản
    if (!form.name || !form.phone || !form.email || !form.subject || !form.message) {
      setError("Vui lòng điền đầy đủ thông tin!");
      return;
    }
    if (!/\S+@\S+\.\S+/.test(form.email)) {
      setError("Email không hợp lệ!");
      return;
    }
    try {
      await axios.post("http://localhost:8000/api/contact", {
        Name: form.name,
        Phone: form.phone,
        Email: form.email,
        Subject: form.subject,
        Message: form.message,
      });
      setSuccess("Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.");
      setForm({
        name: "",
        phone: "",
        email: "",
        subject: "",
        message: "",
      });
    } catch (err) {
      setError("Gửi liên hệ thất bại. Vui lòng thử lại sau!");
    }
  };

  return (
    <>
      {/* FONT + ICONS CDN */}
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      />
      <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap"
        rel="stylesheet"
      />

      

      {/* HTML CONTENT */}
      <div className="contact-container">
        <h3 className="title-contact">Liên hệ</h3>
        <div className="homnet-contact-container">
          <div className="homnet-contact-left">
            <h1>
              Bạn đang gặp <span className="homnet-highlight">vấn đề?</span>
              <br /> Hãy liên hệ chúng tôi!
            </h1>
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3294.9909606261895!2d106.62390764029482!3d10.85511497468035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752b6c59ba4c97%3A0x535e784068f1558b!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1754301984592!5m2!1svi!2s"
              title="Google Map"
              allowFullScreen
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
              className="homnet-map"
            ></iframe>
          </div>
          <div className="homnet-contact-right">
            <form onSubmit={handleSubmit} autoComplete="off">
              {error && <div className="contact-error">{error}</div>}
              {success && <div className="contact-success">{success}</div>}
              <div className="homnet-form-group">
                <p className="homnet-icon">&#xf007;</p>
                <input
                  type="text"
                  placeholder="Tên"
                  name="name"
                  value={form.name}
                  onChange={handleChange}
                />
              </div>
              <div className="homnet-form-group">
                <p className="homnet-icon">&#xf095;</p>
                <input
                  type="text"
                  placeholder="Điện thoại"
                  name="phone"
                  value={form.phone}
                  onChange={handleChange}
                />
              </div>
              <div className="homnet-form-group">
                <p className="homnet-icon">&#xf0e0;</p>
                <input
                  type="email"
                  placeholder="Email"
                  name="email"
                  value={form.email}
                  onChange={handleChange}
                />
              </div>
              <div className="homnet-form-group">
                <p className="homnet-icon">&#xf05a;</p>
                <input
                  type="text"
                  placeholder="Vấn đề"
                  name="subject"
                  value={form.subject}
                  onChange={handleChange}
                />
              </div>
              <div className="homnet-form-group">
                <p className="homnet-icon">&#xf044;</p>
                <input
                  type="text"
                  placeholder="Chúng tôi có thể giúp gì cho bạn?"
                  name="message"
                  value={form.message}
                  onChange={handleChange}
                />
              </div>
              <button className="homnet-submit-btn" type="submit">
                <i className="fa-regular fa-paper-plane"></i> Gửi
              </button>
            </form>
          </div>
        </div>

        <div className="homnet-contact1-info">
          <h2 className="homnet-contact">
            <a href="tel:+18001234665">+1(800)123-4665</a>
          </h2>
          <h2>
            <span
              style={{
                color: "#000",
                textDecoration: "underline",
                cursor: "pointer",
                background: "#000",
                WebkitBackgroundClip: "text",
                WebkitTextFillColor: "transparent"
              }}
              tabIndex={0}
            >
              QTSC 9 Building, Đ. Tô Ký, Tân Chánh Hiệp,
              <p>Quận 12, Hồ Chí Minh, Việt Nam</p>
            </span>
          </h2>
          <h2 className="homnet-contact">
            <a href="mailto:info@example.com">info@example.com</a>
          </h2>
        </div>
      </div>
    </>
  );
};

export default ContactPage;
