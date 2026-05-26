<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — ExpenseFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <style>
        :root { --yellow: #F5C800; --yellow-dark: #C9A200; --yellow-light: #FFF8D6; }
        * { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #FFF9D6 0%, #FFFAE8 40%, #FFF5CC 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(245,200,0,.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(245,200,0,.1) 0%, transparent 50%);
            pointer-events: none;
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px 36px;
            width: 100%; max-width: 420px;
            box-shadow: 0 12px 48px rgba(0,0,0,.1);
            border: 1px solid rgba(245,200,0,.2);
            position: relative; z-index: 1;
        }
        .auth-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 24px; color: #1A1A2E; }
        .auth-logo span { color: var(--yellow-dark); }
        .form-control:focus { border-color: var(--yellow); box-shadow: 0 0 0 3px rgba(245,200,0,.2); }
        .btn-yellow { background: var(--yellow); color: #1A1A2E; font-family: 'Syne', sans-serif; font-weight: 700; border: none; padding: 12px; }
        .btn-yellow:hover { background: var(--yellow-dark); color: #1A1A2E; }
        .form-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #6060A0; }
        h2 { font-family: 'Syne', sans-serif; font-weight: 700; }

        /* Toast */
        .toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; }
        .toast-success { background: #1A1A2E; border-left: 4px solid var(--yellow); color: #fff; border-radius: 10px; min-width: 280px; }
        .toast-error { background: #1A1A2E; border-left: 4px solid #E05050; color: #fff; border-radius: 10px; min-width: 280px; }
    </style>
</head>
<body>
    @yield('content')

    <div class="toast-container">
        @if(session('toast_success'))
        <div class="toast toast-success show align-items-center border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-check-circle-fill me-2" style="color:var(--yellow)"></i>{{ session('toast_success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
        @if(session('toast_error'))
        <div class="toast toast-error show align-items-center border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-exclamation-circle-fill me-2" style="color:#E05050"></i>{{ session('toast_error') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.toast').forEach(el => {
            const t = new bootstrap.Toast(el, { delay: 4000 });
            t.show();
        });
    </script>
</body>
</html>
