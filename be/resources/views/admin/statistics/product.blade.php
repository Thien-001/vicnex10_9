@extends('layouts.layout')

@section('content')
<style>
    .row-compare {
    display: flex;
    align-items: flex-start;
    gap: 30px;
    flex-wrap: wrap;
    background: #f9f9f9;
    padding: 20px 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.group-moc {
    display: flex;
    flex-direction: column;
    gap: 15px;
    flex: 1 1 auto;
}

.group-moc .moc-1,
.group-moc .moc-2 {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;

}

.group-moc select {
    padding: 8px;
    font-size: 15px;
    min-width: 120px;
    flex: 1;
        border-radius: 8px;

}

.button-so-sanh {
    align-self: center;
}

.button-so-sanh button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.button-so-sanh button:hover {
    background-color: #218838;
}
.table-dark tr th{
    color: #fff;
    background-color: #997979;
}


</style>
<div class="head-title">
        <div class="left">
            <h3 class="thongke left mt-5">📊 Biểu Đồ Top 10 Sản Phẩm Bán Chạy</h3>
        </div>
        <a href="{{ route('admin.statistics.product') }}" class="btn-download">
            <span class="text">Quay lại</span>
	</a>
    </div>

{{-- Bộ lọc so sánh --}}
<form method="GET" class="form-compare" style="max-width: 1200px; margin: 0 auto;">
    <div class="row-compare">
        <div class="group-moc">
            <div class="moc-1">
                <select name="month1">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month1') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                    @endfor
                </select>
                <select name="year1">
                    @for ($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year1') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <select name="product1">
                    @foreach ($productList as $id => $name)
                        <option value="{{ $id }}" {{ request('product1') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="moc-2">
                <select name="month2">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month2') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                    @endfor
                </select>
                <select name="year2">
                    @for ($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year2') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <select name="product2">
                    @foreach ($productList as $id => $name)
                        <option value="{{ $id }}" {{ request('product2') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="button-so-sanh">
            <button type="submit">So sánh</button>
        </div>
    </div>
</form>





{{-- Biểu đồ --}}
<div class="gant-chart" style="width:100%;max-width:1200px;margin:32px auto;">
    <canvas id="productChart"></canvas>
</div>

{{-- Bảng thống kê tất cả sản phẩm --}}
<h4 class="thongke left mt-5">📦 Bảng Thống Kê Các Sản Phẩm</h4>
<div class="body-statistics">
    <div class="stat-table mt-4" style="width:100%;max-width:1200px;margin:0 auto;">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng bán</th>
                    <th>Tổng doanh thu (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filteredStats as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->total_sold }}</td>
                        <td>{{ number_format($item->total_revenue) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxProduct = document.getElementById('productChart').getContext('2d');

new Chart(ctxProduct, {
    type: 'bar',
    data: {
        labels: @json($productNames),
        datasets: [
            {
                label: 'Số lượng bán',
                data: @json($productSales),
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                yAxisID: 'y',
            },
            {
                label: 'Doanh thu (VNĐ)',
                data: @json($productRevenue),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        indexAxis: 'x',
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: {
                beginAtZero: true,
                position: 'left',
                title: { display: true, text: 'Số lượng' }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: { drawOnChartArea: false },
                title: { display: true, text: 'Doanh thu (VNĐ)' }
            }
        },
        plugins: {
            legend: { position: 'top' },
        }
    }
});
</script>
@endsection

@section('scripts')

@endsection
