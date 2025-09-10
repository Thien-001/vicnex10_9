import React, { useEffect, useState } from 'react';
import axios from 'axios';
import Header from '../components/home/Header';
import Footer from '../components/home/Footer';
import { useNavigate, useLocation } from 'react-router-dom';
import LocationList from '../components/booking/LocationList';

export default function BookingPage() {
  const navigate = useNavigate();
  const location = useLocation();

  // Redirect nếu có query court_id
  React.useEffect(() => {
    const params = new URLSearchParams(location.search);
    const courtId = params.get('court_id');
    if (courtId) {
      navigate(`/booking/${courtId}`, { replace: true });
    }
  }, [location, navigate]);

  const [locations, setLocations] = useState([]);
  const [courts, setCourts] = useState([]);

  // Lấy danh sách địa điểm và sân
  useEffect(() => {
    axios.get('/api/locations').then(res => {
      setLocations(Array.isArray(res.data.data) ? res.data.data : []);
    });
    axios.get('/api/courts').then(res => {
      setCourts(Array.isArray(res.data.data) ? res.data.data : []);
    });
  }, []);

  // Gom sân theo địa điểm
  const courtsByLocation = locations.map(loc => ({
    ...loc,
    courts: courts.filter(c => c.location?.id === loc.id)
  }));

  return (
    <>
      <Header />
      <main className="booking-container">
        <h1 className="booking-title">Đặt sân cầu lông</h1>
        {courts.length === 0 || locations.length === 0 ? (
          <div style={{textAlign: "center", color: "#888", margin: "40px 0"}}>Đang tải dữ liệu...</div>
        ) : (
          <LocationList
            courtsByLocation={courtsByLocation}
            selectedCourt={null}
            setSelectedCourt={court => {
              navigate(`/booking/${court.Courts_ID}`);
            }}
          />
        )}
      </main>
      <Footer />
    </>
  );
}
