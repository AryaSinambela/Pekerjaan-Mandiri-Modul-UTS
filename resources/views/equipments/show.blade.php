@extends('layouts.app')

@section('title', 'Detail Aset ' . $equipment->asset_code . ' - DEL-LEATS')

@section('content')
<section aria-labelledby="detail-heading">
    <div style="margin-bottom: 1.25rem;">
        <a href="{{ route('equipments.index') }}" class="btn btn-outline">&larr; Kembali ke Katalog</a>
    </div>

    <article class="card-panel" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <div>
            <div class="asset-thumb" style="height: 280px; border-radius: 8px; border: 1px solid var(--border-color);">
                @if($equipment->image_path)
                    <img src="{{ asset('storage/' . $equipment->image_path) }}" alt="Foto {{ $equipment->name }}">
                @else
                    <div style="text-align: center; color: #64748b;">
                        <p style="font-weight: 600;">Belum Ada Foto Fisik</p>
                        <small>{{ $equipment->asset_code }}</small>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                <span class="category-label">{{ $equipment->category->name }} ({{ $equipment->category->code }})</span>
                @if($equipment->status === 'tersedia')
                    <span class="badge badge-tersedia">Tersedia</span>
                @elseif($equipment->status === 'dipinjam')
                    <span class="badge badge-dipinjam">Dipinjam</span>
                @else
                    <span class="badge badge-dalam_perbaikan">Dalam Perbaikan</span>
                @endif
            </div>

            <h1 id="detail-heading" style="font-size: 1.5rem; margin-bottom: 0.25rem;">{{ $equipment->name }}</h1>
            <p class="asset-code" style="margin-bottom: 1rem;">Kode Aset: {{ $equipment->asset_code }}</p>

            <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 1rem;">
                <h3 style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.35rem;">Spesifikasi Teknis:</h3>
                <p style="font-size: 0.95rem;">{{ $equipment->specification }}</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <small style="color: var(--text-muted); display: block;">Kondisi Fisik</small>
                    <strong style="text-transform: capitalize;">{{ str_replace('_', ' ', $equipment->condition) }}</strong>
                </div>
                <div>
                    <small style="color: var(--text-muted); display: block;">Tarif Kompensasi Harian</small>
                    <strong style="color: #0f766e;">Rp {{ number_format($equipment->daily_rental_fee, 0, ',', '.') }} / hari</strong>
                </div>
            </div>

            @if($equipment->status === 'tersedia')
                <a href="{{ route('loans.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-primary">
                    Ajukan Peminjaman Perangkat Ini
                </a>
            @else
                <span class="btn btn-disabled">Perangkat Sedang Tidak Tersedia untuk Dipinjam</span>
            @endif
        </div>
    </article>

    <!-- Riwayat Peminjaman Perangkat Ini -->
    <section class="card-panel" aria-labelledby="history-heading">
        <h2 id="history-heading" style="font-size: 1.15rem; margin-bottom: 1rem;">Riwayat Peminjaman Perangkat Ini</h2>
        @if($equipment->loanRequests->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                        <tr style="background: #f1f5f9; text-align: left;">
                            <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Peminjam (NIM)</th>
                            <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Tgl Pinjam</th>
                            <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Rencana Kembali</th>
                            <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Status</th>
                            <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Estimasi Kompensasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipment->loanRequests as $loan)
                            <tr>
                                <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">
                                    <strong>{{ $loan->borrower_name }}</strong> ({{ $loan->borrower_nim }})
                                </td>
                                <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">{{ $loan->loan_date->format('Y-m-d') }}</td>
                                <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">{{ $loan->expected_return_date->format('Y-m-d') }}</td>
                                <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color); text-transform: uppercase; font-weight: 600;">{{ $loan->status }}</td>
                                <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Rp {{ number_format($loan->total_estimated_fee, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="asset-thumb">
            @if($item->image_path)
                {{-- Cek apakah image_path berupa URL internet atau file lokal storage --}}
                <img src="{{ Str::startsWith($item->image_path, ['http://', 'https://']) ? $item->image_path : asset('storage/' . $item->image_path) }}" alt="Foto {{ $item->name }}">
            @else
                <div style="text-align: center; color: #64748b; padding: 1rem;">
                    <span style="font-size: 0.85rem; font-weight: 600;">[Tanpa Gambar]</span>
            <div style="font-size: 0.78rem;">{{ $item->asset_code }}</div>
        </div>
    @endif
</div>
    </section>
</section>
@endsection