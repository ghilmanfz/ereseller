<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('pages.profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
        ]);

        $whatsapp = $this->normalizeWhatsapp($data['whatsapp']);

        if ($whatsapp === '') {
            throw ValidationException::withMessages([
                'whatsapp' => 'Nomor WhatsApp tidak valid.',
            ]);
        }

        $email = $data['email'];

        if (User::query()->where('whatsapp', $whatsapp)->where('id', '!=', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'whatsapp' => 'Nomor WhatsApp sudah digunakan akun lain.',
            ]);
        }

        if (User::query()->where('email', $email)->where('id', '!=', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah digunakan akun lain.',
            ]);
        }

        $user->update([
            'name' => $data['name'],
            'whatsapp' => $whatsapp,
            'address' => $data['address'],
            'email' => $email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Kata sandi saat ini tidak sesuai.',
            ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
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
