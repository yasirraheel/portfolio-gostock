@extends('layouts.app')

@section('title') {{ __('misc.professional_projects') }} - @endsection

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
							<div class="col-lg-6 text-end">
								<a href="{{ route('user.projects.create') }}" class="btn btn-custom btn-sm">
									<i class="fas fa-plus me-1"></i> {{ __('misc.add_new_project') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				@if($projects->count() > 0)
					@foreach($projects as $project)
						<div class="card shadow-custom border-0 mt-3">
							<div class="card-body p-lg-4">
								<div class="row align-items-start">
									<div class="col-lg-8">
										<div class="d-flex align-items-center mb-2">
											<h5 class="mb-0 me-3">{{ $project->project_name }}</h5>
											<span class="badge badge-{{ $project->status_color }} me-2">{{ $project->status_display }}</span>
											@if($project->featured)
												<span class="badge bg-warning"><i class="fas fa-star me-1"></i>{{ __('misc.featured') }}</span>
											@endif
										</div>
										<p class="text-muted mb-2">
											<strong>{{ __('misc.project_type') }}:</strong> {{ ucfirst($project->project_type) }} | 
											<strong>{{ __('misc.duration') }}:</strong> {{ $project->duration_display }}
											@if($project->client_name)
												| <strong>{{ __('misc.client') }}:</strong> {{ $project->client_name }}
											@endif
										</p>
										@if($project->description)
											<p class="mb-2">{{ Str::limit($project->description, 200) }}</p>
										@endif
									</div>
									<div class="col-lg-4 text-end">
										<div class="dropdown">
											<button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
												<i class="fas fa-cog"></i>
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
								</div>

								<!-- Technologies -->
								@if($project->technologies_list && count($project->technologies_list) > 0)
									<div class="mb-3">
										@foreach($project->technologies_list as $technology)
											<span class="badge bg-light text-dark me-1 mb-1">{{ $technology }}</span>
										@endforeach
									</div>
								@endif

								<!-- Project Images -->
								@if($project->hasImages())
									<div class="mb-3">
										<div class="d-flex flex-wrap gap-2">
											@foreach($project->project_images_list as $image)
												<img src="{{ url('public/portfolio_assets', $image) }}" 
													 class="rounded" 
													 style="width: 80px; height: 60px; object-fit: cover;" 
													 alt="Project image">
											@endforeach
										</div>
									</div>
								@endif

								<!-- Project Links -->
								<div class="d-flex flex-wrap gap-2">
									@if($project->project_url)
										<a href="{{ $project->project_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
											<i class="fas fa-external-link-alt me-1"></i>{{ __('misc.view_project') }}
										</a>
									@endif
									@if($project->github_url)
										<a href="{{ $project->github_url }}" target="_blank" class="btn btn-outline-dark btn-sm">
											<i class="fab fa-github me-1"></i>{{ __('misc.github') }}
										</a>
									@endif
									@if($project->demo_url)
										<a href="{{ $project->demo_url }}" target="_blank" class="btn btn-outline-success btn-sm">
											<i class="fas fa-play me-1"></i>{{ __('misc.demo') }}
										</a>
									@endif
								</div>
							</div>
						</div>
					@endforeach

					<!-- Pagination -->
					@if($projects->hasPages())
						<div class="d-flex justify-content-center mt-4">
							{{ $projects->links() }}
						</div>
					@endif
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
function deleteProject(projectId) {
	const form = document.getElementById('deleteProjectForm');
	form.action = '{{ route("user.projects.destroy", "") }}/' + projectId;
	new bootstrap.Modal(document.getElementById('deleteProjectModal')).show();
}
</script>

@endsection