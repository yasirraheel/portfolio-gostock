<header class="fixed-top bg-white py-3" id="header" style="height: 60px; z-index: 1000;">
        <div class="container-fluid d-flex align-items-stretch px-2 px-lg-4 gap-2 gap-lg-3 h-100">

            @php
                // Determine which logos to show and where to link
                $logoDark = $settings->logo_light;
                $logoLight = $settings->logo_light;
                $faviconToShow = $settings->favicon;
                $linkDestination = url('/');
                $logoPath = 'public/img'; // Default path for admin theme logos
                $isUserPortfolio = false;

                // Check if we're on a user profile page (regardless of auth status)
                // Only use user portfolio settings if we're actually on a user's portfolio page
                if (isset($user) && $user && request()->is($user->portfolio_slug)) {
                    $isUserPortfolio = true;
                    // We're viewing someone's profile, use their logos and link to their portfolio
                    if ($user->portfolio_logo) {
                        $logoDark = $user->portfolio_logo;
                        $logoPath = 'public/portfolio_assets';
                    } else {
                        $logoDark = $settings->logo_light;
                        $logoPath = 'public/img';
                    }

                    if ($user->portfolio_logo_light) {
                        $logoLight = $user->portfolio_logo_light;
                    } elseif ($user->portfolio_logo) {
                        $logoLight = $user->portfolio_logo;
                    } else {
                        $logoLight = $settings->logo_light;
                    }

                    $faviconToShow = $user->portfolio_favicon ?: $settings->favicon;
                    $linkDestination = url('/' . ($user->portfolio_slug ?: $user->username));
                }
                // For all other pages (home, etc.), use global admin theme logo
                // The default values above already handle this case
            @endphp

            @if(isset($user) && $user && request()->is($user->portfolio_slug) && !request()->is('/') && !request()->is('portfolios'))
            <style>
                /* Override global admin colors with user's custom colors on portfolio pages */
                :root {
                    --color-default: {{ $user->portfolio_primary_color ?? '#268707' }} !important;
                }
            </style>
            @endif
            <a href="{{ $linkDestination }}" class="d-flex align-items-center link-dark text-decoration-none fw-bold display-6 h-100" style="padding: 1px; margin: 0;">
                <img src="{{ url($logoPath, $logoDark) }}" class="logoMain d-none d-lg-block" style="height: 100%; width: auto; object-fit: contain;" />
                <img src="{{ url($logoPath, $logoLight) }}" class="logoLight d-none d-lg-block" style="height: 100%; width: auto; object-fit: contain;" />
              </a>

          <div class="d-flex align-items-center ms-auto h-100">

            <!-- Start Nav -->
            <ul class="nav d-flex align-items-center mb-0 navbar-session d-none d-lg-flex">

              @if ($plansActive != 0 && $settings->sell_option == 'on')
                <li><a href="{{url('pricing')}}" class="nav-link px-2 link-dark">{{__('misc.pricing')}}</a></li>
              @endif


              @auth
              @endauth

            </ul><!-- End Nav -->

                @guest
                  <a class="btn btn-custom ms-1 animate-up-2 d-none d-lg-block" href="{{ url('login') }}">
                  <strong>{{ __('auth.login') }}</strong>
                  </a>
                @endguest


            @auth
            <div class="flex-shrink-0 dropdown" style="position: relative;">
              <a href="javascript:void(0);" class="d-block link-dark text-decoration-none" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                @if(isset($user) && $user)
                  @if($user->avatar && file_exists(public_path('avatar/' . $user->avatar)))
                    <div class="navbar-avatar-container" style="position: relative; width: 32px; height: 32px;">
                      <img src="{{ url('public/avatar', $user->avatar) }}"
                           width="32" height="32"
                           class="rounded-circle navbar-avatar-image"
                           style="position: absolute; top: 0; left: 0; z-index: 2;"
                           onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center navbar-avatar-fallback"
                           style="width: 32px; height: 32px; position: absolute; top: 0; left: 0; z-index: 1; display: none;">
                        <span class="fw-bold small">{{ substr($user->name, 0, 2) }}</span>
                      </div>
                    </div>
                  @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                      <span class="fw-bold small">{{ substr($user->name, 0, 2) }}</span>
                    </div>
                  @endif
                @else
                  <i class="bi bi-person-circle fs-3"></i>
                @endif
              </a>
              <ul class="dropdown-menu dropdown-menu-macos dropdown-menu-end arrow-dm" aria-labelledby="dropdownUser2" style="right: 0; left: auto; min-width: 200px;">
                @include('includes.menu-dropdown')
              </ul>
            </div>
            @endauth

            <a class="ms-2 toggle-menu d-block d-lg-none text-dark fs-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" href="#">
            <i class="bi-list"></i>
            </a>

          </div><!-- d-flex -->
        </div><!-- container-fluid -->
      </header>

    <div class="offcanvas offcanvas-end w-75" tabindex="-1" id="offcanvas" data-bs-keyboard="false" data-bs-backdrop="false">
    <div class="offcanvas-header">
        <span class="offcanvas-title" id="offcanvas"></span>
        <button type="button" class="btn-close text-reset close-menu-mobile" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body px-0">
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start" id="menu">

          @if ($plansActive != 0 && $settings->sell_option == 'on')
            <li>
              <a href="{{url('pricing')}}" class="nav-link link-dark text-truncate">
              {{__('misc.pricing')}}
            </a>
          </li>
          @endif

            <li>
                <a href="#explore" data-bs-toggle="collapse" class="nav-link text-truncate link-dark dropdown-toggle">
                    {{__('misc.explore')}}
                  </a>
            </li>

            <div class="collapse ps-3" id="explore">

              <li><a class="nav-link text-truncate text-muted" href="{{ url('members') }}"><i class="bi bi-people me-2"></i> {{ __('misc.members') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('collections') }}"><i class="bi bi-plus-square me-2"></i> {{ __('misc.collections') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('explore/vectors') }}"><i class="bi-bezier me-2"></i> {{ __('misc.vectors') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('tags') }}"><i class="bi-tags me-2"></i> {{ __('misc.tags') }}</a></li>

              @if ($settings->sell_option == 'on')
              <li><a class="nav-link text-truncate text-muted" href="{{ url('photos/premium') }}"><i class="fa fa-crown me-2 text-warning"></i> {{ __('misc.premium') }}</a></li>
              @endif

              <li><a class="nav-link text-truncate text-muted" href="{{ url('featured') }}">{{ __('misc.featured') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('popular') }}">{{ __('misc.popular') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('latest') }}">{{ __('misc.latest') }}</a></li>
              @if ($settings->comments)
              <li><a class="nav-link text-truncate text-muted" href="{{ url('most/commented') }}">{{__('misc.most_commented')}}</a></li>
            @endif
              <li><a class="nav-link text-truncate text-muted" href="{{ url('most/viewed') }}">{{__('misc.most_viewed')}}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('most/downloads') }}">{{__('misc.most_downloads')}}</a></li>
            </div>

          @guest
            <li class="p-3 w-100">
              <a href="{{ url('login') }}" class="btn btn-custom d-block w-100 animate-up-2" title="{{ __('auth.login') }}">
                <strong>{{ __('auth.login') }}</strong>
              </a>
            </li>
          @endguest
        </ul>
    </div>
</div>

@auth
<div class="menuMobile w-100 d-lg-none d-sm-block bg-white shadow-lg p-3 border-top">
	<ul class="list-inline d-flex m-0 text-center">

				<li class="flex-fill">
					<a class="p-3 btn-mobile" href="{{ url('home') }}">
						<i class="bi-house{{ request()->is('/') ? '-fill' : null }} icon-navbar"></i>
					</a>
				</li>

				<li class="flex-fill">
					<a class="p-3 btn-mobile" href="{{ url('latest') }}">
						<i class="bi-compass{{ request()->is('latest') ? '-fill' : null }} icon-navbar"></i>
					</a>
				</li>



      <li class="flex-fill">
				<a href="{{ url(auth()->user()->username) }}" class="p-3 btn-mobile position-relative">

					<i class="bi-person{{ request()->is(auth()->user()->username) ? '-fill' : null }} icon-navbar"></i>
				</a>
			</li>

			</ul>
</div>
@endauth
