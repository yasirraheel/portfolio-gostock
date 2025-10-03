@if (auth()->user()->role && ! request()->is('panel/admin') && ! request()->is('panel/admin/*'))
  <li><a class="dropdown-item" href="{{ url('panel/admin') }}"><i class="bi bi-speedometer2 me-2"></i> {{ __('admin.admin') }}</a></li>
  <li><hr class="dropdown-divider"></li>
@endif

@if (session('admin_id') && session('admin_name'))
  <li><a class="dropdown-item text-warning fw-bold" href="{{ route('admin.return') }}"><i class="fas fa-user-secret me-2"></i> {{ __('admin.return_to_admin') }} ({{ session('admin_name') }})</a></li>
  <li><hr class="dropdown-divider"></li>
@endif

<li>
<a class="dropdown-item" href="{{ url('user/account') }}">
    <i class="bi bi-gear me-2"></i> {{ __('users.account_settings') }}
    </a>
</li>

@if(auth()->user()->portfolio_slug)
<li>
<a class="dropdown-item" href="{{ url(auth()->user()->portfolio_slug) }}">
    <i class="bi bi-eye me-2"></i> {{ __('users.view_portfolio') }}
    </a>
</li>
@endif

<li>
<a class="dropdown-item" href="{{ url(auth()->user()->username) }}">
    <i class="bi bi-person me-2"></i> {{ __('users.my_profile') }}
    </a>
</li>

<li><hr class="dropdown-divider"></li>
<li>
  <a class="dropdown-item" href="javascript:void(0);" id="switchTheme">
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

<li><hr class="dropdown-divider"></li>
<li>
  <a class="dropdown-item" href="{{ url('logout') }}">
    <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('users.logout') }}</a>
  </li>
