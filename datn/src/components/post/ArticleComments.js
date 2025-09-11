import React, { useEffect, useState } from "react";

function ArticleComments({ postId, user }) {
  const [comments, setComments] = useState([]);
  const [commentText, setCommentText] = useState("");
  const [message, setMessage] = useState("");

  useEffect(() => {
    fetch(`http://localhost:8000/api/posts/${postId}/comments`)
      .then(res => res.json())
      .then(data => setComments(Array.isArray(data) ? data : []));
  }, [postId, message]);

  const handleSubmit = () => {
    if (!user) {
      setMessage("Bạn cần đăng nhập để bình luận!");
      return;
    }
    if (!commentText.trim()) {
      setMessage("Vui lòng nhập nội dung bình luận!");
      return;
    }
    fetch(`http://localhost:8000/api/posts/${postId}/comments`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        User_ID: user.ID,
        Post_ID: postId,
        text: commentText
      })
    })
      .then(async res => {
        const data = await res.json().catch(() => null);
        if (!res.ok) {
          setMessage(data?.message || "Bình luận thất bại!");
          return;
        }
        setMessage("Bình luận thành công!");
        setCommentText("");
      })
      .catch(() => setMessage("Bình luận thất bại!"));
  };

  return (
    <div
      style={{
        background: "#f6f8fc",
        borderRadius: 12,
        padding: "24px 28px",
        marginTop: 40,
        boxShadow: "0 2px 12px rgba(1,84,185,0.06)"
      }}
    >
      <h3 style={{ fontSize: 20, fontWeight: 700, color: "#0154b9", marginBottom: 18 }}>
        Bình luận
      </h3>
      <div style={{ marginBottom: 18 }}>
        <textarea
          value={commentText}
          onChange={e => setCommentText(e.target.value)}
          placeholder="Nhập bình luận của bạn..."
          rows={3}
          style={{
            width: "100%",
            borderRadius: 8,
            border: "1.5px solid #b6d4fe",
            padding: 10,
            fontSize: 15,
            resize: "vertical"
          }}
        />
        <button
          onClick={handleSubmit}
          style={{
            background: "#0154b9",
            color: "#fff",
            border: "none",
            borderRadius: 8,
            padding: "8px 24px",
            fontWeight: 600,
            fontSize: "1rem",
            cursor: "pointer",
            marginTop: 8
          }}
        >
          Gửi bình luận
        </button>
        {message && (
          <div style={{
            color: message.includes("thất bại") ? "#d32f2f" : "#388e3c",
            marginTop: 8,
            fontWeight: 500
          }}>{message}</div>
        )}
      </div>
      <div>
        {comments.length === 0 && <div style={{ color: "#888" }}>Chưa có bình luận nào.</div>}
        {comments.map((cmt, idx) => (
          <div key={idx} style={{
            marginBottom: 18,
            padding: "10px 0",
            borderBottom: "1px solid #eee"
          }}>
            <b>{cmt.user_name || `Người dùng #${cmt.User_ID}`}</b>
            <div style={{ fontSize: 15, color: "#333", margin: "4px 0 8px 0" }}>
              {cmt.text}
            </div>
            <div style={{ fontSize: 13, color: "#888" }}>
              {cmt.created_at?.slice(0, 16).replace("T", " ")}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default ArticleComments;