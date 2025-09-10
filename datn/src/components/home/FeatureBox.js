const FeatureBox = ({ title, lines }) => (
  <div className="feature-box">
    <h3>{title}</h3>
    {lines.map((line, i) => (
      <p key={i}>{line}</p>
    ))}
  </div>
);

export default FeatureBox;