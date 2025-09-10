<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    public function createPayment(Request $request)
    {
        // Thông tin cấu hình VNPAY TEST
        $vnp_TmnCode = "S53T702J"; // Mã website VNPAY cấp
        $vnp_HashSecret = "KMTEE9ANX4KRY062R3DQW2HGW4IGNMRJ"; // Chuỗi bí mật VNPAY cấp
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // URL sandbox test

        // Địa chỉ FE nhận kết quả (về trang callback để FE xử lý redirect)
        $vnp_Returnurl = "http://localhost:3000/vnpay-callback";

        $vnp_TxnRef = $request->orderId ?? uniqid(); // Mã đơn hàng
        $vnp_OrderInfo = "Thanh toan don hang DH" . $vnp_TxnRef;
        $vnp_Amount = intval($request->total) * 100; // Nhân 100 theo chuẩn VNPAY
        $vnp_Locale = "vn";
        $vnp_IpAddr = $request->ip();
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $vnp_IpAddr,
            "vnp_Locale"     => $vnp_Locale,
            "vnp_OrderInfo"  => $vnp_OrderInfo,
            "vnp_OrderType"  => "other",
            "vnp_ReturnUrl"  => $vnp_Returnurl,
            "vnp_TxnRef"     => $vnp_TxnRef,
        ];

        // Bước 1: Sort theo alphabet
        ksort($inputData);

        // Bước 2: Tạo query string
        $query = [];
        foreach ($inputData as $key => $value) {
            $query[] = urlencode($key) . "=" . urlencode($value);
        }
        $queryString = implode('&', $query);

        // Bước 3: Tạo secure hash
        $hashData = implode('&', $query);
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Bước 4: Ghép URL
        $vnpUrl = $vnp_Url . "?" . $queryString . "&vnp_SecureHash=" . $vnp_SecureHash;

        return response()->json(['paymentUrl' => $vnpUrl]);
    }
}
