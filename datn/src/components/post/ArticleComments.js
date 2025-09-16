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
        background: "#f6f8fc",
        borderRadius: 16,
        padding: "32px 36px",
        marginTop: 40,
        boxShadow: "0 2px 16px rgba(1,84,185,0.08)",
        maxWidth: 700,
        marginLeft: "auto",
        marginRight: "auto"
      }}
    >
      <h3 style={{
        fontSize: 22,
        fontWeight: 700,
        color: "#0154b9",
        marginBottom: 24,
        letterSpacing: 1
      }}>
        Bình luận bài viết
      </h3>
      <div style={{ marginBottom: 24 }}>
        <textarea
          value={commentText}
          onChange={e => setCommentText(e.target.value)}
          placeholder="Nhập bình luận của bạn..."
          rows={3}
          style={{
            width: "100%",
            borderRadius: 10,
            border: "1.5px solid #b6d4fe",
            padding: 12,
            fontSize: 16,
            resize: "vertical",
            boxSizing: "border-box",
            outline: "none"
          }}
        />
        <button
          onClick={handleSubmit}
          style={{
            background: "#0154b9",
            color: "#fff",
            border: "none",
            borderRadius: 10,
            padding: "10px 32px",
            fontWeight: 600,
            fontSize: "1rem",
            cursor: "pointer",
            marginTop: 12,
            boxShadow: "0 2px 8px rgba(1,84,185,0.08)",
            transition: "background 0.2s"
          }}
        >
          Gửi bình luận
        </button>
        {message && (
          <div style={{
            color: message.includes("thất bại") ? "#d32f2f" : "#388e3c",
            marginTop: 10,
            fontWeight: 500,
            fontSize: 15
          }}>{message}</div>
        )}
      </div>
      <div>
        {comments.length === 0 && <div style={{ color: "#888", fontStyle: "italic" }}>Chưa có bình luận nào.</div>}
        {comments.map((cmt, idx) => (
          <div
            key={idx}
            style={{
              display: "flex",
              alignItems: "flex-start",
              gap: 16,
              background: "#fff",
              borderRadius: 12,
              boxShadow: "0 1px 6px rgba(1,84,185,0.06)",
              padding: "16px 18px",
              marginBottom: 18,
              transition: "box-shadow 0.2s",
            }}
          >
            <img
              src={cmt.user?.Avatar || "/default-avatar.png"}
              alt="avatar"
              style={{
                width: 48,
                height: 48,
                borderRadius: "50%",
                objectFit: "cover",
                border: "2px solid #b6d4fe",
                background: "#f6f8fc"
              }}
              onError={e => { e.target.src = "/default-avatar.png"; }}
            />
            <div style={{ flex: 1 }}>
              <div style={{ fontWeight: 600, fontSize: 16, color: "#0154b9" }}>
                {cmt.user?.Name || `Người dùng #${cmt.User_ID}`}
              </div>
              <div style={{
                fontSize: 15,
                color: "#333",
                margin: "6px 0 10px 0",
                whiteSpace: "pre-line"
              }}>
                {cmt.text}
              </div>
              <div style={{ fontSize: 13, color: "#888" }}>
                {cmt.created_at?.slice(0, 16).replace("T", " ")}
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default ArticleComments;