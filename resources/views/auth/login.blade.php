@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="auth-logo">Expense<span>Flow</span></div>
        <p class="text-muted mt-1" style="font-size:13px">Log In to your account</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px">
        <i class="bi bi-exclamation-triangle me-1"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email address" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password" required>
                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePwd()"><i class="bi bi-eye" id="eyeIcon"></i></button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:13px">Remember me</label>
            </div>
        </div>
        <button type="submit" class="btn btn-yellow w-100">Log In</button>
    </form>

    <p class="text-center mt-3 mb-0" style="font-size:13px;color:#9090A8">
        Don't have an account?
        <a href="{{ route('register') }}" style="color:var(--yellow-dark);font-weight:700;text-decoration:none">Register here</a>
    </p>
</div>

<script>
function togglePwd() {
    const p = document.getElementById('password');
    const i = document.getElementById('eyeIcon');
    p.type = p.type === 'password' ? 'text' : 'password';
    i.className = p.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endsection
