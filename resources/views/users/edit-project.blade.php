@extends('layouts.app')

@section('title') {{ __('misc.edit_project') }} - @endsection

@section('content')
<section class="sect									@error('project_images')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>sm">
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
								<h4 class="mb-0">{{ __('misc.edit_project') }}</h4>
								<small class="text-muted">{{ __('misc.update_project_information') }}</small>
							</div>
							<div class="col-lg-6 text-end">
								<a href="{{ route('user.projects') }}" class="btn btn-outline-primary btn-sm">
									<i class="bi bi-arrow-left me-1"></i>{{ __('misc.back_to_projects') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="card shadow-custom border-0 mt-3">
					<div class="card-body p-lg-4">
						<form method="POST" action="{{ route('user.projects.update') }}" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="id" value="{{ $project->id }}">

							<h6 class="mb-3">{{ __('misc.basic_information') }}</h6>

							<!-- Project Basic Info -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="project_name" class="form-control @error('project_name') is-invalid @enderror"
											   value="{{ old('project_name', $project->project_name) }}" required id="project_name">
										<label for="project_name">{{ __('misc.project_name') }} <span class="text-danger">*</span></label>
										@error('project_name')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<select name="project_type" class="form-select @error('project_type') is-invalid @enderror" required id="project_type">
											<option value="">{{ __('misc.select_project_type') }}</option>
											<option value="personal" {{ old('project_type', $project->project_type) == 'personal' ? 'selected' : '' }}>{{ __('misc.personal') }}</option>
											<option value="professional" {{ old('project_type', $project->project_type) == 'professional' ? 'selected' : '' }}>{{ __('misc.professional') }}</option>
											<option value="open_source" {{ old('project_type', $project->project_type) == 'open_source' ? 'selected' : '' }}>{{ __('misc.open_source') }}</option>
											<option value="freelance" {{ old('project_type', $project->project_type) == 'freelance' ? 'selected' : '' }}>{{ __('misc.freelance') }}</option>
											<option value="startup" {{ old('project_type', $project->project_type) == 'startup' ? 'selected' : '' }}>{{ __('misc.startup') }}</option>
											<option value="academic" {{ old('project_type', $project->project_type) == 'academic' ? 'selected' : '' }}>{{ __('misc.academic') }}</option>
											<option value="other" {{ old('project_type', $project->project_type) == 'other' ? 'selected' : '' }}>{{ __('misc.other') }}</option>
										</select>
										<label for="project_type">{{ __('misc.project_type') }} <span class="text-danger">*</span></label>
										@error('project_type')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<div class="form-floating mb-3">
								<textarea name="description" class="form-control @error('description') is-invalid @enderror"
										  style="height: 100px;" placeholder="{{ __('misc.describe_your_project') }}" 
										  id="description" maxlength="350" oninput="updateCharCount()">{{ old('description', $project->description) }}</textarea>
								<label for="description">{{ __('misc.project_description') }}</label>
								<div class="form-text">
									<span id="charCount">0</span>/350 {{ __('misc.characters') }}
								</div>
								@error('description')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.project_media') }}</h6>

							<!-- PROJECT IMAGES UPLOAD -->
							<div class="mb-4">
								<label class="form-label">{{ __('misc.project_images') }}</label>

								<!-- Current Images Display -->
								@if($project->project_images_list && count($project->project_images_list) > 0)
									<div class="mb-3">
										<h6 class="text-success mb-2">{{ __('misc.current_images') }}:</h6>
										<div class="row">
											@foreach($project->project_images_list as $index => $image)
												<div class="col-md-3 mb-2">
													<div class="card">
														<div class="card-body text-center p-2">
															<div class="position-relative">
																<img src="{{ url('public/portfolio_assets', $image) }}" alt="Project Image {{ $index + 1 }}"
																	 class="img-fluid rounded mb-2" style="width: 100%; height: 120px; object-fit: cover;">
																<div class="form-check position-absolute" style="top: 5px; right: 5px; background: rgba(255,255,255,0.9); border-radius: 3px; padding: 2px;">
																	<input type="checkbox" name="delete_images[]" value="{{ $image }}" class="form-check-input" id="delete_{{ $index }}">
																	<label class="form-check-label text-danger" for="delete_{{ $index }}" title="{{ __('misc.delete_this_image') }}">
																		<i class="bi bi-trash"></i>
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>
											@endforeach
										</div>
										<small class="text-muted">{{ __('misc.check_images_to_delete') }}</small>
									</div>
								@endif

								<!-- Upload New Images -->
								<div class="mb-3">
									<label class="form-label">{{ __('misc.upload_new_images') }}</label>
									<div class="input-group mb-1">
										<input name="project_images[]" type="file" class="form-control custom-file rounded-pill @error('project_images') is-invalid @enderror"
											   multiple accept="image/*" id="projectImages">
									</div>
									<small class="d-block text-muted">
										<i class="bi bi-info-circle me-1"></i>{{ __('misc.multiple_images_allowed') }}
									</small>
									@error('project_images')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<!-- New Image Preview Area -->
								<div id="imagePreview" class="row" style="display: none;">
									<div class="col-12">
										<h6 class="text-muted mb-2">{{ __('misc.new_images_selected') }}:</h6>
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.status_settings') }}</h6>

							<!-- Project Status & Visibility -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<select name="status" class="form-select @error('status') is-invalid @enderror" required id="status">
											<option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>{{ __('misc.planning') }}</option>
											<option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>{{ __('misc.in_progress') }}</option>
											<option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>{{ __('misc.completed') }}</option>
											<option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>{{ __('misc.on_hold') }}</option>
											<option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>{{ __('misc.cancelled') }}</option>
										</select>
										<label for="status">{{ __('misc.project_status') }} <span class="text-danger">*</span></label>
										@error('status')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<select name="visibility" class="form-select @error('visibility') is-invalid @enderror" required id="visibility">
											<option value="public" {{ old('visibility', $project->visibility) == 'public' ? 'selected' : '' }}>{{ __('misc.public') }}</option>
											<option value="private" {{ old('visibility', $project->visibility) == 'private' ? 'selected' : '' }}>{{ __('misc.private') }}</option>
										</select>
										<label for="visibility">{{ __('misc.visibility') }} <span class="text-danger">*</span></label>
										@error('visibility')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.timeline') }}</h6>

							<!-- Project Timeline -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
											   value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}" id="start_date">
										<label for="start_date">{{ __('misc.start_date') }}</label>
										@error('start_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
											   value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}" id="end_date">
										<label for="end_date">{{ __('misc.end_date') }}</label>
										@error('end_date')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.team_information') }}</h6>

							<!-- Project Team Info -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="text" name="role" class="form-control @error('role') is-invalid @enderror"
											   value="{{ old('role', $project->role) }}" placeholder="{{ __('misc.your_role_in_project') }}" id="role">
										<label for="role">{{ __('misc.role') }}</label>
										@error('role')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-floating mb-3">
										<input type="number" name="team_size" class="form-control @error('team_size') is-invalid @enderror"
											   value="{{ old('team_size', $project->team_size) }}" min="1" placeholder="1" id="team_size">
										<label for="team_size">{{ __('misc.team_size') }}</label>
										@error('team_size')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<div class="form-floating mb-3">
								<input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror"
									   value="{{ old('client_name', $project->client_name) }}" placeholder="{{ __('misc.client_or_company_name') }}" id="client_name">
								<label for="client_name">{{ __('misc.client_name') }}</label>
								@error('client_name')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.technical_details') }}</h6>

							<div class="form-floating mb-3">
								<input type="text" name="technologies" class="form-control @error('technologies') is-invalid @enderror"
									   value="{{ old('technologies', implode(', ', $project->technologies_list)) }}" placeholder="Laravel, Vue.js, MySQL, etc." id="technologies">
								<label for="technologies">{{ __('misc.technologies_skills_used') }}</label>
								<div class="form-text text-muted">{{ __('misc.separate_with_commas') }}</div>
								@error('technologies')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.project_links') }}</h6>

							<!-- Project URLs -->
							<div class="row">
								<div class="col-md-4">
									<div class="form-floating mb-3">
										<input type="url" name="project_url" class="form-control @error('project_url') is-invalid @enderror"
											   value="{{ old('project_url', $project->project_url) }}" placeholder="https://example.com" id="project_url">
										<label for="project_url">{{ __('misc.project_url') }}</label>
										@error('project_url')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-floating mb-3">
										<input type="url" name="github_url" class="form-control @error('github_url') is-invalid @enderror"
											   value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/username/repo" id="github_url">
										<label for="github_url">{{ __('misc.github_url') }}</label>
										@error('github_url')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-floating mb-3">
										<input type="url" name="demo_url" class="form-control @error('demo_url') is-invalid @enderror"
											   value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://demo.example.com" id="demo_url">
										<label for="demo_url">{{ __('misc.demo_url') }}</label>
										@error('demo_url')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							<hr class="my-4">
							<h6 class="mb-3">{{ __('misc.additional_details') }}</h6>

							<!-- Project Details -->
							<div class="form-floating mb-3">
								<textarea name="key_features" class="form-control @error('key_features') is-invalid @enderror"
										  style="height: 80px;" placeholder="{{ __('misc.describe_key_features') }}" id="key_features">{{ old('key_features', $project->key_features) }}</textarea>
								<label for="key_features">{{ __('misc.key_features') }}</label>
								@error('key_features')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<div class="form-floating mb-3">
								<textarea name="challenges_solved" class="form-control @error('challenges_solved') is-invalid @enderror"
										  style="height: 80px;" placeholder="{{ __('misc.describe_challenges') }}" id="challenges_solved">{{ old('challenges_solved', $project->challenges_solved) }}</textarea>
								<label for="challenges_solved">{{ __('misc.challenges_solved') }}</label>
								@error('challenges_solved')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<div class="form-check mb-4">
								<input type="checkbox" name="featured" class="form-check-input @error('featured') is-invalid @enderror"
									   id="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }}>
								<label class="form-check-label" for="featured">
									{{ __('misc.featured_project') }}
								</label>
								<div class="form-text text-muted">{{ __('misc.featured_projects_shown_prominently') }}</div>
								@error('featured')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>

							<!-- Form Actions -->
							<div class="d-flex gap-2">
								<button type="submit" class="btn btn-custom">
									<i class="bi bi-check2 me-1"></i>{{ __('misc.update_project') }}
								</button>
								<a href="{{ route('user.projects') }}" class="btn btn-outline-primary">
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

<script>
// Character count for description
function updateCharCount() {
    const textarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const currentLength = textarea.value.length;
    
    charCount.textContent = currentLength;
    
    if (currentLength > 350) {
        charCount.style.color = 'red';
    } else if (currentLength > 315) {
        charCount.style.color = 'orange';
    } else {
        charCount.style.color = 'inherit';
    }
}

// Initialize character count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCharCount();
});

document.getElementById('projectImages').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('imagePreview');

    // Clear previous previews
    const existingPreviews = preview.querySelectorAll('.col-md-3');
    existingPreviews.forEach(preview => preview.remove());

    if (files.length > 0) {
        preview.style.display = 'block';

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-2';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;" alt="Preview ${index + 1}">
                        <small class="text-muted d-block text-center mt-1">${file.name}</small>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endsection
