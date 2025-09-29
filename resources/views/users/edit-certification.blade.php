@extends('layouts.app')

@section('title') {{ __('misc.edit_certification') }} - @endsection

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
								<h4 class="mb-0">{{ __('misc.edit_certification') }}</h4>
								<small class="text-muted">{{ __('misc.update_certification_information') }}</small>
							</div>
							<div class="col-lg-6 text-end">
								<a href="{{ route('user.certifications') }}" class="btn btn-outline-primary btn-sm">
									<i class="bi bi-arrow-left me-1"></i>{{ __('misc.back_to_certifications') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="card shadow-custom border-0 mt-3">
					<div class="card-body p-lg-4">
					<form method="POST" action="{{ route('user.certification.update') }}" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="id" value="{{ $certification->id }}">							<h6 class="mb-3">{{ __('misc.basic_information') }}</h6>

							<!-- Certification Basic Info -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
											   value="{{ old('name', $certification->name) }}" required id="name" placeholder="Certification Name">
										<label for="name">{{ __('misc.certification_name') }} <span class="text-danger">*</span></label>
										@error('name')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="issuing_organization" class="form-control @error('issuing_organization') is-invalid @enderror"
											   value="{{ old('issuing_organization', $certification->issuing_organization) }}" required id="issuing_organization" placeholder="Issuing Organization">
										<label for="issuing_organization">{{ __('misc.issuing_organization') }} <span class="text-danger">*</span></label>
										@error('issuing_organization')
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
										<input type="date" name="issue_date" class="form-control @error('issue_date') is-invalid @enderror"
											   value="{{ old('issue_date', $certification->issue_date ? $certification->issue_date->format('Y-m-d') : '') }}" required id="issue_date">
										<label for="issue_date">{{ __('misc.issue_date') }} <span class="text-danger">*</span></label>
										@error('issue_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror"
											   value="{{ old('expiry_date', $certification->expiry_date ? $certification->expiry_date->format('Y-m-d') : '') }}" id="expiry_date">
										<label for="expiry_date" id="expiry_date_label">{{ __('misc.expiry_date') }}</label>
										<div class="form-check mt-2">
											<input class="form-check-input" type="checkbox" name="does_not_expire"
												   id="does_not_expire" value="1" {{ old('does_not_expire', $certification->does_not_expire) ? 'checked' : '' }}>
											<label class="form-check-label" for="does_not_expire">
												{{ __('misc.does_not_expire') }}
											</label>
										</div>
										@error('expiry_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.additional_details') }}</h6>

							<!-- Credential Info -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="credential_id" class="form-control @error('credential_id') is-invalid @enderror"
											   value="{{ old('credential_id', $certification->credential_id) }}" id="credential_id" placeholder="Credential ID">
										<label for="credential_id">{{ __('misc.credential_id') }}</label>
										@error('credential_id')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="url" name="credential_url" class="form-control @error('credential_url') is-invalid @enderror"
											   value="{{ old('credential_url', $certification->credential_url) }}" id="credential_url" placeholder="https://credential.com">
										<label for="credential_url">{{ __('misc.credential_url') }}</label>
										@error('credential_url')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<div class="form-floating mb-3">
								<textarea name="description" class="form-control @error('description') is-invalid @enderror"
										  style="height: 100px;" id="description" placeholder="Description">{{ old('description', $certification->description) }}</textarea>
								<label for="description">{{ __('misc.description') }}</label>
								@error('description')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<div class="form-floating mb-3">
								<textarea name="skills_gained" class="form-control @error('skills_gained') is-invalid @enderror"
										  style="height: 80px;" id="skills_gained" placeholder="Skills gained">{{ old('skills_gained', $certification->skills_gained) }}</textarea>
								<label for="skills_gained">{{ __('misc.skills_gained') }}</label>
								<div class="form-text text-muted">{{ __('misc.separate_with_commas') }}</div>
								@error('skills_gained')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.media_files') }}</h6>

							<!-- Current Files Display -->
							<div class="row">
								@if($certification->certificate_image)
									<div class="col-md-6">
										<div class="mb-3">
											<h6 class="text-success mb-2">{{ __('misc.current_certificate') }}:</h6>
											<div class="position-relative">
												<img src="{{ url('public/portfolio_assets', $certification->certificate_image) }}"
													 class="img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;"
													 alt="{{ $certification->name }}">
												<small class="text-muted d-block text-center mt-1">{{ __('misc.current_certificate') }}</small>
											</div>
										</div>
									</div>
								@endif
								@if($certification->organization_logo)
									<div class="col-md-6">
										<div class="mb-3">
											<h6 class="text-success mb-2">{{ __('misc.current_logo') }}:</h6>
											<div class="position-relative">
												<img src="{{ url('public/portfolio_assets', $certification->organization_logo) }}"
													 class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;"
													 alt="{{ $certification->issuing_organization }}">
												<small class="text-muted d-block text-center mt-1">{{ __('misc.current_logo') }}</small>
											</div>
										</div>
									</div>
								@endif
							</div>

							<!-- File Uploads -->
							<div class="row">
								<div class="col-md-6">
									<div class="mb-4">
										<label class="form-label">{{ __('misc.certificate_image') }}</label>
										<div class="input-group mb-1">
											<input name="certificate_image" type="file" class="form-control custom-file rounded-pill @error('certificate_image') is-invalid @enderror"
												   accept="image/*,application/pdf" id="certificateImage">
										</div>
										<small class="d-block text-muted">
											<i class="bi bi-info-circle me-1"></i>{{ __('misc.max_file_size_2mb_leave_empty') }}
										</small>
										@error('certificate_image')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-4">
										<label class="form-label">{{ __('misc.organization_logo') }}</label>
										<div class="input-group mb-1">
											<input name="organization_logo" type="file" class="form-control custom-file rounded-pill @error('organization_logo') is-invalid @enderror"
												   accept="image/*" id="organizationLogo">
										</div>
										<small class="d-block text-muted">
											<i class="bi bi-info-circle me-1"></i>{{ __('misc.max_file_size_1mb_leave_empty') }}
										</small>
										@error('organization_logo')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.status_settings') }}</h6>

							<!-- Status -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<select name="status" class="form-select @error('status') is-invalid @enderror" required id="status">
											<option value="active" {{ old('status', $certification->status) == 'active' ? 'selected' : '' }}>{{ __('misc.active') }}</option>
											<option value="inactive" {{ old('status', $certification->status) == 'inactive' ? 'selected' : '' }}>{{ __('misc.inactive') }}</option>
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
									<i class="bi bi-check2 me-1"></i>{{ __('misc.update_certification') }}
								</button>
								<a href="{{ route('user.certifications') }}" class="btn btn-outline-primary">
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
// Handle does not expire checkbox and set initial state
document.addEventListener('DOMContentLoaded', function() {
	const doesNotExpireCheckbox = document.getElementById('does_not_expire');
	const expiryDateField = document.getElementById('expiry_date');
	const expiryDateLabel = document.getElementById('expiry_date_label');

	// Set initial state
	if (doesNotExpireCheckbox.checked) {
		expiryDateField.disabled = true;
		expiryDateLabel.innerHTML = '{{ __("misc.expiry_date") }} ({{ __("misc.no_expiry") }})';
	}

	// Handle changes
	doesNotExpireCheckbox.addEventListener('change', function() {
		if (this.checked) {
			expiryDateField.disabled = true;
			expiryDateField.value = '';
			expiryDateLabel.innerHTML = '{{ __("misc.expiry_date") }} ({{ __("misc.no_expiry") }})';
		} else {
			expiryDateField.disabled = false;
			expiryDateLabel.innerHTML = '{{ __("misc.expiry_date") }}';
		}
	});
});

// Certificate image preview
document.getElementById('certificateImage').addEventListener('change', function(e) {
	const file = e.target.files[0];
	if (file) {
		const reader = new FileReader();
		reader.onload = function(e) {
			let preview = document.getElementById('certificatePreview');
			if (!preview) {
				preview = document.createElement('div');
				preview.id = 'certificatePreview';
				preview.className = 'mt-2';
				document.getElementById('certificateImage').parentNode.parentNode.appendChild(preview);
			}
			preview.innerHTML = `
				<h6 class="text-info mb-2">{{ __('misc.new_certificate_preview') }}:</h6>
				<img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;" alt="Certificate Preview">
				<small class="text-muted d-block">${file.name}</small>
			`;
		};
		reader.readAsDataURL(file);
	}
});

// Organization logo preview
document.getElementById('organizationLogo').addEventListener('change', function(e) {
	const file = e.target.files[0];
	if (file) {
		const reader = new FileReader();
		reader.onload = function(e) {
			let preview = document.getElementById('logoPreview');
			if (!preview) {
				preview = document.createElement('div');
				preview.id = 'logoPreview';
				preview.className = 'mt-2';
				document.getElementById('organizationLogo').parentNode.parentNode.appendChild(preview);
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
