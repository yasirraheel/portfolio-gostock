@extends('layouts.app')

@section('title') Education - @endsection

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
								<h4 class="mb-0">{{ __('misc.education') }}</h4>
								<small class="text-muted">Manage your educational background and academic achievements</small>
							</div>
							<div class="col-lg-6 text-lg-end">
								<a href="{{ route('user.education.add') }}" class="btn btn-custom btn-sm">
									<i class="fas fa-plus me-1"></i> {{ __('misc.add_education') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Education List -->
				@if($educations->count() > 0)
					<div class="row mt-3">
						@foreach($educations as $education)
							<div class="col-12 mb-4">
								<div class="card shadow-custom border-0">
									<div class="card-body p-lg-4">
										<div class="row">
											<div class="col-md-2 text-center mb-3 mb-md-0">
												@if($education->logo)
													<img src="{{ url('public/portfolio_assets', $education->logo) }}"
														 class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
														 alt="{{ $education->institution_name }}">
												@else
													<div class="bg-info text-white rounded d-flex align-items-center justify-content-center"
														 style="width: 60px; height: 60px; font-size: 1.5rem;">
														{{ substr($education->institution_name, 0, 1) }}
													</div>
												@endif
											</div>
											<div class="col-md-8">
												<div class="d-flex justify-content-between align-items-start mb-2">
													<div>
														<h5 class="mb-1">{{ $education->full_degree }}</h5>
														<h6 class="text-info mb-1">
															{{ $education->institution_name }}
															@if($education->website)
																<a href="{{ $education->website }}" target="_blank" class="ms-1">
																	<i class="fas fa-external-link-alt small"></i>
																</a>
															@endif
														</h6>
														<div class="d-flex flex-wrap gap-2 mb-2">
															<span class="badge bg-secondary">{{ $education->education_level_display }}</span>
															@if($education->location)
																<span class="badge bg-light text-dark">
																	<i class="fas fa-map-marker-alt me-1"></i>{{ $education->location }}
																</span>
															@endif
															<span class="badge bg-info">{{ $education->date_range }}</span>
															@if($education->grade)
																<span class="badge bg-success">{{ $education->grade }}</span>
															@endif
															@if($education->is_current)
																<span class="badge bg-warning text-dark">Currently Studying</span>
															@endif
														</div>
													</div>
												</div>

												@if($education->description)
													<div class="mb-2">
														<p class="text-muted mb-0 small">{{ $education->description }}</p>
													</div>
												@endif

												@if($education->activities)
													<div class="mb-2">
														<strong class="small">Activities:</strong>
														<div class="d-flex flex-wrap gap-1">
															@foreach($education->activities_array as $activity)
																<span class="badge bg-outline-primary small">{{ $activity }}</span>
															@endforeach
														</div>
													</div>
												@endif
											</div>
											<div class="col-md-2 text-md-end">
												<div class="dropdown">
													<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
														<i class="bi bi-three-dots-vertical"></i>
													</button>
													<ul class="dropdown-menu">
														<li><a class="dropdown-item" href="{{ route('user.education.edit', $education->id) }}">
															<i class="bi bi-pencil me-1"></i> Edit
														</a></li>
														<li><a class="dropdown-item text-danger" href="#" onclick="confirmEducationDelete({{ $education->id }})">
															<i class="bi bi-trash me-1"></i> Delete
														</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>

					<!-- Pagination -->
					@if($educations->hasPages())
						<div class="d-flex justify-content-center">
							{{ $educations->links() }}
						</div>
					@endif
				@else
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body p-lg-4 text-center">
							<i class="fas fa-graduation-cap display-4 text-muted mb-3"></i>
							<h5>No Education Added Yet</h5>
							<p class="text-muted">Start building your academic profile by adding your educational background.</p>
							<a href="{{ route('user.education.add') }}" class="btn btn-custom">
								<i class="fas fa-graduation-cap me-1"></i> Add Your First Education
							</a>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</section>



@endsection

@section('javascript')
<script>
// Delete confirmation function
function confirmEducationDelete(id) {
	if (confirm('Are you sure you want to delete this education entry?')) {
		window.location.href = '{{ url("account/education/delete") }}/' + id;
	}
}
</script>
@endsection
