<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        $now = Carbon::now();
        $monthDate = $now->format('F j'); 
        $dayIndo = $now->isoFormat('dddd'); 

        $userId = auth()->id();

        $userLocation = auth()->user()->branch_location;

        $totalTransactions = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $now->toDateString())
            ->count();

        return view('admin.home', compact('monthDate', 'dayIndo', 'totalTransactions', 'userLocation'));
    }
}