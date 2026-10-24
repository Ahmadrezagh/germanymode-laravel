<?php

namespace App\Domains\Orders\Http\Controllers\Admin;

use App\Domains\Orders\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function newest(Request $request): View
    {
        return $this->index($request, 'new', 'آخرین سفارشات', 'سفارشات در انتظار / جدید');
    }

    public function done(Request $request): View
    {
        return $this->index($request, 'done', 'سفارشات انجام شده', 'سفارشات تایید/ارسال‌شده');
    }

    public function show(Order $order): View
    {
        $order->load(['items', 'user']);

        return view('admin.orders.show', [
            'order' => $order,
            'title' => 'فاکتور شماره '.$order->factor_no,
        ]);
    }

    private function index(Request $request, string $mode, string $title, string $subtitle): View
    {
        $q = trim((string) $request->query('q', ''));

        $query = Order::query()->with('user')->orderByDesc('id');

        if ($mode === 'new') {
            $query->where(function ($inner) {
                $inner->where('status', 'new')
                    ->orWhere(function ($x) {
                        $x->where('is_shipped', false)->where('status', '!=', 'done');
                    });
            });
        } else {
            $query->where(function ($inner) {
                $inner->where('status', 'done')
                    ->orWhere('is_shipped', true)
                    ->orWhere(function ($x) {
                        $x->where('is_confirmed', true)->where('is_prepared', true);
                    });
            });
        }

        if ($q !== '') {
            $query->where(function ($inner) use ($q) {
                $inner->where('factor_no', 'like', "%{$q}%")
                    ->orWhere('buyer_name', 'like', "%{$q}%")
                    ->orWhere('portal_id', 'like', "%{$q}%");
            });
        }

        $orders = $query->paginate(50)->appends(['q' => $q]);

        return view('admin.orders.index', [
            'title' => $title,
            'subtitle' => $subtitle,
            'orders' => $orders,
            'mode' => $mode,
            'q' => $q,
        ]);
    }
}
