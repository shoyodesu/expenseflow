@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="stat-card">
            <h5 class="fw-bold mb-4" style="font-family:'Syne',sans-serif">
                <i class="bi bi-person-gear me-2" style="color:var(--yellow)"></i>Edit User
            </h5>

            @if($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-select" required>
                            <option value="user"  {{ old('role', $user->role) == 'user'  ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">New Password <small class="text-muted fw-normal">(optional)</small></label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-yellow px-4">
                        <i class="bi bi-save me-1"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
