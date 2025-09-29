<div class="py-5 py-footer-large bg-dark-2 text-light">
  <footer class="container">
     <div class="row">
        <div class="col-md-4">
           @php
               // Determine which logo to show and where to link
               $footerLogo = $settings->logo_light;
               $footerLink = url('/');
               $footerLogoPath = 'public/img'; // Default path for admin theme logos
               $isUserPortfolio = false;

               // Check if we're on a user profile page (regardless of auth status)
               if (isset($user) && $user) {
                   $isUserPortfolio = true;
                   // We're viewing someone's profile, use their logo and link to their portfolio
                   if ($user->portfolio_logo_light) {
                       $footerLogo = $user->portfolio_logo_light;
                       $footerLogoPath = 'public/portfolio_assets';
                   } elseif ($user->portfolio_logo) {
                       $footerLogo = $user->portfolio_logo;
                       $footerLogoPath = 'public/portfolio_assets';
                   } else {
                       $footerLogo = $settings->logo_light;
                       $footerLogoPath = 'public/img';
                   }
                   $footerLink = url('/' . ($user->portfolio_slug ?: $user->username));
               }
               // For all other pages (home, etc.), use global admin theme logo
           @endphp

           @if(isset($user) && $user)
           <style>
               /* Override global admin colors with user's custom colors on portfolio pages */
               :root {
                   --color-default: {{ $user->portfolio_primary_color ?? '#268707' }} !important;
               }
           </style>
           @endif
           <a href="{{ $footerLink }}">
           <img src="{{ url($footerLogoPath, $footerLogo) }}" width="150">
           </a>
           @php
               // Determine which social links to show
               $showSocialLinks = false;
               $socialLinks = [];

               if (isset($user) && $user) {
                   // On portfolio pages, use user's social links
                   if ($user->twitter) { $socialLinks['twitter'] = $user->twitter; $showSocialLinks = true; }
                   if ($user->facebook) { $socialLinks['facebook'] = $user->facebook; $showSocialLinks = true; }
                   if ($user->instagram) { $socialLinks['instagram'] = $user->instagram; $showSocialLinks = true; }
                   if ($user->linkedin) { $socialLinks['linkedin'] = $user->linkedin; $showSocialLinks = true; }
               } else {
                   // On global pages, use admin settings
                   if ($settings->twitter) { $socialLinks['twitter'] = $settings->twitter; $showSocialLinks = true; }
                   if ($settings->facebook) { $socialLinks['facebook'] = $settings->facebook; $showSocialLinks = true; }
                   if ($settings->instagram) { $socialLinks['instagram'] = $settings->instagram; $showSocialLinks = true; }
                   if ($settings->linkedin) { $socialLinks['linkedin'] = $settings->linkedin; $showSocialLinks = true; }
                   if ($settings->youtube) { $socialLinks['youtube'] = $settings->youtube; $showSocialLinks = true; }
                   if ($settings->pinterest) { $socialLinks['pinterest'] = $settings->pinterest; $showSocialLinks = true; }
               }
           @endphp

           @if ($showSocialLinks)
           <span class="w-100 d-block mb-2">{{ __('misc.desc_footer_social') }}</span>
           @endif
           <ul class="list-inline list-social">
              @if (isset($socialLinks['twitter']))
              <li class="list-inline-item"><a href="{{ $socialLinks['twitter'] }}" target="_blank" class="ico-social"><i class="bi-twitter-x"></i></a></li>
              @endif
              @if (isset($socialLinks['facebook']))
              <li class="list-inline-item"><a href="{{ $socialLinks['facebook'] }}" target="_blank" class="ico-social"><i class="fab fa-facebook"></i></a></li>
              @endif
              @if (isset($socialLinks['instagram']))
              <li class="list-inline-item"><a href="{{ $socialLinks['instagram'] }}" target="_blank" class="ico-social"><i class="fab fa-instagram"></i></a></li>
              @endif
              @if (isset($socialLinks['linkedin']))
              <li class="list-inline-item"><a href="{{ $socialLinks['linkedin'] }}" target="_blank" class="ico-social"><i class="fab fa-linkedin"></i></a></li>
              @endif
              @if (isset($socialLinks['youtube']))
              <li class="list-inline-item"><a href="{{ $socialLinks['youtube'] }}" target="_blank" class="ico-social"><i class="fab fa-youtube"></i></a></li>
              @endif
              @if (isset($socialLinks['pinterest']))
              <li class="list-inline-item"><a href="{{ $socialLinks['pinterest'] }}" target="_blank" class="ico-social"><i class="fab fa-pinterest"></i></a></li>
              @endif
           </ul>
           <li>
              <div id="installContainer" class="display-none">
                 <button class="btn btn-custom w-100 rounded-pill mb-4" id="butInstall" type="button">
                 <i class="bi-phone mr-1"></i> {{ __('misc.install_web_app') }}
                 </button>
              </div>
           </li>
        </div>
        <div class="col-md-4">
           <h6 class="text-uppercase">{{__('misc.about')}}</h6>
           <ul class="list-unstyled">
              @foreach (Helper::pages() as $page)
              <li><a class="text-white text-decoration-none" href="{{url('page', $page->slug) }}">{{ $page->title }}</a></li>
              @endforeach
              @if ($settings->link_blog != '')
              <li><a class="text-white text-decoration-none" target="_blank" href="{{ $settings->link_blog }}">{{ __('misc.blog') }}</a></li>
              @endif
              <li><a class="text-white text-decoration-none" href="{{ isset($user) && $user ? url('contact?portfolio=' . ($user->portfolio_slug ?: $user->username)) : url('contact') }}">{{ __('misc.contact') }}</a></li>
              </li>
           </ul>
        </div>
        <div class="col-md-4">
           <h6 class="text-uppercase">{{__('misc.account')}}</h6>
           <ul class="list-unstyled">
              @guest
              <li>
                 <a class="text-white text-decoration-none" href="{{ url('login') }}">{{ __('auth.login') }}</a>
              </li>
              @if ($settings->registration_active == 1)
              <li>
                 <a class="text-white text-decoration-none" href="{{ url('register') }}">{{ __('auth.sign_up') }}</a>
              </li>
              @endif

              <li class="my-2">
               <a class="text-white text-decoration-none" href="javascript:void(0);" id="switchTheme">
                  @if (is_null(request()->cookie('theme')))

                  <i class="bi-{{ $settings->theme == 'light' ? 'moon-stars' : 'sun' }} me-2"></i>
                    {{ $settings->theme == 'light' ? __('misc.dark_mode') : __('misc.light_mode') }}

                    @elseif (request()->cookie('theme') == 'light')
                    <i class="bi-moon-stars me-2"></i> {{ __('misc.dark_mode') }}
                    @elseif (request()->cookie('theme') == 'dark')
                     <i class="bi-sun me-2"></i> {{ __('misc.light_mode') }}
                    @endif
                </a>
               </li>

              @else
              @if (auth()->user()->role)
              <li>
                 <a class="text-white text-decoration-none" href="{{ url('panel/admin') }}">{{ __('admin.admin') }}</a>
              </li>
              @endif
              <li>
                 <a class="text-white text-decoration-none" href="{{ url(auth()->user()->username) }}">{{ __('users.my_profile') }}</a>
              </li>
              <li>
                 <a class="text-white text-decoration-none" href="{{ url('logout') }}">{{ __('users.logout') }}</a>
              </li>
              @endguest
              <li class="dropdown mt-1">
                 <div class="btn-group dropup">
                    <a class="btn btn-outline-light rounded-pill mt-2 dropdown-toggle px-4" id="dropdownLang" href="javascript:;" data-bs-toggle="dropdown">
                    <i class="fa fa-globe me-1"></i>
                    @foreach ($languages as $language)
                    @if ($language->abbreviation == config('app.locale'))
                    {{ $language->name }}
                    @endif
                    @endforeach
                    </a>
                    <div class="dropdown-menu dropdown-menu-macos">
                       @foreach ($languages as $language)
                       <a class="dropdown-item dropdown-lang @if ($language->abbreviation == config('app.locale')) active  @endif" aria-labelledby="dropdownLang" @if ($language->abbreviation != config('app.locale')) href="{{ url('change/lang', $language->abbreviation) }}" @endif>
                       @if ($language->abbreviation == config('app.locale'))
                       <i class="bi bi-check2 me-1"></i>
                       @endif
                       {{ $language->name }}
                       @endforeach
                       </a>
                    </div>
                 </div>
                 <!-- dropup -->
              </li>
           </ul>
        </div>
     </div>
  </footer>
</div>
<footer class="py-2 bg-dark-3 text-white">
  <div class="container">
     <div class="row">
        <div class="col-md-12 text-center">
           &copy; {{ date('Y') }} - {{ $settings->title }}, {{ __('emails.rights_reserved') }}
        </div>
     </div>
  </div>
</footer>
