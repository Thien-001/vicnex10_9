// BreadcrumbNav.jsx
import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { motion } from 'framer-motion';

function BreadcrumbNav({ category, product }) {
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
  // Trang sản phẩm
  else {
    breadcrumbs.push({ name: "Sản phẩm", path: "/product" });
    if (category) {
      breadcrumbs.push({ name: category.Name || category.name, path: `/san-pham/${category.Slug || category.slug}` });
    }
    if (product) {
      breadcrumbs.push({ name: product.Name || product.name, path: location.pathname });
    }
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
            position: sticky;
            top: 100px; /* chỉnh lại cho đúng tổng chiều cao header + nav */
            z-index: 1002;
            // background: #fff;
            padding: 8px 0;
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
