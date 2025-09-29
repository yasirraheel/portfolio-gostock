@extends('layouts.app')

@section('title'){{ __('misc.contact') }} -@endsection

@section('content')
<section class="section section-sm">
  <div class="container">

    <div class="row justify-content-center">
      <!-- Col MD -->
      <div class="col-md-6">

        <div class="col-lg-12 py-5">
          <h1 class="mb-0">
            {{ __('misc.contact') }}
          </h1>
          @if(request('portfolio'))
            @php
              $portfolioUser = \App\Models\User::where('portfolio_slug', request('portfolio'))
                                              ->orWhere('username', request('portfolio'))
                                              ->first();
            @endphp
            @if($portfolioUser)
              @if(request('hire'))
                <p class="lead text-muted mt-0">@lang('misc.subtitle_contact')</p>
                <div class="alert alert-warning">
                  <i class="bi bi-briefcase me-2"></i>
                  <strong>Hiring Inquiry:</strong> {{ $portfolioUser->name }}
                  @if($portfolioUser->profession)
                    <span class="text-dark">({{ $portfolioUser->profession }})</span>
                  @endif
                  <br><small class="text-dark fw-medium">This person is available for hire and looking for work opportunities.</small>
                </div>
              @else
                <p class="lead text-muted mt-0">@lang('misc.subtitle_contact')</p>
                <div class="alert alert-info">
                  <i class="bi bi-person-circle me-2"></i>
                  <strong>Contacting:</strong> {{ $portfolioUser->name }}
                  @if($portfolioUser->profession)
                    <span class="text-muted">({{ $portfolioUser->profession }})</span>
                  @endif
                </div>
              @endif
            @else
              <p class="lead text-muted mt-0">@lang('misc.subtitle_contact')</p>
            @endif
          @else
            <p class="lead text-muted mt-0">@lang('misc.subtitle_contact')</p>
          @endif
        </div>

        @if (session('notification'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('notification') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @include('errors.errors-forms')

        <!-- ***** FORM ***** -->
        <form action="{{ url('contact') }}" method="post" name="form" id="contactForm">

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if(request('portfolio'))
            <input type="hidden" name="portfolio_slug" value="{{ request('portfolio') }}">
          @endif
          @if(request('hire'))
            <input type="hidden" name="hire_inquiry" value="1">
          @endif
          <div class="row">
            <div class="col-md-6">
              <!-- ***** Form Group ***** -->
              <div class="form-floating mb-3">
                <input type="text" required class="form-control" id="inputname"
                  value="{{auth()->user()->username ??  old('full_name')}}" name="full_name"
                  placeholder="{{ __('users.name') }}" title="{{ __('users.name') }}" autocomplete="off">
                <label for="inputname">{{ __('users.name') }}</label>
              </div><!-- ***** Form Group ***** -->
            </div><!-- End Col MD-->

            <div class="col-md-6">
              <!-- ***** Form Group ***** -->
              <div class="form-floating mb-3">
                <input type="email" required class="form-control" id="inputemail"
                  value="{{auth()->user()->email ??  old('email')}}" name="email"
                  placeholder="{{ __('auth.email') }}" title="{{ __('auth.email') }}" autocomplete="off">
                <label for="inputemail">{{ __('auth.email') }}</label>
              </div><!-- ***** Form Group ***** -->
            </div><!-- End Col MD-->
          </div><!-- End row -->

          <!-- ***** Form Group ***** -->
          <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="inputsubject" value="{{old('subject')}}" name="subject"
              placeholder="{{ __('misc.subject') }}" title="{{ __('misc.subject') }}" autocomplete="off">
            <label for="inputsubject">{{ __('misc.subject') }}</label>
          </div><!-- ***** Form Group ***** -->

          <!-- ***** Form Group ***** -->
          <div class="form-floating mb-3">
            <textarea class="form-control" name="message" required placeholder="{{ __('misc.message') }}"
              id="floatingTextarea" style="height: 100px"></textarea>
            <label for="floatingTextarea">{{ __('misc.message') }}</label>
          </div><!-- ***** Form Group ***** -->

          @if($settings->captcha == 'on')
            {!! NoCaptcha::displaySubmit('contactForm', __('auth.send'), [
              'data-size' => 'invisible',
              'class' => 'btn w-100 btn-lg btn-custom'
              ]) !!}

            {!! NoCaptcha::renderJs() !!}

            <small class="d-block text-center mt-3 text-muted">
              {{__('misc.protected_recaptcha')}}
              <a href="https://policies.google.com/privacy" class="text-decoration-underline" target="_blank">{{__('misc.privacy')}}</a> -
              <a href="https://policies.google.com/terms"  class="text-decoration-underline" target="_blank">{{__('misc.terms')}}</a>
            </small>
          @else
            <button type="submit" class="btn w-100 btn-lg btn-custom">
              {{ __('auth.send') }}
            </button>
          @endif

        </form><!-- ***** END FORM ***** -->

      </div><!-- /COL MD -->
    </div><!-- row -->
  </div><!-- container -->
</section>
@endsection
