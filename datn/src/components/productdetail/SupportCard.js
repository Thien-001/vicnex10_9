const SupportCard = ({ imgSrc, alt, title, description, buttonText }) => (
  <div className="support-card">
    <img src={imgSrc} alt={alt} />
    <h3>{title}</h3>
    <p>{description}</p>
    <button>{buttonText}</button>
  </div>
);

export default SupportCard;