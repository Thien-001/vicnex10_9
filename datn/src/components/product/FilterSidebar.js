import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { motion } from 'framer-motion';
import PropTypes from 'prop-types';

// Bộ lọc chỉ hiển thị các biến thể phù hợp với từng danh mục
const filterData = [
  {
    title: 'Lọc theo loại sản phẩm',
    type: 'list',
    options: [
      'Vợt cầu lông',
      'Giày cầu lông',
      'Quần áo cầu lông',
      'Phụ kiện cầu lông',
      'Combo tiết kiệm',
      'Hàng giảm giá',
      'Mới về',
      'Top bán chạy',
    ],
  },
  {
    title: 'Lọc theo thương hiệu',
    type: 'list',
    options: ['Yonex', 'Lining', 'Victor', 'Forza', 'Protech', 'Kumpoo', 'Kawasaki', 'Mizuno', 'Apacs', 'Babolat', 'Adidas', 'Astec'],
  },
  {
    title: 'Lọc theo giá',
    type: 'list',
    options: ['Dưới 500.000đ', '500.000đ - 1.000.000đ', '1.000.000đ - 2.000.000đ', 'Trên 2.000.000đ'],
  },
];



function FilterSidebar({ setFilters, filters }) {
  const [selectedFilters, setSelectedFilters] = useState({});
  const [selectedCategory, setSelectedCategory] = useState("");

  // Hàm xóa tất cả bộ lọc
  const handleClearAll = useCallback(() => {
    setSelectedFilters({});
    setSelectedCategory("");
  }, []);

  // Khi chọn danh mục, cập nhật selectedCategory và reset các biến thể
  const handleCheckboxChange = useCallback((groupTitle, option) => {
    setSelectedFilters((prev) => {
      // Nếu chọn danh mục sản phẩm
      if (groupTitle === "Lọc theo loại sản phẩm") {
        const isSelected = prev[groupTitle]?.includes(option);
        // Nếu bỏ chọn danh mục hiện tại
        if (isSelected) {
          setSelectedCategory("");
          // Xóa tất cả filter biến thể
          const newFilters = { ...prev, [groupTitle]: [] };
          return newFilters;
        } else {
          // Chọn danh mục mới: chỉ giữ filter danh mục mới
          const newFilters = { ...prev, [groupTitle]: [option] };
          setSelectedCategory(option);
          return newFilters;
        }
      }
      // Các nhóm khác
      const group = prev[groupTitle] || [];
      const updatedGroup = group.includes(option)
        ? group.filter((item) => item !== option)
        : [...group, option];
      return {
        ...prev,
        [groupTitle]: updatedGroup,
      };
    });
  }, []);

  // Memoize filters để tránh re-render không cần thiết
  const memoizedFilters = useMemo(() => {
    let filtersWithKeyword = { ...selectedFilters };
    if (
      selectedFilters["Lọc theo loại sản phẩm"] &&
      selectedFilters["Lọc theo loại sản phẩm"].length > 0
    ) {
      filtersWithKeyword.keyword = selectedFilters["Lọc theo loại sản phẩm"][0];
    } else {
      delete filtersWithKeyword.keyword;
    }
    return filtersWithKeyword;
  }, [selectedFilters]);

  // Gửi filters ra ngoài cho component cha xử lý API
  useEffect(() => {
    setFilters(memoizedFilters);
  }, [memoizedFilters, setFilters]);

  return (
    <motion.aside
      className="filter-wrapper"
      initial={{ x: -100, opacity: 0 }}
      animate={{ x: 0, opacity: 1 }}
      transition={{ duration: 0.6, ease: 'easeOut' }}
    >
      <div className="filter-box">
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
          <h3 className="filter-box__title">BỘ LỌC SẢN PHẨM</h3>
          <button
            onClick={handleClearAll}
            style={{
              background: "#fff",
              border: "1px solid #0154b9",
              color: "#0154b9",
              borderRadius: 4,
              padding: "5px 14px",
              fontWeight: 600,
              cursor: "pointer",
              fontSize: 14,
              marginLeft: 8,
              transition: "all 0.2s",
            }}
            onMouseOver={e => e.currentTarget.style.background = "#0154b9"}
            onMouseOut={e => e.currentTarget.style.background = "#fff"}
          >
            Xóa tất cả
          </button>
        </div>

        {/* Hiển thị bộ lọc cơ bản */}
        {filterData.map((group, index) => (
          <div className="filter-group" key={index}>
            <h4 className="filter-group__title">{group.title}</h4>
            <ul className="scrollable-list">
              {group.options.map((option, idx) => (
                <li key={idx}>
                  <label>
                    <input
                      type="checkbox"
                      checked={selectedFilters[group.title]?.includes(option) || false}
                      onChange={() => handleCheckboxChange(group.title, option)}
                      // Chỉ cho phép chọn 1 danh mục sản phẩm
                      disabled={
                        group.title === "Lọc theo loại sản phẩm" &&
                        selectedFilters[group.title]?.length === 1 &&
                        !selectedFilters[group.title]?.includes(option)
                      }
                    />{' '}
                    {option}
                  </label>
                </li>
              ))}
            </ul>
          </div>
        ))}

      </div>
    </motion.aside>
  );
}

FilterSidebar.propTypes = {
  setFilters: PropTypes.func.isRequired,
  filters: PropTypes.object,
};

export default FilterSidebar;
