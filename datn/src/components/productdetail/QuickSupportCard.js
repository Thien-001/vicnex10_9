const QuickSupportCard = ({ icon, title, description, note }) => {
  return (
    <div className="support-card">
      <h4>{icon} {title}</h4>
      <p>{description}</p>
      <small>{note}</small>
    </div>
  );
};

export default QuickSupportCard;
