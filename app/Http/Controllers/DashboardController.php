<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function stats()
    {
        $userId = Auth::id();

        $earnings = Invoice::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('amount');

        $unpaid = Invoice::where('user_id', $userId)
            ->where('status', 'unpaid')
            ->sum('amount');

        $time = TimeEntry::where('user_id', $userId)
            ->sum('minutes');

        return [
            'total_earned' => $earnings,
            'unpaid' => $unpaid,
            'hours_tracked' => round($time / 60, 2)
        ];
    }
}
