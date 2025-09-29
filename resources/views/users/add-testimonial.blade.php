@extends('layouts.app')

@section('title') {{ __('misc.add_testimonial') }} - @endsection

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

				@include('errors.errors-forms')

				<div class="card shadow-custom border-0">
					<div class="card-body p-lg-4">
						<div class="row">
							<div class="col-lg-6">
								<h4 class="mb-0">{{ __('misc.add_testimonial') }}</h4>
								<small class="text-muted">{{ __('misc.add_client_testimonial') }}</small>
							</div>
							<div class="col-lg-6 text-end">
								<a href="{{ route('user.testimonials') }}" class="btn btn-outline-primary btn-sm">
									<i class="bi bi-arrow-left me-1"></i>{{ __('misc.back_to_testimonials') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="card shadow-custom border-0 mt-3">
					<div class="card-body p-lg-4">
						<form method="POST" action="{{ route('user.testimonial.store') }}" enctype="multipart/form-data">
							@csrf

							<!-- Client Information Section -->
							<h6 class="mb-3 text-primary">{{ __('misc.client_information') }}</h6>
							<hr class="mb-3">

							<div class="row">
								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="text" name="client_name" id="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name') }}" placeholder="{{ __('misc.client_name') }}" required>
										<label for="client_name">{{ __('misc.client_name') }} *</label>
										@error('client_name')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="text" name="client_position" id="client_position" class="form-control @error('client_position') is-invalid @enderror" value="{{ old('client_position') }}" placeholder="{{ __('misc.client_position') }}">
										<label for="client_position">{{ __('misc.client_position') }}</label>
										@error('client_position')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="text" name="company_name" id="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" placeholder="{{ __('misc.company_name') }}">
										<label for="company_name">{{ __('misc.company_name') }}</label>
										@error('company_name')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="url" name="client_website" id="client_website" class="form-control @error('client_website') is-invalid @enderror" value="{{ old('client_website') }}" placeholder="{{ __('misc.client_website') }}">
										<label for="client_website">{{ __('misc.client_website') }}</label>
										@error('client_website')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<label class="form-label">{{ __('misc.client_photo') }}</label>
									<div class="card">
										<div class="card-body text-center">
											<div class="position-relative d-inline-block" style="cursor: pointer;" onclick="document.getElementById('clientPhotoInput').click();">
												<div id="clientPhotoPreview" style="width: 80px; height: 80px; margin: 0 auto; position: relative;">
													<div class="bg-light rounded-circle mb-2 d-flex align-items-center justify-content-center placeholder-div"
														 style="width: 80px; height: 80px;">
														<i class="bi bi-person text-muted" style="font-size: 2rem;"></i>
													</div>
												</div>
												<div class="position-absolute top-0 end-0" style="z-index: 10;">
													<span class="badge bg-primary rounded-pill">
														<i class="bi bi-camera"></i>
													</span>
												</div>
											</div>
											<br>
											<input type="file" class="d-none @error('client_photo') is-invalid @enderror" name="client_photo" accept="image/*" id="clientPhotoInput">
											<button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('clientPhotoInput').click();">
												<i class="bi bi-upload"></i> {{ __('misc.choose_image') }}
											</button>
											<small class="text-muted d-block">JPG, PNG, GIF (Max: 1MB)</small>
											@error('client_photo')
												<div class="invalid-feedback d-block">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
							</div>

							<!-- Testimonial Content Section -->
							<h6 class="mb-3 text-primary mt-4">{{ __('misc.testimonial_content') }}</h6>
							<hr class="mb-3">

							<div class="row">
								<div class="col-md-12 mb-3">
									<div class="form-floating">
										<textarea name="testimonial_text" id="testimonial_text" class="form-control @error('testimonial_text') is-invalid @enderror" placeholder="{{ __('misc.testimonial_text') }}" style="height: 120px;" required>{{ old('testimonial_text') }}</textarea>
										<label for="testimonial_text">{{ __('misc.testimonial_text') }} *</label>
										@error('testimonial_text')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror">
											<option value="">{{ __('misc.select_rating') }}</option>
											<option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star</option>
											<option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
											<option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
											<option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
											<option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
										</select>
										<label for="rating">{{ __('misc.rating') }}</label>
										@error('rating')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="date" name="date_received" id="date_received" class="form-control @error('date_received') is-invalid @enderror" value="{{ old('date_received') }}" placeholder="{{ __('misc.date_received') }}">
										<label for="date_received">{{ __('misc.date_received') }}</label>
										@error('date_received')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<!-- Project Information Section -->
							<h6 class="mb-3 text-primary mt-4">{{ __('misc.project_information') }}</h6>
							<hr class="mb-3">

							<div class="row">
								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="text" name="project_type" id="project_type" class="form-control @error('project_type') is-invalid @enderror" value="{{ old('project_type') }}" placeholder="{{ __('misc.project_type') }}">
										<label for="project_type">{{ __('misc.project_type') }}</label>
										@error('project_type')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-check form-switch mt-3">
										<input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
										<label class="form-check-label" for="is_featured">
											{{ __('misc.featured_testimonial') }}
										</label>
									</div>
								</div>

								<div class="col-md-12 mb-3">
									<div class="form-floating">
										<textarea name="project_details" id="project_details" class="form-control @error('project_details') is-invalid @enderror" placeholder="{{ __('misc.project_details') }}" style="height: 100px;">{{ old('project_details') }}</textarea>
										<label for="project_details">{{ __('misc.project_details') }}</label>
										@error('project_details')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-12 mb-3">
									<div class="form-floating">
										<select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
											<option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('misc.active') }}</option>
											<option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('misc.inactive') }}</option>
										</select>
										<label for="status">{{ __('misc.status') }} <span class="text-danger">*</span></label>
										@error('status')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<!-- Submit Section -->
							<hr class="mt-4 mb-3">
							<div class="row">
								<div class="col-md-12 text-end">
									<button type="submit" class="btn btn-custom btn-lg">
										<i class="fas fa-save me-1"></i> {{ __('misc.add_testimonial') }}
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection

@section('javascript')
<script>
// Client Photo Preview
document.getElementById('clientPhotoInput').addEventListener('change', function(e) {
    console.log('Client Photo input changed');
    const file = e.target.files[0];
    if (file) {
        console.log('Client Photo file selected:', file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Client Photo file read successfully');
            const photoPreview = document.getElementById('clientPhotoPreview');
            // Always replace the entire content with the new image
            photoPreview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover; display: block;">';
            console.log('Client Photo preview updated');
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
