import React, { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import PropTypes from 'prop-types';

// Ánh xạ tên sang ID
const categoryNameToId = {
  "Vợt cầu lông": 1,
  "Giày cầu lông": 2,
  "Quần áo cầu lông": 3,
  "Phụ kiện cầu lông": 4,
  "Combo tiết kiệm": 5,
  "Hàng giảm giá": 6,
  "Mới về": 7,
  "Top bán chạy": 8,
};

const filterData = [
  {
    title: 'Lọc theo loại sản phẩm',
    type: 'list',
    options: Object.keys(categoryNameToId),
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

// Các biến thể theo từng danh mục
const variantFilters = {
  "Vợt cầu lông": [
    {
      title: 'Lọc theo cân nặng vợt',
      type: 'list',
      options: ['3U (85–89g)', '4U (80–84g)', '5U (75–79g)'],
    },
    {
      title: 'Lọc theo độ cứng thân',
      type: 'list',
      options: ['Mềm', 'Trung bình', 'Cứng'],
    },
    {
      title: 'Lọc theo điểm cân bằng',
      type: 'list',
      options: ['Nặng đầu (tấn công)', 'Cân bằng', 'Nặng đuôi (phòng thủ)'],
    },
  ],
  "Giày cầu lông": [
    {
      title: 'Lọc theo kích cỡ',
      type: 'list',
      options: ['Size 38', 'Size 39', 'Size 40', 'Size 41', 'Size 42', 'Size 43', 'Size 44'],
    },
    {
      title: 'Lọc theo màu sắc',
      type: 'color',
      options: [
        { label: 'Đỏ', class: 'red' },
        { label: 'Xanh dương', class: 'blue' },
        { label: 'Xanh lá', class: 'green' },
        { label: 'Đen', class: 'black' },
        { label: 'Trắng', class: 'white' },
        { label: 'Vàng', class: 'yellow' },
      ],
    },
  ],
  "Quần áo cầu lông": [ // Đổi từ "Quần áo" thành "Quần áo cầu lông"
    {
      title: 'Lọc theo kích cỡ',
      type: 'list',
      options: ['Size S', 'Size M', 'Size L', 'Size XL'],
    },
    {
      title: 'Lọc theo màu sắc',
      type: 'color',
      options: [
        { label: 'Đỏ', class: 'red' },
        { label: 'Xanh dương', class: 'blue' },
        { label: 'Xanh lá', class: 'green' },
        { label: 'Đen', class: 'black' },
        { label: 'Trắng', class: 'white' },
        { label: 'Vàng', class: 'yellow' },
      ],
    },
    {
      title: 'Lọc theo giới tính',
      type: 'list',
      options: ['Nam', 'Nữ', 'Unisex'],
    },
  ],
  // Thêm các danh mục khác nếu cần
};

function FilterSidebar({ setFilters, filters }) {
  const [selectedFilters, setSelectedFilters] = useState({});
  const [selectedCategory, setSelectedCategory] = useState("");

  // Hàm xóa tất cả bộ lọc
  const handleClearAll = () => {
    setSelectedFilters({});
    setSelectedCategory("");
  };

  // Khi chọn danh mục, cập nhật selectedCategory và reset các biến thể
  const handleCheckboxChange = (groupTitle, option) => {
    setSelectedFilters((prev) => {
      // Nếu chọn danh mục sản phẩm
      if (groupTitle === "Lọc theo loại sản phẩm") {
        const isSelected = prev[groupTitle]?.includes(option);
        // Nếu bỏ chọn danh mục hiện tại
        if (isSelected) {
          setSelectedCategory("");
          // Xóa tất cả filter biến thể
          const newFilters = { ...prev, [groupTitle]: [] };
          Object.values(variantFilters).flat().forEach(vf => {
            delete newFilters[vf.title];
          });
          return newFilters;
        } else {
          // Chọn danh mục mới: chỉ giữ filter danh mục mới, xóa filter biến thể cũ
          const newFilters = { ...prev, [groupTitle]: [option] };
          Object.values(variantFilters).flat().forEach(vf => {
            delete newFilters[vf.title];
          });
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
  };

  // Gửi filters ra ngoài cho component cha xử lý API
  useEffect(() => {
    const outFilters = {};

    // Truyền ID danh mục
    if (
      selectedFilters["Lọc theo loại sản phẩm"] &&
      selectedFilters["Lọc theo loại sản phẩm"].length > 0
    ) {
      const categoryName = selectedFilters["Lọc theo loại sản phẩm"][0];
      outFilters.category_id = categoryNameToId[categoryName];
    }

    if (
      selectedFilters["Lọc theo thương hiệu"] &&
      selectedFilters["Lọc theo thương hiệu"].length > 0
    ) {
      outFilters.brand = selectedFilters["Lọc theo thương hiệu"].join(",");
    }

    if (
      selectedFilters["Lọc theo giá"] &&
      selectedFilters["Lọc theo giá"].length > 0
    ) {
      outFilters.price = selectedFilters["Lọc theo giá"].join(",");
    }

    Object.keys(selectedFilters).forEach((key) => {
      if (
        key !== "Lọc theo loại sản phẩm" &&
        key !== "Lọc theo thương hiệu" &&
        key !== "Lọc theo giá"
      ) {
        outFilters[key] = selectedFilters[key].join(",");
      }
    });

    setFilters(outFilters);
  }, [selectedFilters, setFilters]);

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

        {/* Hiển thị bộ lọc biến thể theo danh mục đã chọn */}
        {selectedCategory &&
          variantFilters[selectedCategory] &&
          variantFilters[selectedCategory].map((group, index) => (
            <div className="filter-group" key={group.title}>
              <h4 className="filter-group__title">{group.title}</h4>
              {group.type === 'color' ? (
                <div className="color-options">
                  {group.options.map((color, idx) => (
                    <span
                      key={idx}
                      className={`color-option ${color.class}`}
                      title={color.label}
                      onClick={() => handleCheckboxChange(group.title, color.label)}
                      style={{
                        border: selectedFilters[group.title]?.includes(color.label)
                          ? '2px solid #333'
                          : '1px solid #ccc',
                        width: 22,
                        height: 22,
                        borderRadius: "50%",
                        display: "inline-block",
                        marginRight: 8,
                        cursor: "pointer",
                        background:
                          color.class === "red" ? "#e53935" :
                          color.class === "blue" ? "#1976d2" :
                          color.class === "green" ? "#43a047" :
                          color.class === "black" ? "#222" :
                          color.class === "white" ? "#fff" :
                          color.class === "yellow" ? "#fbc02d" : "#eee",
                      }}
                    ></span>
                  ))}
                </div>
              ) : (
                <ul className="scrollable-list">
                  {group.options.map((option, idx) => (
                    <li key={idx}>
                      <label>
                        <input
                          type="checkbox"
                          checked={selectedFilters[group.title]?.includes(option) || false}
                          onChange={() => handleCheckboxChange(group.title, option)}
                        />{' '}
                        {option}
                      </label>
                    </li>
                  ))}
                </ul>
              )}
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
