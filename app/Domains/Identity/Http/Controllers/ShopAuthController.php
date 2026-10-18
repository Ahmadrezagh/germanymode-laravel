<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Shared\Http\Controller;

use App\Domains\SiteSettings\Models\SiteSetting;
use App\Domains\Identity\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShopAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && ! Auth::user()->is_admin) {
            return redirect()->route('shop.home');
        }

        return view('shop.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'ایمیل یا رمز عبور نادرست است.',
            ]);
        }

        $user = Auth::user();
        if ($user->is_blocked) {
            Auth::logout();

            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'حساب کاربری شما غیرفعال است.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('shop.home'))->with('ok', 'خوش آمدید '.$user->fullName());
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check() && ! Auth::user()->is_admin) {
            return redirect()->route('shop.home');
        }

        $fields = SiteSetting::getValue('signup_fields', [
            'name' => true,
            'last_name' => true,
            'mobile' => true,
            'email' => true,
            'password' => true,
            'national_code' => false,
            'address' => false,
        ]);

        return view('shop.auth.register', compact('fields'));
    }

    public function register(Request $request): RedirectResponse
    {
        $fields = SiteSetting::getValue('signup_fields', [
            'name' => true,
            'last_name' => true,
            'mobile' => true,
            'email' => true,
            'password' => true,
            'national_code' => false,
            'address' => false,
        ]);

        $rules = [
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];

        if (! empty($fields['name'])) {
            $rules['name'] = ['required', 'string', 'max:80'];
        }
        if (! empty($fields['last_name'])) {
            $rules['last_name'] = ['required', 'string', 'max:80'];
        }
        if (! empty($fields['mobile'])) {
            $rules['mobile'] = ['required', 'string', 'max:20'];
        }
        if (! empty($fields['national_code'])) {
            $rules['national_code'] = ['required', 'string', 'max:20'];
        }
        if (! empty($fields['address'])) {
            $rules['address'] = ['required', 'string', 'max:500'];
        }

        $data = $request->validate($rules);

        $user = User::query()->create([
            'name' => $data['name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'email' => $data['email'],
            'password' => $data['password'],
            'mobile' => $data['mobile'] ?? null,
            'national_code' => $data['national_code'] ?? null,
            'address' => $data['address'] ?? null,
            'is_admin' => false,
            'role' => 'customer',
            'is_blocked' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('shop.home')->with('ok', 'حساب کاربری شما ساخته شد.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('shop.home')->with('ok', 'با موفقیت خارج شدید.');
    }
}
