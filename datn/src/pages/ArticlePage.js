import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import ArticleHeader from "../components/post/ArticleHeader";
import ArticleTags from "../components/post/ArticleTags";
import ArticleContent from "../components/post/ArticleContent";
import ArticleAuthorBox from "../components/post/ArticleAuthorBox";
import ArticleShare from "../components/post/ArticleShare";
import ArticleRelated from "../components/post/ArticleRelated";
import ArticleComments from "../components/post/ArticleComments";
import ArticleSidebar from "../components/articles/ArticleSidebar"; // Đổi sang articles
import BreadcrumbNav from "../components/product/BreadcrumbNav";

function ArticlePage() {
  const { id } = useParams();
  const [article, setArticle] = useState(null);
  const [loading, setLoading] = useState(true);

  // Thêm dòng này để lấy user đã đăng nhập
  const user = JSON.parse(localStorage.getItem("user") || "null");

  useEffect(() => {
    fetch(`/api/posts/${id}`)
      .then(res => res.json())
      .then(data => {
        setArticle(data);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [id]);

  if (loading) return <div>Đang tải...</div>;
  if (!article) return <div>Không tìm thấy bài viết.</div>;

  // Giả sử article.user có trường Avatar, Gender
  const getAvatar = (user) => {
    if (user?.Avatar) {
      return user.Avatar.startsWith("http")
        ? user.Avatar
        : `/` + user.Avatar.replace(/^\/+/, "");
    }
    if (user?.Gender === "female") {
      return "/img/avt/default-avatar-female.png";
    }
    // Mặc định là nam
    return "/img/avt/default-avatar-male.png";
  };

  return (
    <>
      <Header />
      <BreadcrumbNav />
      <div style={{
        maxWidth: 1900,
        margin: "0 auto",
        padding: "32px 16px",
        display: "flex",
        gap: 32,
        marginLeft: 70,
      marginRight: 0
      }}>
        <main style={{ flex: 3 }}>
          <ArticleHeader article={{
            title: article.Title,
            cover: article.Thumbnail
              ? article.Thumbnail.startsWith("http")
                ? article.Thumbnail
                : "/" + article.Thumbnail.replace(/^\/+/, "")
              : "",
            category: article.category?.Name || article.CategoryName || "",
            date: article.Created_at,
          }} />
          <ArticleTags tags={article.Tags || []} />
          <ArticleContent content={article.Content} />
          <ArticleShare article={{ title: article.Title }} />
          <ArticleAuthorBox
            author={{
              name: article.user?.Name,
              avatar: getAvatar(article.user)
            }}
          />
          <ArticleRelated related={article.related || []} />
          <ArticleComments postId={article.Post_ID} user={user} />
        </main>
        <aside style={{ flex: 1 }}>
          <ArticleSidebar />
        </aside>
      </div>
      <Footer />
    </>
  );
}

export default ArticlePage;