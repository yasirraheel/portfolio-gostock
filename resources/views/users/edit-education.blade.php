@extends('layouts.app')

@section('title') {{ __('misc.edit_education') }} - @endsection

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
								<h4 class="mb-0">{{ __('misc.edit_education') }}</h4>
								<small class="text-muted">{{ __('misc.update_educational_information') }}</small>
							</div>
							<div class="col-lg-6 text-end">
								<a href="{{ route('user.education') }}" class="btn btn-outline-primary btn-sm">
									<i class="bi bi-arrow-left me-1"></i>{{ __('misc.back_to_education') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="card shadow-custom border-0 mt-3">
					<div class="card-body p-lg-4">
					<form method="POST" action="{{ route('user.education.update') }}" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="id" value="{{ $education->id }}">							<h6 class="mb-3">{{ __('misc.basic_information') }}</h6>

							<!-- Education Basic Info -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="institution_name" class="form-control @error('institution_name') is-invalid @enderror"
											   value="{{ old('institution_name', $education->institution_name) }}" required id="institution_name" placeholder="Institution Name">
										<label for="institution_name">{{ __('misc.institution_name') }} <span class="text-danger">*</span></label>
										@error('institution_name')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<select name="education_level" class="form-select @error('education_level') is-invalid @enderror" required id="education_level">
											<option value="">{{ __('misc.select_education_level') }}</option>
											<option value="high_school" {{ old('education_level', $education->education_level) == 'high_school' ? 'selected' : '' }}>{{ __('misc.high_school') }}</option>
											<option value="intermediate" {{ old('education_level', $education->education_level) == 'intermediate' ? 'selected' : '' }}>{{ __('misc.intermediate') }}</option>
											<option value="associate" {{ old('education_level', $education->education_level) == 'associate' ? 'selected' : '' }}>{{ __('misc.associate_degree') }}</option>
											<option value="bachelor" {{ old('education_level', $education->education_level) == 'bachelor' ? 'selected' : '' }}>{{ __('misc.bachelor_degree') }}</option>
											<option value="master" {{ old('education_level', $education->education_level) == 'master' ? 'selected' : '' }}>{{ __('misc.master_degree') }}</option>
											<option value="doctorate" {{ old('education_level', $education->education_level) == 'doctorate' ? 'selected' : '' }}>{{ __('misc.doctorate') }}</option>
											<option value="certificate" {{ old('education_level', $education->education_level) == 'certificate' ? 'selected' : '' }}>{{ __('misc.certificate') }}</option>
											<option value="diploma" {{ old('education_level', $education->education_level) == 'diploma' ? 'selected' : '' }}>{{ __('misc.diploma') }}</option>
											<option value="other" {{ old('education_level', $education->education_level) == 'other' ? 'selected' : '' }}>{{ __('misc.other') }}</option>
										</select>
										<label for="education_level">{{ __('misc.education_level') }} <span class="text-danger">*</span></label>
										@error('education_level')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="degree" class="form-control @error('degree') is-invalid @enderror"
											   value="{{ old('degree', $education->degree) }}" required id="degree" placeholder="Degree">
										<label for="degree">{{ __('misc.degree') }} <span class="text-danger">*</span></label>
										@error('degree')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="field_of_study" class="form-control @error('field_of_study') is-invalid @enderror"
											   value="{{ old('field_of_study', $education->field_of_study) }}" required id="field_of_study" placeholder="Field of Study">
										<label for="field_of_study">{{ __('misc.field_of_study') }} <span class="text-danger">*</span></label>
										@error('field_of_study')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.timeline') }}</h6>

							<!-- Timeline -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
											   value="{{ old('start_date', $education->start_date ? $education->start_date->format('Y-m-d') : '') }}" required id="start_date">
										<label for="start_date">{{ __('misc.start_date') }} <span class="text-danger">*</span></label>
										@error('start_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
											   value="{{ old('end_date', $education->end_date ? $education->end_date->format('Y-m-d') : '') }}" id="end_date">
										<label for="end_date" id="end_date_label">{{ __('misc.end_date') }} <span class="text-danger">*</span></label>
										<div class="form-check mt-2">
											<input class="form-check-input" type="checkbox" name="is_current"
												   id="is_current" value="1" {{ old('is_current', $education->is_current) ? 'checked' : '' }}>
											<label class="form-check-label" for="is_current">
												{{ __('misc.currently_studying') }}
											</label>
										</div>
										@error('end_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.additional_details') }}</h6>

							<!-- Location & Grade -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
											   value="{{ old('location', $education->location) }}" id="location" placeholder="Location">
										<label for="location">{{ __('misc.location') }}</label>
										@error('location')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="grade" class="form-control @error('grade') is-invalid @enderror"
											   value="{{ old('grade', $education->grade) }}" id="grade" placeholder="Grade/GPA">
										<label for="grade">{{ __('misc.grade_gpa') }}</label>
										@error('grade')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<div class="form-floating mb-3">
								<input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
									   value="{{ old('website', $education->website) }}" id="website" placeholder="https://website.com">
								<label for="website">{{ __('misc.website') }}</label>
								@error('website')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<div class="form-floating mb-3">
								<textarea name="description" class="form-control @error('description') is-invalid @enderror"
										  style="height: 100px;" id="description" placeholder="Description">{{ old('description', $education->description) }}</textarea>
								<label for="description">{{ __('misc.description') }}</label>
								@error('description')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<div class="form-floating mb-3">
								<textarea name="activities" class="form-control @error('activities') is-invalid @enderror"
										  style="height: 80px;" id="activities" placeholder="Activities">{{ old('activities', $education->activities) }}</textarea>
								<label for="activities">{{ __('misc.activities') }}</label>
								<div class="form-text text-muted">{{ __('misc.separate_with_commas') }}</div>
								@error('activities')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.media_files') }}</h6>

							<!-- Current Logo Display -->
							@if($education->logo)
								<div class="mb-3">
									<h6 class="text-success mb-2">{{ __('misc.current_logo') }}:</h6>
									<div class="row">
										<div class="col-md-2">
											<div class="position-relative">
												<img src="{{ url('public/portfolio_assets', $education->logo) }}"
													 class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;"
													 alt="{{ $education->institution_name }}">
												<small class="text-muted d-block text-center mt-1">{{ __('misc.current_logo') }}</small>
											</div>
										</div>
									</div>
								</div>
							@endif

							<!-- Institution Logo -->
							<div class="mb-4">
								<label class="form-label">{{ __('misc.institution_logo') }}</label>
								<div class="input-group mb-1">
									<input name="logo" type="file" class="form-control custom-file rounded-pill @error('logo') is-invalid @enderror"
										   accept="image/*" id="institutionLogo">
								</div>
								<small class="d-block text-muted">
									<i class="bi bi-info-circle me-1"></i>{{ __('misc.max_file_size_1mb_leave_empty') }}
								</small>
								@error('logo')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.status_settings') }}</h6>

							<!-- Status -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<select name="status" class="form-select @error('status') is-invalid @enderror" required id="status">
											<option value="active" {{ old('status', $education->status) == 'active' ? 'selected' : '' }}>{{ __('misc.active') }}</option>
											<option value="inactive" {{ old('status', $education->status) == 'inactive' ? 'selected' : '' }}>{{ __('misc.inactive') }}</option>
										</select>
										<label for="status">{{ __('misc.status') }} <span class="text-danger">*</span></label>
										@error('status')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<!-- Form Actions -->
							<div class="d-flex gap-2">
								<button type="submit" class="btn btn-custom">
									<i class="bi bi-check2 me-1"></i>{{ __('misc.update_education') }}
								</button>
								<a href="{{ route('user.education') }}" class="btn btn-outline-primary">
									{{ __('misc.cancel') }}
								</a>
							</div>
						</form>
					</div>
				</div>
			</div><!-- End Col -->
		</div>
	</div>
</section>

@endsection

@section('javascript')
<script>
// Handle current education checkbox and set initial state
document.addEventListener('DOMContentLoaded', function() {
	const isCurrentCheckbox = document.getElementById('is_current');
	const endDateField = document.getElementById('end_date');
	const endDateLabel = document.getElementById('end_date_label');

	// Set initial state
	if (isCurrentCheckbox.checked) {
		endDateField.disabled = true;
		endDateLabel.innerHTML = '{{ __("misc.end_date") }} ({{ __("misc.leave_blank_if_current") }})';
	}

	// Handle changes
	isCurrentCheckbox.addEventListener('change', function() {
		if (this.checked) {
			endDateField.disabled = true;
			endDateField.value = '';
			endDateLabel.innerHTML = '{{ __("misc.end_date") }} ({{ __("misc.leave_blank_if_current") }})';
		} else {
			endDateField.disabled = false;
			endDateLabel.innerHTML = '{{ __("misc.end_date") }} <span class="text-danger">*</span>';
		}
	});
});

// Image preview
document.getElementById('institutionLogo').addEventListener('change', function(e) {
	const file = e.target.files[0];
	if (file) {
		const reader = new FileReader();
		reader.onload = function(e) {
			// Create preview if it doesn't exist
			let preview = document.getElementById('logoPreview');
			if (!preview) {
				preview = document.createElement('div');
				preview.id = 'logoPreview';
				preview.className = 'mt-2';
				document.getElementById('institutionLogo').parentNode.parentNode.appendChild(preview);
			}
			preview.innerHTML = `
				<h6 class="text-info mb-2">{{ __('misc.new_logo_preview') }}:</h6>
				<img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" alt="Logo Preview">
				<small class="text-muted d-block">${file.name}</small>
			`;
		};
		reader.readAsDataURL(file);
	}
});
</script>
@endsection
