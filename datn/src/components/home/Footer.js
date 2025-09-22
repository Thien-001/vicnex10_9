import { Link } from "react-router-dom";

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-top">
        {/* Logo */}
        <div className="logo-footer">
          <img src="/img/logo/logormbg.png" alt="Logo VicNex" />
        </div>

        {/* Shop menu */}
        <div className="shop-online-footer">
          <h3>Shop Vicnex</h3>
          <ul>
            <li><Link to="/">Trang chủ</Link></li>
            <li><Link to="/products">Sản phẩm</Link></li>
            <li><Link to="/about">Giới thiệu</Link></li>
            <li><Link to="/news">Tin tức</Link></li>
            <li><Link to="/contact">Liên hệ</Link></li>
          </ul>
        </div>

        {/* Hỗ trợ khách hàng */}
        <div className="support-footer">
          <h3>Hỗ trợ khách hàng</h3>
          <ul>
            <li><Link to="/return-policy">Chính sách đổi trả</Link></li>
            <li><Link to="/warranty-policy">Chính sách bảo hành</Link></li>
            <li><Link to="/shipping-policy">Chính sách giao hàng</Link></li>
            <li><Link to="/faq">Câu hỏi thường gặp</Link></li>
          </ul>
        </div>

        {/* Liên hệ */}
        <div className="contact-footer">
          <h3>Liên hệ</h3>
          <p>📍 QTSC 9 Building, Đ. Tô Ký, Tân Chánh Hiệp, Quận 12, HCM</p>
          <p>📞 (0123) 456-789</p>
          <p>📧 shopvicnex@gmail.com</p>
        </div>

        {/* Kết nối */}
        <div className="social-footer">
          <h3>Kết nối với chúng tôi</h3>
          <ul>
            <li><a href="/#">Facebook</a></li>
            <li><a href="/#">Twitter</a></li>
            <li><a href="/#">Instagram</a></li>
          </ul>
        </div>
      </div>

      {/* Footer bottom */}
      <div className="footer-bottom">
        <p>&copy; {new Date().getFullYear()} SHOP Vicnex. All rights reserved.</p>
      </div>
    </footer>
  );
};

export default Footer;
