@extends('layouts.app')

@section('title', 'Pendaftaran Inventaris Baru - DEL-LEATS')

@section('content')
<section aria-labelledby="form-create-heading" style="max-width: 760px; margin: 0 auto;">
    <div style="margin-bottom: 1.25rem;">
        <h1 id="form-create-heading" style="font-size: 1.5rem;">Formulir Pendaftaran Inventaris Aset Baru</h1>
        <p style="color: var(--text-muted); font-size: 0.92rem;">Daftarkan perangkat teknologi laboratorium baru beserta foto (bisa via upload lokal atau link internet).</p>
    </div>

    <div class="card-panel">
        <form action="{{ route('equipments.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="form-group">
                <label for="category_id">Kategori Perangkat *</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">-- Pilih Kategori Perangkat --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} (Kode: {{ $category->code }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="asset_code">Kode Aset Inventaris (Format: EQ-[KODE_KAT]-[NOMOR]) *</label>
                <input type="text" name="asset_code" id="asset_code" class="form-control" value="{{ old('asset_code') }}" placeholder="Contoh: EQ-IOT-005">
                @error('asset_code')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Nama Perangkat *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Raspberry Pi 4 Model B">
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="specification">Spesifikasi Teknis *</label>
                <textarea name="specification" id="specification" rows="3" class="form-control" placeholder="Uraikan spesifikasi teknis...">{{ old('specification') }}</textarea>
                @error('specification')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="condition">Kondisi Fisik *</label>
                <select name="condition" id="condition" class="form-control">
                    <option value="baik" {{ old('condition', 'baik') === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ old('condition') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ old('condition') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
                @error('condition')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="daily_rental_fee">Tarif Kompensasi Harian (Rp) *</label>
                <input type="number" name="daily_rental_fee" id="daily_rental_fee" class="form-control" value="{{ old('daily_rental_fee') }}" min="0" placeholder="Contoh: 15000">
                @error('daily_rental_fee')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- Pilihan Sumber Gambar -->
            <div class="form-group">
                <label for="image_type">Sumber Gambar Perangkat *</label>
                <select name="image_type" id="image_type" class="form-control" onchange="toggleImageInput(this.value)">
                    <option value="url" {{ old('image_type', 'url') === 'url' ? 'selected' : '' }}>Tautan URL Internet (Link Gambar)</option>
                    <option value="upload" {{ old('image_type') === 'upload' ? 'selected' : '' }}>Unggah Berkas File Lokal (PC)</option>
                </select>
            </div>

            <div class="form-group" id="group-url" style="display: {{ old('image_type', 'url') === 'upload' ? 'none' : 'block' }};">
                <label for="image_url">Tautan URL Gambar Internet *</label>
                <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/photo-...">
                @error('image_url')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" id="group-upload" style="display: {{ old('image_type') === 'upload' ? 'block' : 'none' }};">
                <label for="image">Pilih Berkas Foto (JPEG/PNG/WEBP, Maks. 2MB) *</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                @error('image')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Simpan & Daftarkan Aset</button>
                <a href="{{ route('equipments.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</section>

<script>
    function toggleImageInput(val) {
        if (val === 'url') {
            document.getElementById('group-url').style.display = 'block';
            document.getElementById('group-upload').style.display = 'none';
        } else {
            document.getElementById('group-url').style.display = 'none';
            document.getElementById('group-upload').style.display = 'block';
        }
    }
</script>
@endsection