@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row g-4">

    {{-- Left: Profile Card --}}
    <div class="col-lg-4">
        <div class="stat-card text-center">
            <div class="mb-3">
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}"
                         style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--yellow)">
                @else
                    <div style="width:90px;height:90px;border-radius:50%;background:var(--yellow);
                                display:flex;align-items:center;justify-content:center;
                                font-family:'Syne',sans-serif;font-weight:800;font-size:34px;
                                color:#1A1A2E;margin:0 auto;border:3px solid var(--yellow-dark)">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
            </div>
            <h5 class="fw-bold mb-1" style="font-family:'Syne',sans-serif">{{ $user->name }}</h5>
            <p class="text-muted mb-3" style="font-size:13px">{{ $user->email }}</p>
            <span class="badge-category badge-{{ $user->role }} mb-3 d-inline-block">
                {{ ucfirst($user->role) }}
            </span>

            <hr>
            <div class="text-start" style="font-size:13px">
                @if($user->phone)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Phone</span><span>{{ $user->phone }}</span>
                </div>
                @endif
                @if($user->gender)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Gender</span><span>{{ $user->gender }}</span>
                </div>
                @endif
                @if($user->address)
                <div class="py-2 border-bottom">
                    <div class="text-muted mb-1">Address</div>
                    <div>{{ $user->address }}</div>
                </div>
                @endif
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Member since</span>
                    <span>{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Edit Form --}}
    <div class="col-lg-8">
        <div class="stat-card">
            <h5 class="fw-bold mb-4" style="font-family:'Syne',sans-serif">
                <i class="bi bi-pencil-square me-2" style="color:var(--yellow)"></i>Edit Profile
            </h5>

            @if($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control"
                               placeholder="09XX-XXX-XXXX"
                               value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select…</option>
                            @foreach(['Male','Female','Other','Prefer not to say'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $user->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"
                                  placeholder="123 Rizal St, Quezon City">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="col-12">
                        <hr>
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*"
                               onchange="previewAvatar(this)">
                        <div id="avatarPreview" class="mt-2" style="display:none">
                            <img id="previewImg" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--yellow)">
                        </div>
                    </div>

                    <div class="col-12"><hr></div>

                    <div class="col-md-6">
                        <label class="form-label">New Password <small class="text-muted fw-normal">(leave blank to keep)</small></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-yellow px-4">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('avatarPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
