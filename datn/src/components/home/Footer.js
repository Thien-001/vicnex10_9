import { Link } from "react-router-dom";

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-container">
        {/* Cột logo riêng */}
        <div className="footer-logo-col">
          <div className="footer-logo-row">
            <img src="/img/logo/Logo_vicnec.png" alt="Logo VicNex" />
          </div>
        </div>
        {/* Các cột còn lại */}
        <div className="footer-col">
          <h4>Chính sách & Hỗ trợ</h4>
          <ul>
            <li>📦 Giao hàng toàn quốc</li>
            <li>🔁 Chính sách đổi trả</li>
            <li>🛡️ Bảo hành 1 đổi 1</li>
            <li>❓ Câu hỏi thường gặp</li>
            <li>
              <Link to="/policy">📄 Chính sách & Quy định</Link>
            </li>
          </ul>
        </div>
        <div className="footer-col">
          <h4>Liên hệ & Địa chỉ</h4>
          <p>📞 <b>0123 456 789</b></p>
          <p>📧 support@shop.com</p>
          <p>📍 123 Đường ABC, Quận X, TP.HCM</p>
        </div>
        <div className="footer-col">
          <h4>Kết nối với VicNex</h4>
          <div className="social-icons">
            <a href="/#">Facebook</a>
            <a href="/#">YouTube</a>
            <a href="/#">Zalo</a>
          </div>
          <form>
            <input type="email" placeholder="Nhập email của bạn..." />
            <button type="submit">Đăng ký nhận tin</button>
          </form>
        </div>
      </div>
      <div className="footer-bottom">
        © {new Date().getFullYear()} VicNex. All rights reserved.
      </div>
    </footer>
  );
};

export default Footer;
