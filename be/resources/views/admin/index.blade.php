@extends('layouts.layout')

@section('content')
<style>
    .dashboard-table {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(1,84,185,0.07);
        padding: 18px 16px 10px 16px;
        margin-bottom: 32px;
        overflow-x: auto;
    }
    .dashboard-table h3 {
        /* color: #0154b9; */
        font-weight: 700;
        margin-bottom: 18px;
        font-size: 1.2rem;
    }
    .dashboard-table table {
        width: 100%;
        border-collapse: collapse;
        background: transparent;
    }
    .dashboard-table th, .dashboard-table td {
        padding: 10px 8px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 1rem;
    }
    .dashboard-table th {
        background: #f6f8fc;
        font-weight: 600;
        color: #222;
    }
    .dashboard-table tr:last-child td {
        border-bottom: none;
    }


.status-pending { color: #f59e42; font-weight: 600;}
.status-confirmed {  color: #3b82f6; font-weight: 600;}
.status-shipping, .status-transported {  color: #009688; font-weight: 600;}
.status-completed {  color: #22c55e; font-weight: 600;}
.status-cancelled { color: #ef4444; font-weight: 600;}


.tk-dashboard {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .tk-card {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .tk-card h3 {
      margin-bottom: 15px;
      font-size: 20px;
      border-bottom: 1px solid #eee;
      padding-bottom: 8px;
      font-weight: 600;
    }
    .tk-right-col {
      display: grid;
      grid-template-rows: auto auto auto;
      gap: 20px;
    }

    .tk-bottom-col .tk-card {
      flex: 1;
    }
    .tk-bottom-col {
        grid-column: span 2;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin: 20px 0;
}
    .tk-bottom-col table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }
    .tk-bottom-col thead {
      background: #f8f9fc;
    }
    .tk-bottom-col th,
    .tk-bottom-col td {
      text-align: left;
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
    .tk-bottom-col tbody tr:hover {
      background: #f1f5ff;
    }


    .tk-filter {
      display: flex;
      align-items: center;
      gap: 12px;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .tk-filter label {
      font-size: 14px;
      font-weight: 600;
      color: #333;
    }

    .tk-filter input[type="date"] {
      padding: 6px 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      outline: none;
      transition: border-color 0.3s ease;
    }

    .tk-filter input[type="date"]:focus {
      border-color: #4e73df;
      box-shadow: 0 0 4px rgba(78, 115, 223, 0.4);
    }
</style>

<div class="head-title">
    <div class="left">
        <h1>Dashboard</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Trang chủ</a></li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Tải báo cáo PDF</span>
    </a>
</div>

{{-- Thống kê nhanh --}}
        <ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check' ></i>
					<span class="text">
						<h3>{{ $orderThisMonth }}</h3>
						<p>Đơn hàng</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group' ></i>
					<span class="text">
						<h3>{{ $newUsers }}</h3>
						<p>Khách hàng mới</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle' ></i>
					<span class="text">
						<h3>{{ number_format($revenueThisMonth, 0, ',', '.') }}đ</h3>
						<p>Tổng doanh thu tháng</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-calendar-check' ></i>
					<span class="text">
						<h3>{{ number_format($todayRevenue, 0, ',', '.') }}đ</h3>
						<p>Doanh thu trong ngày</p>
					</span>
				</li>

		</ul>

{{-- thống kê --}}
<div class="tk-dashboard">
    <!-- Revenue -->
    <div class="tk-card">
      <h3>Doanh thu</h3>
      <canvas id="revenueChart"></canvas>
    </div>



    <div class="tk-right-col">
      <div class="tk-filter">
        <label for="from">Từ ngày:</label>
        <input type="date" id="from">
        <label for="to">Đến ngày:</label>
        <input type="date" id="to">
         <button type="button" id="clearFilter" class="btn btn-sm btn-outline-secondary" style="margin-left:8px;">
      Xóa lọc
    </button>
      </div>

      <div class="tk-card">
        <h3>Đơn hàng</h3>
        <canvas id="ordersChart"></canvas>
      </div>

    </div>
    <!-- Cột dưới -->
  <div class="tk-bottom-col">
<!-- Order Status -->
    <div class="tk-card" style="margin-top:20px;">
    <h3>Trạng thái đơn hàng</h3>
    <div style="display:flex; align-items:center; gap:20px;">
        <!-- Biểu đồ trạng thái -->
        <canvas id="orderStatusChart" style="max-width:200px; max-height:200px;"></canvas>

        <!-- Custom legend -->
        <div id="orderStatusLegend"></div>
    </div>

    </div>
    <!-- Top Products -->

        <div class="tk-card">
          <h3>Sản phẩm bán nhiều nhất</h3>
          <table>
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
              </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->total }}</td>
                </tr>
                @endforeach
                </tbody>
          </table>
        </div>
        <div class="tk-card">
          <h3>Sản phẩm này sắp hết hàng</h3>
          <table>
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
              </tr>
            </thead>
            <tbody>
               @foreach($stockProducts as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->total_quantity }}</td>
                </tr>
                @endforeach

                </tbody>
          </table>
        </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.onload = function() {
    // Dữ liệu cũ
    const revenueData = @json($revenueByMonth);
    const ordersData = @json($ordersByMonth);
    const statusData = @json($orderStatusStats);

    const labels = Array.from({length: 12}, (_, i) => 'T' + (i+1));
    const revenueValues = labels.map((_, i) => revenueData[i+1] ?? 0);
    const orderValues = labels.map((_, i) => ordersData[i+1] ?? 0);

    // Chart Doanh thu
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (triệu)',
                data: revenueValues,
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76,175,80,0.2)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    // Chart Đơn hàng theo tháng
    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số đơn hàng',
                data: orderValues,
                backgroundColor: '#2196F3'
            }]
        }
    });

    // Chart Trạng thái đơn hàng
const statusLabels = Object.keys(statusData);
const statusValues = Object.values(statusData);
const statusColors = [
    '#FF9800', // đang xử lý
    '#03A9F4', // đã xác nhận
    '#9C27B0', // đang vận chuyển
    '#00BCD4', // đang giao
    '#4CAF50', // hoàn thành
    '#F44336'  // hủy
];

// Vẽ chart
new Chart(document.getElementById('orderStatusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusValues,
            backgroundColor: statusColors
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false } // Ẩn legend mặc định
        }
    }
});

// Tạo custom legend bên phải
const legendContainer = document.getElementById('orderStatusLegend');
let legendHTML = '<ul style="list-style:none; padding:0; margin:0;">';
statusLabels.forEach((label, i) => {
    legendHTML += `
      <li style="display:flex; align-items:center; gap:8px; margin-bottom:6px; font-size:14px; color:#333;">
        <span style="display:inline-block; width:14px; height:14px; border-radius:3px; background:${statusColors[i]};"></span>
        ${label} – <strong>${statusValues[i]}</strong>
      </li>`;
});
legendHTML += '</ul>';
legendContainer.innerHTML = legendHTML;
}
</script>

{{-- Đơn hàng gần đây --}}
<div class="dashboard-table" style="margin-bottom: 28px;">
    <h3>Đơn hàng gần đây</h3>
    <table>
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            <tr style="cursor:pointer"
                data-bs-toggle="modal"
                data-bs-target="#detailModal"
                onclick="showOrderDetail(@json($order))">
                <td>{{ $order->full_name }}</td>

                <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                <td>
                    @switch($order->status)
                        @case('pending')
                            <span class="status-badge status-pending"><i class="fas fa-clock"></i> Chờ xử lý</span>
                            @break
                        @case('confirmed')
                            <span class="status-badge status-confirmed"><i class="fas fa-check-circle"></i> Đã xác nhận</span>
                            @break
                        @case('transported')
                            <span class="status-badge status-transported"><i class="fas fa-truck"></i> Đang vận chuyển</span>
                            @break
                        @case('shipping')
                            <span class="status-badge status-shipping"><i class="fas fa-box"></i> Đang giao</span>
                            @break
                        @case('completed')
                            <span class="status-badge status-completed"><i class="fas fa-star"></i> Hoàn thành</span>
                            @break
                        @case('cancelled')
                            <span class="status-badge status-cancelled"><i class="fas fa-times-circle"></i> Đã hủy</span>
                            @break
                        @default
                            <span class="status-badge">Không rõ</span>
                    @endswitch
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Lịch đặt sân gần đây --}}
{{-- <div class="dashboard-table">
    <h3>Lịch đặt sân gần đây</h3>
    <table>
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Giờ bắt đầu</th>
                <th>Giờ kết thúc</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentBookings as $booking)
            <tr style="cursor:pointer"
                data-bs-toggle="modal"
                data-bs-target="#detailModal"
                onclick="showBookingDetail(@json($booking))">
                <td style="display:flex;align-items:center;gap:8px;">
                    @if($booking->Avatar)
                        <img src="{{ asset('uploads/users/' . $booking->Avatar) }}"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @endif
                    <span>{{ $booking->user_name }}</span>
                </td>
                <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('d-m-Y') }}</td>
                <td>{{ $booking->Start_time }}</td>
                <td>{{ $booking->End_time }}</td>
                <td>{{ number_format($booking->Total_price, 0, ',', '.') }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}

<!-- Modal chi tiết -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Chi tiết</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body" id="detailModalContent">
        <!-- Nội dung chi tiết sẽ được load ở đây -->
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

<script>
function showOrderDetail(order) {
    console.log(order);
    document.getElementById('detailModalLabel').innerText = 'Chi tiết đơn hàng #' + order.id;
    document.getElementById('detailModalContent').innerHTML = `
        <div>
            <b>Khách hàng:</b> ${order.full_name}<br>
            <b>Ngày đặt:</b> ${order.created_at}<br>
            <b>Tổng tiền:</b> ${Number(order.total_price).toLocaleString()}đ<br>
            <b>Trạng thái:</b> ${order.status}
        </div>
    `;
}

function showBookingDetail(booking) {
    console.log(booking);
    document.getElementById('detailModalLabel').innerText = 'Chi tiết lịch đặt sân #' + booking.id;
    document.getElementById('detailModalContent').innerHTML = `
        <div>
            <b>Khách hàng:</b> ${booking.user_name}<br>
            <b>Ngày đặt:</b> ${booking.created_at}<br>
            <b>Giờ bắt đầu:</b> ${booking.Start_time}<br>
            <b>Giờ kết thúc:</b> ${booking.End_time}<br>
            <b>Tổng tiền:</b> ${Number(booking.Total_price).toLocaleString()}đ
        </div>
    `;
}
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
  const fromInput = document.getElementById("from");
  const toInput   = document.getElementById("to");
  const clearBtn  = document.getElementById("clearFilter");

  // ----- DỮ LIỆU GỐC KHI MỞ TRANG (đã render sẵn) -----
  const revenueDataMonth = @json($revenueByMonth);   // {1: 1000000, 2: ...}
  const ordersDataMonth  = @json($ordersByMonth);    // {1: 10, 2: ...}
  const statusDataInit   = @json($orderStatusStats); // {pending: 3, ...}

  const monthLabels = Array.from({length: 12}, (_, i) => 'T' + (i+1));
  const revenueValuesMonth = monthLabels.map((_, i) => (revenueDataMonth[i+1] ?? 0) / 1_000_000); // triệu
  const orderValuesMonth   = monthLabels.map((_, i) => (ordersDataMonth[i+1] ?? 0));

  // Màu trạng thái + trật tự cố định
  const ALL_STATUS = {
    pending:     'Chờ xử lý',
    confirmed:   'Đã xác nhận',
    transported: 'Đang vận chuyển',
    shipping:    'Đang giao',
    completed:   'Hoàn thành',
    cancelled:   'Đã hủy'
  };
  const STATUS_COLORS = ['#FF9800','#03A9F4','#9C27B0','#00BCD4','#22c55e','#F44336'];

  // ----- KHỞI TẠO CHART BAN ĐẦU TỪ DỮ LIỆU GỐC -----
  const revenueChart = new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: monthLabels,
      datasets: [{
        label: 'Doanh thu (triệu)',
        data: revenueValuesMonth,
        borderColor: '#4CAF50',
        backgroundColor: 'rgba(76,175,80,0.2)',
        fill: true,
        tension: 0.3
      }]
    }
  });

  const ordersChart = new Chart(document.getElementById('ordersChart'), {
    type: 'bar',
    data: {
      labels: monthLabels,
      datasets: [{
        label: 'Số đơn hàng',
        data: orderValuesMonth,
        backgroundColor: '#2196F3'
      }]
    }
  });

  // Build status init (đủ 6 trạng thái)
  const statusLabelsInit = Object.values(ALL_STATUS);
  const statusValuesInit = Object.keys(ALL_STATUS).map(k => statusDataInit[k] ?? 0);

  const orderStatusChart = new Chart(document.getElementById('orderStatusChart'), {
    type: 'doughnut',
    data: {
      labels: statusLabelsInit,
      datasets: [{
        data: statusValuesInit,
        backgroundColor: STATUS_COLORS
      }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
  });

  // Render custom legend ban đầu
  renderStatusLegend(statusLabelsInit, statusValuesInit);

  // ----- HÀM TIỆN ÍCH -----
  function renderStatusLegend(labels, values) {
    const legendContainer = document.getElementById('orderStatusLegend');
    let html = '<ul style="list-style:none; padding:0; margin:0;">';
    labels.forEach((label, i) => {
      html += `
        <li style="display:flex; align-items:center; gap:8px; margin-bottom:6px; font-size:14px; color:#333;">
          <span style="display:inline-block; width:14px; height:14px; border-radius:3px; background:${STATUS_COLORS[i]};"></span>
          ${label} – <strong>${values[i]}</strong>
        </li>`;
    });
    html += '</ul>';
    legendContainer.innerHTML = html;
  }

  function formatDateVN(iso) {
    // iso: 'YYYY-MM-DD' -> 'DD/MM'
    const [y,m,d] = iso.split('-');
    return `${d}/${m}`;
  }

  function unionSortedDates(revObj, ordObj) {
    const s = new Set([...Object.keys(revObj || {}), ...Object.keys(ordObj || {})]);
    return Array.from(s).sort(); // vì key dạng YYYY-MM-DD, sort chuỗi là đủ
  }

  function resetToOriginal() {
    // Revenue
    revenueChart.data.labels = monthLabels;
    revenueChart.data.datasets[0].data = revenueValuesMonth;
    revenueChart.update();

    // Orders
    ordersChart.data.labels = monthLabels;
    ordersChart.data.datasets[0].data = orderValuesMonth;
    ordersChart.update();

    // Status
    const values = Object.keys(ALL_STATUS).map(k => statusDataInit[k] ?? 0);
    orderStatusChart.data.labels = Object.values(ALL_STATUS);
    orderStatusChart.data.datasets[0].data = values;
    orderStatusChart.update();
    renderStatusLegend(Object.values(ALL_STATUS), values);
  }

  async function applyFilter() {
    const from = fromInput.value;
    const to   = toInput.value;
    if (!from || !to) return;

    const url = `{{ route('admin.dashboard.filter') }}?from=${from}&to=${to}`;
    const res = await fetch(url);
    if (!res.ok) {
      // có thể hiển thị toast lỗi nếu muốn
      return;
    }
    const data = await res.json();

    const dates = unionSortedDates(data.revenueByDate, data.ordersByDate);

    // --- Revenue (triệu) ---
    if (dates.length) {
      revenueChart.data.labels = dates.map(formatDateVN);
      revenueChart.data.datasets[0].data = dates.map(d => (data.revenueByDate[d] || 0) / 1_000_000);
    } else {
      revenueChart.data.labels = ['Không có dữ liệu'];
      revenueChart.data.datasets[0].data = [0];
    }
    revenueChart.update();

    // --- Orders ---
    if (dates.length) {
      ordersChart.data.labels = dates.map(formatDateVN);
      ordersChart.data.datasets[0].data = dates.map(d => (data.ordersByDate[d] || 0));
    } else {
      ordersChart.data.labels = ['Không có dữ liệu'];
      ordersChart.data.datasets[0].data = [0];
    }
    ordersChart.update();

    // --- Status ---
    const statusValues = Object.keys(ALL_STATUS).map(k => data.orderStatusStats?.[k] ?? 0);
    const statusLabels = Object.values(ALL_STATUS);
    orderStatusChart.data.labels = statusLabels;
    orderStatusChart.data.datasets[0].data = statusValues;
    orderStatusChart.update();
    renderStatusLegend(statusLabels, statusValues);
  }

  // ----- SỰ KIỆN -----
  fromInput.addEventListener('change', applyFilter);
  toInput.addEventListener('change', applyFilter);
  clearBtn.addEventListener('click', () => {
    fromInput.value = '';
    toInput.value   = '';
    resetToOriginal();
  });
});
</script>



@endpush
