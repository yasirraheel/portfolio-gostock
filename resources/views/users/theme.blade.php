@extends('layouts.app')

@section('title') {{ __('admin.theme') }} - @endsection

@section('content')
<section class="section section-sm">
	<div class="container-custom container pt-5">
		<div class="row">
			<div class="col-md-3">
				@include('users.navbar-settings')
			</div>

			<!-- Col MD -->
			<div class="col-md-9">
				@if (session('success_message'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<i class="bi bi-check2 me-1"></i> {{ session('success_message') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
							<i class="bi bi-x-lg"></i>
						</button>
					</div>
				@endif

				@if (session('notification'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<i class="bi bi-check2 me-1"></i> {{ session('notification') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
							<i class="bi bi-x-lg"></i>
						</button>
					</div>
				@endif

				@include('errors.errors-forms')

				<div class="card shadow-custom border-0">
					<div class="card-body p-lg-4">
						<div class="row">
							<div class="col-lg-6">
								<h4 class="mb-0">{{ __('admin.theme') }}</h4>
								<small class="text-muted">{{ __('misc.customize_your_portfolio_theme') }}</small>
							</div>
						</div>
					</div>
				</div>

				<div class="card shadow-custom border-0 mt-3">
					<div class="card-body p-lg-4">
						<form method="POST" action="{{ route('user.theme.store') }}" enctype="multipart/form-data">
							@csrf

							<h6 class="mb-3">{{ __('misc.branding_assets') }}</h6>

							<!-- Logo Dark -->
							<div class="mb-4">
								@if(auth()->user()->portfolio_logo)
									<div class="mb-3">
										<h6 class="text-success mb-2">{{ __('misc.current_dark_logo') }}:</h6>
										<img src="{{ url('public/portfolio_assets', auth()->user()->portfolio_logo) }}" class="img-thumbnail" style="width:150px; max-height:100px; object-fit: contain;">
									</div>
								@endif
								<label class="form-label">{{ __('admin.logo_dark') }}</label>
								<div class="input-group mb-1">
									<input name="portfolio_logo" type="file" class="form-control custom-file rounded-pill @error('portfolio_logo') is-invalid @enderror" accept="image/png">
								</div>
								<small class="d-block text-muted">
									<i class="bi bi-info-circle me-1"></i>{{ __('misc.recommended_size') }} 400x400 px (PNG)
								</small>
								@error('portfolio_logo')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<!-- Logo Light -->
							<div class="mb-4">
								@if(auth()->user()->portfolio_logo_light)
									<div class="mb-3">
										<h6 class="text-success mb-2">{{ __('misc.current_light_logo') }}:</h6>
										<img src="{{ url('public/portfolio_assets', auth()->user()->portfolio_logo_light) }}" class="img-thumbnail bg-secondary" style="width:150px; max-height:100px; object-fit: contain;">
									</div>
								@endif
								<label class="form-label">{{ __('admin.logo_light') }}</label>
								<div class="input-group mb-1">
									<input name="portfolio_logo_light" type="file" class="form-control custom-file rounded-pill @error('portfolio_logo_light') is-invalid @enderror" accept="image/png">
								</div>
								<small class="d-block text-muted">
									<i class="bi bi-info-circle me-1"></i>{{ __('misc.recommended_size') }} 400x400 px (PNG)
								</small>
								@error('portfolio_logo_light')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<!-- Favicon -->
							<div class="mb-4">
								@if(auth()->user()->portfolio_favicon)
									<div class="mb-3">
										<h6 class="text-success mb-2">{{ __('misc.current_favicon') }}:</h6>
										<img src="{{ url('public/portfolio_assets', auth()->user()->portfolio_favicon) }}" class="img-thumbnail" style="width:48px; height:48px; object-fit: contain;">
									</div>
								@endif
								<label class="form-label">{{ __('misc.favicon') }}</label>
								<div class="input-group mb-1">
									<input name="portfolio_favicon" type="file" class="form-control custom-file rounded-pill @error('portfolio_favicon') is-invalid @enderror" accept="image/png">
								</div>
								<small class="d-block text-muted">
									<i class="bi bi-info-circle me-1"></i>{{ __('misc.recommended_size') }} 48x48 px (PNG)
								</small>
								@error('portfolio_favicon')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.color_scheme') }}</h6>

							<!-- Colors -->
							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.color_primary') }}</label>
										<input type="color" name="portfolio_primary_color" class="form-control form-control-color @error('portfolio_primary_color') is-invalid @enderror" value="{{ auth()->user()->portfolio_primary_color ?? '#007bff' }}" style="height: 3rem;">
										@error('portfolio_primary_color')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">{{ __('admin.color_secondary') }}</label>
										<input type="color" name="portfolio_secondary_color" class="form-control form-control-color @error('portfolio_secondary_color') is-invalid @enderror" value="{{ auth()->user()->portfolio_secondary_color ?? '#6c757d' }}" style="height: 3rem;">
										@error('portfolio_secondary_color')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.appearance_settings') }}</h6>

							<!-- Theme Mode -->
							<div class="form-floating mb-3">
								<select class="form-select @error('portfolio_theme') is-invalid @enderror" name="portfolio_theme" id="portfolio_theme">
									<option value="light" {{ (auth()->user()->portfolio_theme ?? 'light') == 'light' ? 'selected' : '' }}>{{ __('admin.light') }}</option>
									<option value="dark" {{ (auth()->user()->portfolio_theme ?? 'light') == 'dark' ? 'selected' : '' }}>{{ __('admin.dark') }}</option>
									<option value="auto" {{ (auth()->user()->portfolio_theme ?? 'light') == 'auto' ? 'selected' : '' }}>{{ __('misc.auto') }}</option>
								</select>
								<label for="portfolio_theme">{{ __('admin.theme_mode') }}</label>
								@error('portfolio_theme')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.typography_settings') }}</h6>

							<!-- Font Family -->
							<div class="form-floating mb-3">
								<select class="form-select @error('portfolio_font_family') is-invalid @enderror" name="portfolio_font_family" id="portfolio_font_family">
									<option value="Inter" {{ (auth()->user()->portfolio_font_family ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter</option>
									<option value="Poppins" {{ (auth()->user()->portfolio_font_family ?? 'Inter') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
									<option value="Roboto" {{ (auth()->user()->portfolio_font_family ?? 'Inter') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
									<option value="Open Sans" {{ (auth()->user()->portfolio_font_family ?? 'Inter') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
									<option value="Montserrat" {{ (auth()->user()->portfolio_font_family ?? 'Inter') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
									<option value="Source Sans Pro" {{ (auth()->user()->portfolio_font_family ?? 'Inter') == 'Source Sans Pro' ? 'selected' : '' }}>Source Sans Pro</option>
								</select>
								<label for="portfolio_font_family">{{ __('admin.font_family') }}</label>
								@error('portfolio_font_family')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<!-- Font Size -->
							<div class="form-floating mb-3">
								<input type="number" class="form-control @error('portfolio_font_size') is-invalid @enderror" name="portfolio_font_size" value="{{ auth()->user()->portfolio_font_size ?? 16 }}" min="12" max="24" id="portfolio_font_size" placeholder="Font Size">
								<label for="portfolio_font_size">{{ __('admin.font_size') }} (px)</label>
								@error('portfolio_font_size')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<!-- Form Actions -->
							<div class="d-flex gap-2">
								<button type="submit" class="btn btn-custom">
									<i class="bi bi-check2 me-1"></i>{{ __('admin.save') }}
								</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
