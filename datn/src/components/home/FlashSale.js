import { useEffect, useState } from "react";

function getCountdown(startTime, endTime) {
  const now = new Date().getTime();
  const start = new Date(startTime).getTime();
  const end = new Date(endTime).getTime();

  if (now < start) {
    return { status: "Chưa bắt đầu" };
  }
  let diff = Math.max(0, Math.floor((end - now) / 1000));
  const days = Math.floor(diff / 86400);
  const hours = Math.floor((diff % 86400) / 3600);
  const minutes = Math.floor((diff % 3600) / 60);
  const seconds = diff % 60;
  return {
    days,
    hours: hours.toString().padStart(2, "0"),
    minutes: minutes.toString().padStart(2, "0"),
    seconds: seconds.toString().padStart(2, "0"),
    finished: diff === 0,
    status: diff === 0 ? "Đã kết thúc" : "Còn lại",
  };
}

export default function FlashSale() {
  const [flashSales, setFlashSales] = useState([]);
  const [countdowns, setCountdowns] = useState({});
  const [hovered, setHovered] = useState(null);

  useEffect(() => {
    fetch("http://localhost:8000/api/flash-sales")
      .then((res) => res.json())
      .then((data) => setFlashSales(data));
  }, []);

  useEffect(() => {
    if (flashSales.length === 0) return;
    const timer = setInterval(() => {
      const newCountdowns = {};
      flashSales.forEach((fs) => {
        newCountdowns[fs.id] = getCountdown(fs.start_time, fs.end_time);
      });
      setCountdowns(newCountdowns);
    }, 1000);
    return () => clearInterval(timer);
  }, [flashSales]);

  return (
    <div>
      <h2 className="flashsale-title">
        🎉 KHUYẾN MÃI FLASH SALE 🎉
      </h2>
      <div className="flashsale-grid">
        {flashSales.length === 0 && (
          <div>Không có khuyến mãi nào đang diễn ra.</div>
        )}
        {flashSales.map((fs) => (
          <div className="flashsale-box" key={fs.id}>
            <div className="flashsale-content">
              <div>
                <img
                  className="flashsale-image"
                  src={
                    fs.product?.Image
                      ? `/${fs.product.Image}`
                      : "/img/no-image.png"
                  }
                  alt={fs.product?.Name}
                />
              </div>
              <div className="flashsale-product-name">{fs.product?.Name}</div>
              <div className="flashsale-price-sale">
                {Number(fs.price_sale).toLocaleString()}₫
              </div>
              <div className="flashsale-price-old">
                {Number(fs.price_old).toLocaleString()}₫
              </div>
              {fs.discount && (
                <span className="flashsale-discount">-{fs.discount}%</span>
              )}
              <button
                className={
                  hovered === fs.id
                    ? "flashsale-buy-now flashsale-buy-now-hover"
                    : "flashsale-buy-now"
                }
                onMouseEnter={() => setHovered(fs.id)}
                onMouseLeave={() => setHovered(null)}
              >
                Mua ngay
              </button>
              <div className="flashsale-countdown">
                {countdowns[fs.id]?.status === "Chưa bắt đầu" ? (
                  <span className="flashsale-not-started">
                    Chưa bắt đầu
                  </span>
                ) : (
                  <>
                    <span className="flashsale-timebox">
                      {countdowns[fs.id]?.hours || "00"}
                    </span>
                    :
                    <span className="flashsale-timebox">
                      {countdowns[fs.id]?.minutes || "00"}
                    </span>
                    :
                    <span className="flashsale-timebox">
                      {countdowns[fs.id]?.seconds || "00"}
                    </span>
                    {countdowns[fs.id]?.days === 0 &&
                      parseInt(countdowns[fs.id]?.hours) < 1 &&
                      countdowns[fs.id]?.status !== "Đã kết thúc" && (
                        <span className="flashsale-ending-soon">
                          Sắp kết thúc!
                        </span>
                      )}
                    <span className="flashsale-status">
                      {countdowns[fs.id]?.status}
                    </span>
                  </>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}