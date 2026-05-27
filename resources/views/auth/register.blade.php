@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="auth-logo">Expense<span>Flow</span></div>
        <p class="text-muted mt-1" style="font-size:13px">Create your free account</p>
    </div>

    <h2 class="mb-4" style="font-size:20px">Sign Up</h2>

    @if($errors->any())
    <div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Full name" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email address" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min. 8 characters" required>
                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePwd('password','eye1')"><i class="bi bi-eye" id="eye1"></i></button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password2"
                       class="form-control" placeholder="Confirm password" required>
                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePwd('password2','eye2')"><i class="bi bi-eye" id="eye2"></i></button>
            </div>
        </div>
        <button type="submit" class="btn btn-yellow w-100">Create Account</button>
    </form>

    <p class="text-center mt-3 mb-0" style="font-size:13px;color:#9090A8">
        Already have an account?
        <a href="{{ route('login') }}" style="color:var(--yellow-dark);font-weight:700;text-decoration:none">Log in</a>
    </p>
</div>

<script>
function togglePwd(id, iconId) {
    const p = document.getElementById(id);
    const i = document.getElementById(iconId);
    p.type = p.type === 'password' ? 'text' : 'password';
    i.className = p.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endsection
