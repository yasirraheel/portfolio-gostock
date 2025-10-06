@extends('layouts.app')

@section('title')
    {{ $user->meta_title ?? ($user->name . ' - ' . ($user->profession ?? 'Portfolio')) }}
@endsection

@section('description_custom')
    {{ $user->meta_description ?? ($user->bio ?? $user->name . ' - ' . ($user->profession ?? 'Portfolio')) }}
@endsection

@section('keywords_custom')
    {{ $user->meta_keywords ?? ($user->profession ? $user->profession . ', portfolio, ' . $user->name : 'portfolio, ' . $user->name) }}
@endsection

@if ($user->og_image)
    @if (filter_var($user->og_image, FILTER_VALIDATE_URL))
        @section('image_social')
            {{ $user->og_image }}
        @endsection
    @else
        @section('image_social')
            {{ url('public/og', $user->og_image) }}
        @endsection
    @endif
@elseif($user->hero_image)
    @section('image_social')
        {{ url('public/cover', $user->hero_image) }}
    @endsection
@elseif($user->avatar)
    @section('image_social')
        {{ url('public/avatar', $user->avatar) }}
    @endsection
@endif

@php
    // Determine hero background - use user's hero_image if available, otherwise fall back to default portfolio hero image, then global cover
    if ($user->hero_image) {
        $heroBackground = url('public/cover', $user->hero_image);
    } elseif ($settings->default_portfolio_hero_image ?? $settings->img_category) {
        $heroBackground = url('public/img-category', $settings->default_portfolio_hero_image ?? $settings->img_category);
    } else {
        $heroBackground = url('public/cover', $settings->cover);
    }
@endphp

@section('css')
    <link rel="stylesheet" href="{{ url('public/css/portfolio.css') }}">

@endsection

@section('favicon')
    @if($user->portfolio_favicon)
        {{ url('public/portfolio_assets', $user->portfolio_favicon) }}
    @else
        {{ url('favicon/' . $user->portfolio_slug . '.svg') }}
    @endif
@endsection

@section('head')
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $user->meta_title ?? ($user->name . ' - ' . ($user->profession ?? 'Portfolio')) }}">
    <meta property="og:description" content="{{ $user->meta_description ?? ($user->bio ?? $user->name . ' - ' . ($user->profession ?? 'Portfolio')) }}">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url('/' . ($user->portfolio_slug ?: $user->username)) }}">
    <meta property="og:site_name" content="{{ $settings->title }}">

    @if ($user->og_image)
        @if (filter_var($user->og_image, FILTER_VALIDATE_URL))
            <meta property="og:image" content="{{ $user->og_image }}">
        @else
            <meta property="og:image" content="{{ url('public/og', $user->og_image) }}">
        @endif
    @elseif($user->hero_image)
        <meta property="og:image" content="{{ url('public/cover', $user->hero_image) }}">
    @elseif($user->avatar)
        <meta property="og:image" content="{{ url('public/avatar', $user->avatar) }}">
    @endif

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $user->meta_title ?? ($user->name . ' - ' . ($user->profession ?? 'Portfolio')) }}">
    <meta name="twitter:description" content="{{ $user->meta_description ?? ($user->bio ?? $user->name . ' - ' . ($user->profession ?? 'Portfolio')) }}">

    @if ($user->og_image)
        @if (filter_var($user->og_image, FILTER_VALIDATE_URL))
            <meta name="twitter:image" content="{{ $user->og_image }}">
        @else
            <meta name="twitter:image" content="{{ url('public/og', $user->og_image) }}">
        @endif
    @elseif($user->hero_image)
        <meta name="twitter:image" content="{{ url('public/cover', $user->hero_image) }}">
    @elseif($user->avatar)
        <meta name="twitter:image" content="{{ url('public/avatar', $user->avatar) }}">
    @endif

    <!-- Additional Meta Tags -->
    <meta name="author" content="{{ $user->name }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="en">
    <meta name="revisit-after" content="7 days">
@endsection

@section('content')
    <div class="container-fluid home-cover portfolio-hero" style="background-image: url('{{ $heroBackground }}'); margin-top: 60px;">
        <div class="mb-4 position-relative">
            <div class="container px-5">


                <div class="hero-content">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div
                                class="d-flex align-items-center mb-4 hero-profile d-lg-flex d-block text-lg-start text-center">
                                <div class="hero-avatar me-lg-4 mx-auto mx-lg-0 mb-3 mb-lg-0">
                                    @if ($user->avatar && file_exists(public_path('avatar/' . $user->avatar)))
                                        <div class="avatar-container" style="position: relative; width: 120px; height: 120px;">
                                            <img src="{{ url('public/avatar', $user->avatar) }}"
                                                class="rounded-circle avatar-image"
                                                style="width: 120px; height: 120px; object-fit: cover;
                                                       border: 4px solid {{ $user->portfolio_primary_color ?? 'rgba(255,255,255,0.2)' }};
                                                       position: absolute; top: 0; left: 0; z-index: 2;"
                                                alt="{{ $user->name }}"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center avatar-fallback"
                                                style="width: 120px; height: 120px;
                                                       border: 4px solid {{ $user->portfolio_primary_color ?? 'rgba(255,255,255,0.2)' }};
                                                       position: absolute; top: 0; left: 0; z-index: 1; display: none !important;">
                                                <span class="fw-bold" style="font-size: 2.5rem;">{{ substr($user->name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center user-avatar"
                                            style="width: 120px; height: 120px;
                                                   border: 4px solid {{ $user->portfolio_primary_color ?? 'rgba(255,255,255,0.2)' }};">
                                            <span class="fw-bold" style="font-size: 2.5rem;">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="hero-info">
                                    <h1 class="display-4 fw-bold text-white mb-2 hero-name">
                                        <span class="typing-text" data-text="{{ $user->name }}"></span>
                                        <span class="typing-cursor">|</span>
                                    </h1>
                                    @if ($user->profession)
                                        <h2 class="h4 text-white-50 mb-3 hero-title">{{ $user->profession }}</h2>
                                    @endif
                                    @if ($user->available_for_hire == 'yes')
                                        <div class="d-flex justify-content-lg-start justify-content-center">
                                            <span class="badge bg-success fs-6 mb-2 interactive-element">
                                                <i class="bi bi-check-circle me-1"></i>Available for hire
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($user->bio)
                                <div class="hero-bio-container mb-4 text-lg-start text-center mx-lg-0 mx-auto">
                                    <p class="fs-5 text-white mb-0 hero-bio">{{ $user->bio }}</p>
                                </div>
                            @endif

                            <div
                                class="d-flex flex-wrap gap-3 hero-actions justify-content-lg-start justify-content-center">
                                @if ($user->website)
                                    <a href="{{ $user->website }}" target="_blank"
                                        class="btn btn-outline-light btn-lg rounded-pill px-4 interactive-element">
                                        <i class="bi bi-globe me-2"></i>Website
                                    </a>
                                @endif

                                @if ($user->show_contact_form == 'yes')
                                    <a href="{{ url('contact?portfolio=' . ($user->portfolio_slug ?: $user->username)) }}" class="btn btn-lg rounded-pill px-4 interactive-element contact-btn"
                                        @if ($user->portfolio_primary_color) style="background-color: {{ $user->portfolio_primary_color }}; border-color: {{ $user->portfolio_primary_color }}; color: white;"
                                       @else
                                       class="btn btn-light btn-lg rounded-pill px-4 interactive-element" @endif>
                                        <i class="bi bi-envelope me-2"></i>Contact Me
                                    </a>
                                @endif

                                @if ($user->available_for_hire == 'yes')
                                    <a href="{{ url('contact?portfolio=' . ($user->portfolio_slug ?: $user->username) . '&hire=1') }}" class="btn btn-lg rounded-pill px-4 interactive-element hire-btn"
                                        @if ($user->portfolio_primary_color) style="background-color: {{ $user->portfolio_primary_color }}; border-color: {{ $user->portfolio_primary_color }}; color: white;"
                                       @else
                                       class="btn btn-light btn-lg rounded-pill px-4 interactive-element" @endif>
                                        <i class="bi bi-briefcase me-2"></i>Hire Me
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="hero-social text-lg-end">
                                @if ($user->linkedin || $user->facebook || $user->twitter || $user->instagram)
                                    <div class="mb-4">
                                        <p class="text-white-50 mb-3 fw-semibold">Connect with me:</p>
                                        <div class="d-flex justify-content-lg-end justify-content-center gap-3 mb-3">
                                            @if ($user->linkedin)
                                                <a href="{{ $user->linkedin }}" target="_blank"
                                                    class="text-white fs-3 social-link">
                                                    <i class="bi bi-linkedin"></i>
                                                </a>
                                            @endif
                                            @if ($user->facebook)
                                                <a href="{{ $user->facebook }}" target="_blank"
                                                    class="text-white fs-3 social-link">
                                                    <i class="bi bi-facebook"></i>
                                                </a>
                                            @endif
                                            @if ($user->twitter)
                                                <a href="{{ $user->twitter }}" target="_blank"
                                                    class="text-white fs-3 social-link">
                                                    <i class="bi bi-twitter"></i>
                                                </a>
                                            @endif
                                            @if ($user->instagram)
                                                <a href="{{ $user->instagram }}" target="_blank"
                                                    class="text-white fs-3 social-link">
                                                    <i class="bi bi-instagram"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="contact-info">
                                    @if ($user->phone)
                                        <div class="contact-info-item mb-2">
                                            <p
                                                class="text-white-50 mb-0 d-flex align-items-center justify-content-lg-end justify-content-center">
                                                <i class="bi bi-telephone me-2"></i>{{ $user->phone }}
                                            </p>
                                        </div>
                                    @endif

                                    @if ($user->country)
                                        <div class="contact-info-item mb-0">
                                            <p
                                                class="text-white-50 mb-0 d-flex align-items-center justify-content-lg-end justify-content-center">
                                                <i class="bi bi-geo-alt me-2"></i>{{ $user->country ? $user->country->country_name : 'Location not set' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- container-fluid -->

    <style>
        /* Animation styles for portfolio sections */
        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered animation for section cards */
        .skills-section .card,
        .experience-section .card,
        .projects-section .card,
        .education-section .education-card,
        .testimonials-section .testimonial-card,
        .certifications-section .certification-card {
            opacity: 0;
            transform: translateY(50px);
            animation: slideInUp 0.6s ease-out;
            animation-fill-mode: forwards;
        }

        .skills-section .card:nth-child(1) { animation-delay: 0.1s; }
        .skills-section .card:nth-child(2) { animation-delay: 0.2s; }
        .skills-section .card:nth-child(3) { animation-delay: 0.3s; }
        .skills-section .card:nth-child(4) { animation-delay: 0.4s; }
        .skills-section .card:nth-child(5) { animation-delay: 0.5s; }
        .skills-section .card:nth-child(6) { animation-delay: 0.6s; }

        .experience-section .card:nth-child(1) { animation-delay: 0.1s; }
        .experience-section .card:nth-child(2) { animation-delay: 0.2s; }
        .experience-section .card:nth-child(3) { animation-delay: 0.3s; }
        .experience-section .card:nth-child(4) { animation-delay: 0.4s; }

        .projects-section .card:nth-child(1) { animation-delay: 0.1s; }
        .projects-section .card:nth-child(2) { animation-delay: 0.2s; }
        .projects-section .card:nth-child(3) { animation-delay: 0.3s; }
        .projects-section .card:nth-child(4) { animation-delay: 0.4s; }
        .projects-section .card:nth-child(5) { animation-delay: 0.5s; }
        .projects-section .card:nth-child(6) { animation-delay: 0.6s; }

        .education-section .education-card:nth-child(1) { animation-delay: 0.1s; }
        .education-section .education-card:nth-child(2) { animation-delay: 0.2s; }
        .education-section .education-card:nth-child(3) { animation-delay: 0.3s; }
        .education-section .education-card:nth-child(4) { animation-delay: 0.4s; }

        .testimonials-section .testimonial-card:nth-child(1) { animation-delay: 0.1s; }
        .testimonials-section .testimonial-card:nth-child(2) { animation-delay: 0.2s; }
        .testimonials-section .testimonial-card:nth-child(3) { animation-delay: 0.3s; }
        .testimonials-section .testimonial-card:nth-child(4) { animation-delay: 0.4s; }
        .testimonials-section .testimonial-card:nth-child(5) { animation-delay: 0.5s; }
        .testimonials-section .testimonial-card:nth-child(6) { animation-delay: 0.6s; }

        .certifications-section .certification-card:nth-child(1) { animation-delay: 0.1s; }
        .certifications-section .certification-card:nth-child(2) { animation-delay: 0.2s; }
        .certifications-section .certification-card:nth-child(3) { animation-delay: 0.3s; }
        .certifications-section .certification-card:nth-child(4) { animation-delay: 0.4s; }
        .certifications-section .certification-card:nth-child(5) { animation-delay: 0.5s; }
        .certifications-section .certification-card:nth-child(6) { animation-delay: 0.6s; }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover animations for cards */
        .skills-section .card:hover,
        .experience-section .card:hover,
        .projects-section .card:hover,
        .education-section .education-card:hover,
        .testimonials-section .testimonial-card:hover,
        .certifications-section .certification-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Typing animation for name
            const typingElement = document.querySelector('.typing-text');
            if (typingElement) {
                const text = typingElement.dataset.text;
                let index = 0;

                // Clear the text initially
                typingElement.textContent = '';

                // Start typing after a delay
                setTimeout(() => {
                    function typeWriter() {
                        if (index < text.length) {
                            typingElement.textContent += text.charAt(index);
                            index++;
                            setTimeout(typeWriter, 100); // Adjust speed here (100ms per character)
                        }
                    }
                    typeWriter();
                }, 1000); // Start typing after 1 second
            }

            // Scroll-triggered animations for skills cards
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const card = entry.target;
                        card.classList.add('animate-in');

                        // Animate progress bars
                        const progressBars = card.querySelectorAll('.progress-bar');
                        progressBars.forEach(function(bar) {
                            setTimeout(function() {
                                const width = bar.getAttribute('data-width');
                                console.log('Animating progress bar to:', width +
                                    '%');

                                // Method 1: Set inline style with important
                                bar.style.setProperty('width', width + '%',
                                    'important');

                                // Method 2: Use CSS custom property
                                bar.style.setProperty('--progress-width', width +
                                    '%');
                                bar.classList.add('animate-progress');

                                // Method 3: Force immediate update
                                setTimeout(function() {
                                    bar.style.width = width + '%';
                                }, 50);
                            }, 700);
                        });

                        // Stop observing this card
                        observer.unobserve(card);
                    }
                });
            }, observerOptions);

            // Observe all skill cards
            const skillCards = document.querySelectorAll('.skills-section .card');
            skillCards.forEach(function(card) {
                observer.observe(card);
            });

            // Observe experience timeline items
            const experienceItems = document.querySelectorAll('.experience-section .timeline-item');
            experienceItems.forEach(function(item) {
                observer.observe(item);
            });

            // Observe education timeline items
            const educationItems = document.querySelectorAll('.education-section .education-card');
            educationItems.forEach(function(item) {
                observer.observe(item);
            });

            // Observe certification cards
            const certificationItems = document.querySelectorAll('.certifications-section .certification-card');
            certificationItems.forEach(function(item) {
                observer.observe(item);
            });

            // Observe testimonial cards
            const testimonialItems = document.querySelectorAll('.testimonials-section .testimonial-card');
            testimonialItems.forEach(function(item) {
                observer.observe(item);
            });

            // Observe project cards
            const projectItems = document.querySelectorAll('.projects-section .project-card');
            projectItems.forEach(function(item) {
                observer.observe(item);
            });

            // Observe custom section cards
            const customSectionItems = document.querySelectorAll('.custom-section .custom-section-card');
            customSectionItems.forEach(function(item) {
                observer.observe(item);
            });
        });
    </script>

    <div class="container-fluid">

        {{-- Skills Section --}}
        @if ($skills->count() > 0)
            <div class="container px-1 mb-1 skills-section">
                <div class="btn-block text-center mb-1 animate-fade-in">
                    <h3 class="m-0" style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">{{ __('misc.skills') }}</h3>
                    <p style="color: {{ $user->portfolio_primary_color ?? '#007bff' }}; opacity: 0.8;">
                        {{ __('misc.my_professional_skills') }}
                    </p>
                </div>

                <div class="row g-4">
                    @foreach ($skills->where('status', 'active') as $skill)
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    @if ($skill->fas_icon)
                                        <div class="mb-3">
                                            <i class="{{ $skill->fas_icon }} fa-3x"
                                                style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};"></i>
                                        </div>
                                    @endif

                                    <h5 class="card-title fw-bold mb-3">{{ $skill->skill_name }}</h5>

                                    @if ($skill->description)
                                        <p class="card-text text-muted mb-3">{{ $skill->description }}</p>
                                    @endif

                                    <div class="skill-progress mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small fw-semibold">{{ $skill->proficiency_display }}</span>
                                            <span class="small text-muted">{{ $skill->proficiency_percentage }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="background-color: {{ $user->portfolio_primary_color ?? '#007bff' }};"
                                                data-width="{{ $skill->proficiency_percentage }}"
                                                aria-valuenow="{{ $skill->proficiency_percentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Experience Section --}}
        @if ($experiences->count() > 0)
            <div class="container px-1 mb-1 experience-section">
                <div class="btn-block text-center mb-2 animate-fade-in">
                    <h3 class="m-0" style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">{{ __('misc.experience') }}</h3>
                    <p style="color: {{ $user->portfolio_primary_color ?? '#007bff' }}; opacity: 0.8;">
                        {{ __('misc.my_professional_experience') }}
                    </p>
                </div>

                <div class="row g-4">
                    @foreach ($experiences->where('status', 'active') as $experience)
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    {{-- Timeline Date Badge --}}
                                    <div class="text-center mb-4">
                                        <span class="badge px-3 py-2 fs-6 mb-2"
                                            style="background-color: {{ $user->portfolio_primary_color ?? '#007bff' }} !important; color: white;">
                                            {{ $experience->date_range }}
                                        </span>
                                        <div class="text-muted small">{{ $experience->duration }}</div>
                                    </div>

                                    {{-- Company Logo and Info --}}
                                    <div class="d-flex align-items-start mb-3">
                                        @if ($experience->company_logo)
                                            <img src="{{ url('public/portfolio_assets', $experience->company_logo) }}"
                                                class="rounded me-3"
                                                style="width: 60px; height: 60px; object-fit: cover;"
                                                alt="{{ $experience->company_name }}">
                                        @else
                                            <div class="text-white rounded me-3 d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px; min-width: 60px; font-size: 1.5rem; background-color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                {{ substr($experience->company_name, 0, 1) }}
                                            </div>
                                        @endif

                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1 fw-bold">{{ $experience->job_title }}</h5>
                                            <h6 class="card-subtitle mb-2"
                                                style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                @if ($experience->company_website)
                                                    <a href="{{ $experience->company_website }}"
                                                        target="_blank" class="text-decoration-none"
                                                        style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                        {{ $experience->company_name }}
                                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                    </a>
                                                @else
                                                    {{ $experience->company_name }}
                                                @endif
                                            </h6>

                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <span class="badge bg-secondary">{{ $experience->employment_type_display }}</span>
                                                @if ($experience->location)
                                                    <span class="badge bg-outline-secondary text-muted">
                                                        <i class="bi bi-geo-alt me-1"></i>{{ $experience->location }}
                                                    </span>
                                                @endif
                                                @if ($experience->is_current)
                                                    <span class="badge bg-success">Current Position</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if ($experience->description)
                                        <p class="card-text mb-3">{{ $experience->description }}</p>
                                    @endif

                                    @if ($experience->achievements)
                                        <div class="mb-3">
                                            <h6 class="fw-semibold mb-2">Key Achievements:</h6>
                                            <div class="achievements-list">
                                                @php
                                                    $achievements = $experience->achievements;
                                                    $lines = [];

                                                    // Try to decode as JSON first (new format)
                                                    $jsonAchievements = json_decode($achievements, true);
                                                    if (is_array($jsonAchievements)) {
                                                        $lines = $jsonAchievements;
                                                    } else {
                                                        // Fallback to old format - split by common delimiters
                                                        $delimiters = ['\n', '\r\n', '•', '-', '*', '1.', '2.', '3.', '4.', '5.', '6.', '7.', '8.', '9.'];

                                                        foreach ($delimiters as $delimiter) {
                                                            if (strpos($achievements, $delimiter) !== false) {
                                                                $lines = array_filter(array_map('trim', explode($delimiter, $achievements)));
                                                                break;
                                                            }
                                                        }

                                                        // If no delimiters found, treat as single line
                                                        if (empty($lines)) {
                                                            $lines = [$achievements];
                                                        }
                                                    }
                                                @endphp

                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($lines as $line)
                                                        @if (!empty(trim($line)))
                                                            <li class="d-flex align-items-start mb-2">
                                                                <i class="bi bi-check-circle-fill text-success me-2 mt-1 flex-shrink-0" style="font-size: 0.8rem;"></i>
                                                                <span class="text-muted small">{{ trim($line) }}</span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($experience->technologies_used)
                                        <div class="technologies-used">
                                            <h6 class="fw-semibold mb-2">Technologies Used:</h6>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($experience->technologies_array as $tech)
                                                    <span class="badge bg-light text-dark border">{{ $tech }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Projects Section --}}
        @if ($projects->count() > 0)
            <div class="container px-1 mb-1 projects-section">
                <div class="btn-block text-center mb-2 animate-fade-in">
                    <h3 class="m-0" style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">{{ __('misc.projects') }}</h3>
                    <p style="color: {{ $user->portfolio_primary_color ?? '#007bff' }}; opacity: 0.8;">
                        {{ __('misc.my_featured_projects') }}
                    </p>
                </div>

                <div class="row g-4">
                    @foreach ($projects->take(6) as $project)
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-2">
                                    {{-- Project Header with Status and Featured Badge --}}
                                    <div class="mb-2">
                                        {{-- Status and Featured Badges Row --}}
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="badge bg-{{ $project->status_color }} px-3 py-2 rounded-pill">
                                                <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                                {{ $project->status_display }}
                                        </span>
                                            @if($project->featured)
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                    <i class="fas fa-star me-1"></i>{{ __('misc.featured') }}
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Duration Row --}}
                                        <div class="text-muted small text-center">
                                            {{ $project->formatted_start_date }} - {{ $project->formatted_end_date }}
                                        </div>
                                    </div>

                                    {{-- Project Images Display --}}
                                    @if($project->hasImages())
                                        <div class="mb-2">
                                            <div class="row g-2">
                                                @foreach($project->project_images_list as $index => $image)
                                                    <div class="col-{{ count($project->project_images_list) > 1 ? '6' : '12' }} col-md-{{ count($project->project_images_list) > 1 ? '6' : '12' }}">
                                                        <img src="{{ url('public/portfolio_assets', $image) }}"
                                                             class="img-fluid rounded shadow-sm"
                                                             style="width: 100%; height: 150px; object-fit: contain; background-color: #f8f9fa; border: 1px solid #e9ecef;"
                                                             alt="Project image {{ $index + 1 }}"
                                                             loading="lazy">
                                                    </div>
                                                @endforeach
                                            </div>
                                            </div>
                                        @endif

                                    {{-- Project Title and Type --}}
                                    <div class="mb-2">
                                            <h5 class="card-title mb-1 fw-bold">{{ $project->project_name }}</h5>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="badge bg-primary">{{ $project->project_type_display }}</span>
                                            @if($project->client_name)
                                                    <span class="badge bg-outline-secondary text-muted">
                                                        <i class="bi bi-building me-1"></i>{{ $project->client_name }}
                                                    </span>
                                                @endif
                                        </div>
                                    </div>

                                    {{-- Project Description --}}
                                    @if($project->description)
                                        <div class="card-text mb-2">
                                            <p class="text-muted small mb-0">{{ strip_tags($project->description) }}</p>
                                        </div>
                                                @endif

                                    {{-- Team Information --}}
                                    @if($project->role || $project->team_size)
                                        <div class="mb-2">
                                            <div class="row g-2">
                                                @if($project->role)
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">{{ __('misc.role') }}</small>
                                                        <span class="fw-semibold">{{ $project->role }}</span>
                                            </div>
                                                @endif
                                                @if($project->team_size)
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">{{ __('misc.team_size') }}</small>
                                                        <span class="fw-semibold">{{ $project->team_size }} {{ $project->team_size == 1 ? 'person' : 'people' }}</span>
                                        </div>
                                                @endif
                                    </div>
                                        </div>
                                    @endif

                                    {{-- Key Features --}}
                                    @if($project->key_features)
                                        <div class="mb-2">
                                            <h6 class="fw-semibold mb-2 text-primary">
                                                <i class="bi bi-star me-1"></i>{{ __('misc.key_features') }}
                                            </h6>
                                            <ul class="text-muted small mb-0 ps-3">
                                                @foreach(explode("\n", strip_tags($project->key_features)) as $feature)
                                                    @if(trim($feature))
                                                        <li>{{ trim($feature) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {{-- Technologies Used --}}
                                    @if($project->technologies_list && count($project->technologies_list) > 0)
                                        <div class="mb-2">
                                            <h6 class="fw-semibold mb-2 text-primary">
                                                <i class="bi bi-code-slash me-1"></i>{{ __('misc.technologies_skills_used') }}
                                            </h6>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($project->technologies_list as $tech)
                                                    <span class="badge bg-light text-dark border">{{ $tech }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Challenges Solved --}}
                                    @if($project->challenges_solved)
                                        <div class="mb-2">
                                            <h6 class="fw-semibold mb-2 text-success">
                                                <i class="bi bi-trophy me-1"></i>{{ __('misc.challenges_solved') }}
                                            </h6>
                                            <ul class="text-muted small mb-0 ps-3">
                                                @foreach(explode("\n", strip_tags($project->challenges_solved)) as $challenge)
                                                    @if(trim($challenge))
                                                        <li>{{ trim($challenge) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {{-- Project Links --}}
                                    <div class="d-flex flex-wrap gap-2 mt-auto">
                                        @if($project->project_url)
                                            <a href="{{ $project->project_url }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary border">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                {{ __('misc.view_project') }}
                                            </a>
                                        @endif
                                        @if($project->github_url)
                                            <a href="{{ $project->github_url }}" target="_blank"
                                                class="btn btn-sm btn-dark text-white border">
                                                <i class="fab fa-github me-1"></i>
                                                {{ __('misc.github') }}
                                            </a>
                                        @endif
                                        @if($project->demo_url)
                                            <a href="{{ $project->demo_url }}" target="_blank"
                                                class="btn btn-sm btn-outline-success border">
                                                <i class="fas fa-play me-1"></i>
                                                {{ __('misc.demo') }}
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Duration Display --}}
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $project->duration }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Education Section --}}
        @if ($educations->count() > 0)
            <div class="container px-1 mb-1 education-section">
                <div class="btn-block text-center mb-1 animate-fade-in">
                    <h3 class="m-0" style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">{{ __('misc.education') }}</h3>
                    <p style="color: {{ $user->portfolio_primary_color ?? '#007bff' }}; opacity: 0.8;">
                        {{ __('misc.my_educational_background') }}
                    </p>
                </div>

                <div class="row g-4">
                    @foreach ($educations->where('status', 'active') as $education)
                        <div class="col-lg-6 col-md-6 col-12">
                                    <div class="education-card border-0 shadow-sm h-100">
                                        <div class="education-header p-4">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    @if ($education->logo)
                                                        <img src="{{ url('public/portfolio_assets', $education->logo) }}"
                                                            class="institution-logo"
                                                            alt="{{ $education->institution_name }}">
                                                    @else
                                                        <div class="institution-logo-fallback"
                                                            style="background-color: {{ $user->portfolio_secondary_color ?? '#28a745' }} !important;">
                                                            <i class="bi bi-mortarboard-fill text-white fs-2"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <h4 class="education-degree mb-1">{{ $education->full_degree }}</h4>
                                                    <h5 class="education-institution mb-2"
                                                        style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                        @if ($education->website)
                                                            <a href="{{ $education->website }}" target="_blank"
                                                                class="text-decoration-none"
                                                                style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                                <i
                                                                    class="bi bi-building me-2"></i>{{ $education->institution_name }}
                                                                <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                            </a>
                                                        @else
                                                            <i
                                                                class="bi bi-building me-2"></i>{{ $education->institution_name }}
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="col-auto text-end">
                                                    <div class="timeline-date-education">
                                                        <span class="badge px-3 py-2 fs-6 mb-2 education-date-badge"
                                                            style="background-color: transparent !important; color: {{ $user->portfolio_secondary_color ?? '#28a745' }} !important; border: 1px solid {{ $user->portfolio_secondary_color ?? '#28a745' }};">
                                                            {{ $education->date_range }}
                                                        </span>
                                                        <div class="text-muted small">
                                                            {{ $education->duration }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="education-body p-4 pt-0">
                                            <div class="education-badges mb-3">
                                                <span class="badge bg-secondary me-2 mb-2">
                                                    <i
                                                        class="bi bi-award me-1"></i>{{ $education->education_level_display }}
                                                </span>
                                                @if ($education->location)
                                                    <span class="badge bg-outline-secondary text-muted me-2 mb-2">
                                                        <i class="bi bi-geo-alt me-1"></i>{{ $education->location }}
                                                    </span>
                                                @endif
                                                @if ($education->is_current)
                                                    <span class="badge bg-success me-2 mb-2">
                                                        <i class="bi bi-clock me-1"></i>Currently Studying
                                                    </span>
                                                @endif
                                                @if ($education->grade)
                                                    <span class="badge bg-info me-2 mb-2">
                                                        <i class="bi bi-star me-1"></i>{{ $education->grade }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if ($education->description)
                                                <div class="education-description mb-3">
                                                    <p class="text-muted mb-0">{{ $education->description }}</p>
                                                </div>
                                            @endif

                                            @if ($education->activities)
                                                <div class="education-activities">
                                                    <h6 class="fw-semibold mb-2">
                                                        <i class="bi bi-people me-1"></i>Activities & Societies:
                                                    </h6>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach ($education->activities_array as $activity)
                                                            <span
                                                                class="badge bg-light text-dark border">{{ $activity }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Certifications Section --}}
        @if ($certifications->count() > 0)
            <div class="container px-1 mb-1 certifications-section">
                <div class="btn-block text-center mb-5 animate-fade-in">
                    <h3 class="m-0" style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">{{ __('misc.certifications') }}</h3>
                    <p style="color: {{ $user->portfolio_primary_color ?? '#007bff' }}; opacity: 0.8;">
                        {{ __('misc.my_professional_certifications') }}
                    </p>
                </div>

                <div class="row g-4">
                    @foreach ($certifications->where('status', 'active') as $certification)
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="certification-card border-0 shadow-sm h-100">
                                <div class="certification-header p-4">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            @if ($certification->organization_logo)
                                                <img src="{{ url('public/portfolio_assets', $certification->organization_logo) }}"
                                                    class="organization-logo"
                                                    alt="{{ $certification->issuing_organization }}">
                                            @else
                                                <div class="organization-logo-fallback"
                                                    style="background-color: {{ $user->portfolio_primary_color ?? '#007bff' }} !important;">
                                                    <i class="bi bi-award-fill text-white fs-2"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <h4 class="certification-name mb-1">{{ $certification->name }}</h4>
                                            <h5 class="certification-organization mb-2"
                                                style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                @if ($certification->credential_url)
                                                    <a href="{{ $certification->credential_url }}" target="_blank"
                                                        class="text-decoration-none"
                                                        style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                        <i
                                                            class="bi bi-building me-2"></i>{{ $certification->issuing_organization }}
                                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                    </a>
                                                @else
                                                    <i
                                                        class="bi bi-building me-2"></i>{{ $certification->issuing_organization }}
                                                @endif
                                            </h5>
                                        </div>
                                        <div class="col-auto text-end">
                                            <div class="timeline-date-certification">
                                                <span class="badge px-3 py-2 fs-6 mb-2 certification-date-badge"
                                                    style="background-color: transparent !important; color: {{ $user->portfolio_primary_color ?? '#007bff' }} !important; border: 1px solid {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                    {{ $certification->validity_period }}
                                                </span>
                                                <div class="text-muted small">
                                                    {{ $certification->expiry_status }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="certification-body p-4 pt-0">
                                    <div class="certification-badges mb-3">
                                        @if ($certification->credential_id)
                                            <span class="badge bg-secondary me-2 mb-2">
                                                <i class="bi bi-hash me-1"></i>{{ $certification->credential_id }}
                                            </span>
                                        @endif
                                        @if ($certification->is_expired)
                                            <span class="badge bg-danger me-2 mb-2">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Expired
                                            </span>
                                        @elseif ($certification->expiry_status === 'Expiring soon')
                                            <span class="badge bg-warning me-2 mb-2">
                                                <i class="bi bi-clock me-1"></i>Expiring Soon
                                            </span>
                                        @elseif ($certification->does_not_expire)
                                            <span class="badge bg-success me-2 mb-2">
                                                <i class="bi bi-infinity me-1"></i>Never Expires
                                            </span>
                                        @else
                                            <span class="badge bg-info me-2 mb-2">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        @endif
                                    </div>

                                    @if ($certification->description)
                                        <div class="certification-description mb-3">
                                            <p class="text-muted mb-0">{{ $certification->description }}</p>
                                        </div>
                                    @endif

                                    @if ($certification->skills_gained)
                                        <div class="certification-skills">
                                            <h6 class="fw-semibold mb-2">
                                                <i class="bi bi-gear me-1"></i>Skills Gained:
                                            </h6>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($certification->skills_array as $skill)
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Testimonials Section --}}
        @if ($testimonials->count() > 0)
            <div class="container px-5 mb-5 testimonials-section">
                <div class="btn-block text-center mb-5 animate-fade-in">
                    <h3 class="m-0" style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">{{ __('misc.testimonials') }}</h3>
                    <p style="color: {{ $user->portfolio_primary_color ?? '#007bff' }}; opacity: 0.8;">
                        {{ __('misc.what_clients_say_about_me') }}
                    </p>
                </div>

                <div class="row g-4">
                    @foreach ($testimonials->take(6) as $testimonial)
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="testimonial-card border-0 shadow-sm h-100">
                                <div class="testimonial-header p-4">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            @if ($testimonial->client_photo)
                                                <img src="{{ url('public/portfolio_assets', $testimonial->client_photo) }}"
                                                    class="client-photo" alt="{{ $testimonial->client_name }}">
                                            @else
                                                <div class="client-photo-fallback"
                                                    style="background-color: {{ $user->portfolio_primary_color ?? '#007bff' }} !important;">
                                                    <i class="bi bi-person-fill text-white fs-2"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <h4 class="client-name mb-1">{{ $testimonial->client_name }}</h4>
                                            <h5 class="client-position mb-2"
                                                style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                {{ $testimonial->client_position }}
                                                @if ($testimonial->company_name)
                                                    @if ($testimonial->client_website)
                                                        <a href="{{ $testimonial->client_website }}" target="_blank"
                                                            class="text-decoration-none ms-2"
                                                            style="color: {{ $user->portfolio_primary_color ?? '#007bff' }};">
                                                            @ {{ $testimonial->company_name }}
                                                            <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">@
                                                            {{ $testimonial->company_name }}</span>
                                                    @endif
                                                @endif
                                            </h5>
                                        </div>
                                        <div class="col-auto text-end">
                                            <div class="timeline-date-testimonial">
                                                @if ($testimonial->rating)
                                                    <div class="testimonial-rating mb-2">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $testimonial->rating)
                                                                <i class="bi bi-star-fill" style="color: #ffc107;"></i>
                                                            @else
                                                                <i class="bi bi-star"
                                                                    style="color: rgba(255, 255, 255, 0.3);"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                @endif
                                                @if ($testimonial->date_received)
                                                    <div class="text-muted small">
                                                        {{ $testimonial->date_received->format('M Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="testimonial-body p-4 pt-0">
                                    <div class="testimonial-badges mb-3">
                                        @if ($testimonial->project_type)
                                            <span class="badge bg-secondary me-2 mb-2">
                                                <i class="bi bi-briefcase me-1"></i>{{ $testimonial->project_type }}
                                            </span>
                                        @endif
                                        @if ($testimonial->is_featured)
                                            <span class="badge bg-warning me-2 mb-2">
                                                <i class="bi bi-star me-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>

                                    <div class="testimonial-quote mb-3">
                                        <div class="quote-icon mb-2">
                                            <i class="bi bi-quote"
                                                style="font-size: 2rem; color: {{ $user->portfolio_primary_color ?? '#007bff' }}; opacity: 0.3;"></i>
                                        </div>
                                        <p class="testimonial-text mb-0">{{ $testimonial->testimonial_text }}</p>
                                    </div>

                                    @if ($testimonial->project_details)
                                        <div class="project-details">
                                            <h6 class="fw-semibold mb-2">
                                                <i class="bi bi-info-circle me-1"></i>Project Details:
                                            </h6>
                                            <p class="text-muted small mb-0">{{ $testimonial->project_details }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Custom Sections --}}
        @if ($customSections->count() > 0)
            @foreach ($customSections->sortBy('order_position') as $customSection)
                <div class="container px-5 mb-5 custom-section">
                    <div class="btn-block text-center mb-5">
                        <h3 class="m-0">
                            @if ($customSection->icon)
                                <i class="{{ $customSection->icon }} me-2"></i>
                            @endif
                            {{ $customSection->title }}
                        </h3>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-10 col-md-12">
                            <div class="custom-section-card">
                                @if ($customSection->image)
                                    <div class="custom-section-image mb-4 text-center">
                                        <img src="{{ url('public/portfolio_assets', $customSection->image) }}"
                                             alt="{{ $customSection->title }}"
                                             class="custom-section-img">
                                    </div>
                                @endif

                                <div class="custom-section-content">
                                    {!! nl2br(e($customSection->content)) !!}
                                </div>

                                @if ($customSection->link_url && $customSection->link_text)
                                    <div class="custom-section-actions text-center mt-4">
                                        <a href="{{ $customSection->link_url }}"
                                           target="_blank"
                                           class="btn btn-outline-light">
                                            {{ $customSection->link_text }}
                                            <i class="fas fa-external-link-alt ms-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif



        @if ($settings->google_adsense && $settings->google_ads_index == 'on' && $settings->google_adsense_index != '')
            <div class="col-md-12 mt-3">
                {!! $settings->google_adsense_index !!}
            </div>
        @endif
    </div><!-- container photos -->



    @if ($settings->show_counter == 'on')
        <section class="section py-4 bg-dark text-white counter-stats">
            <div class="container">
                <div class="row g-4">
                    {{-- Always show portfolio views --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                            <span class="me-3 display-4"><i class="bi bi-eye align-baseline"></i></span>
                            <div class="text-center text-md-start">
                                <h3 class="mb-0"><span class="counter">{{ number_format($portfolioStats['totalViews']) }}</span></h3>
                                <h5 class="mb-0">{{ __('misc.portfolio_views') }}</h5>
                            </div>
                        </div>
                    </div>

                    {{-- Show projects if available, otherwise show skills --}}
                    @if ($portfolioStats['totalProjects'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-briefcase align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalProjects'] }}</span></h3>
                                    <h5 class="mb-0">{{ __('misc.projects') }}</h5>
                                </div>
                            </div>
                        </div>
                    @elseif ($portfolioStats['totalSkills'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-gear align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalSkills'] }}</span></h3>
                                    <h5 class="mb-0">{{ __('misc.skills') }}</h5>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-layers align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalSections'] }}</span></h3>
                                    <h5 class="mb-0">Portfolio Sections</h5>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Show experience years if available, otherwise show certifications --}}
                    @if ($portfolioStats['experienceYears'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-calendar-check align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counterStats">{{ $portfolioStats['experienceYears'] }}</span></h3>
                                    <h5 class="mb-0">{{ __('misc.years_experience') }}</h5>
                                </div>
                            </div>
                        </div>
                    @elseif ($portfolioStats['totalCertifications'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-award align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalCertifications'] }}</span></h3>
                                    <h5 class="mb-0">{{ __('misc.certifications') }}</h5>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-mortarboard align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalEducations'] }}</span></h3>
                                    <h5 class="mb-0">{{ __('misc.education') }}</h5>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Fourth stat - prioritize testimonials, then skills, then sections --}}
                    @if ($portfolioStats['totalTestimonials'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-chat-quote align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalTestimonials'] }}</span></h3>
                                    <h5 class="mb-0">{{ __('misc.testimonials') }}</h5>
                                </div>
                            </div>
                        </div>
                    @elseif ($portfolioStats['totalSkills'] > 0 && $portfolioStats['totalProjects'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-gear align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalSkills'] }}</span></h3>
                                    <h5 class="mb-0">{{ __('misc.skills_mastered') }}</h5>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center align-items-center">
                                <span class="me-3 display-4"><i class="bi bi-collection align-baseline"></i></span>
                                <div class="text-center text-md-start">
                                    <h3 class="mb-0"><span class="counter">{{ $portfolioStats['totalSections'] }}</span></h3>
                                    <h5 class="mb-0">Portfolio Sections</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif



@endsection

@section('javascript')
    <script type="text/javascript">
        // FORCE 2 CARDS PER ROW - NUCLEAR OPTION
        document.addEventListener('DOMContentLoaded', function() {
            // Force all sections to display 2 cards per row


            sections.forEach(selector => {
                const elements = document.querySelectorAll(selector);
                elements.forEach(el => {
                    el.style.flex = '0 0 50%';
                    el.style.maxWidth = '50%';
                    el.style.width = '50%';
                    el.style.display = 'block';
                    el.style.float = 'left';
                });
            });
        });

        // Ensure avatar fallback works properly
        document.addEventListener('DOMContentLoaded', function() {
            const avatarImg = document.querySelector('.hero-avatar .avatar-image');
            const avatarFallback = document.querySelector('.hero-avatar .avatar-fallback');

            if (avatarImg && avatarFallback) {
                // Initially hide fallback if image exists
                avatarFallback.style.display = 'none !important';

                // Check if image loaded successfully
                avatarImg.addEventListener('load', function() {
                    // Image loaded successfully, ensure fallback is hidden
                    avatarImg.style.display = 'block';
                    avatarFallback.style.display = 'none !important';
                });

                avatarImg.addEventListener('error', function() {
                    // Image failed to load, show fallback
                    this.style.display = 'none';
                    avatarFallback.style.display = 'flex !important';
                });

                // Additional check after a short delay
                setTimeout(function() {
                    if (avatarImg.complete && avatarImg.naturalHeight === 0) {
                        // Image failed to load
                        avatarImg.style.display = 'none';
                        avatarFallback.style.display = 'flex !important';
                    } else if (avatarImg.complete && avatarImg.naturalHeight > 0) {
                        // Image loaded successfully
                        avatarImg.style.display = 'block';
                        avatarFallback.style.display = 'none !important';
                    }
                }, 100);
            }
        });

        $('#imagesFlex').flexImages({
            rowHeight: 320,
            maxRows: 8,
            truncate: true
        });
        $('#imagesFlexFeatured').flexImages({
            rowHeight: 320,
            maxRows: 8,
            truncate: true
        });

        @if (session('success_verify'))
            swal({
                title: "{{ __('misc.welcome') }}",
                text: "{{ __('users.account_validated') }}",
                type: "success",
                confirmButtonText: "{{ __('users.ok') }}"
            });
        @endif

        @if (session('error_verify'))
            swal({
                title: "{{ __('misc.error_oops') }}",
                text: "{{ __('users.code_not_valid') }}",
                type: "error",
                confirmButtonText: "{{ __('users.ok') }}"
            });
        @endif
    </script>
@endsection
