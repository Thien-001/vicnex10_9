import React from "react";

function ArticleHeader({ article }) {
  return (
    <header style={{ 
      marginBottom: 48,
      position: "relative"
    }}>
      {/* Article Meta Info */}
      <div style={{
        display: "flex",
        flexDirection: "column",
        gap: 24,
        marginBottom: 40 // SPACE TRƯỚC HÌNH ẢNH
      }}>
        {/* Category Badge */}
        {article.category && (
          <div style={{
            display: "inline-block",
            alignSelf: "flex-start",
            background: "linear-gradient(90deg, #0154b9, #3bb2ff)",
            color: "white",
            borderRadius: 25,
            padding: "10px 24px",
            fontSize: 15,
            fontWeight: 700,
            letterSpacing: 0.5,
            textTransform: "uppercase",
            boxShadow: "0 6px 20px rgba(1,84,185,0.3)",
            marginBottom: 8
          }}>
            <span style={{ marginRight: 8 }}>📁</span>
            {article.category}
          </div>
        )}

        {/* META INFO BLOCK - TRƯỚC TITLE */}
        <div style={{
          display: "flex",
          alignItems: "center",
          flexWrap: "wrap",
          gap: 24,
          padding: "20px 28px",
          background: "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
          borderRadius: 16,
          border: "2px solid rgba(1,84,185,0.1)",
          boxShadow: "0 6px 24px rgba(1,84,185,0.08)",
          marginBottom: 16
        }}>
          {/* Published Date */}
          <div style={{
            display: "flex",
            alignItems: "center",
            gap: 10,
            color: "#4a5568",
            fontSize: 16,
            fontWeight: 600
          }}>
            <span style={{ 
              fontSize: "22px",
              background: "linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%)",
              borderRadius: "50%",
              width: 36,
              height: 36,
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              boxShadow: "0 3px 12px rgba(1,84,185,0.15)"
            }}>
              📅
            </span>
            <span>
              {article.date ? new Date(article.date).toLocaleDateString('vi-VN', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
              }) : "Thứ Năm, 21 tháng 8, 2025"}
            </span>
          </div>

          {/* Reading Time */}
          <div style={{
            display: "flex",
            alignItems: "center",
            gap: 10,
            color: "#4a5568",
            fontSize: 16,
            fontWeight: 600
          }}>
            <span style={{ 
              fontSize: "22px",
              background: "linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%)",
              borderRadius: "50%",
              width: 36,
              height: 36,
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              boxShadow: "0 3px 12px rgba(76,175,80,0.15)"
            }}>
              ⏱️
            </span>
            <span>5-7 phút đọc</span>
          </div>

          {/* View Count */}
          <div style={{
            display: "flex",
            alignItems: "center",
            gap: 10,
            color: "#4a5568",
            fontSize: 16,
            fontWeight: 600
          }}>
            <span style={{ 
              fontSize: "22px",
              background: "linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%)",
              borderRadius: "50%",
              width: 36,
              height: 36,
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              boxShadow: "0 3px 12px rgba(255,152,0,0.15)"
            }}>
              👁️
            </span>
            <span>{article.views || "1,234"} lượt xem</span>
          </div>
        </div>

        {/* Title - SAU META INFO, TRƯỚC HÌNH ẢNH */}
        <h1 style={{
          fontSize: 42,
          fontWeight: 900,
          margin: 0,
          lineHeight: 1.2,
          color: "#1a202c",
          letterSpacing: "-1px",
          background: "linear-gradient(135deg, #1a202c 0%, #2d3748 100%)",
          WebkitBackgroundClip: "text",
          WebkitTextFillColor: "transparent",
          backgroundClip: "text",
          textShadow: "none",
          marginBottom: 8
        }}>
          {article.title}
        </h1>

        {/* Article Description/Excerpt - TRƯỚC HÌNH ẢNH */}
        {article.excerpt && (
          <div style={{
            background: "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
            borderRadius: 16,
            padding: "24px 28px",
            border: "1px solid rgba(1,84,185,0.1)",
            boxShadow: "0 4px 16px rgba(1,84,185,0.08)",
            position: "relative"
          }}>
            <div style={{
              position: "absolute",
              top: -2,
              left: 28,
              width: 4,
              height: "calc(100% + 4px)",
              background: "linear-gradient(90deg, #0154b9, #3bb2ff)",
              borderRadius: 2
            }} />
            <p style={{
              fontSize: 18,
              lineHeight: 1.6,
              color: "#4a5568",
              margin: 0,
              fontStyle: "italic",
              fontWeight: 500
            }}>
              "{article.excerpt}"
            </p>
          </div>
        )}
      </div>

      {/* Cover Image Container - ĐẶT SAU TẤT CẢ */}
      {article.cover && (
        <div style={{
          position: "relative",
          width: "100%",
          height: 400,
          borderRadius: 20,
          overflow: "hidden",
          boxShadow: "0 12px 40px rgba(1,84,185,0.15)",
          background: "linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%)"
        }}>
          <img
            src={article.cover}
            alt={article.title}
            style={{
              width: "100%",
              height: "100%",
              objectFit: "cover",
              transition: "transform 0.5s ease"
            }}
            onMouseEnter={(e) => {
              e.target.style.transform = "scale(1.05)";
            }}
            onMouseLeave={(e) => {
              e.target.style.transform = "scale(1)";
            }}
            onError={(e) => {
              e.target.style.display = "none";
              e.target.parentElement.innerHTML = `
                <div style="
                  width: 100%; 
                  height: 100%; 
                  display: flex; 
                  flex-direction: column;
                  align-items: center; 
                  justify-content: center;
                  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                  color: #64748b;
                ">
                  <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.6;">📄</div>
                  <div style="font-size: 18px; font-weight: 600;">Hình ảnh bài viết</div>
                </div>
              `;
            }}
          />
          
          {/* Gradient Overlay */}
          <div style={{
            position: "absolute",
            bottom: 0,
            left: 0,
            right: 0,
            height: "40%",
            background: "linear-gradient(to top, rgba(0,0,0,0.3), transparent)",
            pointerEvents: "none"
          }} />
        </div>
      )}
    </header>
  );
}

export default ArticleHeader;