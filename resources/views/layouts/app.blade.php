<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DEL-LEATS - Sistem Manajemen Aset & Peminjaman Laboratorium')</title>
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-hover: #1d4ed8;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        /* Header & Navigasi Semantik */
        header {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff;
            text-decoration: none;
        }

        .brand-subtitle {
            font-size: 0.8rem;
            color: #cbd5e1;
            display: block;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        nav a {
            color: #e2e8f0;
            text-decoration: none;
            padding: 0.5rem 0.9rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.92rem;
            transition: background-color 0.2s, color 0.2s;
        }

        nav a:hover,
        nav a.active {
            background-color: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }

        main {
            flex: 1;
            padding: 2rem 0;
        }

        /* Flash Notification */
        .flash-success {
            background-color: #DCFCE7;
            color: #166534;
            border: 1px solid #86efac;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .flash-error {
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid #fca5a5;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        /* Indikator Visual Status Ketersediaan (Status Badge) Sesuai Spesifikasi */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .badge-tersedia {
            background-color: #DCFCE7;
            color: #166534;
        }

        .badge-dipinjam {
            background-color: #FEF9C3;
            color: #854D0E;
        }

        .badge-dalam_perbaikan {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        /* Tata Letak Responsif CSS Grid: 1 Kolom (Mobile), 2 Kolom (Tablet), 3 Kolom (Desktop) */
        .equipment-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 640px) {
            .equipment-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .equipment-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Kartu Aset (<article>) */
        article.asset-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        article.asset-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }

        .asset-thumb {
            width: 100%;
            height: 190px;
            background-color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid var(--border-color);
        }

        .asset-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .asset-body {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .asset-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .category-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--primary);
            background-color: #dbeafe;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
        }

        .asset-code {
            font-family: monospace;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .asset-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .asset-fee {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f766e;
            margin-top: auto;
            padding-top: 0.5rem;
        }

        .asset-actions {
            padding: 0.9rem 1.25rem;
            background-color: #f8fafc;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.6rem;
        }

        /* Tombol & Form */
        .btn {
            display: inline-block;
            padding: 0.55rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background-color: #ffffff;
            color: var(--text-main);
            border-color: #cbd5e1;
        }

        .btn-outline:hover {
            background-color: #f1f5f9;
        }

        .btn-disabled {
            background-color: #e2e8f0;
            color: #64748b;
            cursor: not-allowed;
        }

        .card-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
            font-size: 0.92rem;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.8rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
        }

        .error-text {
            color: #dc2626;
            font-size: 0.84rem;
            margin-top: 0.35rem;
            font-weight: 500;
        }

        /* Filter Bar */
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-item {
            flex: 1 1 200px;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination-links {
            display: flex;
            gap: 0.4rem;
            list-style: none;
            flex-wrap: wrap;
        }

        .pagination-links a,
        .pagination-links span {
            padding: 0.45rem 0.85rem;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: var(--text-main);
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .pagination-links .active span {
            background-color: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        footer {
            background-color: #0f172a;
            color: #94a3b8;
            text-align: center;
            padding: 1.25rem 0;
            font-size: 0.85rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <div>
                <a href="{{ route('equipments.index') }}" class="brand-title">DEL-LEATS</a>
                <span class="brand-subtitle">DEL Lab Equipment & Asset Tracking System — Institut Teknologi Del</span>
            </div>
            <nav aria-label="Navigasi Utama">
                <ul>
                    <li>
                        <a href="{{ route('equipments.index') }}" class="{{ request()->routeIs('equipments.index') || request()->routeIs('equipments.show') ? 'active' : '' }}">
                            Katalog Aset
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('equipments.create') }}" class="{{ request()->routeIs('equipments.create') ? 'active' : '' }}">
                            Tambah Aset
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('loans.create') }}" class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">
                            Riwayat Peminjaman
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="flash-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flash-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Laboratorium Rekayasa Perangkat Lunak dan Jaringan — Institut Teknologi Del. 12S3101 PPW.</p>
        </div>
    </footer>
</body>
</html>