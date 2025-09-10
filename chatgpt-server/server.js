const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");
const { OpenAI } = require("openai");

const app = express();
app.use(bodyParser.json());
app.use(cors()); // Cho phép frontend React gọi API

const openai = new OpenAI({
  apiKey: "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",
});

app.post("/api/chat-badminton", async (req, res) => {
  const userMsg = req.body.message;
  const prompt = `Bạn là trợ lý cầu lông. Chỉ trả lời các câu hỏi về cầu lông. Nếu câu hỏi không liên quan cầu lông, hãy từ chối. Câu hỏi: ${userMsg}`;

  try {
    const completion = await openai.chat.completions.create({
      model: "gpt-3.5-turbo",
      messages: [{ role: "user", content: prompt }],
      max_tokens: 300,
    });
    const reply = completion.choices[0].message.content;
    res.json({ reply });
  } catch (err) {
    console.error("OpenAI error:", err); // Thêm dòng này để log lỗi chi tiết
    res.json({ reply: "Xin lỗi, tôi không trả lời được lúc này." });
  }
});

app.listen(8000, () => {
  console.log("ChatGPT server running on port 8000");
});