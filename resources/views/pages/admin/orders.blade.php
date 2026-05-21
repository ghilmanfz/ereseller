@extends('layouts.admin')
@section('title', 'Kelola Pesanan')
@section('page_title', 'Kelola Pesanan')

@section('content')
@php
    $statusLabels = [
        'pending_payment' => 'Menunggu Pembayaran',
        'payment_submitted' => 'Menunggu Verifikasi',
        'awaiting_shipment_cod' => 'Menunggu Pengiriman (COD)',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'ready_for_pickup' => 'Siap Diambil',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $paymentLabels = [
        'transfer' => 'Transfer / VA',
        'ewallet' => 'E-Wallet',
        'cod' => 'COD',
        'pay_at_store' => 'Bayar di Toko',
    ];
@endphp

<div class="card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
        <div>
            <h2 class="text-base font-bold text-neutral-800 font-sans">Kelola Pesanan Masuk</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Pantau, verifikasi pembayaran, dan lanjutkan status pesanan tanpa edit manual.</p>
        </div>
        <form method="GET" action="/admin/pesanan" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ $currentQuery ?? '' }}" placeholder="Cari ID Pesanan..." class="input-field !py-2 !w-44">
            <select name="payment" class="input-field !py-2 !w-40">
                <option value="">Semua Bayar</option>
                <option value="transfer" {{ ($currentPayment ?? '') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="ewallet" {{ ($currentPayment ?? '') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                <option value="cod" {{ ($currentPayment ?? '') === 'cod' ? 'selected' : '' }}>COD</option>
                <option value="pay_at_store" {{ ($currentPayment ?? '') === 'pay_at_store' ? 'selected' : '' }}>Bayar di Toko</option>
            </select>
            <select name="status" class="input-field !py-2 !w-44">
                <option value="">Semua Status</option>
                <option value="pending_payment" {{ ($currentStatus ?? '') === 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="payment_submitted" {{ ($currentStatus ?? '') === 'payment_submitted' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="awaiting_shipment_cod" {{ ($currentStatus ?? '') === 'awaiting_shipment_cod' ? 'selected' : '' }}>Menunggu COD</option>
                <option value="processing" {{ ($currentStatus ?? '') === 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="shipped" {{ ($currentStatus ?? '') === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                <option value="ready_for_pickup" {{ ($currentStatus ?? '') === 'ready_for_pickup' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="completed" {{ ($currentStatus ?? '') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ ($currentStatus ?? '') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn-outline text-sm !py-2 !px-4">Filter</button>
        </form>
    </div>

    {{-- Bulk Actions --}}
    @if($orders->count() > 0 && auth()->user()->role === 'admin')
        <div class="mb-4 p-4 bg-neutral-50 rounded-lg hidden" id="bulk-actions">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="select-all" class="rounded border-neutral-300">
                    <span class="text-sm font-semibold text-neutral-700">
                        <span id="selected-count">0</span> dipilih
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.bulk-verify-payment') }}" class="inline" id="bulk-verify-form">
                        @csrf
                        <div class="hidden-order-ids"></div>
                        <button type="button" class="px-3 py-1.5 text-xs font-semibold bg-primary-600 text-white rounded-lg hover:bg-primary-700" onclick="submitBulkAction('bulk-verify-form')">Verifikasi Pembayaran</button>
                    </form>
                    <form method="POST" action="{{ route('admin.bulk-advance-status') }}" class="inline" id="bulk-status-form">
                        @csrf
                        <div class="hidden-order-ids"></div>
                        <button type="button" class="px-3 py-1.5 text-xs font-semibold border border-primary-300 text-primary-700 rounded-lg hover:bg-primary-50" onclick="submitBulkAction('bulk-status-form')">Lanjutkan Status</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-200">
                    @if($orders->count() > 0)
                        <th class="text-left py-3 px-2 w-8">
                            <input type="checkbox" id="select-all-header" class="rounded border-neutral-300">
                        </th>
                    @endif
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">ID Pesanan</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Pelanggan</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Metode Bayar</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Bukti</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Status</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-neutral-50">
                        <td class="py-3 px-2 w-8">
                            @if(auth()->user()->role === 'admin')
                            <input type="checkbox" name="order_ids" value="{{ $order->id }}" class="rounded border-neutral-300" onchange="updateBulkActionsUI()">
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-mono text-xs text-neutral-700">{{ $order->order_code }}</p>
                            <p class="text-[11px] text-neutral-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-medium text-neutral-800">{{ $order->recipient_name }}</p>
                            <p class="text-xs text-neutral-500">{{ $order->recipient_whatsapp }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <span class="badge bg-neutral-100 text-neutral-700">{{ $paymentLabels[$order->payment_method] ?? strtoupper($order->payment_method) }}</span>
                        </td>
                        <td class="py-3 px-2">
                            @if($order->payment_proof_path)
                                <a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank" class="inline-flex items-center gap-2">
                                    <img src="{{ asset('storage/'.$order->payment_proof_path) }}" alt="Bukti" class="w-10 h-10 rounded-lg object-cover border border-neutral-200">
                                    <span class="text-xs font-semibold text-primary-600">Lihat</span>
                                </a>
                            @elseif(in_array($order->payment_method, ['cod', 'pay_at_store'], true))
                                <span class="text-xs text-neutral-500">Tidak perlu</span>
                            @else
                                <span class="text-xs text-amber-600">Belum upload</span>
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <span class="badge bg-primary-50 text-primary-700">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex flex-col gap-2">
                            @if(auth()->user()->role === 'admin')
                                @if(in_array($order->payment_method, ['transfer', 'ewallet'], true) && $order->status === 'payment_submitted')
                                    <form method="POST" action="/admin/pesanan/{{ $order->id }}/verifikasi-pembayaran">
                                        @csrf
                                        <button class="px-3 py-1.5 text-xs font-semibold bg-primary-600 text-white rounded-lg hover:bg-primary-700">Verifikasi Bayar</button>
                                    </form>
                                @endif

                                {{-- Status Change Form --}}
                                @php
                                    $transitions = [
                                        'pending_payment' => ['payment_submitted', 'cancelled'],
                                        'payment_submitted' => ['processing', 'ready_for_pickup', 'cancelled'],
                                        'awaiting_shipment_cod' => ['shipped', 'cancelled'],
                                        'processing' => ['shipped', 'cancelled'],
                                        'shipped' => ['completed', 'cancelled'],
                                        'ready_for_pickup' => ['completed', 'cancelled'],
                                    ];
                                    $validNextStatus = $transitions[$order->status] ?? [];
                                @endphp
                                @if(! empty($validNextStatus))
                                    <form method="POST" action="{{ route('admin.change-order-status', $order->id) }}" class="flex items-center gap-1">
                                        @csrf
                                        <select name="new_status" class="px-2 py-1.5 text-xs border border-primary-300 text-primary-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-200 w-full">
                                            <option value="">Pilih Status</option>
                                            @foreach($validNextStatus as $nextStatus)
                                                <option value="{{ $nextStatus }}">{{ $statusLabels[$nextStatus] ?? $nextStatus }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold border border-primary-300 text-primary-700 rounded-lg hover:bg-primary-50 whitespace-nowrap">Ubah</button>
                                    </form>
                                @else
                                    <span class="text-xs text-neutral-400">Status final</span>
                                @endif

                                @if($order->shipping_method === 'pickup' && $order->status === 'ready_for_pickup')
                                    <form method="POST" action="/admin/pesanan/{{ $order->id }}/reminder-pickup">
                                        @csrf
                                        <button class="px-3 py-1.5 text-xs font-semibold border border-neutral-300 text-neutral-700 rounded-lg hover:bg-neutral-50">Reminder Pickup</button>
                                    </form>
                                @endif
                            @else
                                <span class="text-xs text-neutral-400">View only</span>
                            @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-sm text-neutral-500">Belum ada data pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->count() > 0)
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<script>
function updateBulkActionsUI() {
    const checkboxes = document.querySelectorAll('input[name=order_ids]');
    const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const bulkActionsDiv = document.getElementById('bulk-actions');
    const selectAllHeader = document.getElementById('select-all-header');
    const selectAll = document.getElementById('select-all');
    const selectedCountLabel = document.getElementById('selected-count');

    if (selectedCount > 0) {
        bulkActionsDiv.classList.remove('hidden');
    } else {
        bulkActionsDiv.classList.add('hidden');
    }

    if (selectedCountLabel) {
        selectedCountLabel.textContent = String(selectedCount);
    }

    selectAllHeader.checked = selectedCount === checkboxes.length && checkboxes.length > 0;
    if (selectAll) {
        selectAll.checked = selectAllHeader.checked;
    }
}

function submitBulkAction(formId) {
    const checkboxes = document.querySelectorAll('input[name=order_ids]:checked');
    const form = document.getElementById(formId);
    const hiddenDiv = form.querySelector('.hidden-order-ids');

    if (!hiddenDiv) {
        return;
    }
    
    hiddenDiv.innerHTML = '';
    checkboxes.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'order_ids[]';
        input.value = cb.value;
        hiddenDiv.appendChild(input);
    });
    
    form.submit();
}

document.getElementById('select-all-header')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name=order_ids]');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActionsUI();
});

document.getElementById('select-all')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name=order_ids]');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkActionsUI();
});

document.querySelectorAll('input[name=order_ids]').forEach(cb => {
    cb.addEventListener('change', updateBulkActionsUI);
});
</script>
@endsection
