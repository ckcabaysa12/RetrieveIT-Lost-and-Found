<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --lf-teal: #2d6a6a; --lf-teal-dark: #1e4848; --lf-bg: #f6f8f7; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--lf-bg); min-height: 100vh; display: flex; align-items: center; }
        .auth-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(45,106,106,.1); }
        .auth-header { color: var(--lf-teal); font-weight: 700; }
        .btn-lf { background: var(--lf-teal); border: none; color: #fff; font-weight: 600; border-radius: 12px; }
        .btn-lf:hover { background: var(--lf-teal-dark); color: #fff; }
        .form-control { border-radius: 10px; }
        .site-logo { display: inline-flex; align-items: center; justify-content: center; text-decoration: none; line-height: 1; }
        .site-logo__img { height: 80px; width: auto; display: block; max-width: 320px; object-fit: contain; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none d-inline-block">
                        @include('partials.logo', ['class' => 'site-logo--auth'])
                        <p class="text-muted small mb-0 mt-2">Web-Based Lost and Found Management System</p>
                    </a>
                </div>
                <div class="card auth-card">
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ $slot }}
                    </div>
                </div>
                @unless(request()->routeIs('home'))
                    <p class="text-center small text-muted mt-3 mb-0">
                        <a href="{{ route('home') }}" class="text-decoration-none">← Back to home</a>
                    </p>
                @endunless
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
