<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --lf-teal: #2d6a6a;
            --lf-teal-soft: #e8f4f4;
            --lf-teal-dark: #1e4848;
            --lf-amber: #e8a838;
            --lf-bg: #f6f8f7;
            --lf-card: #ffffff;
            --lf-text: #1f2933;
            --lf-muted: #64748b;
            --lf-radius: 16px;
            --lf-shadow: 0 2px 16px rgba(45, 106, 106, 0.07);
            --lf-shadow-hover: 0 8px 28px rgba(45, 106, 106, 0.12);
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--lf-bg); color: var(--lf-text); min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }

        .navbar-lf { background: var(--lf-card) !important; border-bottom: 1px solid rgba(45,106,106,.08); box-shadow: var(--lf-shadow); padding: .65rem 0; }
        .navbar-brand { color: var(--lf-teal) !important; font-weight: 700; font-size: 1.15rem; }
        .nav-link { color: var(--lf-muted) !important; font-weight: 500; font-size: .925rem; border-radius: 10px; padding: .45rem .9rem !important; transition: all .2s; }
        .nav-link:hover, .nav-link.active { color: var(--lf-teal) !important; background: var(--lf-teal-soft); }

        .btn-lf { background: var(--lf-teal); border: none; color: #fff; font-weight: 600; border-radius: 12px; padding: .6rem 1.35rem; transition: all .2s; }
        .btn-lf:hover { background: var(--lf-teal-dark); color: #fff; transform: translateY(-1px); }
        .btn-lf-outline { border: 2px solid var(--lf-teal); color: var(--lf-teal); font-weight: 600; border-radius: 12px; background: transparent; }
        .btn-lf-outline:hover { background: var(--lf-teal); color: #fff; }
        .btn-amber { background: var(--lf-amber); border: none; color: #1f2933; font-weight: 600; border-radius: 12px; }
        .btn-amber:hover { background: #d4952f; color: #1f2933; }

        .card-lf { background: var(--lf-card); border: 1px solid rgba(45,106,106,.06); border-radius: var(--lf-radius); box-shadow: var(--lf-shadow); overflow: hidden; transition: box-shadow .2s, transform .2s; }
        .card-lf:hover { box-shadow: var(--lf-shadow-hover); }
        .card-lf .card-header { background: var(--lf-teal-soft); border-bottom: 1px solid rgba(45,106,106,.08); font-weight: 600; color: var(--lf-teal-dark); }

        .page-hero {
            background: linear-gradient(135deg, var(--lf-teal) 0%, var(--lf-teal-dark) 55%, #163838 100%);
            border-radius: var(--lf-radius); color: #fff; padding: 2.25rem 2rem; position: relative; overflow: hidden;
        }
        .page-hero::after { content: ''; position: absolute; top: -40%; right: -10%; width: 280px; height: 280px; background: rgba(232,168,56,.15); border-radius: 50%; }
        .page-hero h1, .page-hero h2 { font-weight: 700; position: relative; z-index: 1; }
        .page-hero p { opacity: .9; position: relative; z-index: 1; margin-bottom: 0; }

        .stat-pill { background: var(--lf-card); border-radius: var(--lf-radius); padding: 1.35rem 1rem; box-shadow: var(--lf-shadow); text-align: center; border: 1px solid rgba(45,106,106,.05); height: 100%; }
        .stat-pill .num { font-size: 1.85rem; font-weight: 700; color: var(--lf-teal); line-height: 1.2; }
        .stat-pill .lbl { font-size: .8rem; color: var(--lf-muted); font-weight: 500; text-transform: uppercase; letter-spacing: .03em; }

        .badge-lost { background: #fef2f2; color: #dc2626; font-weight: 600; border-radius: 20px; padding: .35em .75em; }
        .badge-found { background: #ecfdf5; color: #059669; font-weight: 600; border-radius: 20px; padding: .35em .75em; }
        .badge-status { font-weight: 600; border-radius: 20px; padding: .35em .75em; font-size: .75rem; }

        .verified-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8;
            font-size: .72rem; font-weight: 700; padding: .25rem .6rem; border-radius: 20px;
        }
        .verified-badge i { font-size: .85rem; }

        .flow-step { position: relative; padding: .75rem 0 .75rem 2rem; border-left: 2px dashed rgba(45,106,106,.25); margin-left: .6rem; }
        .flow-step::before {
            content: ''; position: absolute; left: -.5rem; top: 1rem;
            width: 14px; height: 14px; background: var(--lf-amber); border-radius: 50%;
            border: 3px solid var(--lf-card); box-shadow: 0 0 0 2px var(--lf-amber);
        }
        .flow-step:last-child { border-left-color: transparent; padding-bottom: 0; }
        .flow-step strong { display: block; color: var(--lf-teal-dark); font-size: .9rem; }

        .feature-tile { padding: 1.25rem; border-radius: var(--lf-radius); background: var(--lf-card); border: 1px solid rgba(45,106,106,.06); height: 100%; transition: transform .2s; }
        .feature-tile:hover { transform: translateY(-3px); box-shadow: var(--lf-shadow); }
        .feature-tile .icon-wrap { width: 44px; height: 44px; border-radius: 12px; background: var(--lf-teal-soft); color: var(--lf-teal); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: .75rem; }

        .item-card { border-radius: var(--lf-radius); overflow: hidden; }
        .item-card .card-img-wrap { height: 190px; background: var(--lf-teal-soft); overflow: hidden; }
        .item-card .card-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .item-card .placeholder-img { height: 100%; display: flex; align-items: center; justify-content: center; color: var(--lf-teal); font-size: 2.5rem; opacity: .4; }

        .admin-nav-pill { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: 12px; background: var(--lf-card); border: 1px solid rgba(45,106,106,.12); color: var(--lf-teal); text-decoration: none; font-weight: 600; font-size: .875rem; transition: all .2s; }
        .admin-nav-pill:hover { background: var(--lf-teal); color: #fff; border-color: var(--lf-teal); }

        .safety-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: var(--lf-radius); padding: 1rem 1.25rem; }
        .safety-box .title { color: #b45309; font-weight: 700; font-size: .9rem; }

        .section-label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--lf-teal); margin-bottom: .5rem; }

        footer.lf-footer { background: var(--lf-card); border-top: 1px solid rgba(45,106,106,.08); color: var(--lf-muted); font-size: .875rem; margin-top: auto; }

        .form-control, .form-select { border-radius: 10px; border-color: rgba(45,106,106,.15); padding: .55rem .85rem; }
        .form-control:focus, .form-select:focus { border-color: var(--lf-teal); box-shadow: 0 0 0 .2rem rgba(45,106,106,.15); }

        @media (max-width: 768px) { .page-hero { padding: 1.5rem; } }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.navigation')

    <main class="py-4">
        <div class="container">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>

    <footer class="lf-footer py-4">
        <div class="container text-center">
            <p class="mb-1 fw-semibold text-dark">{{ config('app.full_title') }}</p>
            <p class="mb-0 small">Trust-Based Claiming · User Verification · Safe Pickup</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
