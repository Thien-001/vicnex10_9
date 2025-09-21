import React from "react";
import { Link } from "react-router-dom";

function ArticleCard({ article }) {
  // Xử lý đường dẫn ảnh
  const getImageUrl = (thumbnail) => {
    if (!thumbnail) return "/img/default-article.jpg";
    if (thumbnail.startsWith("http")) return thumbnail;
    return `http://localhost:8000/${thumbnail}`;
  };

  // Format ngày tháng
  const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
      day: '2-digit',
      month: '2-digit', 
      year: 'numeric'
    });
  };

  // Tạo excerpt nếu không có
  const getExcerpt = (content, excerpt) => {
    if (excerpt) return excerpt;
    if (!content) return "Nội dung bài viết sẽ được cập nhật sớm...";
    
    // Remove HTML tags và lấy 150 ký tự đầu
    const textContent = content.replace(/<[^>]*>/g, '').trim();
    return textContent.length > 150 
      ? textContent.substring(0, 150) + "..."
      : textContent;
  };

  return (
    <article style={{
      background: "linear-gradient(135deg, #ffffff 0%, #f8faff 100%)",
      borderRadius: 16,
      padding: 0,
      width: "100%",
      maxWidth: 320,
      minHeight: 480,
      boxShadow: "0 8px 32px rgba(1, 84, 185, 0.12)",
      display: "flex",
      flexDirection: "column",
      overflow: "hidden",
      transition: "all 0.3s ease",
      border: "1px solid rgba(1, 84, 185, 0.08)",
      position: "relative",
      marginTop: -60,
    }}
    onMouseEnter={(e) => {
      e.currentTarget.style.transform = "translateY(-8px)";
      e.currentTarget.style.boxShadow = "0 20px 48px rgba(1, 84, 185, 0.2)";
    }}
    onMouseLeave={(e) => {
      e.currentTarget.style.transform = "translateY(0)";
      e.currentTarget.style.boxShadow = "0 8px 32px rgba(1, 84, 185, 0.12)";
    }}
    >
      {/* Image Container */}
      <div style={{ 
        position: "relative", 
        overflow: "hidden",
        height: 200
      }}>
        <img
          src={getImageUrl(article.Thumbnail)}
          alt={article.Title}
          style={{
            width: "100%",
            height: "100%",
            objectFit: "cover",
            transition: "transform 0.3s ease"
          }}
          onMouseEnter={(e) => {
            e.currentTarget.style.transform = "scale(1.1)";
          }}
          onMouseLeave={(e) => {
            e.currentTarget.style.transform = "scale(1)";
          }}
          onError={(e) => {
            e.target.src = "/img/default-article.jpg";
          }}
        />
        
        {/* Gradient Overlay */}
        <div style={{
          position: "absolute",
          bottom: 0,
          left: 0,
          right: 0,
          height: "50%",
          background: "linear-gradient(to top, rgba(0,0,0,0.4), transparent)",
          pointerEvents: "none",
        }} />

        {/* View Count Badge */}
        {article.View > 0 && (
          <div style={{
            position: "absolute",
            top: 12,
            right: 12,
            background: "rgba(0,0,0,0.7)",
            color: "white",
            padding: "4px 8px",
            borderRadius: 12,
            fontSize: 12,
            fontWeight: 600,
            display: "flex",
            alignItems: "center",
            gap: 4
          }}>
            <span>👁️</span>
            {article.View.toLocaleString()}
          </div>
        )}

        {/* Featured Badge */}
        {article.Is_Featured === 1 && (
          <div style={{
            position: "absolute",
            top: 12,
            left: 12,
            background: "linear-gradient(90deg, #ff6b6b, #feca57)",
            color: "white",
            padding: "4px 8px",
            borderRadius: 12,
            fontSize: 12,
            fontWeight: 700,
            display: "flex",
            alignItems: "center",
            gap: 4
          }}>
            <span>⭐</span>
            HOT
          </div>
        )}
      </div>

      {/* Content */}
      <div style={{ 
        padding: "24px", 
        flex: 1, 
        display: "flex", 
        flexDirection: "column",
        justifyContent: "space-between"
      }}>
        {/* Header Info */}
        <div>
          {/* Date */}
          <div style={{ 
            color: "#64748b", 
            fontSize: 13, 
            marginBottom: 12,
            display: "flex",
            alignItems: "center",
            gap: "6px",
            fontWeight: 500
          }}>
            <span>📅</span>
            {formatDate(article.Created_at)}
          </div>

          {/* Title */}
          <h2 style={{
            fontSize: 18,
            fontWeight: 700,
            margin: "0 0 12px 0",
            lineHeight: "1.4",
            color: "#1a202c",
            display: "-webkit-box",
            WebkitLineClamp: 2,
            WebkitBoxOrient: "vertical",
            overflow: "hidden",
            minHeight: "50px"
          }}>
            <Link 
              to={`/article/${article.Post_ID}`} 
              style={{ 
                color: "inherit", 
                textDecoration: "none",
                transition: "color 0.2s ease"
              }}
              onMouseEnter={(e) => e.target.style.color = "#0154b9"}
              onMouseLeave={(e) => e.target.style.color = "#1a202c"}
            >
              {article.Title}
            </Link>
          </h2>

          {/* Excerpt */}
          <div style={{
            color: "#4a5568",
            fontSize: 14,
            lineHeight: "1.6",
            marginBottom: 20,
            display: "-webkit-box",
            WebkitLineClamp: 3,
            WebkitBoxOrient: "vertical",
            overflow: "hidden",
            minHeight: "63px",
            maxHeight: "63px"
          }}>
            {getExcerpt(article.Content, article.Excerpt)}
          </div>
        </div>

        {/* Read More Button */}
        <div style={{ marginTop: "auto" }}>
          <Link 
            to={`/article/${article.Post_ID}`} 
            style={{
              display: "inline-flex",
              alignItems: "center",
              justifyContent: "center",
              gap: "8px",
              color: "white",
              background: "linear-gradient(90deg, #0154b9, #3bb2ff)",
              padding: "12px 24px",
              borderRadius: 25,
              textDecoration: "none",
              fontWeight: 600,
              fontSize: 14,
              transition: "all 0.3s ease",
              boxShadow: "0 4px 12px rgba(1, 84, 185, 0.3)",
              width: "100%"
            }}
            onMouseEnter={(e) => {
              e.target.style.transform = "translateY(-2px)";
              e.target.style.boxShadow = "0 6px 20px rgba(1, 84, 185, 0.4)";
            }}
            onMouseLeave={(e) => {
              e.target.style.transform = "translateY(0)";
              e.target.style.boxShadow = "0 4px 12px rgba(1, 84, 185, 0.3)";
            }}
          >
            Đọc tiếp <span>→</span>
          </Link>
        </div>
      </div>
    </article>
  );
}

export default ArticleCard;