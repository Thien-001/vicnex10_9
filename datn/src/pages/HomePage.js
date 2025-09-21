import React, { useEffect, useState } from "react";
import "../App.css";

// Import các component con trong trang Home
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import Banner from "../components/home/Banner";
import FlashSale from "../components/home/FlashSale";
import ReviewList from "../components/home/ReviewList";
import ProductGrid from "../components/home/ProductGrid";
import FaqSection from "../components/home/faq/FaqSection";
import FeatureSection from "../components/home/FeatureSection";
import SectionHeading from "../components/home/SectionHeading";
import SpecialOfferBox from "../components/home/SpecialOfferBox";
import ProductCategories from "../components/home/ProductCategories";
import ProductGridBestSeller from "../components/home/ProductGrid copy";
import ChatBotBadminton from "../components/ChatBotBadminton"; // Thêm dòng này
import WelcomeModal from "../components/home/WelcomeModal";

const API_URL = "http://localhost:8000";

function HomePage() {
  const [popup, setPopup] = useState(null);
  const [showModal, setShowModal] = useState(true);

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, []);

  useEffect(() => {
    fetch(`${API_URL}/api/popup`)
      .then((res) => res.json())
      .then((data) => setPopup(data))
      .catch((err) => {
        console.error("Lỗi fetch popup:", err);
        setShowModal(false);
      });
  }, []);

  return (
    <div>
      {/* Phần đầu trang, menu, logo */}
      <Header />

      {/* Banner chính lớn, thường là ảnh slider hoặc quảng cáo */}
      <Banner />

      {/* Phần giới thiệu các tính năng nổi bật */}
      <FeatureSection />

      {/* Tiêu đề phụ trang cho phần danh mục sản phẩm */}
      <SectionHeading
        title="Danh Mục Sản Phẩm Chính"
        subtitle="Sản phẩm đa dạng – Chất lượng chính hãng – Mua sắm tiện lợi"
      />
      {/* Hiển thị các danh mục sản phẩm chính */}
      <ProductCategories />

      {/* Tiêu đề phụ cho phần khuyến mãi */}
      <SectionHeading
        title="Khuyến Mãi Hot Tháng 6"
        subtitle="Giảm giá lên tới 50% tất cả sản phẩm cầu lông"
      />
      {/* Lưới hiển thị sản phẩm */}
      <ProductGrid />

        {/* Flash Sale - hiển thị 1 sản phẩm đang giảm giá */}
        <FlashSale
          image="img/product/hinh10.png"
          alt="Flash Sale"
        productName="Vợt Cầu Lông Yonex Astrox 88D Pro"
        priceSale="2.990.000₫"
        priceOld="3.500.000₫"
        discount="-15%"
        endTime="2025-06-10T23:59:59" // chuỗi thời gian ISO, trong FlashSale cần xử lý thành Date
      />

      {/* Tiêu đề phụ cho sản phẩm nổi bật */}
      <SectionHeading
        title="Sản Phẩm Nổi Bật"
        subtitle="Gợi ý những sản phẩm đang được khách hàng ưa chuộng nhất, bán chạy nhất trong tháng."
      />
      {/* Lưới hiển thị sản phẩm nổi bật */}
      <ProductGrid />

      {/* Tiêu đề phụ phần đánh giá khách hàng */}
      <SectionHeading
        title="Khách hàng nói gì về chúng tôi?"
        subtitle="Gợi ý những sản phẩm đang được khách hàng ưa chuộng nhất, bán chạy nhất trong tháng."
      />
      {/* Danh sách review */}
      <ReviewList />

      {/* Tiêu đề phụ phần sản phẩm bán chạy nhất */}
      <SectionHeading
        title="Top sản phẩm nhiều lượt mua"
        subtitle="Gợi ý những sản phẩm đang được khách hàng ưa chuộng nhất, bán chạy nhất shop."
      />
      {/* Lưới hiển thị sản phẩm bán chạy nhất */}
      <ProductGridBestSeller type="is_best_seller" />

      {/* Tiêu đề phụ phần FAQ */}
      <SectionHeading
        title="Khách hàng thường hỏi gì"
        subtitle="Chúng tôi sẽ hỗ trợ hết mình để bạn có một trải nghiệm tốt nhất"
      />
      {/* Phần hỏi đáp */}
      <FaqSection />

      {/* Box ưu đãi đặc biệt */}
      <SpecialOfferBox />

      {/* Phần footer cuối trang */}
      <Footer />
      <ChatBotBadminton /> {/* Thêm dòng này để hiển thị chatbox robot */}

      {/* Hiển thị popup chào mừng nếu có */}
      {showModal && popup && popup.image_url && (
        <WelcomeModal
          imageUrl={`${API_URL}/popup-image/${popup.image_url.split('/').pop()}`}
          alt={popup.title || "Thông báo"}
          onClose={() => setShowModal(false)}
        />
      )}
    </div>
  );
}

export default HomePage;
