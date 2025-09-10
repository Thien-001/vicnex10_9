const SupportForm = () => (
  <div className="support-form">
    <h3>Liên hệ bộ phận CSKH</h3>
    <p>Phản hồi nhanh trong vòng 24 giờ làm việc</p>
    <form>
      <input type="text" placeholder="Họ và tên" required />
      <input type="tel" placeholder="Số điện thoại" required />
      <textarea placeholder="Nội dung cần hỗ trợ" required></textarea>
      <button type="submit">Gửi yêu cầu</button>
    </form>
  </div>
);

export default SupportForm;