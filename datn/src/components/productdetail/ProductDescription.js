// import { useState } from "react";

// function ProductDescription({ product }) {
//   const [expanded, setExpanded] = useState(false);

//   // Kiểm tra product có tồn tại và có trường details không
//   const hasDetails =
//     product &&
//     typeof product.details === "string" &&
//     product.details.trim().length > 0;

//   return (
//     <div
//       className="product-description"
//       style={{
//         background: "#f5f9ff",
//         borderRadius: 16,
//         boxShadow: "0 4px 24px rgba(1,84,185,0.08)",
//         padding: "32px 24px",        
//       }}
//     >
//       <h2
//         style={{
//           color: "#0154b9",
//           fontWeight: 700,
//           fontSize: 24,
//           marginBottom: 18,
//           letterSpacing: 0.5,
//         }}
//       >
//         Chi Tiết Sản Phẩm
//       </h2>
//       <div
//         className="product-description-content"
//         style={{
//           maxHeight: expanded ? "none" : 400,
//           overflow: expanded ? "visible" : "hidden",
//           position: "relative",
//           transition: "max-height 0.3s",
//           fontSize: 17,
//           color: "#222",
//           lineHeight: 1.7,
//         }}
//       >
//         {hasDetails ? (
//           <div
//             style={{ wordBreak: "break-word" }}
//             dangerouslySetInnerHTML={{ __html: product.details }}
//           />
//         ) : (
//           <p style={{ color: "#888", fontStyle: "italic" }}>
//             Chưa có thông tin chi tiết sản phẩm.
//           </p>
//         )}
//         {!expanded && hasDetails && (
//           <div
//             style={{
//               position: "absolute",
//               bottom: 0,
//               left: 0,
//               width: "100%",
//               height: 80,
//               background:
//                 "linear-gradient(to bottom, rgba(255,255,255,0), #fff 90%)",
//               pointerEvents: "none",
//               borderRadius: "0 0 16px 16px",
//             }}
//           />
//         )}
//       </div>
//       {hasDetails && (
//         <button
//           className="see-more-btn"
//           style={{
//             marginTop: 18,
//             background: expanded
//               ? "linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%)"
//               : "#0154b9",
//             color: "#fff",
//             border: "none",
//             borderRadius: 8,
//             padding: "10px 32px",
//             fontWeight: 700,
//             fontSize: 16,
//             cursor: "pointer",
//             boxShadow: "0 2px 8px rgba(1,84,185,0.08)",
//             transition: "background 0.2s",
//           }}
//           onClick={() => setExpanded(!expanded)}
//         >
//           {expanded ? "Rút gọn" : "Xem thêm"}
//         </button>
//       )}
//     </div>
//   );
// }

// export default ProductDescription;
