@extends('layouts.app')

@section('title') Professional Skills - @endsection

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
								<h4 class="mb-0">Professional Skills</h4>
								<small class="text-muted">Manage your professional skills and expertise levels</small>
							</div>
							<div class="col-lg-6 text-lg-end">
								<button type="button" class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addSkillModal">
									<i class="fas fa-plus me-1"></i> Add Skill
								</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Skills List -->
				@if($skills->count() > 0)
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body p-lg-4">
							<div class="row">
								@foreach($skills as $skill)
									<div class="col-lg-6 mb-4">
										<div class="card border">
											<div class="card-body">
												<div class="d-flex justify-content-between align-items-start mb-2">
													<div class="d-flex align-items-center">
														<i class="{{ $skill->fas_icon }} text-primary me-2" style="font-size: 1.2rem;"></i>
														<h6 class="mb-0">{{ $skill->skill_name }}</h6>
													</div>
													<div class="dropdown">
														<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
															<i class="bi bi-three-dots-vertical"></i>
														</button>
														<ul class="dropdown-menu">
															<li><a class="dropdown-item edit-skill" href="#"
																data-id="{{ $skill->id }}"
																data-name="{{ $skill->skill_name }}"
																data-description="{{ $skill->description }}"
																data-icon="{{ $skill->fas_icon }}"
																data-status="{{ $skill->status }}"
																data-level="{{ $skill->proficiency_level }}">
																<i class="bi bi-pencil me-1"></i> Edit
															</a></li>
															<li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $skill->id }})">
																<i class="bi bi-trash me-1"></i> Delete
															</a></li>
														</ul>
													</div>
												</div>

												@if($skill->description)
													<p class="text-muted small mb-2">{{ Str::limit($skill->description, 80) }}</p>
												@endif

												<div class="mb-2">
													<small class="text-muted">Proficiency: </small>
													<span class="badge bg-primary">{{ $skill->proficiency_display }}</span>
													<span class="badge bg-{{ $skill->status == 'active' ? 'success' : 'secondary' }} ms-1">{{ ucfirst($skill->status) }}</span>
												</div>

												<div class="progress" style="height: 6px;">
													<div class="progress-bar" role="progressbar" style="width: {{ $skill->proficiency_percentage }}%"></div>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>

							<!-- Pagination -->
							@if($skills->hasPages())
								<div class="d-flex justify-content-center">
									{{ $skills->links() }}
								</div>
							@endif
						</div>
					</div>
				@else
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body p-lg-4 text-center">
							<i class="fas fa-user-graduate display-4 text-muted mb-3"></i>
							<h5>No Skills Added Yet</h5>
							<p class="text-muted">Start building your professional profile by adding your skills and expertise.</p>
							<button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addSkillModal">
								<i class="fas fa-plus me-1"></i> Add Your First Skill
							</button>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</section>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-labelledby="addSkillModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="{{ route('user.skills.store') }}">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="addSkillModalLabel">Add New Skill</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" name="skill_name" class="form-control @error('skill_name') is-invalid @enderror" placeholder="e.g., JavaScript, Web Design, Project Management" required id="skill_name">
								<label for="skill_name">{{ __('misc.skill_name') }} <span class="text-danger">*</span></label>
								@error('skill_name')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-12 mb-3">
							<div class="form-floating">
								<textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 80px;" placeholder="Brief description of your experience with this skill" id="description">{{ old('description') }}</textarea>
								<label for="description">{{ __('misc.skill_description') }}</label>
								@error('description')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" name="fas_icon" class="form-control @error('fas_icon') is-invalid @enderror" placeholder="fas fa-code" value="fas fa-star" required id="fas_icon">
								<label for="fas_icon">{{ __('misc.skill_icon') }} <span class="text-danger">*</span></label>
								<small class="form-text text-muted">{{ __('misc.use_fontawesome_icons') }}</small>
								@error('fas_icon')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-6 mb-3">
							<div class="form-floating">
								<select name="proficiency_level" class="form-select @error('proficiency_level') is-invalid @enderror" required id="proficiency_level">
									<option value="beginner" {{ old('proficiency_level') == 'beginner' ? 'selected' : '' }}>{{ __('misc.beginner') }}</option>
									<option value="intermediate" {{ old('proficiency_level', 'intermediate') == 'intermediate' ? 'selected' : '' }}>{{ __('misc.intermediate') }}</option>
									<option value="advanced" {{ old('proficiency_level') == 'advanced' ? 'selected' : '' }}>{{ __('misc.advanced') }}</option>
									<option value="expert" {{ old('proficiency_level') == 'expert' ? 'selected' : '' }}>{{ __('misc.expert') }}</option>
								</select>
								<label for="proficiency_level">{{ __('misc.proficiency_level') }} <span class="text-danger">*</span></label>
								@error('proficiency_level')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-6 mb-3">
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">{{ __('misc.cancel') }}</button>
					<button type="submit" class="btn btn-custom">{{ __('misc.add_skill') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Skill Modal -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-labelledby="editSkillModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="{{ route('user.skills.update') }}">
				@csrf
				<input type="hidden" name="id" id="edit_skill_id">
				<div class="modal-header">
					<h5 class="modal-title" id="editSkillModalLabel">{{ __('misc.edit_skill') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" name="skill_name" id="edit_skill_name" class="form-control @error('skill_name') is-invalid @enderror" required placeholder="Skill Name">
								<label for="edit_skill_name">{{ __('misc.skill_name') }} <span class="text-danger">*</span></label>
								@error('skill_name')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-12 mb-3">
							<div class="form-floating">
								<textarea name="description" id="edit_skill_description" class="form-control @error('description') is-invalid @enderror" style="height: 80px;" placeholder="Brief description of your experience with this skill"></textarea>
								<label for="edit_skill_description">{{ __('misc.skill_description') }}</label>
								@error('description')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-12 mb-3">
							<div class="form-floating">
								<input type="text" name="fas_icon" id="edit_skill_icon" class="form-control @error('fas_icon') is-invalid @enderror" required placeholder="fas fa-code">
								<label for="edit_skill_icon">{{ __('misc.skill_icon') }} <span class="text-danger">*</span></label>
								<small class="form-text text-muted">{{ __('misc.use_fontawesome_icons') }}</small>
								@error('fas_icon')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-6 mb-3">
							<div class="form-floating">
								<select name="proficiency_level" id="edit_skill_level" class="form-select @error('proficiency_level') is-invalid @enderror" required>
									<option value="beginner">{{ __('misc.beginner') }}</option>
									<option value="intermediate">{{ __('misc.intermediate') }}</option>
									<option value="advanced">{{ __('misc.advanced') }}</option>
									<option value="expert">{{ __('misc.expert') }}</option>
								</select>
								<label for="edit_skill_level">{{ __('misc.proficiency_level') }} <span class="text-danger">*</span></label>
								@error('proficiency_level')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>

						<div class="col-sm-6 mb-3">
							<div class="form-floating">
								<select name="status" id="edit_skill_status" class="form-select @error('status') is-invalid @enderror" required>
									<option value="active">{{ __('misc.active') }}</option>
									<option value="inactive">{{ __('misc.inactive') }}</option>
								</select>
								<label for="edit_skill_status">{{ __('misc.status') }} <span class="text-danger">*</span></label>
								@error('status')
									<div class="invalid-feedback">{{ $message }}</div>
								@enderror
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">{{ __('misc.cancel') }}</button>
					<button type="submit" class="btn btn-custom">{{ __('misc.update_skill') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('javascript')
<script>
// Edit skill functionality
$(document).on('click', '.edit-skill', function(e) {
	e.preventDefault();

	$('#edit_skill_id').val($(this).data('id'));
	$('#edit_skill_name').val($(this).data('name'));
	$('#edit_skill_description').val($(this).data('description'));
	$('#edit_skill_icon').val($(this).data('icon'));
	$('#edit_skill_level').val($(this).data('level'));
	$('#edit_skill_status').val($(this).data('status'));

	$('#editSkillModal').modal('show');
});

// Delete skill functionality
function confirmDelete(skillId) {
	if (confirm('Are you sure you want to delete this skill? This action cannot be undone.')) {
		// Create a form and submit it
		let form = document.createElement('form');
		form.method = 'POST';
		form.action = '{{ route("user.skills.destroy", "") }}/' + skillId;

		// Add CSRF token
		let csrfInput = document.createElement('input');
		csrfInput.type = 'hidden';
		csrfInput.name = '_token';
		csrfInput.value = '{{ csrf_token() }}';
		form.appendChild(csrfInput);

		// Add DELETE method
		let methodInput = document.createElement('input');
		methodInput.type = 'hidden';
		methodInput.name = '_method';
		methodInput.value = 'DELETE';
		form.appendChild(methodInput);

		document.body.appendChild(form);
		form.submit();
	}
}
</script>
@endsection
