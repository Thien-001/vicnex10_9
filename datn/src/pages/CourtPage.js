import React, { useEffect, useState } from 'react';
import axios from 'axios';
import Header from '../components/home/Header';
import Footer from '../components/home/Footer';
import CourtCard from '../components/court/CourtCard';
import { Link } from 'react-router-dom';

// Helper để format giờ
const formatTime = (hour, minute = 0) => {
  const h = hour.toString().padStart(2, '0');
  const m = minute.toString().padStart(2, '0');
  return `${h}:${m}`;
};

const TIME_SLOTS = {
  morning: Array.from({ length: 7 }, (_, i) => formatTime(i + 5)), // 05:00 - 11:00
  afternoon: Array.from({ length: 6 }, (_, i) => formatTime(i + 12)), // 12:00 - 17:00
  evening: Array.from({ length: 6 }, (_, i) => formatTime(i + 18)), // 18:00 - 23:00
};

const addOneHour = (time) => {
  const [h, m] = time.split(':').map(Number);
  const newHour = (h + 1) % 24;
  return formatTime(newHour, m);
};

export default function CourtPage() {
  const [courts, setCourts] = useState([]);
  const [selectedDate, setSelectedDate] = useState(() => {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = (today.getMonth() + 1).toString().padStart(2, '0');
    const dd = today.getDate().toString().padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
  });
  const [activeTab, setActiveTab] = useState('morning');

  useEffect(() => {
    const fetchCourts = async () => {
      try {
        const times = TIME_SLOTS[activeTab];
        const start_time = times[0] + ':00';
        const end_time = addOneHour(times[times.length - 1]) + ':00';

        const res = await axios.get('/api/courts', {
          params: {
            date: selectedDate,
            start_time,
            end_time
          }
        });
        setCourts((res.data.data || []).filter(court => court.Status === 1));
      } catch (err) {
        setCourts([]);
      }
    };

    fetchCourts();
  }, [selectedDate, activeTab]);

  // Gom sân theo địa điểm
  const courtsByLocation = courts.reduce((acc, court) => {
    const locationName = court.location?.name || 'Khác';
    if (!acc[locationName]) acc[locationName] = [];
    acc[locationName].push(court);
    return acc;
  }, {});

  // Sắp xếp để "Khác" ở cuối
  const sortedLocations = Object.keys(courtsByLocation).sort((a, b) => {
    if (a === 'Khác') return 1;
    if (b === 'Khác') return -1;
    return a.localeCompare(b, 'vi');
  });

  return (
    <>
      <Header />
      <main style={{ maxWidth: 1200, margin: '0 auto', padding: '32px 16px', marginBottom: 100 }}>
        <h1 style={{ marginBottom: 24, fontSize: 28, fontWeight: 700, color: "#0154b9" }}>
          Danh sách sân cầu lông
        </h1>
        <label style={{ marginBottom: 16, display: 'block', fontWeight: '600' }}>
          Chọn ngày:{' '}
          <input
            type="date"
            value={selectedDate}
            onChange={(e) => setSelectedDate(e.target.value)}
            min={(() => {
              const today = new Date();
              const yyyy = today.getFullYear();
              const mm = (today.getMonth() + 1).toString().padStart(2, '0');
              const dd = today.getDate().toString().padStart(2, '0');
              return `${yyyy}-${mm}-${dd}`;
            })()}
            style={{ padding: '6px 10px', fontSize: 16, borderRadius: 6, border: '1px solid #ccc' }}
          />
        </label>
        <div style={{ display: 'flex', gap: 12, marginBottom: 24 }}>
          {Object.keys(TIME_SLOTS).map((slotKey) => (
            <button
              key={slotKey}
              onClick={() => setActiveTab(slotKey)}
              style={{
                padding: '8px 20px',
                borderRadius: 9999,
                fontWeight: 600,
                cursor: 'pointer',
                backgroundColor: activeTab === slotKey ? '#2563eb' : '#e5e7eb',
                color: activeTab === slotKey ? '#fff' : '#374151',
                border: 'none',
                transition: 'all 0.3s ease',
                userSelect: 'none',
              }}
              onMouseEnter={e => {
                if (activeTab !== slotKey) e.target.style.backgroundColor = '#3b82f6';
                if (activeTab !== slotKey) e.target.style.color = '#fff';
              }}
              onMouseLeave={e => {
                if (activeTab !== slotKey) e.target.style.backgroundColor = '#e5e7eb';
                if (activeTab !== slotKey) e.target.style.color = '#374151';
              }}
            >
              {{
                morning: 'Buổi sáng',
                afternoon: 'Buổi chiều',
                evening: 'Buổi tối'
              }[slotKey]}
            </button>
          ))}
        </div>
        {courts.length === 0 ? (
          <p>Không có sân nào trong hệ thống.</p>
        ) : (
          sortedLocations.map(locationName => (
            <div key={locationName} style={{ marginBottom: 36 }}>
              <h2 style={{ color: "#0154b9", fontWeight: 600, marginBottom: 16 }}>{locationName}</h2>
              <div className="court-grid" style={{
                display: 'grid',
                gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))',
                gap: 32,
              }}>
                {courtsByLocation[locationName].map(court => (
                  <CourtCard key={court.Courts_ID} court={court} />
                ))}
              </div>
            </div>
          ))
        )}
      </main>
      <Footer />
      <style>{`
        .court-card {
          background: #fff;
          border-radius: 14px;
          box-shadow: 0 4px 24px #0154b91a;
          overflow: hidden;
          display: flex;
          flex-direction: column;
          transition: box-shadow 0.18s, transform 0.18s;
        }
        .court-card:hover {
          box-shadow: 0 8px 32px #0154b933;
          transform: translateY(-4px) scale(1.03);
        }
        .court-card-img {
          width: 100%;
          height: 160px;
          object-fit: cover;
          border-radius: 14px 14px 0 0;
        }
        .court-card-info {
          padding: 16px 14px 14px 14px;
          flex: 1;
          display: flex;
          flex-direction: column;
          gap: 6px;
        }
        .court-card-title {
          font-size: 1.1rem;
          font-weight: 700;
          color: #1a237e;
          margin-bottom: 2px;
        }
        .court-card-meta {
          font-size: 14px;
          color: #1976d2;
        }
        .court-card-price {
          font-size: 15px;
          color: #e53935;
          font-weight: 700;
        }
        .court-card-time {
          font-size: 14px;
          color: #0154b9;
        }
        .court-card-action {
          margin-top: 10px;
        }
        .court-card-btn {
          background: linear-gradient(90deg, #0154b9 0%, #3bb2ff 100%);
          color: #fff;
          border: none;
          border-radius: 8px;
          padding: 8px 20px;
          font-weight: 700;
          font-size: 15px;
          cursor: pointer;
          text-decoration: none;
          transition: background 0.18s, transform 0.18s;
          display: inline-block;
        }
        .court-card-btn:hover {
          background: linear-gradient(90deg, #3bb2ff 0%, #0154b9 100%);
          transform: scale(1.06);
        }
        @media (max-width: 600px) {
          .court-grid { grid-template-columns: 1fr; gap: 16px; }
          .court-card-img { height: 100px; }
        }
      `}</style>
    </>
  );
}
