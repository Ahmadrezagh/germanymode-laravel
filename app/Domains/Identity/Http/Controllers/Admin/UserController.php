<?php

namespace App\Domains\Identity\Http\Controllers\Admin;

use App\Shared\Http\Controller;
use App\Domains\Identity\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));

        $users = User::query()
            ->where(function ($query) {
                $query->where('is_admin', false)->orWhereNull('is_admin');
            })
            ->where(function ($query) {
                $query->whereNull('role')->orWhere('role', 'customer');
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('mobile', 'like', "%{$q}%");
                });
            })
            ->orderByRaw('portal_id is null')
            ->orderByDesc('portal_id')
            ->orderByDesc('id')
            ->paginate(200)
            ->appends(['q' => $q]);

        return view('admin.users.index', compact('users', 'q'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'address' => 'nullable|string',
            'province' => 'nullable|string|max:80',
            'city' => 'nullable|string|max:80',
        ]);

        User::query()->create([
            ...$data,
            'password' => Hash::make($data['password'] ?: '123456'),
            'is_admin' => false,
            'role' => 'customer',
        ]);

        return back()->with('ok', 'کاربر ثبت شد.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'address' => 'nullable|string',
            'is_blocked' => 'nullable|boolean',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_blocked'] = $request->boolean('is_blocked');
        $user->update($data);

        return back()->with('ok', 'کاربر به‌روزرسانی شد.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return back()->with('error', 'نمی‌توان مدیر را از این بخش حذف کرد.');
        }
        $user->delete();

        return back()->with('ok', 'کاربر حذف شد.');
    }
}
