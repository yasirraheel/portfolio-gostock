@extends('layouts.app')

@section('title', 'Professional Portfolios - ' . ($settings->title ?? 'Portfolio Platform'))
@section('description', 'Discover amazing professional portfolios created by talented individuals. Browse through diverse portfolios showcasing skills, experience, and achievements.')
@section('keywords', 'portfolios, professional portfolios, portfolio showcase, talent, skills, experience, achievements, careers, jobs')

@push('styles')
<style>
.portfolio-card {
    background-color: #ffffff !important;
    border: 1px solid #e9ecef !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
}

.portfolio-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    border-color: var(--color-default, #007bff) !important;
}

/* Mobile responsiveness improvements */
@media (max-width: 576px) {
    .portfolio-card .card-body {
        padding: 1rem !important;
    }
    
    .portfolio-card .d-flex.flex-column.flex-sm-row {
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    
    .portfolio-card .btn {
        width: 100% !important;
        font-size: 0.875rem;
    }
    
    .portfolio-card h5 {
        font-size: 1.1rem;
    }
    
    .portfolio-card .card-text {
        font-size: 0.8rem;
        line-height: 1.3;
    }
    
    .portfolio-card .text-muted {
        font-size: 0.75rem;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="container-fluid py-5 bg-primary text-white" style="margin-top: 80px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-3">Professional Portfolios</h1>
                <p class="fs-5 mb-0">Discover amazing portfolios created by talented professionals from around the world</p>
            </div>
        </div>
    </div>
</div>

<!-- Portfolios Grid Section -->
<div class="container-fluid py-5 py-large">
    <div class="container">
        <div class="row g-4">
            @if($portfolios->count() > 0)
                @foreach($portfolios as $portfolioUser)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100 portfolio-card">
                            <div class="card-body p-3 p-md-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        @if($portfolioUser->avatar && file_exists(public_path('avatar/' . $portfolioUser->avatar)))
                                            <img src="{{ url('public/avatar', $portfolioUser->avatar) }}"
                                                 alt="{{ $portfolioUser->name }} Portfolio"
                                                 class="rounded-circle"
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        @endif
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; {{ $portfolioUser->avatar && file_exists(public_path('avatar/' . $portfolioUser->avatar)) ? 'display: none;' : '' }}">
                                            <span class="fw-bold">{{ substr($portfolioUser->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1 fw-bold text-truncate">{{ $portfolioUser->name }}</h5>
                                        <p class="text-muted mb-1 small text-truncate">{{ $portfolioUser->profession ?? 'Professional' }}</p>
                                        @if($portfolioUser->country)
                                            <p class="text-muted mb-0 small d-flex align-items-center">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                <span class="text-truncate">{{ $portfolioUser->country->country_name }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if($portfolioUser->bio)
                                    <p class="card-text text-muted mb-3 small" style="line-height: 1.4;">
                                        {{ Str::limit($portfolioUser->bio, 100) }}
                                    </p>
                                @endif

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="bi bi-calendar me-1"></i>
                                        <span>{{ \Carbon\Carbon::parse($portfolioUser->date)->format('M Y') }}</span>
                                    </small>
                                    <a href="{{ url($portfolioUser->portfolio_slug) }}"
                                       class="btn btn-outline-custom btn-sm w-100 w-sm-auto">
                                        <i class="bi bi-eye me-1"></i>View Portfolio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-person-workspace display-1 text-muted"></i>
                        <h3 class="mt-3">No Portfolios Yet</h3>
                        <p class="text-muted fs-5">Be the first to create an amazing portfolio and showcase your talent!</p>
                        @guest
                            <a href="{{ url('register') }}" class="btn btn-custom btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Get Started Free
                            </a>
                        @else
                            <a href="{{ url('user/account') }}" class="btn btn-custom btn-lg">
                                <i class="bi bi-gear me-2"></i>Create Your Portfolio
                            </a>
                        @endguest
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($portfolios->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $portfolios->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Call to Action Section -->
@if($portfolios->count() > 0)
<div class="container-fluid py-5" style="background-color: var(--color-default, #007bff); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                @auth
                    <h3 class="mb-3">Ready to Update Your Portfolio?</h3>
                    <p class="mb-0 fs-5" style="color: rgba(255, 255, 255, 0.8);">Keep your portfolio fresh and up-to-date with your latest projects, skills, and achievements.</p>
                @else
                    <h3 class="mb-3">Ready to Create Your Own Portfolio?</h3>
                    <p class="mb-0 fs-5" style="color: rgba(255, 255, 255, 0.8);">Join thousands of professionals who have already created their portfolios and are showcasing their talents to the world.</p>
                @endauth
            </div>
            <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end justify-content-center">
                    @auth
                        <a href="{{ url('user/account') }}" class="btn btn-light btn-lg" style="color: var(--color-default, #007bff); font-weight: 600;">
                            <i class="bi bi-gear me-2"></i>Manage Portfolio
                        </a>
                    @else
                        <a href="{{ url('register') }}" class="btn btn-light btn-lg" style="color: var(--color-default, #007bff); font-weight: 600;">
                            <i class="bi bi-person-plus me-2"></i>Get Started Free
                        </a>
                        <a href="{{ url('login') }}" class="btn btn-outline-light btn-lg" style="border-color: rgba(255, 255, 255, 0.5); color: white;">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('meta')
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('portfolios') }}">
<meta property="og:title" content="Professional Portfolios - {{ $settings->title ?? 'Portfolio Platform' }}">
<meta property="og:description" content="Discover amazing professional portfolios created by talented individuals. Browse through diverse portfolios showcasing skills, experience, and achievements.">
<meta property="og:image" content="{{ url('public/img', $settings->logo_light ?? 'logo.png') }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url('portfolios') }}">
<meta property="twitter:title" content="Professional Portfolios - {{ $settings->title ?? 'Portfolio Platform' }}">
<meta property="twitter:description" content="Discover amazing professional portfolios created by talented individuals. Browse through diverse portfolios showcasing skills, experience, and achievements.">
<meta property="twitter:image" content="{{ url('public/img', $settings->logo_light ?? 'logo.png') }}">

<!-- Additional SEO -->
<meta name="robots" content="index, follow">
<meta name="author" content="{{ $settings->title ?? 'Portfolio Platform' }}">
<meta name="revisit-after" content="7 days">
<link rel="canonical" href="{{ url('portfolios') }}">

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Professional Portfolios",
  "description": "Discover amazing professional portfolios created by talented individuals",
  "url": "{{ url('portfolios') }}",
  "mainEntity": {
    "@type": "ItemList",
    "numberOfItems": "{{ $portfolios->total() }}",
    "itemListElement": [
      @foreach($portfolios as $index => $portfolioUser)
      {
        "@type": "Person",
        "position": {{ $index + 1 }},
        "name": "{{ $portfolioUser->name }}",
        "jobTitle": "{{ $portfolioUser->profession ?? 'Professional' }}",
        "url": "{{ url($portfolioUser->portfolio_slug) }}",
        @if($portfolioUser->country)
        "address": {
          "@type": "PostalAddress",
          "addressCountry": "{{ $portfolioUser->country->country_name }}"
        },
        @endif
        "description": "{{ Str::limit($portfolioUser->bio ?? 'Professional portfolio', 150) }}"
      }{{ $index < $portfolios->count() - 1 ? ',' : '' }}
      @endforeach
    ]
  }
}
</script>
@endpush
