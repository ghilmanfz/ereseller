@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan')

@section('content')
<div class="max-w-3xl">
    {{-- Success Toast --}}
    @if(session('success'))
        <div id="success-toast" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('success-toast');
                if (toast) toast.remove();
            }, 4000);
        </script>
    @endif

    <div class="card p-6">
        <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Pengaturan Toko
            @if(auth()->user()->role === 'owner')
            <span class="ml-2 text-xs font-normal text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">Read Only</span>
            @endif
        </h2>
        <form id="settings-form" method="POST" action="/admin/pengaturan" class="space-y-4" @if(auth()->user()->role === 'owner') onsubmit="return false;" @endif>
            @csrf
            
            {{-- Store Name --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nama Toko</label>
                <input 
                    type="text" 
                    name="store_name" 
                    value="{{ old('store_name', $settings['store_name']) }}" 
                    class="input-field {{ $errors->has('store_name') ? 'border-red-500' : '' }}" 
                    required>
                @if($errors->has('store_name'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('store_name') }}</p>
                @endif
            </div>

            {{-- Store WhatsApp --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">WhatsApp Toko</label>
                <input 
                    type="text" 
                    name="store_whatsapp" 
                    value="{{ old('store_whatsapp', $settings['store_whatsapp']) }}" 
                    class="input-field {{ $errors->has('store_whatsapp') ? 'border-red-500' : '' }}" 
                    placeholder="Contoh: 082233334444"
                    required>
                @if($errors->has('store_whatsapp'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('store_whatsapp') }}</p>
                @endif
            </div>

            {{-- Pickup Address --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Alamat Pickup</label>
                <textarea 
                    name="pickup_address" 
                    rows="3" 
                    class="input-field {{ $errors->has('pickup_address') ? 'border-red-500' : '' }}" 
                    placeholder="Alamat toko untuk pickup pesanan"
                    required>{{ old('pickup_address', $settings['pickup_address']) }}</textarea>
                @if($errors->has('pickup_address'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('pickup_address') }}</p>
                @endif
            </div>

            {{-- Pickup Reminder Template --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Template Reminder Pickup</label>
                <textarea 
                    name="pickup_reminder_template" 
                    rows="4" 
                    class="input-field {{ $errors->has('pickup_reminder_template') ? 'border-red-500' : '' }}" 
                    placeholder="Pesan yang akan dikirim saat mengingatkan customer untuk pickup"
                    required>{{ old('pickup_reminder_template', $settings['pickup_reminder_template']) }}</textarea>
                <p class="text-xs text-neutral-500 mt-1.5">Gunakan {order_code} dan {customer_name} sebagai placeholder</p>
                @if($errors->has('pickup_reminder_template'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('pickup_reminder_template') }}</p>
                @endif
            </div>

            {{-- Payment Methods Section --}}
            <div class="pt-2 border-t border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-700 mb-4 mt-4">Data Pembayaran</h3>
            </div>

            {{-- Bank Account Number --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">No. Rekening Bank</label>
                <input 
                    type="text" 
                    name="bank_account_number" 
                    value="{{ old('bank_account_number', $settings['bank_account_number']) }}" 
                    class="input-field {{ $errors->has('bank_account_number') ? 'border-red-500' : '' }}" 
                    placeholder="Contoh: 1234567890 atau atas nama Bank">
                @if($errors->has('bank_account_number'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('bank_account_number') }}</p>
                @endif
            </div>

            {{-- E-Wallet Account --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nomor E-Wallet (OVO/GoPay/Dana)</label>
                <input 
                    type="text" 
                    name="ewallet_number" 
                    value="{{ old('ewallet_number', $settings['ewallet_number']) }}" 
                    class="input-field {{ $errors->has('ewallet_number') ? 'border-red-500' : '' }}" 
                    placeholder="Contoh: 081234567890">
                @if($errors->has('ewallet_number'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('ewallet_number') }}</p>
                @endif
            </div>

            {{-- Form Actions --}}
            @if(auth()->user()->role === 'admin')
            <div class="flex gap-2 pt-4 border-t border-neutral-200">
                <button type="submit" class="btn-primary">
                    <span id="submit-text">Simpan Pengaturan</span>
                </button>
                <button type="reset" class="btn-outline">Reset</button>
            </div>
            @else
            <div class="flex gap-2 pt-4 border-t border-neutral-200">
                <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Anda hanya memiliki akses baca. Pengaturan tidak dapat diubah.
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<script>
// Track unsaved changes
const form = document.getElementById('settings-form');
let isFormDirty = false;

form?.addEventListener('change', () => {
    isFormDirty = true;
});

form?.addEventListener('submit', () => {
    isFormDirty = false;
});

// Warn on unsaved changes
window.addEventListener('beforeunload', (e) => {
    if (isFormDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Submit feedback
form?.addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submit-text');
    const originalText = 'Simpan Pengaturan';
    
    submitBtn.disabled = true;
    submitText.textContent = 'Menyimpan...';
    
    setTimeout(() => {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    }, 2000);
});
</script>
@endsection
