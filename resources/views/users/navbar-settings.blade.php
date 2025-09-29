<button class="btn btn-custom mb-4 w-100 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navSettings" aria-expanded="false" aria-controls="collapseExample">
    <i class="bi bi-menu-down me-2"></i> {{ __('misc.menu') }}
  </button>

  <div class="card shadow-sm mb-3 collapse d-lg-block card-settings" id="navSettings">
  <div class="list-group list-group-flush">

    <a class="list-group-item list-group-item-action d-flex justify-content-between" href="{{ url(auth()->user()->username) }}">
			<div>
				<i class="bi-person me-2"></i>
				<span>{{ __('users.my_profile') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard*'))active @endif" href="{{ url('user/dashboard') }}">
			<div>
				<i class="bi bi-speedometer2 me-2"></i>
				<span>Portfolio Dashboard</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/account*') && !request()->is('user/account/password*'))active @endif" href="{{ url('user/account') }}">
			<div>
				<i class="bi bi-person me-2"></i>
				<span>{{ __('users.account_settings') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/account/password*'))active @endif" href="{{ url('user/account/password') }}">
			<div>
				<i class="bi bi-key me-2"></i>
				<span>{{ __('auth.password') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/theme*'))active @endif" href="{{ url('user/theme') }}">
			<div>
				<i class="bi bi-palette me-2"></i>
				<span>{{ __('admin.theme') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/skills*'))active @endif" href="{{ url('user/skills') }}">
			<div>
				<i class="fas fa-user-graduate me-2"></i>
				<span>{{ __('misc.professional_skills') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/experience*'))active @endif" href="{{ url('user/experience') }}">
			<div>
				<i class="fas fa-briefcase me-2"></i>
				<span>{{ __('misc.professional_experience') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/education*'))active @endif" href="{{ url('user/education') }}">
			<div>
				<i class="fas fa-graduation-cap me-2"></i>
				<span>{{ __('misc.education') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/certifications*') || request()->is('user/certification*'))active @endif" href="{{ url('user/certifications') }}">
			<div>
				<i class="fas fa-certificate me-2"></i>
				<span>{{ __('misc.certifications') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/projects*'))active @endif" href="{{ url('user/projects') }}">
			<div>
				<i class="fas fa-project-diagram me-2"></i>
				<span>{{ __('misc.professional_projects') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/testimonials*') || request()->is('user/testimonial*'))active @endif" href="{{ url('user/testimonials') }}">
			<div>
				<i class="fas fa-quote-right me-2"></i>
				<span>{{ __('misc.testimonials') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/custom-section*'))active @endif" href="{{ url('user/custom-sections') }}">
			<div>
				<i class="fas fa-plus-square me-2"></i>
				<span>{{ __('misc.custom_sections') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

  </div>
</div>
