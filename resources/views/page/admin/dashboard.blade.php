@extends('layout.app')

@section('content')

<div class="ttl">Dashboard</div>

<div class="content-box" style="padding:14px;">
    <div class="stat-grid">
        <div class="stat-card card-primary">
            <div class="stat-label">Total Order</div>
            <div class="stat-value">{{ $totalOrders }}</div>
        </div>
        <div class="stat-card card-secondary">
            <div class="stat-label">Pendapatan</div>
            <div class="stat-value" style="color:var(--secondary);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card" style="background:#fff8f0; border-color:rgba(245,158,11,.2);">
            <div class="stat-label">Menunggu</div>
            <div class="stat-value" style="color:#b45309;">{{ $pendingOrders }}</div>
        </div>
        <div class="stat-card card-primary">
            <div class="stat-label">Diproses</div>
            <div class="stat-value" style="color:var(--primary);">{{ $processingOrders }}</div>
        </div>
    </div>

    <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:14px;">
        @php($periods = ['1hari' => '1 Hari', '1minggu' => '1 Minggu', '1bulan' => '1 Bulan', '1tahun' => '1 Tahun', 'all' => 'All'])
        @foreach($periods as $key => $label)
            <a href="?period={{ $key }}" class="period-btn {{ $period === $key ? 'period-btn-active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="chart-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <div>
                <div style="font-weight:900; font-size:16px; color:var(--secondary);">Revenue</div>
                <div style="font-weight:1000; font-size:24px; color:var(--primary); margin-top:2px;">Rp {{ number_format(array_sum($chartData['values']), 0, ',', '.') }}</div>
            </div>
            <div style="display:flex; gap:12px; align-items:center;">
                <span style="width:8px; height:8px; border-radius:50%; background:var(--primary); display:inline-block;"></span>
                <span style="font-weight:800; font-size:11px; color:var(--text-muted); text-transform:uppercase;">Revenue</span>
            </div>
        </div>
        <div id="chartRevenue"></div>
    </div>
</div>

<style>
.period-btn {
    padding:6px 14px;
    border-radius:999px;
    font-weight:900;
    font-size:12px;
    background:#f1f5f9;
    color:#475569;
    transition:all .15s;
    text-decoration:none;
}
.period-btn:hover { background:#e2e8f0; }
.period-btn-active {
    background:var(--secondary) !important;
    color:#fff !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function() {
    var labels = @json($chartData['labels']);
    var values = @json($chartData['values']);
    var tickAmount = {{ $tickAmount }};

    var options = {
        chart: {
            type: 'area',
            height: 280,
            toolbar: { show: false },
            background: 'transparent',
            foreColor: 'rgba(30,41,59,.5)',
            fontFamily: 'Inter, sans-serif',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        series: [{
            name: 'Revenue',
            data: values
        }],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2.5,
            colors: ['#4BC89B']
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.04,
                colorStops: [
                    { offset: 0, color: '#4BC89B', opacity: 0.35 },
                    { offset: 100, color: '#4BC89B', opacity: 0.04 }
                ]
            }
        },
        markers: {
            size: 0,
            hover: {
                size: 5,
                sizeOffset: 3
            }
        },
        tooltip: {
            theme: 'light',
            x: { show: true },
            y: {
                formatter: function(val) {
                    return 'Rp ' + Number(val).toLocaleString('id-ID');
                }
            }
        },
        grid: {
            borderColor: 'rgba(2,6,23,.06)',
            strokeDashArray: 3,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } }
        },
        xaxis: {
            categories: labels,
            tickAmount: tickAmount,
            labels: {
                style: {
                    colors: 'rgba(30,41,59,.5)',
                    fontSize: '10px',
                    fontWeight: 700
                },
                maxHeight: 40,
                rotate: 0
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
            tooltip: { enabled: false }
        },
        yaxis: {
            labels: {
                style: {
                    colors: 'rgba(30,41,59,.5)',
                    fontSize: '10px',
                    fontWeight: 700
                },
                formatter: function(val) {
                    if (val >= 1000000) return 'Rp' + (val / 1000000).toFixed(1) + 'jt';
                    if (val >= 1000) return 'Rp' + (val / 1000).toFixed(0) + 'rb';
                    return 'Rp' + val;
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        }
    };

    var chart = new ApexCharts(document.querySelector('#chartRevenue'), options);
    chart.render();

    function updateStats(data) {
        var cards = document.querySelectorAll('.content-box > div:first-child > div');
        if (cards.length >= 4) {
            cards[0].querySelector('div:last-child').textContent = data.totalOrders;
            cards[1].querySelector('div:last-child').textContent = 'Rp ' + Number(data.totalRevenue).toLocaleString('id-ID');
            cards[2].querySelector('div:last-child').textContent = data.pendingOrders;
            cards[3].querySelector('div:last-child').textContent = data.processingOrders;
        }
    }

    setInterval(function() {
        var url = '{{ route("admin.dashboard.data") }}?period={{ $period }}';
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                chart.updateOptions({
                    xaxis: { categories: d.chartData.labels, tickAmount: d.tickAmount }
                });
                chart.updateSeries([{ data: d.chartData.values }]);
                updateStats(d);
            })
            .catch(function() {});
    }, 30000);
})();
</script>

@endsection
