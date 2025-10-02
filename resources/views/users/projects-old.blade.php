@extends('layouts.app')

@sect							<div class="col-lg-6 text-end">
								<a href="{{ route('user.projects.create') }}" class="btn btn-custom btn-sm">
									<i class="fas fa-plus me-1"></i> {{ __('misc.add_new_project') }}
								</a>
							</div>title') {{ __('misc.professional_projects') }} - @endsection

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
								<h4 class="mb-0">{{ __('misc.professional_projects') }}</h4>
								<small class="text-muted">{{ __('misc.manage_projects_desc') }}</small>
							</div>
							<div class="col-lg-6 text-lg-end">
								<button type="button" class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
									<i class="fas fa-plus me-1"></i> {{ __('misc.add_new_project') }}
								</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Projects List -->
				@if($projects->count() > 0)
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body p-lg-4">
							<div class="row">
								@foreach($projects as $project)
									<div class="col-lg-6 mb-4">
										<div class="card border h-100">
											<div class="card-body">
												<!-- Project Header -->
												<div class="d-flex justify-content-between align-items-start mb-3">
													<div>
														<h5 class="mb-1">{{ $project->project_name }}</h5>
														<div class="d-flex align-items-center mb-1">
															<span class="badge bg-{{ $project->status_color }} me-2">{{ $project->status_display }}</span>
															<small class="text-muted">{{ $project->project_type_display }}</small>
															@if($project->featured)
																<i class="fas fa-star text-warning ms-2" title="{{ __('misc.featured_project') }}"></i>
															@endif
														</div>
													</div>
													<div class="dropdown">
														<button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
															<i class="fas fa-ellipsis-v"></i>
														</button>
														<ul class="dropdown-menu">
															<li><a class="dropdown-item" href="{{ route('user.projects.edit', $project->id) }}">
																<i class="fas fa-edit me-2"></i>{{ __('misc.edit') }}
															</a></li>
															<li><hr class="dropdown-divider"></li>
															<li><a class="dropdown-item text-danger" href="#" onclick="deleteProject({{ $project->id }})">
																<i class="fas fa-trash me-2"></i>{{ __('admin.delete') }}
															</a></li>
														</ul>
													</div>
												</div>

												<!-- Project Image -->
												@if($project->hasImages())
													<div class="mb-3">
														<img src="{{ url('public/portfolio_assets', $project->getMainImage()) }}" 
															 class="img-fluid rounded" 
															 style="height: 150px; width: 100%; object-fit: cover;"
															 alt="{{ $project->project_name }}">
													</div>
												@endif

												<!-- Project Details -->
												@if($project->description)
													<div class="text-muted small mb-2">{!! Str::limit($project->description, 120) !!}</div>
												@endif

												<div class="mb-2">
													<small class="text-muted">
														<i class="fas fa-calendar me-1"></i>
														{{ $project->formatted_start_date }} - {{ $project->formatted_end_date }}
														@if($project->duration)
															<span class="text-primary">({{ $project->duration }})</span>
														@endif
													</small>
												</div>

												@if($project->role)
													<div class="mb-2">
														<small class="text-muted">
															<i class="fas fa-user-tie me-1"></i>{{ $project->role }}
														</small>
													</div>
												@endif

												@if($project->client_name)
													<div class="mb-2">
														<small class="text-muted">
															<i class="fas fa-building me-1"></i>{{ $project->client_name }}
														</small>
													</div>
												@endif

												@if($project->team_size)
													<div class="mb-2">
														<small class="text-muted">
															<i class="fas fa-users me-1"></i>{{ __('misc.team_size') }}: {{ $project->team_size }}
														</small>
													</div>
												@endif

												<!-- Technologies -->
												@if(count($project->technologies_list) > 0)
													<div class="mb-3">
														<div class="d-flex flex-wrap gap-1">
															@foreach($project->technologies_list as $tech)
																<span class="badge bg-light text-dark">{{ $tech }}</span>
															@endforeach
														</div>
													</div>
												@endif

												<!-- Project Links -->
												<div class="d-flex gap-2">
													@if($project->project_url)
														<a href="{{ $project->project_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
															<i class="fas fa-external-link-alt me-1"></i>{{ __('misc.view_project') }}
														</a>
													@endif
													@if($project->github_url)
														<a href="{{ $project->github_url }}" target="_blank" class="btn btn-sm btn-outline-dark">
															<i class="fab fa-github me-1"></i>{{ __('misc.github') }}
														</a>
													@endif
													@if($project->demo_url)
														<a href="{{ $project->demo_url }}" target="_blank" class="btn btn-sm btn-outline-success">
															<i class="fas fa-play me-1"></i>{{ __('misc.demo') }}
														</a>
													@endif
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>

							<!-- Pagination -->
							@if($projects->hasPages())
								<div class="d-flex justify-content-center mt-4">
									{{ $projects->links() }}
								</div>
							@endif
						</div>
					</div>
				@else
					<!-- Empty State -->
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body text-center p-5">
							<i class="fas fa-project-diagram text-muted mb-3" style="font-size: 3rem;"></i>
							<h5>{{ __('misc.no_projects_yet') }}</h5>
							<p class="text-muted mb-4">{{ __('misc.no_projects_description') }}</p>
							<a href="{{ route('user.projects.create') }}" class="btn btn-custom">
								<i class="fas fa-plus me-1"></i> {{ __('misc.add_first_project') }}
							</a>
						</div>
					</div>
				@endif
			</div><!-- End Col -->
		</div>
	</div>
</section>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form method="POST" action="{{ route('user.projects.store') }}" enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">{{ __('misc.add_new_project') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.project_name') }} <span class="text-danger">*</span></label>
								<input type="text" name="project_name" class="form-control" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.project_type') }} <span class="text-danger">*</span></label>
								<select name="project_type" class="form-control" required>
									<option value="personal">{{ __('misc.personal') }}</option>
									<option value="professional">{{ __('misc.professional') }}</option>
									<option value="open_source">{{ __('misc.open_source') }}</option>
									<option value="freelance">{{ __('misc.freelance') }}</option>
									<option value="startup">{{ __('misc.startup') }}</option>
									<option value="academic">{{ __('misc.academic') }}</option>
									<option value="other">{{ __('misc.other') }}</option>
								</select>
							</div>
						</div>
					</div>
					
					<div class="mb-3">
						<label class="form-label">{{ __('misc.project_description') }}</label>
						<textarea name="description" class="form-control" rows="3"></textarea>
					</div>

					<!-- PROJECT IMAGES UPLOAD - PROMINENT POSITION -->
					<div class="mb-4" style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 2px dashed #007bff;">
						<label class="form-label text-primary fw-bold">
							<i class="fas fa-images me-2"></i>{{ __('misc.project_images') }}
						</label>
						<input type="file" name="project_images[]" class="form-control form-control-lg" multiple accept="image/*" style="border: 2px solid #007bff;">
						<small class="text-muted mt-2 d-block">
							<i class="fas fa-info-circle me-1"></i>{{ __('misc.multiple_images_allowed') }}
						</small>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.project_status') }} <span class="text-danger">*</span></label>
								<select name="status" class="form-control" required>
									<option value="planning">{{ __('misc.planning') }}</option>
									<option value="in_progress" selected>{{ __('misc.in_progress') }}</option>
									<option value="completed">{{ __('misc.completed') }}</option>
									<option value="on_hold">{{ __('misc.on_hold') }}</option>
									<option value="cancelled">{{ __('misc.cancelled') }}</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.visibility') }} <span class="text-danger">*</span></label>
								<select name="visibility" class="form-control" required>
									<option value="public" selected>{{ __('misc.public') }}</option>
									<option value="private">{{ __('misc.private') }}</option>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.start_date') }}</label>
								<input type="date" name="start_date" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.end_date') }}</label>
								<input type="date" name="end_date" class="form-control">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.role') }}</label>
								<input type="text" name="role" class="form-control" placeholder="e.g., Full Stack Developer">
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.team_size') }}</label>
								<input type="number" name="team_size" class="form-control" min="1">
							</div>
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label">{{ __('misc.client_name') }}</label>
						<input type="text" name="client_name" class="form-control">
					</div>

					<div class="mb-3">
						<label class="form-label">{{ __('misc.technologies_skills_used') }}</label>
						<input type="text" name="technologies" class="form-control" placeholder="PHP, Laravel, React, MySQL">
						<small class="text-muted">{{ __('misc.separate_with_commas') }}</small>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.project_url') }}</label>
								<input type="url" name="project_url" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.github_url') }}</label>
								<input type="url" name="github_url" class="form-control">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label class="form-label">{{ __('misc.demo_url') }}</label>
								<input type="url" name="demo_url" class="form-control">
							</div>
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label">{{ __('misc.key_features') }}</label>
						<textarea name="key_features" class="form-control" rows="2"></textarea>
					</div>

					<div class="mb-3">
						<label class="form-label">{{ __('misc.challenges_solved') }}</label>
						<textarea name="challenges_solved" class="form-control" rows="2"></textarea>
					</div>

					<div class="form-check">
						<input type="checkbox" name="featured" class="form-check-input" id="featured">
						<label class="form-check-label" for="featured">
							{{ __('misc.featured_project') }}
						</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('misc.cancel') }}</button>
					<button type="submit" class="btn btn-custom">{{ __('misc.save_project') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form method="POST" action="{{ route('user.projects.update') }}" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" id="edit_project_id">
				<div class="modal-header">
					<h5 class="modal-title">{{ __('misc.edit_project') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body" id="editProjectContent">
					<!-- Content will be loaded dynamically -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('misc.cancel') }}</button>
					<button type="submit" class="btn btn-custom">{{ __('misc.update_project') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form method="POST" id="deleteProjectForm">
				@csrf
				@method('DELETE')
				<div class="modal-header">
					<h5 class="modal-title">{{ __('misc.delete_project') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<p>{{ __('misc.confirm_delete_project') }}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('misc.cancel') }}</button>
					<button type="submit" class="btn btn-danger">{{ __('admin.delete') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
function editProject(projectId) {
	// Find project data
	@foreach($projects as $project)
		if ({{ $project->id }} == projectId) {
			document.getElementById('edit_project_id').value = projectId;
			
			let content = `
				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.project_name') }} <span class="text-danger">*</span></label>
							<input type="text" name="project_name" class="form-control" value="{{ $project->project_name }}" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.project_type') }} <span class="text-danger">*</span></label>
							<select name="project_type" class="form-control" required>
								<option value="personal" {{ $project->project_type == 'personal' ? 'selected' : '' }}>{{ __('misc.personal') }}</option>
								<option value="professional" {{ $project->project_type == 'professional' ? 'selected' : '' }}>{{ __('misc.professional') }}</option>
								<option value="open_source" {{ $project->project_type == 'open_source' ? 'selected' : '' }}>{{ __('misc.open_source') }}</option>
								<option value="freelance" {{ $project->project_type == 'freelance' ? 'selected' : '' }}>{{ __('misc.freelance') }}</option>
								<option value="startup" {{ $project->project_type == 'startup' ? 'selected' : '' }}>{{ __('misc.startup') }}</option>
								<option value="academic" {{ $project->project_type == 'academic' ? 'selected' : '' }}>{{ __('misc.academic') }}</option>
								<option value="other" {{ $project->project_type == 'other' ? 'selected' : '' }}>{{ __('misc.other') }}</option>
							</select>
						</div>
					</div>
				</div>
				
				<div class="mb-3">
					<label class="form-label">{{ __('misc.project_description') }}</label>
					<textarea name="description" class="form-control" rows="3">{{ $project->description }}</textarea>
				</div>

				<!-- PROJECT IMAGES UPLOAD - PROMINENT POSITION -->
				<div class="mb-4" style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 2px dashed #007bff;">
					<label class="form-label text-primary fw-bold">
						<i class="fas fa-images me-2"></i>{{ __('misc.project_images') }}
					</label>
					<input type="file" name="project_images[]" class="form-control form-control-lg" multiple accept="image/*" style="border: 2px solid #007bff;">
					<small class="text-muted mt-2 d-block">
						<i class="fas fa-info-circle me-1"></i>{{ __('misc.multiple_images_allowed') }}
					</small>
					
					@if($project->project_images && count($project->project_images) > 0)
						<div class="current-images mt-3">
							<label class="form-label text-success">{{ __('misc.current_images') }}:</label>
							<div class="row">
								@foreach($project->project_images as $image)
									<div class="col-md-3 mb-2">
										<img src="{{ asset('storage/' . $image) }}" alt="Project Image" class="img-thumbnail" style="max-width: 100px; height: 80px; object-fit: cover;">
									</div>
								@endforeach
							</div>
						</div>
					@endif
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.project_status') }} <span class="text-danger">*</span></label>
							<select name="status" class="form-control" required>
								<option value="planning" {{ $project->status == 'planning' ? 'selected' : '' }}>{{ __('misc.planning') }}</option>
								<option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>{{ __('misc.in_progress') }}</option>
								<option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>{{ __('misc.completed') }}</option>
								<option value="on_hold" {{ $project->status == 'on_hold' ? 'selected' : '' }}>{{ __('misc.on_hold') }}</option>
								<option value="cancelled" {{ $project->status == 'cancelled' ? 'selected' : '' }}>{{ __('misc.cancelled') }}</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.visibility') }} <span class="text-danger">*</span></label>
							<select name="visibility" class="form-control" required>
								<option value="public" {{ $project->visibility == 'public' ? 'selected' : '' }}>{{ __('misc.public') }}</option>
								<option value="private" {{ $project->visibility == 'private' ? 'selected' : '' }}>{{ __('misc.private') }}</option>
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.start_date') }}</label>
							<input type="date" name="start_date" class="form-control" value="{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.end_date') }}</label>
							<input type="date" name="end_date" class="form-control" value="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.role') }}</label>
							<input type="text" name="role" class="form-control" value="{{ $project->role }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.team_size') }}</label>
							<input type="number" name="team_size" class="form-control" value="{{ $project->team_size }}" min="1">
						</div>
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('misc.client_name') }}</label>
					<input type="text" name="client_name" class="form-control" value="{{ $project->client_name }}">
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('misc.technologies_skills_used') }}</label>
					<input type="text" name="technologies" class="form-control" value="{{ implode(', ', $project->technologies_list) }}">
					<small class="text-muted">{{ __('misc.separate_with_commas') }}</small>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.project_url') }}</label>
							<input type="url" name="project_url" class="form-control" value="{{ $project->project_url }}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.github_url') }}</label>
							<input type="url" name="github_url" class="form-control" value="{{ $project->github_url }}">
						</div>
					</div>
					<div class="col-md-4">
						<div class="mb-3">
							<label class="form-label">{{ __('misc.demo_url') }}</label>
							<input type="url" name="demo_url" class="form-control" value="{{ $project->demo_url }}">
						</div>
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('misc.key_features') }}</label>
					<textarea name="key_features" class="form-control" rows="2">{{ $project->key_features }}</textarea>
				</div>

				<div class="mb-3">
					<label class="form-label">{{ __('misc.challenges_solved') }}</label>
					<textarea name="challenges_solved" class="form-control" rows="2">{{ $project->challenges_solved }}</textarea>
				</div>

				<div class="form-check">
					<input type="checkbox" name="featured" class="form-check-input" id="edit_featured" {{ $project->featured ? 'checked' : '' }}>
					<label class="form-check-label" for="edit_featured">
						{{ __('misc.featured_project') }}
					</label>
				</div>
			`;
			
			document.getElementById('editProjectContent').innerHTML = content;
			new bootstrap.Modal(document.getElementById('editProjectModal')).show();
			return;
		}
	@endforeach
}

function deleteProject(projectId) {
	const form = document.getElementById('deleteProjectForm');
	form.action = '{{ route("user.projects.destroy", "") }}/' + projectId;
	new bootstrap.Modal(document.getElementById('deleteProjectModal')).show();
}
</script>

@endsection