@extends('layouts.app')

@section('title') Professional Experience - @endsection

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
								<h4 class="mb-0">{{ __('misc.professional_experience') }}</h4>
								<small class="text-muted">{{ __('misc.manage_work_experience') }}</small>
							</div>
							<div class="col-lg-6 text-lg-end">
								<a href="{{ route('user.experience.create') }}" class="btn btn-custom btn-sm">
									<i class="fas fa-plus me-1"></i> {{ __('misc.add_experience') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Experience List -->
				@if($experiences->count() > 0)
					<div class="row mt-3">
						@foreach($experiences as $experience)
							<div class="col-12 mb-4">
								<div class="card shadow-custom border-0">
									<div class="card-body p-lg-4">
										<div class="row">
											<div class="col-md-2 text-center mb-3 mb-md-0">
												@if($experience->company_logo)
													<img src="{{ url('public/portfolio_assets', $experience->company_logo) }}"
														 class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
														 alt="{{ $experience->company_name }}">
												@else
													<div class="bg-primary text-white rounded d-flex align-items-center justify-content-center"
														 style="width: 60px; height: 60px; font-size: 1.5rem;">
														{{ substr($experience->company_name, 0, 1) }}
													</div>
												@endif
											</div>
											<div class="col-md-8">
												<div class="d-flex justify-content-between align-items-start mb-2">
													<div>
														<h5 class="mb-1">{{ $experience->job_title }}</h5>
														<h6 class="text-primary mb-1">
															{{ $experience->company_name }}
															@if($experience->company_website)
																<a href="{{ $experience->company_website }}" target="_blank" class="ms-1">
																	<i class="fas fa-external-link-alt small"></i>
																</a>
															@endif
														</h6>
														<div class="d-flex flex-wrap gap-2 mb-2">
															<span class="badge bg-secondary">{{ $experience->employment_type_display }}</span>
															@if($experience->location)
																<span class="badge bg-light text-dark">
																	<i class="fas fa-map-marker-alt me-1"></i>{{ $experience->location }}
																</span>
															@endif
															<span class="badge bg-info">{{ $experience->date_range }}</span>
															<span class="badge bg-success">{{ $experience->duration }}</span>
															@if($experience->is_current)
																<span class="badge bg-warning text-dark">Current Position</span>
															@endif
														</div>
													</div>
												</div>

												@if($experience->description)
													<div class="mb-3">
														<h6>Description:</h6>
														<p class="text-muted mb-0">{{ $experience->description }}</p>
													</div>
												@endif

												@if($experience->achievements)
													<div class="mb-3">
														<h6>Key Achievements:</h6>
														<div class="text-muted">
															{!! nl2br(e($experience->achievements)) !!}
														</div>
													</div>
												@endif

												@if($experience->technologies_used)
													<div class="mb-3">
														<h6>Technologies Used:</h6>
														<div class="d-flex flex-wrap gap-1">
															@foreach($experience->technologies_array as $tech)
																<span class="badge bg-primary">{{ $tech }}</span>
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
														<li><a class="dropdown-item" href="{{ route('user.experience.edit', $experience->id) }}">
															<i class="bi bi-pencil me-1"></i> {{ __('misc.edit') }}
														</a></li>
														<li><a class="dropdown-item text-danger" href="#" onclick="confirmExperienceDelete({{ $experience->id }})">
															<i class="bi bi-trash me-1"></i> {{ __('misc.delete') }}
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
					@if($experiences->hasPages())
						<div class="d-flex justify-content-center">
							{{ $experiences->links() }}
						</div>
					@endif
				@else
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body p-lg-4 text-center">
							<i class="fas fa-briefcase display-4 text-muted mb-3"></i>
							<h5>{{ __('misc.no_experience_yet') }}</h5>
							<p class="text-muted">{{ __('misc.no_experience_description') }}</p>
							<button type="button" class="btn btn-custom" onclick="window.location.href='{{ route('user.experience.create') }}'">
								<i class="fas fa-plus me-1"></i> {{ __('misc.add_first_experience') }}
							</button>
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
// Delete experience functionality
function confirmExperienceDelete(experienceId) {
	if (confirm('Are you sure you want to delete this experience? This action cannot be undone.')) {
		let form = document.createElement('form');
		form.method = 'POST';
		form.action = '{{ route("user.experience.destroy", "") }}/' + experienceId;

		let csrfInput = document.createElement('input');
		csrfInput.type = 'hidden';
		csrfInput.name = '_token';
		csrfInput.value = '{{ csrf_token() }}';
		form.appendChild(csrfInput);

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
