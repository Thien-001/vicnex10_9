// filepath: /home/titus/Documents/GitHub/datn1/datn/src/components/checkout/LeafletMapPicker.js
import React, { useEffect } from "react";
import { MapContainer, TileLayer, Marker, useMapEvents, useMap } from "react-leaflet";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// Icon marker đẹp màu xanh
const customIcon = new L.Icon({
  iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
  iconSize: [38, 38],
  iconAnchor: [19, 38],
  popupAnchor: [0, -38],
});

function LocationPicker({ latitude, longitude, setLocation }) {
  useMapEvents({
    click(e) {
      setLocation({ latitude: e.latlng.lat, longitude: e.latlng.lng });
    },
  });
  return latitude && longitude ? (
    <Marker position={[latitude, longitude]} icon={customIcon} />
  ) : null;
}

// Thêm component này để pan/zoom khi vị trí thay đổi
function MapAutoPan({ latitude, longitude }) {
  const map = useMap();
  useEffect(() => {
    if (latitude && longitude) {
      map.setView([latitude, longitude], 17, { animate: true });
    }
  }, [latitude, longitude, map]);
  return null;
}

function LeafletMapPicker({ latitude, longitude, setLocation }) {
  return (
    <MapContainer
      center={[latitude || 10.762622, longitude || 106.660172]}
      zoom={latitude && longitude ? 17 : 13}
      style={{
        height: 320,
        borderRadius: 18,
        boxShadow: "0 8px 32px rgba(1,84,185,0.12)",
        border: "2px solid #3bb2ff",
        marginBottom: 16,
      }}
    >
      <TileLayer
        url="https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png"
        attribution="&copy; CartoDB"
      />
      <LocationPicker latitude={latitude} longitude={longitude} setLocation={setLocation} />
      <MapAutoPan latitude={latitude} longitude={longitude} />
    </MapContainer>
  );
}

export default LeafletMapPicker;