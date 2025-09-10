import { useEffect } from "react";
import { useNavigate } from "react-router-dom";

export default function VnpayCallback({ setCartItems }) {
  const navigate = useNavigate();

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const code = params.get("vnp_ResponseCode");
    if (code === "00") {
      // Thanh toán thành công
      setCartItems && setCartItems([]);
      localStorage.removeItem("cart");
      navigate("/thankyou");
    } else {
      // Hủy/thất bại
      navigate("/cart");
    }
  }, [navigate, setCartItems]);

  return <div>Đang xử lý kết quả thanh toán...</div>;
}