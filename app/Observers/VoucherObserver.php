<?php

namespace App\Observers;

use App\Models\voucher;

class VoucherObserver
{
    /**
     * Handle the voucher "created" event.
     */
    public function created(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the voucher "updated" event.
     */
    public function updated(Voucher $voucher): void
    {
        if ($voucher->number_use == 0) {
            $voucher->status = 'inactive';
        }
    }

    /**
     * Handle the voucher "deleted" event.
     */
    public function deleted(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the voucher "restored" event.
     */
    public function restored(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the voucher "force deleted" event.
     */
    public function forceDeleted(Voucher $voucher): void
    {
        //
    }
}
