import { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import Carousel from "../components/product/Carousel";
import ProductList from "../components/product/ProductList";
import PromoBanner from "../components/product/PromoBanner";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import SupportSection from "../components/product/SupportSection";
import FilterSidebar from "../components/product/FilterSidebar";
import SectionHeading from "../components/home/SectionHeading";
import RecentlyViewed from "../components/product/RecentlyViewed";
import Hotmonthproduct from "../components/product/Hotmonthproduct";
import RecomendProduct from "../components/product/RecomendProduct";
import CompareFloatingButton from "../components/product/CompareFloatingButton";
import CompareModal from "../components/product/CompareModal";
import CompareNoticeModal from "../components/product/CompareNoticeModal";
import ProductSortDropdown from "../components/product/ProductSortDropdown"; // import thêm dòng này

function ProductPage() {
  const [filters, setFilters] = useState({});
  const [allProducts, setAllProducts] = useState([]);
  const [filteredProducts, setFilteredProducts] = useState([]);
  const [page, setPage] = useState(1);
  const [hotProducts, setHotProducts] = useState([]);
  const [compareProducts, setCompareProducts] = useState([]);
  const [showCompare, setShowCompare] = useState(false);
  const [showNotice, setShowNotice] = useState(false);
  const [sort, setSort] = useState("default");

  const location = useLocation();
  const params = new URLSearchParams(location.search);
  const categoryId = params.get("category");

  // Fetch toàn bộ sản phẩm 1 lần
  useEffect(() => {
    fetch("http://localhost:8000/api/products")
      .then((res) => res.json())
      .then((data) => setAllProducts(data.data || []));
  }, []);

  // Lọc sản phẩm khi filter thay đổi
  useEffect(() => {
    let result = allProducts;

    // Lọc theo loại sản phẩm (danh mục + đặc biệt)
    if (filters["Lọc theo loại sản phẩm"] && filters["Lọc theo loại sản phẩm"].length > 0) {
      result = result.filter((p) => {
        return filters["Lọc theo loại sản phẩm"].some((type) => {
          // Lọc theo danh mục
          if (
            [
              "Vợt cầu lông",
              "Giày cầu lông",
              "Quần áo cầu lông",
              "Phụ kiện cầu lông",
              "Combo tiết kiệm"
            ].includes(type)
          ) {
            return p.category?.Name === type;
          }
          // Lọc đặc biệt
          if (type === "Hàng giảm giá") return Number(p.Discount_price) < Number(p.Price);
          if (type === "Mới về") return p.is_new === true || p.is_new === 1;
          if (type === "Top bán chạy") return p.is_best_seller === true || p.is_best_seller === 1;
          return false;
        });
      });
    }

    // Lọc theo thương hiệu
    if (filters["Lọc theo thương hiệu"] && filters["Lọc theo thương hiệu"].length > 0) {
      result = result.filter(
        (p) => filters["Lọc theo thương hiệu"].includes(p.Brand)
      );
    }

    // Lọc theo giá
    if (filters["Lọc theo giá"] && filters["Lọc theo giá"].length > 0) {
      result = result.filter((p) => {
        const price = Number(p.Discount_price || p.Price);
        return filters["Lọc theo giá"].some((range) => {
          if (range === "Dưới 500.000đ") return price < 500000;
          if (range === "500.000đ - 1.000.000đ") return price >= 500000 && price <= 1000000;
          if (range === "1.000.000đ - 2.000.000đ") return price > 1000000 && price <= 2000000;
          if (range === "Trên 2.000.000đ") return price > 2000000;
          return true;
        });
      });
    }

    // Lọc theo biến thể (ví dụ: cân nặng vợt)
    if (filters["Lọc theo cân nặng vợt"] && filters["Lọc theo cân nặng vợt"].length > 0) {
      result = result.filter((p) =>
        Array.isArray(p.variants) &&
        p.variants.some(variant =>
          filters["Lọc theo cân nặng vợt"].some(weight =>
            variant.Variant_name && variant.Variant_name.includes(weight)
          )
        )
      );
    }

    // ...lọc các biến thể khác tương tự...

    // Sắp xếp sản phẩm
    let sorted = [...result];
    if (sort === "price-asc") sorted.sort((a, b) => (a.Discount_price || a.Price) - (b.Discount_price || b.Price));
    if (sort === "price-desc") sorted.sort((a, b) => (b.Discount_price || b.Price) - (a.Discount_price || a.Price));
    if (sort === "newest") sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    if (sort === "bestseller") sorted.sort((a, b) => (b.is_best_seller || 0) - (a.is_best_seller || 0));
    setFilteredProducts(sorted);
  }, [filters, allProducts, sort]);

  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_hot=1")
      .then((res) => res.json())
      .then((data) => setHotProducts(data.data || []));
  }, []);

  const handleAddCompare = (product) => {
    if (compareProducts.find((p) => p.Product_ID === product.Product_ID)) return;
    if (compareProducts.length >= 2) return;
    setCompareProducts([...compareProducts, product]);
  };

  const handleRemoveCompare = (productId) => {
    setCompareProducts(compareProducts.filter((p) => p.Product_ID !== productId));
  };

  return (
    <div>
      <Header />
      <BreadcrumbNav />
      <SectionHeading
        title="TẤT CẢ SẢN PHẨM"
        subtitle="Tìm kiếm sản phẩm dễ dàng với bộ lọc thông minh!"
      />
      <div className="layout">
        <FilterSidebar setFilters={setFilters} />
        <div className="product-list-container">
          <ProductSortDropdown sort={sort} setSort={setSort} />
          <ProductList
            products={filteredProducts}
            sort={sort}
            page={page}
            filters={mapFilters(filters)}
            onAddCompare={handleAddCompare}
            compareProducts={compareProducts}
          />
        </div>
      </div>
      <Carousel page={page} setPage={setPage} />
      <PromoBanner />
      <RecentlyViewed />
      <Hotmonthproduct products={hotProducts} />
      <RecomendProduct />
      <SupportSection />
      <Footer />

      <CompareFloatingButton
        count={compareProducts.length}
        onClick={() => setShowCompare(true)}
        onEmptyCompare={() => setShowNotice(true)}
      />

      {showCompare && (
        <CompareModal
          products={compareProducts}
          onClose={() => setShowCompare(false)}
          onRemove={handleRemoveCompare}
        />
      )}

      {showNotice && (
        <CompareNoticeModal
          message="Vui lòng chọn ít nhất 1 sản phẩm để so sánh!"
          onClose={() => setShowNotice(false)}
        />
      )}
    </div>
  );
}

function mapFilters(filters) {
  const mapped = {};
  if (filters["Lọc theo loại sản phẩm"] && filters["Lọc theo loại sản phẩm"].length > 0) {
    mapped.category = filters["Lọc theo loại sản phẩm"][0];
  }
  if (filters["Lọc theo thương hiệu"] && filters["Lọc theo thương hiệu"].length > 0) {
    mapped.brand = filters["Lọc theo thương hiệu"].join(",");
  }
  if (filters["Lọc theo giá"] && filters["Lọc theo giá"].length > 0) {
    mapped.price = filters["Lọc theo giá"].join(",");
  }
  // ...map các filter khác nếu cần...
  return mapped;
}

export default ProductPage;
