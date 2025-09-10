// export default Banner;
import { useEffect, useState } from "react";

const API_URL = "http://localhost:8000";

const Banner = () => {
  const [banners, setBanners] = useState([]);
  const [index1, setIndex1] = useState(0);
  const [index2, setIndex2] = useState(0);
  const [index3, setIndex3] = useState(0);

  useEffect(() => {
    fetch(`${API_URL}/api/banners?is_active=1`)
      .then((res) => res.json())
      .then((data) => {
        setBanners(Array.isArray(data) ? data : []);
      });
  }, []);

  const banner1List = banners.filter((b) => b.position === 1);
  const banner2List = banners.filter((b) => b.position === 2);
  const banner3List = banners.filter((b) => b.position === 3);

  // Đổi ảnh banner 1 mỗi 5s nếu có nhiều ảnh
  useEffect(() => {
    if (banner1List.length > 1) {
      const interval1 = setInterval(() => {
        setIndex1((prev) => (prev + 1) % banner1List.length);
      }, 5000);
      return () => clearInterval(interval1);
    }
  }, [banner1List]);

  // Đổi ảnh banner 2 mỗi 5s
  useEffect(() => {
    if (banner2List.length > 1) {
      const interval2 = setInterval(() => {
        setIndex2((prev) => (prev + 1) % banner2List.length);
      }, 5000);
      return () => clearInterval(interval2);
    }
  }, [banner2List]);

  // Đổi ảnh banner 3 mỗi 5s
  useEffect(() => {
    if (banner3List.length > 1) {
      const interval3 = setInterval(() => {
        setIndex3((prev) => (prev + 1) % banner3List.length);
      }, 5000);
      return () => clearInterval(interval3);
    }
  }, [banner3List]);

  return (
    <div className="banner">
      <div className="banner-item item-1">
        {banner1List.length > 0 && (
          <img
            src={`${API_URL}/admin/banner-image/${banner1List[index1 % banner1List.length].id}`}
            alt="Banner 1"
            loading="lazy"
          />
        )}
      </div>
      <div className="banner-item item-2">
        {banner2List.length > 0 && (
          <img
            src={`${API_URL}/admin/banner-image/${banner2List[index2 % banner2List.length].id}`}
            alt="Banner 2"
            loading="lazy"
          />
        )}
      </div>
      <div className="banner-item item-3">
        {banner3List.length > 0 && (
          <img
            src={`${API_URL}/admin/banner-image/${banner3List[index3 % banner3List.length].id}`}
            alt="Banner 3"
            loading="lazy"
          />
        )}
      </div>
    </div>
  );
};

export default Banner;
