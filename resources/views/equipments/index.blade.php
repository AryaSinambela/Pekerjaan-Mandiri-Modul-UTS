@extends('layouts.app')

@section('title', 'Katalog Aset Laboratorium - DEL-LEATS')

@section('content')
<section aria-labelledby="catalog-heading">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 id="catalog-heading" style="font-size: 1.5rem;">Katalog Peralatan & Inventaris Laboratorium</h1>
            <p style="color: var(--text-muted); font-size: 0.92rem;">Telusuri ketersediaan perangkat laboratorium untuk keperluan praktikum, tugas akhir, dan riset.</p>
        </div>
        <div style="display: flex; gap: 0.6rem;">
            <a href="{{ route('equipments.create') }}" class="btn btn-primary">+ Tambah Aset Baru</a>
            <a href="{{ route('loans.create') }}" class="btn btn-outline">Ajukan Peminjaman</a>
        </div>
    </div>

    <!-- Formulir Pencarian & Pemfilteran (Metode GET) -->
    <div class="card-panel">
        <form action="{{ route('equipments.index') }}" method="GET" class="filter-form">
            <div class="filter-item">
                <label for="search" style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Kata Kunci (Nama / Kode Aset)</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Contoh: drone, EQ-IOT-005..." value="{{ request('search') }}">
            </div>

            <div class="filter-item">
                <label for="category_id" style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Kategori Perangkat</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="status" style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.3rem;">Status Ketersediaan</label>
                <select name="status" id="status" class="form-control">
                    <option value="semua" {{ request('status', 'semua') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dalam_perbaikan" {{ request('status') === 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">Filter & Cari</button>
                <a href="{{ route('equipments.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Keterangan Ringkasan Hasil Filter (Sesuai Kasus Uji 3) -->
    <div style="margin-bottom: 1rem; font-size: 0.92rem; color: var(--text-muted); font-weight: 500;">
        @if(request()->filled('search'))
            Menampilkan {{ $equipments->count() }} dari {{ $equipments->total() }} hasil pencarian untuk kata kunci "{{ request('search') }}"
        @else
            Menampilkan {{ $equipments->count() }} dari total {{ $equipments->total() }} perangkat laboratorium
        @endif
    </div>

    <!-- Grid Katalog Kartu Aset -->
    @if($equipments->count() > 0)
        <div class="equipment-grid">
            @foreach($equipments as $item)
                <article class="asset-card">
                    <div class="asset-thumb">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="Foto {{ $item->name }}">
                        @else
                            <div style="text-align: center; color: #64748b; padding: 1rem;">
                                <span style="font-size: 0.85rem; font-weight: 600;">[Thumbnail Perangkat]</span>
                                <div style="font-size: 0.78rem;">{{ $item->asset_code }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="asset-body">
                        <div class="asset-meta">
                            <span class="category-label">{{ $item->category->name }}</span>
                            @if($item->status === 'tersedia')
                                <span class="badge badge-tersedia">Tersedia</span>
                            @elseif($item->status === 'dipinjam')
                                <span class="badge badge-dipinjam">Dipinjam</span>
                            @else
                                <span class="badge badge-dalam_perbaikan">Dalam Perbaikan</span>
                            @endif
                        </div>

                        <div class="asset-code">Kode Aset: {{ $item->asset_code }}</div>
                        <h2 class="asset-title">{{ $item->name }}</h2>

                        <p style="font-size: 0.86rem; color: var(--text-muted);">
                            {{ \Illuminate\Support\Str::limit($item->specification, 90) }}
                        </p>

                        <div class="asset-fee">
                            Biaya: Rp {{ number_format($item->daily_rental_fee, 0, ',', '.') }} / hari
                        </div>
                    </div>

                    <div class="asset-actions">
                        <a href="{{ route('equipments.show', $item->id) }}" class="btn btn-outline" style="flex: 1;">
                            Detail Spesifikasi
                        </a>
                        @if($item->status === 'tersedia')
                            <a href="{{ route('loans.create', ['equipment_id' => $item->id]) }}" class="btn btn-primary" style="flex: 1;">
                                Ajukan Pinjam
                            </a>
                        @else
                            <span class="btn btn-disabled" style="flex: 1;">Tidak Tersedia</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Tautan Paginasi dengan Mempertahankan Query String -->
        <div class="pagination-wrapper">
            @if ($equipments->hasPages())
                <ul class="pagination-links">
                    @if ($equipments->onFirstPage())
                        <li><span>&laquo; Prev</span></li>
                    @else
                        <li><a href="{{ $equipments->previousPageUrl() }}">&laquo; Prev</a></li>
                    @endif

                    @foreach ($equipments->getUrlRange(1, $equipments->lastPage()) as $page => $url)
                        <li class="{{ $page == $equipments->currentPage() ? 'active' : '' }}">
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($equipments->hasMorePages())
                        <li><a href="{{ $equipments->nextPageUrl() }}">Next &raquo;</a></li>
                    @else
                        <li><span>Next &raquo;</span></li>
                    @endif
                </ul>
            @else
                <!-- Tetap merender link halaman aktif beserta query string seperti pada Kasus Uji 3 -->
                <ul class="pagination-links">
                    <li class="active">
                        <a href="{{ $equipments->url(1) }}">1</a>
                    </li>
                </ul>
            @endif
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
</section>
@endsection