<?php
namespace App\Services;

use App\Models\Installment;
use Carbon\Carbon;

class InstallmentNotificationService
{
    public function lateInstallments()
    {
        return Installment::with('order.client')
            ->where(function ($query) {
                $query->whereDate('due_date', '<', Carbon::today())->whereColumn('paid_amount', '<', 'amount');
            })
            ->orWhere(function ($query) {
                $query->where('paid_amount', '>' , 0)
                    ->whereColumn('paid_amount', '<', 'amount');
            })
            ->orderBy('due_date')
            ->get();
    }

    public function count()
    {
        return $this->lateInstallments()->count();
    }

    public function totalRemaining()
    {
        return $this->lateInstallments()->sum(function ($item) {
            return $item->amount - $item->paid_amount;
        });
    }
}
