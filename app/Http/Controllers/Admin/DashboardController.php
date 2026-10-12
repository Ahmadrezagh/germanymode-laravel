<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::query()->count(),
            'categories' => Category::query()->count(),
            'priced' => Product::query()->where('price', '>', 0)->count(),
            'users' => User::query()->count(),
        ];

        $latestProducts = Product::query()->latest('id')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'latestProducts'));
    }
}
