@extends('layouts.app')

@section('title', 'Pengajuan & Riwayat Peminjaman - DEL-LEATS')

@section('content')
<section aria-labelledby="loan-form-heading">
    <div style="max-width: 780px; margin: 0 auto;">
        <div style="margin-bottom: 1.25rem;">
            <h1 id="loan-form-heading" style="font-size: 1.5rem;">Formulir Pengajuan Peminjaman Aset Laboratorium</h1>
            <p style="color: var(--text-muted); font-size: 0.92rem;">Pastikan perangkat berstatus Tersedia dan durasi peminjaman maksimal 7 hari kalender.</p>
        </div>

        <div class="card-panel">
            <form action="{{ route('loans.store') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label for="equipment_id">Pilihan Perangkat Laboratorium *</label>
                    <select name="equipment_id" id="equipment_id" class="form-control">
                        <option value="">-- Pilih Perangkat Laboratorium --</option>
                        @foreach($equipments as $eq)
                            <option value="{{ $eq->id }}" {{ (string) old('equipment_id', $selectedEquipmentId) === (string) $eq->id ? 'selected' : '' }}>
                                {{ $eq->asset_code }} - {{ $eq->name }} (Status: {{ $eq->status }} | Rp {{ number_format($eq->daily_rental_fee, 0, ',', '.') }}/hari)
                            </option>
                        @endforeach
                    </select>
                    @error('equipment_id')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="borrower_name">Nama Peminjam (Minimal 3 Karakter) *</label>
                    <input type="text" name="borrower_name" id="borrower_name" class="form-control" value="{{ old('borrower_name') }}" placeholder="Masukkan nama lengkap mahasiswa">
                    @error('borrower_name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="borrower_nim">NIM Mahasiswa (Format 8 Karakter NIM Del, misal: 12S24017) *</label>
                    <input type="text" name="borrower_nim" id="borrower_nim" class="form-control" value="{{ old('borrower_nim') }}" placeholder="Contoh: 12S24017">
                    @error('borrower_nim')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="borrower_email">Surel Mahasiswa *</label>
                    <input type="email" name="borrower_email" id="borrower_email" class="form-control" value="{{ old('borrower_email') }}" placeholder="Contoh: mahasiswa@del.ac.id">
                    @error('borrower_email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label for="loan_date">Tanggal Pinjam *</label>
                        <input type="date" name="loan_date" id="loan_date" class="form-control" value="{{ old('loan_date') }}">
                        @error('loan_date')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expected_return_date">Rencana Kembali (Maks. 7 Hari) *</label>
                        <input type="date" name="expected_return_date" id="expected_return_date" class="form-control" value="{{ old('expected_return_date') }}">
                        @error('expected_return_date')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="purpose">Tujuan Peminjaman *</label>
                    <textarea name="purpose" id="purpose" rows="3" class="form-control" placeholder="Contoh: Praktikum, Tugas Akhir, atau Riset Laboratorium">{{ old('purpose') }}</textarea>
                    @error('purpose')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan Peminjaman</button>
                    <a href="{{ route('equipments.index') }}" class="btn btn-outline">Kembali ke Katalog</a>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat Peminjaman Terbaru -->
        <section class="card-panel" aria-labelledby="recent-loans-heading">
            <h2 id="recent-loans-heading" style="font-size: 1.15rem; margin-bottom: 1rem;">Riwayat Pengajuan Peminjaman Terbaru</h2>
            @if($recentLoans->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                        <thead>
                            <tr style="background: #f1f5f9; text-align: left;">
                                <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Perangkat</th>
                                <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Peminjam</th>
                                <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Periode Pinjam</th>
                                <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Status</th>
                                <th style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">Total Estimasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLoans as $loan)
                                <tr>
                                    <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">
                                        <strong>{{ $loan->equipment->asset_code ?? '-' }}</strong><br>
                                        <span style="color: var(--text-muted);">{{ $loan->equipment->name ?? '-' }}</span>
                                    </td>
                                    <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">
                                        {{ $loan->borrower_name }}<br>
                                        <small style="color: var(--text-muted);">{{ $loan->borrower_nim }} | {{ $loan->borrower_email }}</small>
                                    </td>
                                    <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color);">
                                        {{ $loan->loan_date->format('Y-m-d') }} s.d. {{ $loan->expected_return_date->format('Y-m-d') }}
                                    </td>
                                    <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color); font-weight: 700; text-transform: uppercase;">
                                        {{ $loan->status }}
                                    </td>
                                    <td style="padding: 0.65rem; border-bottom: 1px solid var(--border-color); font-weight: 600; color: #0f766e;">
                                        Rp {{ number_format($loan->total_estimated_fee, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="color: var(--text-muted); font-size: 0.9rem;">Belum ada data transaksi pengajuan peminjaman.</p>
            @endif
        </section>
    </div>
</section>
@endsection