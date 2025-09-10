import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import Thankyou from "../components/thankyou/Thankyou";
import Hotmonthproduct from "../components/product/Hotmonthproduct";
import RecomendProduct from "../components/product/RecomendProduct";
import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";

export default function ThanhyouPage() {
  const [hotProducts, setHotProducts] = useState([]);
  const location = useLocation();
  const products = location.state?.products || []; // lấy sản phẩm vừa mua
  const booking = location.state?.booking; // lấy thông tin đặt sân nếu có

  useEffect(() => {
    fetch("http://localhost:8000/api/products?is_hot=1")
      .then((res) => res.json())
      .then((data) => setHotProducts(data.data || []));
  }, []);

  return (
    <>
      <Header />
      <BreadcrumbNav current="Cảm ơn" />
      <Thankyou products={products} booking={booking} /> {/* truyền thêm booking */}
      <div>
        <Hotmonthproduct products={hotProducts} />
        <RecomendProduct />
      </div>
      <Footer />
    </>
  );
}