import React, { useEffect, useState } from "react";

function ArticleComments({ postId, user }) {
  const [comments, setComments] = useState([]);
  const [commentText, setCommentText] = useState("");
  const [message, setMessage] = useState("");

  useEffect(() => {
    fetch(`http://localhost:8000/api/posts/${postId}/comments`)
      .then(res => {
        if (!res.ok) throw new Error("Không lấy được bình luận!");
        return res.json();
      })
      .then(data => setComments(Array.isArray(data) ? data : []))
      .catch(() => setComments([]));
  }, [postId, message]);

  const handleSubmit = async () => {
    if (!user) {
      setMessage("Bạn cần đăng nhập để bình luận!");
      return;
    }
    if (!commentText.trim()) {
      setMessage("Vui lòng nhập nội dung bình luận!");
      return;
    }
    try {
      const response = await fetch(`http://localhost:8000/api/posts/${postId}/comments`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          User_ID: user.ID,
          text: commentText
        })
      });
      const data = await response.json();
      if (!response.ok || !data.comment) {
        setMessage(data?.message || "Bình luận thất bại!");
        return;
      }
      setMessage("Bình luận thành công!");
      setCommentText("");
      setComments(prev => [
        {
          ...data.comment,
          user: {
            Name: user.Name,
            Avatar: user.Avatar
          },
          created_at: new Date().toISOString()
        },
        ...prev
      ]);
    } catch (error) {
      setMessage("Bình luận thất bại!");
      console.error("Lỗi gửi bình luận:", error);
    }
  };

  return (
    <div
      style={{
        background: "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
        borderRadius: 16,
        padding: "32px", // GIẢM PADDING
        marginTop: 40,
        boxShadow: "0 8px 32px rgba(1,84,185,0.12)",
        border: "1px solid rgba(1,84,185,0.08)",
        width: "100%", // FULL WIDTH
        // REMOVE maxWidth, marginLeft, marginRight
      }}
    >
      <h3 style={{
        fontSize: 24, // TĂNG FONT SIZE
        fontWeight: 700,
        color: "#0154b9",
        marginBottom: 28,
        letterSpacing: 0.5,
        display: "flex",
        alignItems: "center",
        gap: 12
      }}>
        <span style={{ fontSize: "28px" }}>💬</span>
        Bình luận bài viết ({comments.length})
      </h3>
      
      {/* COMMENT FORM */}
      <div style={{ 
        marginBottom: 32,
        background: "#ffffff",
        borderRadius: 12,
        padding: "24px",
        boxShadow: "0 4px 16px rgba(1,84,185,0.08)",
        border: "1px solid rgba(1,84,185,0.06)"
      }}>
        <textarea
          value={commentText}
          onChange={e => setCommentText(e.target.value)}
          placeholder="Chia sẻ suy nghĩ của bạn về bài viết này..."
          rows={4} // TĂNG ROWS
          style={{
            width: "100%",
            borderRadius: 12,
            border: "2px solid #e2e8f0",
            padding: "16px 20px", // TĂNG PADDING
            fontSize: 16,
            resize: "vertical",
            boxSizing: "border-box",
            outline: "none",
            background: "#ffffff",
            transition: "border-color 0.3s ease",
            fontFamily: "inherit",
            lineHeight: 1.5
          }}
          onFocus={(e) => {
            e.target.style.borderColor = "#0154b9";
            e.target.style.boxShadow = "0 0 0 3px rgba(1,84,185,0.1)";
          }}
          onBlur={(e) => {
            e.target.style.borderColor = "#e2e8f0";
            e.target.style.boxShadow = "none";
          }}
        />
        
        <div style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
          marginTop: 16
        }}>
          <div style={{
            color: "#64748b",
            fontSize: 14,
            display: "flex",
            alignItems: "center",
            gap: 8
          }}>
            <span>💡</span>
            <span>Hãy chia sẻ ý kiến tích cực và xây dựng</span>
          </div>
          
          <button
            onClick={handleSubmit}
            style={{
              background: "linear-gradient(90deg, #0154b9, #3bb2ff)",
              color: "#fff",
              border: "none",
              borderRadius: 12,
              padding: "12px 32px", // TĂNG PADDING
              fontWeight: 600,
              fontSize: 16,
              cursor: "pointer",
              boxShadow: "0 4px 16px rgba(1,84,185,0.3)",
              transition: "all 0.3s ease",
              display: "flex",
              alignItems: "center",
              gap: 8
            }}
            onMouseEnter={(e) => {
              e.target.style.transform = "translateY(-2px)";
              e.target.style.boxShadow = "0 6px 24px rgba(1,84,185,0.4)";
            }}
            onMouseLeave={(e) => {
              e.target.style.transform = "translateY(0)";
              e.target.style.boxShadow = "0 4px 16px rgba(1,84,185,0.3)";
            }}
          >
            <span>📤</span>
            Gửi bình luận
          </button>
        </div>
        
        {message && (
          <div style={{
            color: message.includes("thất bại") ? "#dc3545" : "#28a745",
            marginTop: 12,
            fontWeight: 600,
            fontSize: 15,
            padding: "12px 16px",
            borderRadius: 8,
            background: message.includes("thất bại") 
              ? "rgba(220,53,69,0.1)" 
              : "rgba(40,167,69,0.1)",
            border: `1px solid ${message.includes("thất bại") ? "#dc3545" : "#28a745"}20`,
            display: "flex",
            alignItems: "center",
            gap: 8
          }}>
            <span>{message.includes("thất bại") ? "❌" : "✅"}</span>
            {message}
          </div>
        )}
      </div>
      
      {/* COMMENTS LIST */}
      <div>
        {comments.length === 0 && (
          <div style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
            padding: "40px 20px",
            color: "#64748b",
            textAlign: "center",
            background: "#ffffff",
            borderRadius: 12,
            border: "2px dashed #e2e8f0"
          }}>
            <div style={{ fontSize: "48px", marginBottom: 16, opacity: 0.6 }}>💭</div>
            <h4 style={{ fontSize: 18, fontWeight: 600, marginBottom: 8 }}>
              Chưa có bình luận nào
            </h4>
            <p style={{ fontSize: 15, opacity: 0.8 }}>
              Hãy là người đầu tiên chia sẻ suy nghĩ về bài viết này!
            </p>
          </div>
        )}
        
        {comments.map((cmt, idx) => (
          <div
            key={idx}
            style={{
              display: "flex",
              alignItems: "flex-start",
              gap: 20, // TĂNG GAP
              background: "#ffffff",
              borderRadius: 12,
              boxShadow: "0 4px 16px rgba(1,84,185,0.08)",
              padding: "20px 24px", // TĂNG PADDING
              marginBottom: 20, // TĂNG MARGIN
              transition: "all 0.3s ease",
              border: "1px solid rgba(1,84,185,0.06)"
            }}
            onMouseEnter={(e) => {
              e.target.style.boxShadow = "0 8px 32px rgba(1,84,185,0.15)";
              e.target.style.transform = "translateY(-2px)";
            }}
            onMouseLeave={(e) => {
              e.target.style.boxShadow = "0 4px 16px rgba(1,84,185,0.08)";
              e.target.style.transform = "translateY(0)";
            }}
          >
            <div style={{
              position: "relative",
              flexShrink: 0
            }}>
              <img
                src={cmt.user?.Avatar || "/default-avatar.png"}
                alt="avatar"
                style={{
                  width: 52, // TĂNG SIZE
                  height: 52,
                  borderRadius: "50%",
                  objectFit: "cover",
                  border: "3px solid #e2e8f0",
                  background: "#f6f8fc",
                  boxShadow: "0 4px 12px rgba(1,84,185,0.1)"
                }}
                onError={e => { e.target.src = "/default-avatar.png"; }}
              />
              <div style={{
                position: "absolute",
                bottom: -2,
                right: -2,
                width: 16,
                height: 16,
                background: "#28a745",
                borderRadius: "50%",
                border: "2px solid #ffffff"
              }} />
            </div>
            
            <div style={{ flex: 1 }}>
              <div style={{
                display: "flex",
                alignItems: "center",
                gap: 12,
                marginBottom: 8
              }}>
                <div style={{ 
                  fontWeight: 700, 
                  fontSize: 17, // TĂNG FONT SIZE
                  color: "#0154b9" 
                }}>
                  {cmt.user?.Name || `Người dùng #${cmt.User_ID}`}
                </div>
                <div style={{
                  background: "linear-gradient(90deg, #0154b9, #3bb2ff)",
                  color: "#ffffff",
                  fontSize: 11,
                  fontWeight: 600,
                  padding: "2px 8px",
                  borderRadius: 12,
                  textTransform: "uppercase",
                  letterSpacing: 0.5
                }}>
                  Thành viên
                </div>
              </div>
              
              <div style={{
                fontSize: 16, // TĂNG FONT SIZE
                color: "#2d3748",
                margin: "12px 0 16px 0",
                whiteSpace: "pre-line",
                lineHeight: 1.6,
                background: "rgba(1,84,185,0.02)",
                padding: "12px 16px",
                borderRadius: 8,
                border: "1px solid rgba(1,84,185,0.05)"
              }}>
                {cmt.text}
              </div>
              
              <div style={{
                display: "flex",
                alignItems: "center",
                gap: 16,
                fontSize: 14,
                color: "#64748b"
              }}>
                <div style={{
                  display: "flex",
                  alignItems: "center",
                  gap: 6
                }}>
                  <span>🕒</span>
                  {cmt.created_at?.slice(0, 16).replace("T", " ")}
                </div>
                
                <button style={{
                  background: "none",
                  border: "none",
                  color: "#64748b",
                  fontSize: 14,
                  cursor: "pointer",
                  display: "flex",
                  alignItems: "center",
                  gap: 4,
                  padding: "4px 8px",
                  borderRadius: 6,
                  transition: "all 0.2s ease"
                }}
                onMouseEnter={(e) => {
                  e.target.style.background = "rgba(1,84,185,0.1)";
                  e.target.style.color = "#0154b9";
                }}
                onMouseLeave={(e) => {
                  e.target.style.background = "none";
                  e.target.style.color = "#64748b";
                }}>
                  <span>👍</span>
                  Thích
                </button>
                
                <button style={{
                  background: "none",
                  border: "none",
                  color: "#64748b",
                  fontSize: 14,
                  cursor: "pointer",
                  display: "flex",
                  alignItems: "center",
                  gap: 4,
                  padding: "4px 8px",
                  borderRadius: 6,
                  transition: "all 0.2s ease"
                }}
                onMouseEnter={(e) => {
                  e.target.style.background = "rgba(1,84,185,0.1)";
                  e.target.style.color = "#0154b9";
                }}
                onMouseLeave={(e) => {
                  e.target.style.background = "none";
                  e.target.style.color = "#64748b";
                }}>
                  <span>💬</span>
                  Trả lời
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default ArticleComments;