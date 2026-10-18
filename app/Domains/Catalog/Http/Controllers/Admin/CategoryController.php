<?php

namespace App\Domains\Catalog\Http\Controllers\Admin;

use App\Shared\Http\Controller;
use App\Domains\Catalog\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with('parent')
            ->withCount('products')
            ->orderBy('id')
            ->paginate(40);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parents = Category::query()->orderBy('name')->get();

        return view('admin.categories.form', [
            'category' => new Category,
            'parents' => $parents,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::query()->create($this->validated($request));

        return redirect()->route('admin.categories.index')->with('success', 'دسته‌بندی ذخیره شد.');
    }

    public function edit(Category $category): View
    {
        $parents = Category::query()->where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.categories.form', [
            'category' => $category,
            'parents' => $parents,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validated($request));

        return redirect()->route('admin.categories.index')->with('success', 'دسته‌بندی به‌روزرسانی شد.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'دسته‌بندی حذف شد.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);
    }
}
