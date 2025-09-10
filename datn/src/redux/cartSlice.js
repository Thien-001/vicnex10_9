import { createSlice } from "@reduxjs/toolkit";

const cartSlice = createSlice({
  name: "cart",
  initialState: [],
  reducers: {
    addToCart: (state, action) => {
      // Nếu sản phẩm đã có trong giỏ, tăng số lượng
      const existing = state.find(item => item.Product_ID === action.payload.Product_ID);
      if (existing) {
        existing.quantity += action.payload.quantity || 1;
      } else {
        state.push({ ...action.payload, quantity: action.payload.quantity || 1 });
      }
    },
    // Bạn có thể thêm các reducer khác như removeFromCart, updateQuantity...
  },
});

export const { addToCart } = cartSlice.actions;
export default cartSlice.reducer;