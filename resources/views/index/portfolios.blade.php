@extends('layouts.app')

@section('title', 'Portfolios - ' . $settings->title)

@section('css')
<style>
/* Force admin color for portfolio elements */
.portfolio-avatar {
    background-color: {{ $settings->color_default }} !important;
}

.portfolio-btn-outline {
    color: {{ $settings->color_default }} !important;
    border-color: {{ $settings->color_default }} !important;
}

.portfolio-btn-outline:hover {
    background-color: {{ $settings->color_default }} !important;
    color: white !important;
}

.portfolio-btn-main {
    background-color: {{ $settings->color_default }} !important;
    border-color: {{ $settings->color_default }} !important;
    color: white !important;
}

.portfolio-btn-main:hover {
    background-color: {{ $settings->color_default }} !important;
    border-color: {{ $settings->color_default }} !important;
    color: white !important;
    opacity: 0.9;
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-3">Portfolio Showcase</h1>
                <p class="fs-5 text-muted mb-0">Discover amazing portfolios created by talented professionals</p>
            </div>
        </div>
    </div>
</section>

<!-- Portfolios Grid -->
<section class="py-5">
    <div class="container">
        @if($users->count() > 0)
            <div class="row g-4">
                @foreach($users as $user)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4 text-center">
                                <div class="mb-3">
                                    @if($user->avatar)
                                        <img src="{{ url('public/avatar', $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="portfolio-avatar text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                            <span class="fw-bold fs-4">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="card-title mb-2">{{ $user->name }}</h5>
                                @if($user->profession)
                                    <p class="text-muted mb-3">{{ $user->profession }}</p>
                                @endif
                                @if($user->bio)
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($user->bio, 100) }}</p>
                                @endif
                                <a href="{{ url($user->portfolio_slug) }}" class="btn portfolio-btn-outline btn-sm">
                                    <i class="bi bi-eye me-1"></i>View Portfolio
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $users->links() }}
            </div>
        @else
            <!-- No portfolios message -->
            <div class="row">
                <div class="col-12 text-center">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <i class="bi bi-person-workspace display-1 text-muted mb-3"></i>
                            <h5 class="mb-3">No Portfolios Available</h5>
                            <p class="text-muted mb-4">No public portfolios have been created yet. Be the first to create an amazing portfolio!</p>
                            @auth
                                <a href="{{ url('user/account') }}" class="btn portfolio-btn-main">
                                    <i class="bi bi-person-gear me-1"></i>Create Your Portfolio
                                </a>
                            @else
                                <a href="{{ url('register') }}" class="btn portfolio-btn-main">
                                    <i class="bi bi-person-plus me-1"></i>Get Started
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
@if($users->count() > 0)
<section class="py-5 text-white" style="background: linear-gradient(135deg, var(--color-default, #007bff), #0056b3);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-3">Ready to Create Your Own Portfolio?</h2>
                <p class="mb-0 fs-5">Join our community of professionals and showcase your work to the world.</p>
            </div>
            <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                @auth
                    <a href="{{ url('user/account') }}" class="btn btn-lg btn-light rounded-pill px-4 me-2">
                        <i class="bi bi-person-gear me-2"></i>Manage Portfolio
                    </a>
                @else
                    <a href="{{ url('register') }}" class="btn btn-lg btn-light rounded-pill px-4 me-2">
                        <i class="bi bi-person-plus me-2"></i>Get Started Free
                    </a>
                    <a href="{{ url('login') }}" class="btn btn-lg btn-outline-light rounded-pill px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endif
@endsection
