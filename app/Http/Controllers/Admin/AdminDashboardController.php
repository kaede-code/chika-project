<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'all');

        $totalOrders = Order::query()->count();
        $totalRevenue = (int) Order::query()->where('status', 'Selesai')->sum('total_amount');
        $pendingOrders = Order::query()->where('status', 'Menunggu Verifikasi')->count();
        $processingOrders = Order::query()->where('status', 'Diproses')->count();

        $chartData = $this->getChartData($period);

        $tickAmount = match ($period) {
            '1hari' => 8,
            '1minggu' => 7,
            '1bulan' => 10,
            '1tahun' => 12,
            default => 12,
        };

        return view('page.admin.dashboard', compact(
            'period', 'totalOrders', 'totalRevenue', 'pendingOrders', 'processingOrders', 'chartData', 'tickAmount'
        ));
    }

    private function getChartData(string $period): array
    {
        $now = Carbon::now();
        $driver = DB::getDriverName();

        if ($period === '1hari') {
            $start = $now->copy()->subHours(24)->minute(0)->second(0);
            $labelFormat = 'H:i';
            $keyFormat = 'Y-m-d H:00';
            $step = 'hour';
            $groupExpr = $driver === 'mysql'
                ? "DATE_FORMAT(created_at, '%Y-%m-%d %H:00')"
                : "strftime('%Y-%m-%d %H:00', created_at)";
        } elseif ($period === '1minggu') {
            $start = $now->copy()->subDays(7);
            $labelFormat = 'd M';
            $keyFormat = 'Y-m-d';
            $step = 'day';
            $groupExpr = 'DATE(created_at)';
        } elseif ($period === '1bulan') {
            $start = $now->copy()->subDays(30);
            $labelFormat = 'd M';
            $keyFormat = 'Y-m-d';
            $step = 'day';
            $groupExpr = 'DATE(created_at)';
        } elseif ($period === '1tahun') {
            $start = $now->copy()->subMonths(12);
            $labelFormat = 'M Y';
            $keyFormat = 'Y-m';
            $step = 'month';
            $groupExpr = $driver === 'mysql'
                ? "DATE_FORMAT(created_at, '%Y-%m')"
                : "strftime('%Y-%m', created_at)";
        } else {
            $start = $now->copy()->subYears(5);
            $labelFormat = 'M Y';
            $keyFormat = 'Y-m';
            $step = 'month';
            $groupExpr = $driver === 'mysql'
                ? "DATE_FORMAT(created_at, '%Y-%m')"
                : "strftime('%Y-%m', created_at)";
        }

        $labels = [];
        $cursor = $start->copy();
        while ($cursor->lessThanOrEqualTo($now)) {
            $labels[] = $cursor->format($labelFormat);
            $cursor = match ($step) {
                'hour' => $cursor->addHour(),
                'day' => $cursor->addDay(),
                'month' => $cursor->addMonth(),
                default => $cursor->addDay(),
            };
        }

        $rows = Order::query()
            ->selectRaw("{$groupExpr} as bucket, SUM(total_amount) as total")
            ->where('status', 'Selesai')
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $now)
            ->groupBy(DB::raw($groupExpr))
            ->orderBy(DB::raw($groupExpr))
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $map[(string)$row->bucket] = (int)$row->total;
        }

        $values = [];
        $cursor2 = $start->copy();
        while ($cursor2->lessThanOrEqualTo($now)) {
            $key = $cursor2->format($keyFormat);
            $values[] = $map[$key] ?? 0;
            $cursor2 = match ($step) {
                'hour' => $cursor2->addHour(),
                'day' => $cursor2->addDay(),
                'month' => $cursor2->addMonth(),
                default => $cursor2->addDay(),
            };
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function data(Request $request)
    {
        $period = $request->query('period', 'all');
        $chartData = $this->getChartData($period);

        $tickAmount = match ($period) {
            '1hari' => 8,
            '1minggu' => 7,
            '1bulan' => 10,
            '1tahun' => 12,
            default => 12,
        };

        return response()->json([
            'chartData' => $chartData,
            'tickAmount' => $tickAmount,
            'totalOrders' => Order::query()->count(),
            'totalRevenue' => (int) Order::query()->where('status', 'Selesai')->sum('total_amount'),
            'pendingOrders' => Order::query()->where('status', 'Menunggu Verifikasi')->count(),
            'processingOrders' => Order::query()->where('status', 'Diproses')->count(),
        ]);
    }
}
