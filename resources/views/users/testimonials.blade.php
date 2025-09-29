@extends('layouts.app')

@section('title') {{ __('misc.testimonials') }} - @endsection

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
								<h4 class="mb-0">{{ __('misc.testimonials') }}</h4>
								<small class="text-muted">{{ __('misc.manage_testimonials') }}</small>
							</div>
							<div class="col-lg-6 text-lg-end">
								<a href="{{ route('user.testimonial.create') }}" class="btn btn-custom btn-sm">
									<i class="fas fa-plus me-1"></i> {{ __('misc.add_testimonial') }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Testimonials List -->
				@if($testimonials->count() > 0)
					<div class="row mt-3">
						@foreach($testimonials as $testimonial)
							<div class="col-12 mb-4">
								<div class="card shadow-custom border-0">
									<div class="card-body p-lg-4">
										<div class="row">
											<div class="col-md-2 text-center mb-3 mb-md-0">
												@if($testimonial->client_photo)
													<img src="{{ url('public/portfolio_assets', $testimonial->client_photo) }}"
														 class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;"
														 alt="{{ $testimonial->client_name }}">
												@else
													<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
														 style="width: 60px; height: 60px; font-size: 1.5rem;">
														{{ substr($testimonial->client_name, 0, 1) }}
													</div>
												@endif
											</div>
											<div class="col-md-8">
												<div class="d-flex justify-content-between align-items-start mb-2">
													<div>
														<h5 class="mb-1">{{ $testimonial->client_name }}</h5>
														<h6 class="text-primary mb-1">
															{{ $testimonial->client_position }}
															@if($testimonial->company_name)
																- {{ $testimonial->company_name }}
															@endif
															@if($testimonial->client_website)
																<a href="{{ $testimonial->client_website }}" target="_blank" class="ms-1">
																	<i class="fas fa-external-link-alt small"></i>
																</a>
															@endif
														</h6>
														<div class="d-flex flex-wrap gap-2 mb-2">
															@if($testimonial->rating)
																<div class="d-flex align-items-center">
																	@for($i = 1; $i <= 5; $i++)
																		<i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
																	@endfor
																	<span class="ms-1 small text-muted">({{ $testimonial->rating }}/5)</span>
																</div>
															@endif
															@if($testimonial->project_type)
																<span class="badge bg-secondary">{{ $testimonial->project_type }}</span>
															@endif
															@if($testimonial->date_received)
																<span class="badge bg-info">{{ date('M Y', strtotime($testimonial->date_received)) }}</span>
															@endif
															@if($testimonial->is_featured)
																<span class="badge bg-warning text-dark">{{ __('misc.featured') }}</span>
															@endif
															<span class="badge {{ $testimonial->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
																{{ __('misc.' . $testimonial->status) }}
															</span>
														</div>
													</div>
												</div>

												@if($testimonial->testimonial_text)
													<div class="mb-3">
														<blockquote class="blockquote">
															<p class="mb-0">"{{ $testimonial->testimonial_text }}"</p>
														</blockquote>
													</div>
												@endif

												@if($testimonial->project_details)
													<div class="mb-3">
														<h6>{{ __('misc.project_details') }}:</h6>
														<p class="text-muted mb-0">{{ $testimonial->project_details }}</p>
													</div>
												@endif
											</div>
											<div class="col-md-2 text-md-end">
												<div class="dropdown">
													<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
														<i class="bi bi-three-dots-vertical"></i>
													</button>
													<ul class="dropdown-menu">
														<li><a class="dropdown-item" href="{{ route('user.testimonial.edit', $testimonial->id) }}">
															<i class="bi bi-pencil me-1"></i> {{ __('misc.edit') }}
														</a></li>
														<li><a class="dropdown-item text-danger" href="#" onclick="confirmTestimonialDelete({{ $testimonial->id }})">
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
					@if($testimonials->hasPages())
						<div class="d-flex justify-content-center">
							{{ $testimonials->links() }}
						</div>
					@endif
				@else
					<div class="card shadow-custom border-0 mt-3">
						<div class="card-body p-lg-4 text-center">
							<i class="fas fa-quote-right display-4 text-muted mb-3"></i>
							<h5>{{ __('misc.no_testimonials_yet') }}</h5>
							<p class="text-muted">{{ __('misc.no_testimonials_description') }}</p>
							<button type="button" class="btn btn-custom" onclick="window.location.href='{{ route('user.testimonial.create') }}'">
								<i class="fas fa-plus me-1"></i> {{ __('misc.add_first_testimonial') }}
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
// Delete testimonial functionality
function confirmTestimonialDelete(testimonialId) {
	if (confirm('Are you sure you want to delete this testimonial? This action cannot be undone.')) {
		let form = document.createElement('form');
		form.method = 'POST';
		form.action = '{{ route("user.testimonial.destroy", "") }}/' + testimonialId;

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
