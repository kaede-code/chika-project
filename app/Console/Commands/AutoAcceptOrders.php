<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoAcceptOrders extends Command
{
    protected $signature = 'orders:auto-accept';
    protected $description = 'Auto-accept orders in "Diproses" status after 3 days';

    public function handle()
    {
        $cutoff = Carbon::now()->subDays(3);

        $updated = Order::query()
            ->where('status', 'Diproses')
            ->where('updated_at', '<=', $cutoff)
            ->update(['status' => 'Selesai']);

        $this->info("Auto-accepted {$updated} order(s).");
    }
}
