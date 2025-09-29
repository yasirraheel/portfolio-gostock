@extends('layouts.app')

@section('title') Certifications - @endsection

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
								<h4 class="mb-0">{{ __('misc.certifications') }}</h4>
								<small class="text-muted">Manage your professional certifications and credentials</small>
							</div>
							<div class="col-lg-6 text-lg-end">
								<a href="{{ route('user.certification.add') }}" class="btn btn-custom btn-sm">
									<i class="fas fa-plus me-1"></i> {{ __('misc.add_certification') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Certifications List -->
				@if($certifications->count() > 0)
					<div class="row mt-3">
						@foreach($certifications as $certification)
							<div class="col-12 mb-4">
								<div class="card shadow-custom border-0">
									<div class="card-body p-lg-4">
										<div class="row">
											<div class="col-md-2 text-center mb-3 mb-md-0">
												@if($certification->organization_logo)
													<img src="{{ url('public/portfolio_assets', $certification->organization_logo) }}"
														 class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
														 alt="{{ $certification->issuing_organization }}">
												@else
													<div class="bg-warning text-white rounded d-flex align-items-center justify-content-center"
														 style="width: 60px; height: 60px; font-size: 1.5rem;">
														{{ substr($certification->issuing_organization, 0, 1) }}
													</div>
												@endif
											</div>
											<div class="col-md-8">
												<div class="d-flex justify-content-between align-items-start mb-2">
													<div>
														<h5 class="mb-1">{{ $certification->name }}</h5>
														<h6 class="text-warning mb-1">{{ $certification->issuing_organization }}</h6>
														<div class="d-flex flex-wrap gap-2 mb-2">
															<span class="badge bg-info">{{ $certification->validity_period }}</span>
															<span class="badge
																@if($certification->expiry_status == 'Active') bg-success
																@elseif($certification->expiry_status == 'Expiring soon') bg-warning
																@elseif($certification->expiry_status == 'Expired') bg-danger
																@else bg-secondary @endif">
																{{ $certification->expiry_status }}
															</span>
															@if($certification->credential_id)
																<span class="badge bg-light text-dark">ID: {{ $certification->credential_id }}</span>
															@endif
														</div>
													</div>
												</div>

												@if($certification->description)
													<div class="mb-2">
														<p class="text-muted mb-0 small">{{ $certification->description }}</p>
													</div>
												@endif

												@if($certification->skills_gained)
													<div class="mb-2">
														<strong class="small">Skills Gained:</strong>
														<div class="d-flex flex-wrap gap-1">
															@foreach($certification->skills_array as $skill)
																<span class="badge bg-primary small">{{ $skill }}</span>
															@endforeach
														</div>
													</div>
												@endif

												@if($certification->credential_url)
													<div class="mb-2">
														<a href="{{ $certification->credential_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
															<i class="fas fa-external-link-alt me-1"></i> View Certificate
														</a>
													</div>
												@endif
											</div>
											<div class="col-md-2 text-md-end">
												<div class="dropdown">
													<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
														<i class="bi bi-three-dots-vertical"></i>
													</button>
													<ul class="dropdown-menu">
														<li><a class="dropdown-item" href="{{ route('user.certification.edit', $certification->id) }}">
															<i class="bi bi-pencil me-1"></i> Edit
														</a></li>
														<li><a class="dropdown-item text-danger" href="#" onclick="confirmCertificationDelete({{ $certification->id }})">
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
					@if($certifications->hasPages())
						<div class="d-flex justify-content-center">
							{{ $certifications->links() }}
						</div>
					@endif
				@else
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body p-lg-4 text-center">
							<i class="fas fa-certificate display-4 text-muted mb-3"></i>
							<h5>No Certifications Added Yet</h5>
							<p class="text-muted">Start building your professional profile by adding your certifications and credentials.</p>
							<a href="{{ route('user.certification.add') }}" class="btn btn-custom">
								<i class="fas fa-certificate me-1"></i> Add Your First Certification
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
function confirmCertificationDelete(id) {
	if (confirm('Are you sure you want to delete this certification?')) {
		window.location.href = '{{ url("account/certification/delete") }}/' + id;
	}
}
</script>
@endsection
