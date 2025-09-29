@extends('layouts.app')

@section('title') {{__('misc.custom_sections')}} - @endsection

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
								<h4 class="mb-0">{{__('misc.custom_sections')}}</h4>
								<small class="text-muted">{{__('misc.manage_custom_sections')}}</small>
							</div>

							<div class="col-lg-6 text-lg-end">
								<a class="btn btn-custom btn-sm" href="{{ url('user/custom-section/add') }}">
									<i class="fas fa-plus me-1"></i> {{__('misc.add_custom_section')}}
								</a>
							</div>
						</div>

						<hr>

						@if ($customSections->count() != 0)
							@foreach ($customSections as $section)
								<div class="card shadow-sm mb-3">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-start">

											<div class="d-flex">
												<div class="me-3">
													@if($section->image)
														<img src="{{ url('public/portfolio_assets', $section->image) }}"
															class="rounded"
															style="width: 60px; height: 60px; object-fit: cover;"
															onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
														<div class="d-flex align-items-center justify-content-center bg-light rounded"
															style="width: 60px; height: 60px; display: none;">
															<i class="{{ $section->icon ?? 'fas fa-cube' }} text-muted"></i>
														</div>
													@else
														<div class="d-flex align-items-center justify-content-center bg-light rounded"
															style="width: 60px; height: 60px;">
															<i class="{{ $section->icon ?? 'fas fa-cube' }} text-muted"></i>
														</div>
													@endif
												</div>

												<div>
													<h5 class="mb-1">{{ $section->title }}</h5>
													<p class="text-muted mb-1">
														{{ Str::limit($section->content, 150) }}
													</p>
													<small class="text-muted">
														<i class="fas fa-sort me-1"></i>{{__('misc.order')}}: {{ $section->order_position ?? 0 }}
														<span class="badge {{ $section->status == 'active' ? 'bg-success' : 'bg-secondary' }} ms-2">
															{{ __('misc.' . $section->status) }}
														</span>
													</small>
												</div>
											</div>

											<!-- Three Dots Menu -->
											<div class="dropdown">
												<button class="btn btn-link text-muted" type="button" id="dropdownMenuButton{{ $section->id }}" data-bs-toggle="dropdown" aria-expanded="false">
													<i class="fas fa-ellipsis-v"></i>
												</button>
												<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $section->id }}">
													<li>
														<a class="dropdown-item" href="{{ url('user/custom-section/edit', $section->id) }}">
															<i class="fas fa-edit me-2"></i>{{__('admin.edit')}}
														</a>
													</li>
													<li>
														<a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteCustomSection({{ $section->id }})">
															<i class="fas fa-trash me-2"></i>{{__('admin.delete')}}
														</a>
													</li>
												</ul>
											</div>

										</div>
									</div>
								</div>
							@endforeach

							@if ($customSections->hasPages())
								{{ $customSections->links() }}
							@endif

						@else
							<div class="text-center py-5">
								<i class="fas fa-plus-square display-4 text-muted mb-3"></i>
								<h4 class="text-muted">{{__('misc.no_custom_sections_yet')}}</h4>
								<p class="text-muted mb-4">{{__('misc.no_custom_sections_desc')}}</p>
								<a class="btn btn-custom" href="{{ url('user/custom-section/add') }}">
									<i class="fas fa-plus me-1"></i> {{__('misc.add_first_custom_section')}}
								</a>
							</div>
						@endif

					</div>
				</div>

			</div>
		</div>
	</div>
</section><!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">{{__('misc.confirm_delete')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{__('misc.confirm_delete_custom_section')}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.cancel')}}</button>
        <form id="deleteForm" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">{{__('admin.delete')}}</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function deleteCustomSection(id) {
  const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
  const form = document.getElementById('deleteForm');
  form.action = `/user/custom-section/delete/${id}`;
  modal.show();
}
</script>

@endsection
