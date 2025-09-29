@extends('layouts.app')

@section('title') {{ __('misc.add_experience') }} - @endsection

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
								<h4 class="mb-0">{{ __('misc.add_experience') }}</h4>
								<small class="text-muted">{{ __('misc.add_your_work_experience') }}</small>
							</div>
							<div class="col-lg-6 text-end">
								<a href="{{ route('user.experience') }}" class="btn btn-outline-primary btn-sm">
									<i class="bi bi-arrow-left me-1"></i>{{ __('misc.back_to_experience') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="card shadow-custom border-0 mt-3">
					<div class="card-body p-lg-4">
						<form method="POST" action="{{ route('user.experience.store') }}" enctype="multipart/form-data">
							@csrf

							<!-- Company Information Section -->
							<h6 class="mb-3 text-primary">{{ __('misc.company_information') }}</h6>
							<hr class="mb-3">

							<div class="row">
								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" placeholder="Google, Microsoft" required id="company_name" value="{{ old('company_name') }}">
										<label for="company_name">{{ __('misc.company_name') }} <span class="text-danger">*</span></label>
										@error('company_name')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="text" name="job_title" class="form-control @error('job_title') is-invalid @enderror" placeholder="Senior Software Engineer" required id="job_title" value="{{ old('job_title') }}">
										<label for="job_title">{{ __('misc.job_title') }} <span class="text-danger">*</span></label>
										@error('job_title')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror" required id="employment_type">
											<option value="full_time" {{ old('employment_type', 'full_time') == 'full_time' ? 'selected' : '' }}>{{ __('misc.full_time') }}</option>
											<option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>{{ __('misc.part_time') }}</option>
											<option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>{{ __('misc.contract') }}</option>
											<option value="freelance" {{ old('employment_type') == 'freelance' ? 'selected' : '' }}>{{ __('misc.freelance') }}</option>
											<option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>{{ __('misc.internship') }}</option>
											<option value="temporary" {{ old('employment_type') == 'temporary' ? 'selected' : '' }}>{{ __('misc.temporary') }}</option>
										</select>
										<label for="employment_type">{{ __('misc.employment_type') }} <span class="text-danger">*</span></label>
										@error('employment_type')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="text" name="location" class="form-control @error('location') is-invalid @enderror" placeholder="New York, Remote" id="location" value="{{ old('location') }}">
										<label for="location">{{ __('misc.location') }}</label>
										@error('location')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<input type="url" name="company_website" class="form-control @error('company_website') is-invalid @enderror" placeholder="https://company.com" id="company_website" value="{{ old('company_website') }}">
										<label for="company_website">{{ __('misc.company_website') }}</label>
										@error('company_website')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<label class="form-label">{{ __('misc.company_logo') }}</label>
									<div class="card">
										<div class="card-body text-center">
											<div class="position-relative d-inline-block" style="cursor: pointer;" onclick="document.getElementById('companyLogoInput').click();">
												<div id="companyLogoPreview" style="width: 80px; height: 80px; margin: 0 auto; position: relative;">
													<div class="bg-light rounded mb-2 d-flex align-items-center justify-content-center placeholder-div"
														 style="width: 80px; height: 80px;">
														<i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
													</div>
												</div>
												<div class="position-absolute top-0 end-0" style="z-index: 10;">
													<span class="badge bg-primary rounded-pill">
														<i class="bi bi-camera"></i>
													</span>
												</div>
											</div>
											<br>
											<input type="file" class="d-none @error('company_logo') is-invalid @enderror" name="company_logo" accept="image/*" id="companyLogoInput">
											<button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('companyLogoInput').click();">
												<i class="bi bi-upload"></i> {{ __('misc.choose_image') }}
											</button>
											<small class="text-muted d-block">JPG, PNG, GIF (Max: 1MB)</small>
											@error('company_logo')
												<div class="invalid-feedback d-block">{{ $message }}</div>
											@enderror
										</div>
									</div>
								</div>
							</div>

							<!-- Employment Timeline Section -->
							<h6 class="mb-3 text-primary mt-4">{{ __('misc.employment_timeline') }}</h6>
							<hr class="mb-3">

							<div class="row">
								<div class="col-md-4 mb-3">
									<div class="form-floating">
										<input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" required id="start_date" value="{{ old('start_date') }}">
										<label for="start_date">{{ __('misc.start_date') }} <span class="text-danger">*</span></label>
										@error('start_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-4 mb-3">
									<div class="form-floating">
										<input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
										<label for="end_date">{{ __('misc.end_date') }}</label>
										@error('end_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-4 mb-3">
									<div class="form-check mt-4">
										<input class="form-check-input" type="checkbox" name="is_current" id="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}>
										<label class="form-check-label" for="is_current">
											{{ __('misc.currently_working_here') }}
										</label>
									</div>
								</div>
							</div>

							<!-- Job Details Section -->
							<h6 class="mb-3 text-primary mt-4">{{ __('misc.job_details') }}</h6>
							<hr class="mb-3">

							<div class="row">
								<div class="col-12 mb-3">
									<div class="form-floating">
										<textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 100px;" placeholder="Describe your role and responsibilities..." id="description">{{ old('description') }}</textarea>
										<label for="description">{{ __('misc.job_description') }}</label>
										@error('description')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-12 mb-3">
									<div class="form-floating">
										<textarea name="achievements" class="form-control @error('achievements') is-invalid @enderror" style="height: 100px;" placeholder="List your key achievements and accomplishments..." id="achievements">{{ old('achievements') }}</textarea>
										<label for="achievements">{{ __('misc.key_achievements') }}</label>
										@error('achievements')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-12 mb-3">
									<div class="form-floating">
										<input type="text" name="technologies_used" class="form-control @error('technologies_used') is-invalid @enderror" placeholder="JavaScript, React, Node.js, Python (comma separated)" id="technologies_used" value="{{ old('technologies_used') }}">
										<label for="technologies_used">{{ __('misc.technologies_skills_used') }}</label>
										<small class="form-text text-muted">{{ __('misc.comma_separated_values') }}</small>
										@error('technologies_used')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>

								<div class="col-md-6 mb-3">
									<div class="form-floating">
										<select name="status" class="form-select @error('status') is-invalid @enderror" required id="status">
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

							<hr class="mt-4 mb-4">

							<div class="row">
								<div class="col-md-12">
									<button type="submit" class="btn btn-custom me-2">
										<i class="bi bi-check2 me-1"></i>{{ __('misc.add_experience') }}
									</button>
									<a href="{{ route('user.experience') }}" class="btn btn-outline-secondary">
										<i class="bi bi-arrow-left me-1"></i>{{ __('misc.cancel') }}
									</a>
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
// Current job checkbox functionality
$(document).ready(function() {
	$('#is_current').change(function() {
		if ($(this).is(':checked')) {
			$('#end_date').val('').prop('disabled', true);
		} else {
			$('#end_date').prop('disabled', false);
		}
	});

	// Initialize on page load
	if ($('#is_current').is(':checked')) {
		$('#end_date').prop('disabled', true);
	}
});

// Company Logo Preview
document.getElementById('companyLogoInput').addEventListener('change', function(e) {
    console.log('Company Logo input changed');
    const file = e.target.files[0];
    if (file) {
        console.log('Company Logo file selected:', file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Company Logo file read successfully');
            const logoPreview = document.getElementById('companyLogoPreview');
            // Always replace the entire content with the new image
            logoPreview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded mb-2" style="width: 80px; height: 80px; object-fit: cover; display: block;">';
            console.log('Company Logo preview updated');
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
