<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-installment-status')]
#[Description('Command description')]
class SyncInstallmentStatus extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
         $orders = Order::with('installments')->get();

        foreach ($orders as $order) {

            // بنفس الـ Query اللى انت كاتبه
            $hasLate = $order->installments()
                    ->where(function ($query) {
                        $query->whereDate('due_date', '<', today())->whereColumn('paid_amount', '<', 'amount');
                    })
                    ->orWhere(function ($query) {
                        $query->where('paid_amount', '>' , 0)
                            ->whereColumn('paid_amount', '<', 'amount');
                    })
                ->exists();

            if ($hasLate) {

                $status = 'late';

            } else {

                $completed = $order->installments()
                    ->whereColumn('paid_amount', '<', 'amount')
                    ->doesntExist();

                $status = $completed ? 'completed' : 'active';
            }

            if ($order->installment_status != $status) {

                $order->update([
                    'installment_status' => $status,
                ]);

            }
        }

        $this->info('Installment status synced successfully.');

        return self::SUCCESS;
    }
}
