@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('topbar-actions')
    <button class="btn btn-yellow btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-1"></i> Add User
    </button>
@endsection

@section('content')

{{-- Filters --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-12 col-md-6">
        <input type="text" name="search" class="form-control"
               placeholder="🔍 Search name or email…" value="{{ request('search') }}">
    </div>
    <div class="col-6 col-md-3">
        <select name="role" class="form-select" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user"  {{ request('role') == 'user'  ? 'selected' : '' }}>User</option>
        </select>
    </div>
    <div class="col-6 col-md-3">
        <button type="submit" class="btn btn-yellow w-100">Search</button>
    </div>
</form>

<div class="table-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th><th>User</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-muted" style="font-size:12px">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}"
                                     style="width:32px;height:32px;border-radius:50%;object-fit:cover">
                            @else
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--yellow);
                                            display:flex;align-items:center;justify-content:center;
                                            font-weight:800;font-size:13px;color:#1A1A2E;flex-shrink:0">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                            @endif
                            <span style="font-weight:500">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge-category badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('users.edit', $user) }}"
                               class="btn btn-sm" style="background:#E0EEFF;color:#4A8FE0;font-size:12px">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-usr-{{ $user->id }}" method="POST"
                                  action="{{ route('users.destroy', $user) }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm"
                                        style="background:#FFE5E5;color:#E05050;font-size:12px"
                                        onclick="confirmDelete('del-usr-{{ $user->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-2 d-block mb-2"></i>No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-3 d-flex justify-content-between align-items-center border-top">
        <small class="text-muted">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</small>
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden">
            <div class="modal-header" style="background:var(--yellow);border:none">
                <h5 class="modal-title fw-bold" style="font-family:'Syne',sans-serif;color:#1A1A2E">
                    <i class="bi bi-person-plus me-2"></i>Add New User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Juan Dela Cruz" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="user@example.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Min. 8 chars" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Role *</label>
                            <select name="role" class="form-select" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-yellow px-4">
                        <i class="bi bi-save me-1"></i> Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if($errors->any())
        new bootstrap.Modal(document.getElementById('addUserModal')).show();
    @endif
</script>
@endpush
