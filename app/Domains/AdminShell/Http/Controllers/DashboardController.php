<?php

namespace App\Domains\AdminShell\Http\Controllers;

use App\Shared\Http\Controller;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Identity\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::query()->count(),
            'categories' => Category::query()->count(),
            'priced' => Product::query()->whereEffectivePriceGreaterThan(0)->count(),
            'users' => User::query()->count(),
        ];

        $latestProducts = Product::query()->with('media')->latest('id')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'latestProducts'));
    }
}
