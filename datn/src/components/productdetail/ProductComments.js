import React, { useEffect, useState } from "react";

function ProductComments({ productId, user }) {
  const [comments, setComments] = useState([]);
  const [content, setContent] = useState("");
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(""); // Thông báo gửi bình luận

  // CSS in component
  const styles = {
    container: {
      background: "#fff",
      borderRadius: 8,
      padding: "20px 12px",
      margin: "24px 0",
      boxShadow: "0 2px 12px rgba(1, 84, 185, 0.06)",
      maxWidth: 1400,
      width: "100%",
      marginLeft: "auto",
      marginRight: "auto",
    },
    title: {
      marginBottom: 20,
      color: "#0154b9",
      fontSize: "1.4rem",
      fontWeight: 700,
      letterSpacing: "0.5px",
    },
    form: {
      marginBottom: 24,
    },
    textarea: {
      width: "100%",
      borderRadius: 8,
      border: "1px solid #e5e7eb",
      padding: 14,
      fontSize: "1rem",
      resize: "vertical",
      marginBottom: 8,
      transition: "border 0.2s",
    },
    button: {
      background: "#0154b9",
      color: "#fff",
      border: "none",
      borderRadius: 8,
      padding: "10px 28px",
      fontWeight: 600,
      fontSize: "1rem",
      cursor: "pointer",
      transition: "background 0.2s",
      marginTop: 8,
    },
    buttonDisabled: {
      background: "#b3c6e6",
      cursor: "not-allowed",
    },
    commentList: {
      marginTop: 24,
    },
    commentItem: {
      borderBottom: "1px solid #f0f0f0",
      padding: "16px 0",
      display: "flex",
      gap: 16,
    },
    avatar: {
      width: 40,
      height: 40,
      borderRadius: "50%",
      background: "#e0e7ff",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      fontWeight: 700,
      color: "#0154b9",
      fontSize: "1.1rem",
      flexShrink: 0,
      overflow: "hidden",
    },
    avatarImg: {
      width: "100%",
      height: "100%",
      objectFit: "cover",
      borderRadius: "50%",
    },
    commentContent: {
      flex: 1,
    },
    commentUser: {
      fontWeight: 600,
      color: "#0154b9",
      marginBottom: 2,
    },
    commentDate: {
      fontSize: 12,
      color: "#888",
      marginBottom: 4,
    },
    commentText: {
      color: "#222",
      fontSize: "1rem",
      lineHeight: 1.5,
    },
    noComment: {
      color: "#888",
    },
    message: {
      marginBottom: 12,
      color: "#388e3c",
      fontWeight: 500,
    },
    error: {
      marginBottom: 12,
      color: "#d32f2f",
      fontWeight: 500,
    },
    count: {
      color: "#0154b9",
      fontWeight: 500,
      marginBottom: 8,
      fontSize: 15,
    },
  };

  // Lấy danh sách bình luận
  useEffect(() => {
    if (!productId) return;
    fetch(`http://localhost:8000/api/products/${productId}/comments`)
      .then(res => res.json())
      .then(data => {
        setComments(data);
        console.log("comments data:", data); // Thêm dòng này để xem dữ liệu trả về
      })
      .catch(() => setComments([]));
  }, [productId]);

  // Gửi bình luận mới
  const handleSubmit = async (e) => {
    e.preventDefault();
    const res = await fetch(`http://localhost:8000/api/products/${productId}/comments`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Content: content,
        User_ID: user.ID,
        Status: 1,
      }),
    });

    const data = await res.json();
    console.log("POST response:", data); // <-- Thêm dòng này để xem response trả về khi gửi bình luận

    if (res.ok) {
      setContent("");
      setMessage("Bình luận đã được gửi!");
      fetch(`http://localhost:8000/api/products/${productId}/comments`)
        .then(res => res.json())
        .then(data => {
          setComments(data);
          console.log("GET comments after post:", data);
        });
    } else {
      setMessage("Có lỗi: " + (data.message || JSON.stringify(data)));
    }
  };

  return (
    <div style={styles.container}>
      <h3 style={styles.title}>Bình luận sản phẩm</h3>
      <div style={styles.count}>
        {comments.length} bình luận
      </div>
      {message && (
        <div style={message.includes("thất bại") || message.includes("đăng nhập") ? styles.error : styles.message}>
          {message}
        </div>
      )}
      <form style={styles.form} onSubmit={handleSubmit}>
        <textarea
          value={content}
          onChange={e => setContent(e.target.value)}
          placeholder={user ? "Nhập bình luận của bạn..." : "Bạn cần đăng nhập để bình luận"}
          rows={3}
          style={styles.textarea}
          disabled={!user}
        />
        <button
          type="submit"
          disabled={loading || !content.trim() || !user}
          style={{
            ...styles.button,
            ...(loading || !content.trim() || !user ? styles.buttonDisabled : {}),
          }}
        >
          {loading ? "Đang gửi..." : "Gửi bình luận"}
        </button>
      </form>
      <div style={styles.commentList}>
        {comments.length === 0 && (
          <div style={styles.noComment}>Chưa có bình luận nào.</div>
        )}
        {comments.map(c => (
          <div className="product-comment-item" style={styles.commentItem} key={c.Comment_ID}>
            <div style={styles.avatar}>
              {c.user?.Avatar ? (
                <img
                  src={
                    c.user.Avatar.startsWith("http")
                      ? c.user.Avatar
                      : "/" + c.user.Avatar
                  }
                  alt="avatar"
                  style={styles.avatarImg}
                />
              ) : (
                (c.user?.Name || "U").charAt(0).toUpperCase()
              )}
            </div>
            <div style={styles.commentContent}>
              <div style={styles.commentUser}>{c.user?.Name || "Người dùng"}</div>
              <div style={styles.commentDate}>{c.Create_at}</div>
              <div style={styles.commentText}>{c.Content}</div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default ProductComments;