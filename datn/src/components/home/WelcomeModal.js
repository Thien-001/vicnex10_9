const WelcomeModal = ({ imageUrl, alt, onClose }) => {
  if (!imageUrl) return null;
  return (
    <div className="welcome-modal-overlay">
      <div className="welcome-modal-content">
        <button className="welcome-modal-close" onClick={onClose}>×</button>
        <img src={imageUrl} alt={alt || "Thông báo"} />
      </div>
    </div>
  );
};
export default WelcomeModal;