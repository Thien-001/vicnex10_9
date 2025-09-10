const SupportCard = ({ image, alt, title, description, buttonText }) => {
  return (
    <div className="support-card">
      <img src={image} alt={alt} />
      <h3>{title}</h3>
      <p dangerouslySetInnerHTML={{ __html: description }} />
      <button>{buttonText}</button>
    </div>
  );
};

export default SupportCard;
