@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="container-fluid home-cover">
      <div class="mb-4 position-relative custom-pt-6">
        <div class="container px-5">
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
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold text-white mb-4">Create Your Professional Portfolio</h1>
                    <p class="fs-4 text-white mb-4">Build, customize, and share your portfolio with HR professionals. Free to use, professional results.</p>
                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('account') }}" class="btn btn-lg btn-main rounded-pill btn-custom px-4 arrow">
                                <i class="bi bi-person-gear me-2"></i>Manage Portfolio
                            </a>
                            <a href="{{ url('account') }}" class="btn btn-lg btn-outline-light rounded-pill px-4">
                                <i class="bi bi-eye me-2"></i>View Portfolio
                            </a>
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
                <div class="col-lg-6 text-center">
                    <div class="hero-mockup">
                        <i class="bi bi-laptop display-1 text-white opacity-75"></i>
                        <p class="text-white mt-3">Professional Portfolio Preview</p>
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
                        <a href="{{ url('account') }}" class="btn btn-lg btn-main rounded-pill btn-custom px-4 arrow">
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
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fw-bold">JS</span>
                            </div>
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">Software Developer</small>
                            </div>
                        </div>
                        <p class="card-text text-muted">"This platform helped me land my dream job! The portfolio looked so professional that HR was impressed from the first glance."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fw-bold">MJ</span>
                            </div>
                            <div>
                                <h6 class="mb-0">Maria Johnson</h6>
                                <small class="text-muted">UX Designer</small>
                            </div>
                        </div>
                        <p class="card-text text-muted">"The private portfolio feature is amazing. I can share my work with specific companies without making it public."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fw-bold">DR</span>
                            </div>
                            <div>
                                <h6 class="mb-0">David Rodriguez</h6>
                                <small class="text-muted">Marketing Manager</small>
                            </div>
                        </div>
                        <p class="card-text text-muted">"Free and easy to use! I created my portfolio in 30 minutes and got 3 job interviews the same week."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
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
                @auth
                    <a href="{{ url('account') }}" class="btn btn-lg btn-light rounded-pill px-4 me-2">
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
