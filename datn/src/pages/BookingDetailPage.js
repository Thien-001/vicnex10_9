import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axios from "axios";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import BookingCourtInfo from "../components/BookingDetailPage/BookingCourtInfo";
import BookingDatePicker from "../components/BookingDetailPage/BookingDatePicker";
import BookingSlotSelector from "../components/BookingDetailPage/BookingSlotSelector";
import BookingNote from "../components/BookingDetailPage/BookingNote";
import BookingAction from "../components/BookingDetailPage/BookingAction";

export default function BookingDetailPage() {
  const { courtId } = useParams();
  const navigate = useNavigate();
  const [court, setCourt] = useState(null);
  const [bookings, setBookings] = useState([]);
  const [selectedDate, setSelectedDate] = useState(() => {
    const now = new Date();
    const yyyy = now.getFullYear();
    const mm = (now.getMonth() + 1).toString().padStart(2, '0');
    const dd = now.getDate().toString().padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
  });
  const [selectedStartSlot, setSelectedStartSlot] = useState('');
  const [selectedEndSlot, setSelectedEndSlot] = useState('');
  const [note, setNote] = useState('');
  const [loading, setLoading] = useState(false);
  const [user] = useState(() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch {
      return null;
    }
  });

  // Lấy thông tin chi tiết sân
  useEffect(() => {
    axios.get(`/api/courts/${courtId}`)
      .then(res => setCourt(res.data?.data || res.data))
      .catch(() => setCourt(null));
  }, [courtId]);

  // Lấy lịch đặt của sân theo ngày
  useEffect(() => {
    if (!court || !selectedDate) return;
    axios.get('/api/court_bookings', {
      params: { court_id: court.Courts_ID, date: selectedDate }
    })
      .then(res => setBookings(Array.isArray(res.data?.data) ? res.data.data : res.data))
      .catch(() => setBookings([]));
  }, [court, selectedDate]);

  // Tạo allSlots và bookedSlots cho BookingSlotSelector
  const getAllSlots = () => {
    if (!court) return [];
    const open = court.open_time ? parseInt(court.open_time.split(':')[0]) : 6;
    const close = court.close_time ? parseInt(court.close_time.split(':')[0]) : 22;
    const slots = [];
    for (let h = open; h < close; h++) {
      slots.push(`${h.toString().padStart(2, '0')}:00`);
    }
    return slots;
  };
  const allSlots = getAllSlots();
  const bookedSlots = bookings.map(b => b.Start_time.slice(0,5));

  // Hàm đặt sân
  const handleBooking = () => {
    if (!user) {
      alert('Vui lòng đăng nhập để đặt sân!');
      return;
    }
    if (!selectedStartSlot || !selectedEndSlot) {
      alert('Vui lòng chọn khung giờ!');
      return;
    }
    const startHour = parseInt(selectedStartSlot.split(':')[0]);
    const endHour = parseInt(selectedEndSlot.split(':')[0]);
    const duration = endHour - startHour + 1;
    const booking = {
      Courts_ID: court.Courts_ID,
      CourtName: court.Name,
      Court_type: court.Court_type,
      Location: court.location?.name + " - " + court.location?.address,
      Booking_date: selectedDate,
      Start_time: selectedStartSlot + ':00',
      End_time: (endHour + 1).toString().padStart(2, '0') + ':00',
      Duration_hours: duration,
      Price_per_hour: court.Price_per_hour,
      Total_price: court.Price_per_hour * duration,
      Note: note,
      Status: true
    };
    localStorage.setItem('pendingBooking', JSON.stringify(booking));
    navigate('/checkout');
  };

  return (
    <>
      <Header />
      <main>
        <h1 className="booking-title" style={{textAlign: "center", margin: "32px 0 24px 0", color: "#0154b9", fontWeight: 800, fontSize: "2.1rem", letterSpacing: "-1px"}}>
          Thông tin chi tiết sân
        </h1>
        {court ? (
          <div className="booking-detail-container">
            <BookingCourtInfo court={court} />
            <div className="booking-detail-info-box">
              <BookingDatePicker selectedDate={selectedDate} setSelectedDate={setSelectedDate} />
              <BookingSlotSelector
                allSlots={allSlots}
                bookedSlots={bookedSlots}
                selectedStartSlot={selectedStartSlot}
                setSelectedStartSlot={setSelectedStartSlot}
                selectedEndSlot={selectedEndSlot}
                setSelectedEndSlot={setSelectedEndSlot}
                loading={loading}
                selectedDate={selectedDate}
              />
              <BookingNote note={note} setNote={setNote} loading={loading} />
              <BookingAction
                onBooking={handleBooking}
                loading={loading}
                disabled={!selectedStartSlot || !selectedEndSlot}
              />
            </div>
          </div>
        ) : (
          <div style={{textAlign: "center", color: "#888", margin: "40px 0"}}>Đang tải dữ liệu...</div>
        )}
      </main>
      <Footer />
    </>
  );
}