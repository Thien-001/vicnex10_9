// BreadcrumbNav.jsx
import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { motion } from 'framer-motion';

function BreadcrumbNav({ category, product, article }) {
  const location = useLocation();

  let breadcrumbs = [
    { name: "Trang chủ", path: "/" },
  ];

  // Trang liên hệ
  if (location.pathname === "/contact") {
    breadcrumbs.push({ name: "Liên hệ", path: null });
  }
  // Trang giỏ hàng
  else if (location.pathname === "/cart") {
    breadcrumbs.push({ name: "Giỏ hàng", path: null });
  }
  // Trang bài viết tổng
  else if (location.pathname === "/article") {
    breadcrumbs.push({ name: "Bài viết", path: null });
  }
  // Trang chi tiết bài viết
  else if (location.pathname.startsWith("/article/")) {
    breadcrumbs.push({ name: "Bài viết", path: "/article" });
    if (article) {
      breadcrumbs.push({ name: article.Title || article.title, path: location.pathname });
    }
  }
  // Trang sản phẩm tổng
  else if (location.pathname === "/product") {
    breadcrumbs.push({ name: "Sản phẩm", path: null });
  }
  // Trang chuyên mục sản phẩm
  else if (location.pathname.startsWith("/san-pham/")) {
    breadcrumbs.push({ name: "Sản phẩm", path: "/product" });
    if (category) {
      breadcrumbs.push({ name: category.Name || category.name, path: location.pathname });
    }
  }
  // Trang chi tiết sản phẩm
  else if (location.pathname.startsWith("/product/")) {
    breadcrumbs.push({ name: "Sản phẩm", path: "/product" });
    if (category) {
      breadcrumbs.push({ name: category.Name || category.name, path: `/san-pham/${category.Slug || category.slug}` });
    }
    if (product) {
      breadcrumbs.push({ name: product.Name || product.name, path: location.pathname });
    }
  }
  // Trang vợt cầu lông
  else if (location.pathname.startsWith("/product/vot-cau-long")) {
    breadcrumbs.push({ name: "Sản phẩm", path: "/product" });
    breadcrumbs.push({ name: "Vợt cầu lông", path: location.pathname });
  }
  // Trang tìm kiếm
  else if (location.pathname.startsWith("/search")) {
    breadcrumbs.push({ name: "Tìm kiếm", path: null });
  }
  // Trang tài khoản
  else if (location.pathname.startsWith("/account")) {
    breadcrumbs.push({ name: "Tài khoản", path: null });
  }
  // Trang đăng nhập
  else if (location.pathname === "/login") {
    breadcrumbs.push({ name: "Đăng nhập", path: null });
  }
  // Trang đăng ký
  else if (location.pathname === "/register") {
    breadcrumbs.push({ name: "Đăng ký", path: null });
  }
  // Trang lỗi 404
  else if (location.pathname === "/404") {
    breadcrumbs.push({ name: "Không tìm thấy trang", path: null });
  }
  // Mặc định: Sản phẩm
  else {
    breadcrumbs.push({ name: "Sản phẩm", path: "/product" });
  }

  return (
    <motion.nav
      className="breadcrumb-nav"
      initial={{ opacity: 0, x: -40 }}
      animate={{ opacity: 1, x: 0 }}
      transition={{ duration: 0.6, ease: 'easeOut' }}
    >
      <style>
        {`
          .breadcrumb-nav {
            // position: sticky;
            top: 100px;
            z-index: 1002;
            // padding: 8px 0;
          }
        `}
      </style>
      <div className="breadcrumb-container">
        {breadcrumbs.map((item, idx) => (
          <React.Fragment key={idx}>
            {item.path && idx < breadcrumbs.length - 1 ? (
              <>
                <Link to={item.path}>{item.name}</Link>
                <span className="separator">›</span>
              </>
            ) : (
              <span className="current">{item.name}</span>
            )}
          </React.Fragment>
        ))}
      </div>
    </motion.nav>
  );
}

export default BreadcrumbNav;
