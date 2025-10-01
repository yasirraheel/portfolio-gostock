@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="container-fluid home-cover">
      <div class="mb-4 position-relative custom-pt-6">
        <div class="container px-2 px-lg-4">
          @if ($settings->announcement != '' && $settings->announcement_show == 'all'
              || $settings->announcement != '' && $settings->announcement_show == 'users' && auth()->check())
            <div class="alert alert-{{$settings->type_announcement}} announcements display-none alert-dismissible fade show" role="alert">
              <h4 class="alert-heading"><i class="bi-megaphone me-2"></i> {{ __('admin.announcements') }}</h4>
                    <p class="update-text">{!! $settings->announcement !!}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="closeAnnouncements">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
            @endif

            <div class="row align-items-center min-vh-75">
                <div class="col-12 text-center">
                    <h1 class="display-3 fw-bold text-white mb-4 hero-title">{{ $settings->welcome_text ?? __('seo.welcome_text') }}</h1>
                    <p class="fs-4 text-white mb-4 subtitle-blurred hero-subtitle">{{ $settings->welcome_subtitle ?? __('seo.welcome_subtitle') }}</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center hero-buttons">
                        @auth
                            <a href="{{ url('user/account') }}" class="btn btn-lg btn-main rounded-pill btn-custom px-4 arrow">
                                <i class="bi bi-person-gear me-2"></i>Manage Portfolio
                            </a>
                            @if(auth()->user()->portfolio_slug)
                                <a href="{{ url(auth()->user()->portfolio_slug) }}" class="btn btn-lg btn-outline-light rounded-pill px-4">
                                    <i class="bi bi-eye me-2"></i>View Portfolio
                                </a>
                            @else
                                <a href="{{ url('user/account') }}" class="btn btn-lg btn-outline-light rounded-pill px-4" title="Please set your portfolio URL first">
                                    <i class="bi bi-eye me-2"></i>View Portfolio
                                </a>
		  @endif
                        @else
                            <a href="{{ url('register') }}" class="btn btn-lg btn-main rounded-pill btn-custom px-4 arrow">
                                <i class="bi bi-person-plus me-2"></i>Get Started Free
                            </a>
                            <a href="{{ url('login') }}" class="btn btn-lg btn-outline-light rounded-pill px-4">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
      </div>
</div>


<!-- Features Section -->
<div class="container-fluid py-5 py-large">
    <div class="container">
  <div class="btn-block text-center mb-5">
            <h3 class="m-0">Why Choose Our Portfolio Platform?</h3>
            <p class="text-muted">Everything you need to create a professional portfolio that stands out to employers</p>
  </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-palette display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Custom Themes</h5>
                        <p class="card-text text-muted">Choose from multiple professional themes and customize colors to match your personal brand.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Private Portfolios</h5>
                        <p class="card-text text-muted">Keep your portfolio private with password protection and share only with selected HR professionals.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-phone display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Mobile Responsive</h5>
                        <p class="card-text text-muted">Your portfolio looks perfect on all devices - desktop, tablet, and mobile.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-envelope display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Contact Integration</h5>
                        <p class="card-text text-muted">Built-in contact forms allow HR professionals to reach out directly through your portfolio.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-file-earmark-text display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Rich Content</h5>
                        <p class="card-text text-muted">Showcase your experience, skills, projects, and achievements in a structured, professional format.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-gift display-4 text-primary"></i>
  </div>
                        <h5 class="card-title">Completely Free</h5>
                        <p class="card-text text-muted">No hidden costs, no premium features locked behind paywalls. Everything is free to use.</p>
  </div>
  </div>
      </div>
  </div>
    </div>
</div>

<!-- Portfolio Showcase Section -->
<div id="featured-portfolios" class="container-fluid py-5 py-large bg-light">
    <div class="container">
        <div class="btn-block text-center mb-5">
            <h3 class="m-0">Featured Portfolios</h3>
            <p class="text-muted">Discover amazing portfolios created by our talented users</p>
        </div>

        <div class="row g-4">
            @php
                $featuredPortfolios = \App\Models\User::where('status', 'active')
                    ->whereNotNull('portfolio_slug')
                    ->where('portfolio_slug', '!=', '')
                    ->where('portfolio_private', 0)
                    ->select(['id', 'name', 'username', 'avatar', 'profession', 'bio', 'portfolio_slug', 'countries_id'])
                    ->with(['country:id,country_name'])
                    ->take(6)
                    ->get();
            @endphp

            @if($featuredPortfolios->count() > 0)
                @foreach($featuredPortfolios as $user)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        @if($user->avatar)
                                            <img src="{{ url('public/avatar', $user->avatar) }}"
                                                 alt="{{ $user->name }}"
                                                 class="rounded-circle"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <span class="fw-bold">{{ substr($user->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->profession ?? 'Professional' }}</small>
                                    </div>
                                </div>

                                @if($user->bio)
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($user->bio, 100) }}
                                    </p>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $user->country ? $user->country->country_name : 'Location not set' }}
                                    </div>
                                    <a href="{{ url($user->portfolio_slug) }}"
                                       class="btn btn-sm btn-outline-custom">
                                        <i class="bi bi-eye me-1"></i>View Portfolio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback content if no portfolios -->
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-person-workspace display-1 text-muted"></i>
                        <h5 class="mt-3">No Portfolios Yet</h5>
                        <p class="text-muted">Be the first to create an amazing portfolio!</p>
                        @guest
                            <a href="{{ url('register') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i>Get Started
                            </a>
                        @endguest
                    </div>
                </div>
            @endif
        </div>

        @if($featuredPortfolios->count() > 0)
            <div class="text-center mt-5">
                <a href="{{ url('#featured-portfolios') }}" class="btn btn-custom">
                    <i class="bi bi-grid me-2"></i>View All Portfolios
                </a>
            </div>
        @endif
    </div>
</div>

<!-- How It Works Section -->
    <section class="section py-5 py-large bg-light">
      <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6 text-center mb-4">
                <i class="bi bi-laptop display-1 text-primary mb-3"></i>
                <h3 class="mb-3">Professional Portfolio Preview</h3>
                <p class="text-muted">See how your portfolio will look to potential employers</p>
            </div>
            <div class="col-12 col-lg-6">
                <h2 class="mb-4">How It Works</h2>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <span class="fw-bold">1</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">Sign Up Free</h5>
                                <p class="text-muted mb-0">Create your account in seconds with just your email address.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <span class="fw-bold">2</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">Build Your Portfolio</h5>
                                <p class="text-muted mb-0">Add your experience, skills, projects, and customize your theme.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <span class="fw-bold">3</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">Share with HR</h5>
                                <p class="text-muted mb-0">Get your custom URL and share it with potential employers.</p>
                            </div>
                        </div>
        </div>
          </div>
                <div class="mt-4">
                    @auth
                        <a href="{{ url('user/account') }}" class="btn btn-lg btn-main rounded-pill btn-custom px-4 arrow">
                            Manage Your Portfolio
                        </a>
                    @else
                        <a href="{{ url('register') }}" class="btn btn-lg btn-main rounded-pill btn-custom px-4 arrow">
                            Get Started Now
                        </a>
                    @endauth
                </div>
        </div>
      </div>
      </div>
    </section>

<!-- Stats Section -->
    @if ($settings->show_counter == 'on')
    <section class="section py-2 bg-dark text-white counter-stats">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
              <span class="me-3 display-4"><i class="bi bi-people align-baseline"></i></span>
              <div>
                        <h3 class="mb-0"><span class="counter">{{ $userCount ?? '1,250' }}</span></h3>
                        <h5>Active Users</h5>
                    </div>
              </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
                    <span class="me-3 display-4"><i class="bi bi-briefcase align-baseline"></i></span>
              <div>
                        <h3 class="mb-0"><span class="counter">{{ $userCount ?? '3,500' }}</span></h3>
                        <h5 class="font-weight-light">Portfolios Created</h5>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
                    <span class="me-3 display-4"><i class="bi bi-handshake align-baseline"></i></span>
              <div>
                        <h3 class="mb-0"><span class="counterStats">{{ $userCount ?? '850' }}</span></h3>
                        <h5 class="font-weight-light">Successful Hires</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

<!-- Testimonials Section -->
    <section class="section py-5 py-large">
      <div class="container">
        <div class="btn-block text-center mb-5">
            <h3 class="m-0">What Our Users Say</h3>
            <p class="text-muted">Real feedback from professionals who found success with our platform</p>
        </div>

        <div class="row g-4">
            @php
                $testimonials = \App\Models\LandingTestimonial::active()->ordered()->get();
            @endphp

            @if($testimonials->count() > 0)
                @foreach($testimonials as $testimonial)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm testimonial-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <span class="fw-bold">{{ $testimonial->initials }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $testimonial->client_name }}</h6>
                                        <small class="text-muted">{{ $testimonial->client_position }}</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted">"{{ $testimonial->testimonial_text }}"</p>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @endif
        </div>
    </div>
</section>



<!-- Final CTA Section -->
<section class="section py-5 py-large bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-3">Ready to Create Your Professional Portfolio?</h2>
                <p class="mb-0 fs-5">Join thousands of professionals who have already found success with our platform. It's completely free and takes just minutes to get started.</p>
            </div>
            <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end justify-content-center">
                    @auth
                        <a href="{{ url('user/account') }}" class="btn btn-lg btn-light rounded-pill px-4">
                            <i class="bi bi-person-gear me-2"></i>Manage Portfolio
                        </a>
                    @else
                        <a href="{{ url('register') }}" class="btn btn-lg btn-light rounded-pill px-4">
                            <i class="bi bi-person-plus me-2"></i>Get Started Free
                        </a>
                        <a href="{{ url('login') }}" class="btn btn-lg btn-outline-light rounded-pill px-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('javascript')
	<script type="text/javascript">
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
