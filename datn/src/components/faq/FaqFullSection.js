import React from "react";
import FaqItem from "../home/faq/FaqItem";

const faqGroups = [
	{
		group: "Đặt hàng & Thanh toán",
		faqs: [
			{
				question: "Làm thế nào để đặt hàng trên website?",
				answer: `Bạn có thể đặt hàng theo các bước sau:
1. Truy cập website và tìm kiếm sản phẩm bạn muốn mua.
2. Nhấn nút "Thêm vào giỏ hàng" tại trang chi tiết sản phẩm.
3. Sau khi chọn xong, nhấn vào biểu tượng giỏ hàng để kiểm tra lại đơn hàng.
4. Nhấn "Thanh toán", điền đầy đủ thông tin giao hàng và chọn phương thức thanh toán phù hợp.
5. Xác nhận đơn hàng. Hệ thống sẽ gửi email xác nhận và thông tin đơn hàng cho bạn.`,
			},
			{
				question: "Các hình thức thanh toán nào được chấp nhận?",
				answer: `Chúng tôi hỗ trợ nhiều hình thức thanh toán linh hoạt:
- Thanh toán khi nhận hàng (COD).
- Chuyển khoản ngân hàng qua các tài khoản được hiển thị khi đặt hàng.
- Thanh toán qua thẻ tín dụng/ghi nợ (Visa, MasterCard).
- Thanh toán qua ví điện tử như Momo, ZaloPay, ShopeePay.
Bạn có thể lựa chọn phương thức phù hợp nhất khi hoàn tất đơn hàng.`,
			},
			{
				question: "Tôi có thể kiểm tra tình trạng đơn hàng như thế nào?",
				answer: `Sau khi đặt hàng thành công, bạn sẽ nhận được email xác nhận cùng mã đơn hàng.
Bạn có thể kiểm tra trạng thái đơn hàng bằng cách:
- Đăng nhập tài khoản, vào mục "Đơn hàng của tôi" để xem chi tiết trạng thái từng đơn.
- Hoặc liên hệ bộ phận Chăm sóc khách hàng qua hotline/email, cung cấp mã đơn hàng để được hỗ trợ nhanh nhất.`,
			},
			{
				question: "Có thể hủy đơn hàng sau khi đã đặt không?",
				answer: `Bạn có thể hủy đơn hàng nếu đơn chưa được chuyển sang trạng thái "Đang giao".
Để hủy đơn, vui lòng:
- Đăng nhập vào tài khoản, vào mục "Đơn hàng của tôi", chọn đơn muốn hủy và nhấn "Hủy đơn".
- Hoặc liên hệ trực tiếp CSKH qua hotline/email, cung cấp mã đơn hàng để được hỗ trợ hủy nhanh nhất.
Lưu ý: Đơn hàng đã giao cho đơn vị vận chuyển sẽ không thể hủy.`,
			},
			{
				question: "Tôi có thể đặt hàng qua điện thoại không?",
				answer: `Có, bạn hoàn toàn có thể đặt hàng qua điện thoại.
Vui lòng gọi hotline 1800 1234 (miễn phí) từ 8h-21h mỗi ngày. Nhân viên sẽ tư vấn, xác nhận thông tin và hỗ trợ bạn đặt hàng nhanh chóng.`,
			},
		],
	},
	{
		group: "Vận chuyển & Giao nhận",
		faqs: [
			{
				question: "Thời gian giao hàng mất bao lâu?",
				answer: `Thời gian giao hàng phụ thuộc vào địa chỉ nhận hàng:
- Nội thành TP.HCM, Hà Nội: 1-2 ngày làm việc.
- Các tỉnh/thành khác: 2-5 ngày làm việc.
- Một số khu vực vùng sâu, vùng xa có thể lâu hơn.
Chúng tôi luôn cố gắng giao hàng sớm nhất có thể. Khi đơn hàng được giao cho đơn vị vận chuyển, bạn sẽ nhận được mã vận đơn để theo dõi.`,
			},
			{
				question: "Tôi có thể thay đổi địa chỉ nhận hàng sau khi đặt không?",
				answer: `Bạn có thể thay đổi địa chỉ nhận hàng nếu đơn hàng chưa được giao cho đơn vị vận chuyển.
Vui lòng liên hệ CSKH càng sớm càng tốt qua hotline/email, cung cấp mã đơn hàng và địa chỉ mới để được hỗ trợ.`,
			},
			{
				question: "Phí vận chuyển được tính như thế nào?",
				answer: `Phí vận chuyển được tính dựa trên địa chỉ nhận hàng và tổng giá trị đơn hàng:
- Đơn hàng từ 1.000.000đ trở lên: Miễn phí vận chuyển toàn quốc.
- Đơn hàng dưới 1.000.000đ: Phí vận chuyển từ 20.000đ - 40.000đ tùy khu vực.
Phí cụ thể sẽ được hiển thị tại bước thanh toán trước khi bạn xác nhận đơn hàng.`,
			},
			{
				question: "Tôi có thể nhận hàng vào cuối tuần không?",
				answer: `Có, chúng tôi giao hàng tất cả các ngày trong tuần, kể cả thứ 7 và Chủ nhật (trừ các ngày lễ lớn).`,
			},
			{
				question: "Nếu nhận hàng trễ thì phải làm sao?",
				answer: `Nếu quá thời gian dự kiến mà bạn chưa nhận được hàng, vui lòng:
- Kiểm tra trạng thái đơn hàng bằng mã vận đơn được gửi qua SMS/email.
- Liên hệ CSKH qua hotline/email, cung cấp mã đơn hàng để được kiểm tra và hỗ trợ xử lý nhanh nhất.`,
			},
		],
	},
	{
		group: "Bảo hành & Đổi trả",
		faqs: [
			{
				question: "Chính sách bảo hành sản phẩm ra sao?",
				answer: `Tất cả sản phẩm chính hãng đều được bảo hành từ 6 đến 24 tháng tùy theo từng loại sản phẩm và nhà sản xuất.
Điều kiện bảo hành:
- Sản phẩm còn nguyên tem, phiếu bảo hành/hóa đơn mua hàng.
- Lỗi kỹ thuật do nhà sản xuất, không áp dụng cho lỗi do người dùng gây ra.
Bạn có thể mang sản phẩm đến trung tâm bảo hành hoặc gửi về địa chỉ công ty để được hỗ trợ.`,
			},
			{
				question: "Tôi muốn đổi trả sản phẩm thì phải làm gì?",
				answer: `Bạn có thể đổi trả sản phẩm trong vòng 7 ngày kể từ khi nhận hàng nếu sản phẩm bị lỗi kỹ thuật hoặc không đúng mô tả.
Các bước thực hiện:
1. Liên hệ CSKH qua hotline/email, cung cấp mã đơn hàng và hình ảnh sản phẩm.
2. Nhân viên xác nhận điều kiện đổi trả và hướng dẫn bạn gửi sản phẩm về công ty.
3. Sau khi kiểm tra, chúng tôi sẽ đổi/trả hoặc hoàn tiền theo quy định.`,
			},
			{
				question: "Thời gian xử lý đổi trả mất bao lâu?",
				answer: `Thời gian xử lý đổi trả từ 3-5 ngày làm việc kể từ khi chúng tôi nhận được sản phẩm đổi trả.
Chúng tôi sẽ thông báo kết quả kiểm tra và tiến hành đổi/trả hoặc hoàn tiền ngay sau khi xác nhận đủ điều kiện.`,
			},
			{
				question: "Sản phẩm đổi trả cần điều kiện gì?",
				answer: `Sản phẩm đổi trả cần đáp ứng các điều kiện:
- Còn nguyên tem, hộp, chưa qua sử dụng, đầy đủ phụ kiện và quà tặng kèm (nếu có).
- Có hóa đơn mua hàng hoặc phiếu bảo hành.
- Không bị trầy xước, móp méo, hư hỏng do tác động bên ngoài.
Các trường hợp không đủ điều kiện sẽ không được hỗ trợ đổi trả.`,
			},
			{
				question: "Tôi có phải trả phí khi đổi trả không?",
				answer: `Nếu sản phẩm bị lỗi do nhà sản xuất hoặc giao nhầm, bạn sẽ được đổi trả miễn phí (bao gồm cả phí vận chuyển).
Các trường hợp đổi trả do nhu cầu cá nhân hoặc không phải lỗi của nhà sản xuất, bạn sẽ chịu phí vận chuyển phát sinh.`,
			},
		],
	},
	{
		group: "Khác",
		faqs: [
			{
				question: "Làm sao để nhận ưu đãi giảm giá?",
				answer: `Bạn có thể nhận ưu đãi giảm giá bằng cách:
- Đăng ký nhận bản tin qua email để nhận mã giảm giá và thông tin khuyến mãi mới nhất.
- Theo dõi website, fanpage Facebook/Zalo để cập nhật các chương trình ưu đãi.
- Tham gia các sự kiện, minigame do công ty tổ chức để nhận quà tặng hấp dẫn.`,
			},
			{
				question: "Tôi cần hỗ trợ thêm thì liên hệ ở đâu?",
				answer: `Bạn có thể liên hệ với chúng tôi qua:
- Hotline: 1800 1234 (miễn phí, 8h-21h mỗi ngày)
- Email: info@example.com
- Fanpage Facebook/Zalo của công ty
Chúng tôi luôn sẵn sàng hỗ trợ bạn nhanh nhất có thể.`,
			},
			{
				question: "Tôi có thể góp ý hoặc phản ánh dịch vụ không?",
				answer: `Chúng tôi luôn lắng nghe ý kiến khách hàng để cải thiện dịch vụ.
Bạn có thể gửi góp ý/phản ánh qua:
- Email: info@example.com
- Hotline: 1800 1234
Mọi ý kiến của bạn sẽ được tiếp nhận và phản hồi trong vòng 24h.`,
			},
			{
				question: "Có chương trình khách hàng thân thiết không?",
				answer: `Chúng tôi có chương trình tích điểm đổi quà và ưu đãi riêng cho khách hàng thân thiết:
- Đăng ký tài khoản để tự động tích điểm khi mua hàng.
- Điểm thưởng có thể đổi lấy mã giảm giá, quà tặng hoặc ưu đãi đặc biệt.
- Thông tin chi tiết sẽ được gửi qua email hoặc cập nhật trên website.`,
			},
			{
				question: "Thông tin cá nhân của tôi có được bảo mật không?",
				answer: `Chúng tôi cam kết bảo mật tuyệt đối thông tin cá nhân của khách hàng:
- Thông tin chỉ được sử dụng cho mục đích giao dịch và chăm sóc khách hàng.
- Không chia sẻ cho bên thứ ba khi chưa có sự đồng ý của bạn.
- Áp dụng các biện pháp bảo mật hiện đại để bảo vệ dữ liệu khách hàng.
Bạn có thể xem chi tiết tại trang "Chính sách bảo mật" trên website.`,
			},
		],
	},
];

const FaqFullSection = () => (
	<>
		<style>{`
      .faq-full-section {
        max-width: 900px;
        margin: 48px auto 0 auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 32px rgba(1,84,185,0.08);
        padding: 36px 28px 32px 28px;
      }
      .faq-full-title {
        text-align: center;
        font-size: 2.2rem;
        font-weight: 700;
        color: #0154b9;
        margin-bottom: 32px;
        letter-spacing: 1px;
      }
      .faq-group-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0154b9;
        margin: 32px 0 16px 0;
        border-left: 4px solid #0154b9;
        padding-left: 12px;
      }
      @media (max-width: 800px) {
        .faq-full-section {
          padding: 18px 6px 18px 6px;
        }
      }
    `}</style>
		<div className="faq-full-section">
			<div className="faq-full-title">
				<i
					className="fa-regular fa-circle-question"
					style={{ marginRight: 10 }}
				></i>
				Giải đáp thắc mắc
			</div>
			{faqGroups.map((group, idx) => (
				<div key={idx}>
					<div className="faq-group-title">{group.group}</div>
					{group.faqs.map((faq, i) => (
						<FaqItem key={i} question={faq.question} answer={faq.answer} />
					))}
				</div>
			))}
		</div>
	</>
);

export default FaqFullSection;