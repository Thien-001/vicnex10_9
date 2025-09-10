<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourtBooking;
use App\Models\Court;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Carbon;

class CourtBookingController extends Controller
{
    public function index()
    {
$bookings = CourtBooking::with(['court', 'user'])->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $users = User::all(); // Thêm dòng này
        $courts = Court::all();
        $vouchers = Voucher::all();
        return view('admin.bookings.create', compact('users', 'courts', 'vouchers'));
    }

    public function store(Request $request)
{
    $request->validate([
        'customer_name'   => 'required|string|max:100',
        'user_id' => 'nullable|exists:user,ID',
        'courts_id'       => 'required|exists:courts,Courts_ID',
        'booking_date'    => 'required|date|after_or_equal:today',
        'start_time'      => 'required|date_format:H:i',
        'end_time'        => 'required|date_format:H:i|after:start_time',
        'price_per_hour'  => 'required|numeric|min:0',
        'status'          => 'nullable|in:active,cancelled',
        'note'            => 'nullable|string|max:255',
    ]);

    $start = Carbon::createFromFormat('H:i', $request->start_time);
$end = Carbon::createFromFormat('H:i', $request->end_time);

if ($end->lessThanOrEqualTo($start)) {
    return back()->withErrors(['end_time' => 'Giờ kết thúc phải sau giờ bắt đầu.'])->withInput();
}

$duration = $start->diffInHours($end);
$total = $duration * $request->price_per_hour;


    CourtBooking::create([
        'customer_name'     => $request->customer_name,
        'User_ID'           => $request->user_id, // nullable
        'Courts_ID'         => $request->courts_id,
        'Booking_date'      => $request->booking_date,
        'Start_time'        => $request->start_time,
        'End_time'          => $request->end_time,
        'Duration_hours'    => $duration,
        'Price_per_hour'    => $request->price_per_hour,
        'Total_price'       => $total,
        'Status'            => 0, // <-- luôn là 0 khi tạo mới
        'Note'              => $request->note,
        'Create_at'         => now(),
        'Update_at'         => now(),
    ]);

    return redirect()->route('admin.bookings.index')->with('success', 'Đặt sân thành công!');
}


    public function edit($id)
    {
        $booking = CourtBooking::findOrFail($id);
        $courts = Court::all();
        $users = User::all();
        $vouchers = Voucher::all();

        return view('admin.bookings.edit', compact('booking', 'courts', 'users', 'vouchers'));
    }

    public function update(Request $request, $id)
    {
        $booking = CourtBooking::findOrFail($id);

        $request->validate([
            'customer_name'   => 'required|string|max:100',
            'user_id' => 'nullable|exists:user,ID',
            'courts_id'       => 'required|exists:courts,Courts_ID',
            'booking_date'    => 'required|date',
            'start_time'      => 'required|date_format:H:i',
            'end_time'        => 'required|date_format:H:i|after:start_time',
            'price_per_hour'  => 'required|numeric|min:0',
            'status'          => 'nullable|in:active,cancelled',
            'note'            => 'nullable|string',
            'vouchers_id'     => 'nullable|exists:vouchers,id',
        ]);

        $start = Carbon::createFromFormat('H:i', $request->start_time);
$end = Carbon::createFromFormat('H:i', $request->end_time);

if ($end->lessThanOrEqualTo($start)) {
    return back()->withErrors(['end_time' => 'Giờ kết thúc phải sau giờ bắt đầu.'])->withInput();
}

$duration = $start->diffInHours($end);
$total = $duration * $request->price_per_hour;


        $booking->update([
            'customer_name'     => $request->customer_name,
            'User_ID'           => $request->user_id,
            'Courts_ID'         => $request->courts_id,
            'Booking_date'      => $request->booking_date,
            'Start_time'        => $request->start_time,
            'End_time'          => $request->end_time,
            'Duration_hours'    => $duration,
            'Price_per_hour'    => $request->price_per_hour,
            'Total_price'       => $total,
            'Status'            => $request->status === 'cancelled' ? 0 : 1,
            'Note'              => $request->note,
            'Update_at'         => now(),
            'Vouchers_ID'       => $request->vouchers_id,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật lịch đặt thành công!');
    }

    public function destroy($id)
    {
        CourtBooking::findOrFail($id)->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Xóa lịch đặt thành công!');
    }
    public function confirm($id)
    {
        $booking = CourtBooking::findOrFail($id);
        $booking->Status = 1; // hoặc trạng thái bạn muốn khi xác nhận
        $booking->save();

        return redirect()->back()->with('success', 'Đã xác nhận lịch đặt sân!');
    }
}
