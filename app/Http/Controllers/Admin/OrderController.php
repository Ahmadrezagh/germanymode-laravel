<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function newest(): View
    {
        $orders = collect();

        return view('admin.orders.index', [
            'title' => 'آخرین سفارشات',
            'subtitle' => '50 سفارش آخر',
            'orders' => $orders,
            'mode' => 'new',
        ]);
    }

    public function done(): View
    {
        $orders = collect();

        return view('admin.orders.index', [
            'title' => 'سفارشات انجام شده',
            'subtitle' => '50 سفارش آخر انجامی',
            'orders' => $orders,
            'mode' => 'done',
        ]);
    }
}
