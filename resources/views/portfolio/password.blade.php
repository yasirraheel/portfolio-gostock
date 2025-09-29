@extends('layouts.app')

@section('title') Portfolio Access - @endsection

@section('content')
<section class="section section-sm">
  <div class="container">
    <div class="row justify-content-center">
      <!-- Col MD -->
      <div class="col-md-6">
        <div class="col-lg-12 py-5">
          <h1 class="mb-0 text-center">
            <i class="bi bi-lock me-2"></i>{{ __('misc.portfolio_access') }}
          </h1>
          <p class="lead text-muted mt-3 text-center">{{ __('misc.portfolio_password_required') }}</p>
        </div>

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @include('errors.errors-forms')

        <!-- ***** FORM ***** -->
        <form action="{{ url('portfolio/password') }}" method="post" name="form" id="passwordForm">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="slug" value="{{ $slug }}">

          <div class="form-floating mb-3">
            <input type="password" required class="form-control" id="inputPassword" name="password" placeholder="Enter portfolio password">
            <label for="inputPassword">Portfolio Password</label>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-lg btn-custom">
              <i class="bi bi-unlock me-2"></i>{{ __('misc.access_portfolio') }}
            </button>
          </div>

          <div class="text-center mt-4">
            <small class="text-muted">
              <i class="bi bi-info-circle me-1"></i>
              {{ __('misc.contact_owner_for_access') }}
            </small>
            <br>
            <small class="text-muted">
              <i class="bi bi-clock me-1"></i>
              {{ __('misc.access_expires_after') }}
            </small>
          </div>
        </form>

        <!-- Portfolio Owner Info -->
        @if($user)
        <div class="card mt-4">
          <div class="card-body text-center">
            <h6 class="card-title">Portfolio Owner</h6>
            <p class="card-text mb-2">
              <strong>{{ $user->name }}</strong>
              @if($user->profession)
                <br><span class="text-muted">{{ $user->profession }}</span>
              @endif
            </p>
            @if($user->show_contact_form == 'yes')
              <a href="{{ url('contact?portfolio=' . $slug) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-envelope me-1"></i>Contact Owner
              </a>
            @endif
          </div>
        </div>
        @endif

      </div><!-- /COL MD -->
    </div><!-- row -->
  </div><!-- container -->
</section>
@endsection

@section('javascript')
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Focus on password input
    document.getElementById('inputPassword').focus();

    // Handle form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('inputPassword').value;
        if (!password.trim()) {
            e.preventDefault();
            alert('Please enter a password');
            return false;
        }
    });
});
</script>
@endsection
