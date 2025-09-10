@extends('layouts.layout')

@section('content')
<style>
    .head-title {
        margin-top: 24px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .head-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0154b9;
        margin: 0;
    }

    .btn-download {
        background: #f6f8fc;
        color: #0154b9;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        border: 1px solid #e0e7ef;
    }

    .btn-download:hover {
        background: #e0e7ef;
        color: #003e8a;
    }

    .gant-chart {
        width: 100%;
        max-width: 1200px;
        margin: 32px auto 0 auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(1, 84, 185, 0.07);
        padding: 24px 18px 18px 18px;
    }

    .stat-table {
        width: 100%;
        max-width: 1200px;
        margin: 32px auto 0 auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(1, 84, 185, 0.07);
        padding: 18px 12px 12px 12px;
    }

    .stat-table table {
        width: 100%;
        border-collapse: collapse;
        background: transparent;
    }

    .stat-table th,
    .stat-table td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 1rem;
        text-align: center;
    }

    .stat-table th {
        background: #f6f8fc;
        font-weight: 700;
        color: #0154b9;
    }

    .stat-table tr:last-child td {
        border-bottom: none;
    }

    .stat-table h4 {
        color: #0154b9;
        font-weight: 700;
        margin-bottom: 18px;
        font-size: 1.2rem;
    }

    @media (max-width: 900px) {
        .gant-chart,
        .stat-table {
            max-width: 100%;
            padding: 10px 4px;
        }
    }
</style>

<div class="head-title">
    <div class="left">
        <h3>📊 Thống Kê Đặt Sân Theo Tháng</h3>
    </div>
    <a href="{{ route('admin.statistics.booking') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<div class="gant-chart">
    <canvas id="statChart"></canvas>
</div>

<div class="stat-table">
    <h4>📋 Bảng Thống Kê Đặt Sân</h4>
    <table>
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Lượt đặt sân</th>
                <th>Doanh thu đặt sân (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $index => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $courtBookingCounts[$index] }}</td>
                    <td>{{ number_format($courtBookingRevenue[$index]) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('statChart').getContext('2d');
    const statChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Lượt đặt sân',
                    data: @json($courtBookingCounts),
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    yAxisID: 'y1',
                },
                {
                    label: 'Doanh thu đặt sân (VNĐ)',
                    data: @json($courtBookingRevenue),
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    yAxisID: 'y',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Doanh thu (VNĐ)' }
                },
                y1: {
                    beginAtZero: true,
                    type: 'linear',
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Số lượt đặt sân' }
                }
            }
        }
    });
</script>
@endsection

@section('scripts')

@endsection
