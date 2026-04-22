<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('pages.auth.login');
    }

    public function showRegister(): View
    {
        return view('pages.auth.register');
    }

    public function showForgotPassword(): View
    {
        return view('pages.auth.forgot-password');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'whatsapp' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $normalizedWhatsapp = $this->normalizeWhatsapp($credentials['whatsapp']);

        /** @var User|null $user */
        $user = User::query()->where('whatsapp', $normalizedWhatsapp)->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'whatsapp' => 'Nomor WhatsApp atau kata sandi tidak valid.',
            ]);
        }

        Auth::login($user, (bool) $request->boolean('remember'));

        $request->session()->regenerate();

        if (in_array($user->role, ['admin', 'owner'], true)) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/katalog');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8'],
            'terms' => ['accepted'],
        ]);

        $whatsapp = $this->normalizeWhatsapp($data['whatsapp']);

        if ($whatsapp === '') {
            throw ValidationException::withMessages([
                'whatsapp' => 'Nomor WhatsApp tidak valid.',
            ]);
        }

        if (User::query()->where('whatsapp', $whatsapp)->exists()) {
            throw ValidationException::withMessages([
                'whatsapp' => 'Nomor WhatsApp sudah terdaftar.',
            ]);
        }

        $email = $data['email'];

        if (User::query()->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah digunakan.',
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'whatsapp' => $whatsapp,
            'address' => $data['address'],
            'email' => $email,
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect('/katalog')->with('success', 'Pendaftaran berhasil. Selamat datang.');
    }

    public function resetForgotPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User|null $user */
        $user = User::query()->where('email', $data['email'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect('/login')->with('success', 'Kata sandi berhasil direset. Silakan login.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function devLogin(Request $request, string $role): RedirectResponse
    {
        if (! in_array($role, ['admin', 'owner', 'customer'], true)) {
            abort(404);
        }

        $user = User::query()->where('role', $role)->first();

        if (! $user) {
            $seedWhatsapp = match($role) {
                'admin'    => '081111111111',
                'owner'    => '083333333333',
                default    => '082222222222',
            };
            $user = User::create([
                'name' => match($role) {
                    'admin'  => 'Admin Sintia',
                    'owner'  => 'Owner Demo',
                    default  => 'Customer Demo',
                },
                'whatsapp' => $seedWhatsapp,
                'address' => 'Parungpanjang, Bogor',
                'email' => $seedWhatsapp.'@sr12.local',
                'password' => Hash::make('password123'),
                'role' => $role,
                'status' => 'active',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect(in_array($role, ['admin', 'owner'], true) ? '/admin' : '/katalog');
    }

    private function normalizeWhatsapp(string $whatsapp): string
    {
        $digits = preg_replace('/\D+/', '', $whatsapp) ?? '';

        if (str_starts_with($digits, '62')) {
            $digits = '0'.substr($digits, 2);
        }

        return $digits;
    }
}
