<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
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
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <i class="bi bi-box-seam fs-2" style="color:var(--lf-teal)"></i>
                        <h1 class="h4 auth-header mt-2 mb-0">{{ config('app.name') }}</h1>
                        <p class="text-muted small mb-0">Web-Based Lost and Found Management System</p>
                    </a>
                </div>
                <div class="card auth-card">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
                <p class="text-center small text-muted mt-3 mb-0">
                    <a href="{{ route('home') }}" class="text-decoration-none">← Back to home</a>
                </p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
