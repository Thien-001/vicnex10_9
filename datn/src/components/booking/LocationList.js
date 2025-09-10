import React from "react";

const LocationList = ({ courtsByLocation, selectedCourt, setSelectedCourt }) => (
  <div className="location-list">
    {courtsByLocation.map(loc => (
      <div className="location-row" key={loc.id}>
        <div className="location-name">{loc.name}</div>
        <div className="location-courts">
          {loc.courts.length === 0 ? (
            <div className="court-card court-card-empty">Chưa có sân nào</div>
          ) : (
            loc.courts.map(court => (
              <div
                className={`court-card${selectedCourt?.Courts_ID === court.Courts_ID ? ' selected' : ''}`}
                key={court.Courts_ID}
                onClick={() => setSelectedCourt(court)}
              >
                <img
                  src={court.Image || "/no-image.png"}
                  alt={court.Name}
                  className="court-card-img"
                />
                <div className="court-card-name">{court.Name}</div>
                <div className="court-card-type">{court.Court_type}</div>
                <div className="court-card-price">
                  {Number(court.Price_per_hour).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })} / giờ
                </div>
              </div>
            ))
          )}
        </div>
      </div>
    ))}
  </div>
);

export default LocationList;