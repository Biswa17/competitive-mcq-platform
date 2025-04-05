@extends('layouts.admin')

@section('title', 'Settings')

@section('page_title', 'Settings')

@section('content')
<div class="container-fluid">
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Application Settings</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="card-title">General Settings</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="app_name" class="form-label">Application Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('app_name') is-invalid @enderror" id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" required>
                                @error('app_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="app_url" class="form-label">Application URL <span class="text-danger">*</span></label>
                                <input type="url" class="form-control @error('app_url') is-invalid @enderror" id="app_url" name="app_url" value="{{ old('app_url', $settings['app_url']) }}" required>
                                @error('app_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="app_timezone" class="form-label">Timezone <span class="text-danger">*</span></label>
                                <select class="form-select @error('app_timezone') is-invalid @enderror" id="app_timezone" name="app_timezone" required>
                                    <option value="UTC" {{ old('app_timezone', $settings['app_timezone']) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Asia/Kolkata" {{ old('app_timezone', $settings['app_timezone']) == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                                    <option value="America/New_York" {{ old('app_timezone', $settings['app_timezone']) == 'America/New_York' ? 'selected' : '' }}>America/New York (EST)</option>
                                    <option value="Europe/London" {{ old('app_timezone', $settings['app_timezone']) == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                    <option value="Australia/Sydney" {{ old('app_timezone', $settings['app_timezone']) == 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney (AEST)</option>
                                </select>
                                @error('app_timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="app_locale" class="form-label">Locale <span class="text-danger">*</span></label>
                                <select class="form-select @error('app_locale') is-invalid @enderror" id="app_locale" name="app_locale" required>
                                    <option value="en" {{ old('app_locale', $settings['app_locale']) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="hi" {{ old('app_locale', $settings['app_locale']) == 'hi' ? 'selected' : '' }}>Hindi</option>
                                    <option value="es" {{ old('app_locale', $settings['app_locale']) == 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ old('app_locale', $settings['app_locale']) == 'fr' ? 'selected' : '' }}>French</option>
                                </select>
                                @error('app_locale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="card-title">Mail Settings</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_address" class="form-label">From Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required>
                                @error('mail_from_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_name" class="form-label">From Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required>
                                @error('mail_from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="card-title">Application Settings</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pagination_limit" class="form-label">Pagination Limit <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('pagination_limit') is-invalid @enderror" id="pagination_limit" name="pagination_limit" value="{{ old('pagination_limit', $settings['pagination_limit']) }}" min="5" max="100" required>
                                <div class="form-text">Number of items to display per page (5-100)</div>
                                @error('pagination_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="default_role" class="form-label">Default User Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('default_role') is-invalid @enderror" id="default_role" name="default_role" required>
                                    <option value="student" {{ old('default_role', $settings['default_role']) == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="teacher" {{ old('default_role', $settings['default_role']) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="admin" {{ old('default_role', $settings['default_role']) == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <div class="form-text">Default role assigned to new users</div>
                                @error('default_role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Changing these settings will update your application's .env file. The application may need to be restarted for some changes to take effect.
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
