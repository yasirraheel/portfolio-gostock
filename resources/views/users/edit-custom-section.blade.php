@extends('layouts.app')

@section('title') {{__('misc.edit_custom_section')}} - @endsection

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
						<div class="row align-items-center mb-4">
							<div class="col-lg-6">
								<h4 class="mb-0">{{__('misc.edit_custom_section')}}</h4>
								<small class="text-muted">{{__('misc.manage_custom_sections')}}</small>
							</div>
							<div class="col-lg-6 text-lg-end">
								<a href="{{ url('user/custom-sections') }}" class="btn btn-outline-primary btn-sm">
									<i class="fas fa-arrow-left me-1"></i> {{__('misc.back_to_custom_sections')}}
								</a>
							</div>
						</div>

						<hr class="mb-4">

						<form action="{{ url('user/custom-section/update', $customSection->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                  <!-- Section Information -->
                  <div class="col-lg-12">
                    <div class="card shadow-sm mb-4">
                      <div class="card-header">
                        <h5 class="card-title mb-0">
                          <i class="fas fa-info-circle me-2"></i>{{__('misc.section_information')}}
                        </h5>
                      </div>
                      <div class="card-body">

                        <div class="row">
                          <div class="col-lg-6">
                            <div class="form-floating mb-3">
                              <input type="text" name="title" class="form-control" id="title" placeholder="{{__('misc.section_title')}}" required value="{{ old('title', $customSection->title) }}">
                              <label for="title">{{__('misc.section_title')}} *</label>
                            </div>
                          </div>

                          <div class="col-lg-6">
                            <div class="form-floating mb-3">
                              <input type="number" name="order_position" class="form-control" id="order_position" placeholder="{{__('misc.order_position')}}" value="{{ old('order_position', $customSection->order_position) }}" min="0">
                              <label for="order_position">{{__('misc.order_position')}}</label>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-floating mb-3">
                              <select name="icon" class="form-control" id="icon">
                                <option value="">{{__('misc.select_icon')}}</option>
                                <optgroup label="{{__('misc.general_icons')}}">
                                  <option value="fas fa-cube" {{ old('icon', $customSection->icon) == 'fas fa-cube' ? 'selected' : '' }}>📦 Cube</option>
                                  <option value="fas fa-star" {{ old('icon', $customSection->icon) == 'fas fa-star' ? 'selected' : '' }}>⭐ Star</option>
                                  <option value="fas fa-heart" {{ old('icon', $customSection->icon) == 'fas fa-heart' ? 'selected' : '' }}>❤️ Heart</option>
                                  <option value="fas fa-lightbulb" {{ old('icon', $customSection->icon) == 'fas fa-lightbulb' ? 'selected' : '' }}>💡 Lightbulb</option>
                                  <option value="fas fa-trophy" {{ old('icon', $customSection->icon) == 'fas fa-trophy' ? 'selected' : '' }}>🏆 Trophy</option>
                                  <option value="fas fa-medal" {{ old('icon', $customSection->icon) == 'fas fa-medal' ? 'selected' : '' }}>🏅 Medal</option>
                                </optgroup>
                                <optgroup label="{{__('misc.business_icons')}}">
                                  <option value="fas fa-briefcase" {{ old('icon', $customSection->icon) == 'fas fa-briefcase' ? 'selected' : '' }}>💼 Briefcase</option>
                                  <option value="fas fa-handshake" {{ old('icon', $customSection->icon) == 'fas fa-handshake' ? 'selected' : '' }}>🤝 Handshake</option>
                                  <option value="fas fa-chart-line" {{ old('icon', $customSection->icon) == 'fas fa-chart-line' ? 'selected' : '' }}>📈 Chart</option>
                                  <option value="fas fa-bullseye" {{ old('icon', $customSection->icon) == 'fas fa-bullseye' ? 'selected' : '' }}>🎯 Target</option>
                                </optgroup>
                                <optgroup label="{{__('misc.tech_icons')}}">
                                  <option value="fas fa-code" {{ old('icon', $customSection->icon) == 'fas fa-code' ? 'selected' : '' }}>💻 Code</option>
                                  <option value="fas fa-laptop" {{ old('icon', $customSection->icon) == 'fas fa-laptop' ? 'selected' : '' }}>💻 Laptop</option>
                                  <option value="fas fa-mobile-alt" {{ old('icon', $customSection->icon) == 'fas fa-mobile-alt' ? 'selected' : '' }}>📱 Mobile</option>
                                  <option value="fas fa-database" {{ old('icon', $customSection->icon) == 'fas fa-database' ? 'selected' : '' }}>🗄️ Database</option>
                                </optgroup>
                                <optgroup label="{{__('misc.creative_icons')}}">
                                  <option value="fas fa-palette" {{ old('icon', $customSection->icon) == 'fas fa-palette' ? 'selected' : '' }}>🎨 Palette</option>
                                  <option value="fas fa-paint-brush" {{ old('icon', $customSection->icon) == 'fas fa-paint-brush' ? 'selected' : '' }}>🖌️ Brush</option>
                                  <option value="fas fa-camera" {{ old('icon', $customSection->icon) == 'fas fa-camera' ? 'selected' : '' }}>📷 Camera</option>
                                  <option value="fas fa-music" {{ old('icon', $customSection->icon) == 'fas fa-music' ? 'selected' : '' }}>🎵 Music</option>
                                </optgroup>
                              </select>
                              <label for="icon">{{__('misc.section_icon')}}</label>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-floating mb-3">
                              <textarea name="content" class="form-control" id="content" placeholder="{{__('misc.section_content')}}" style="height: 150px;" required>{{ old('content', $customSection->content) }}</textarea>
                              <label for="content">{{__('misc.section_content')}} *</label>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <!-- Optional Image/Media -->
                  <div class="col-lg-12">
                    <div class="card shadow-sm mb-4">
                      <div class="card-header">
                        <h5 class="card-title mb-0">
                          <i class="fas fa-image me-2"></i>{{__('misc.optional_media')}}
                        </h5>
                      </div>
                      <div class="card-body">

                        <div class="row">
                          <div class="col-lg-12">
                            <label class="form-label">{{__('misc.section_image')}}</label>
                            <div class="card">
                              <div class="card-body text-center">
                                <div class="position-relative d-inline-block" style="cursor: pointer;" onclick="document.getElementById('sectionImageInput').click();">
                                  <div id="sectionImagePreview" style="width: 200px; height: 120px; margin: 0 auto; position: relative;">
                                    @if ($customSection->image)
                                      <img src="{{ url('public/portfolio_assets', $customSection->image) }}"
                                           class="img-fluid rounded mb-2"
                                           style="width: 200px; height: 120px; object-fit: cover; display: block;"
                                           onerror="this.style.display='none'; this.parentNode.querySelector('.placeholder-div').style.display='flex';">
                                      <div class="bg-light rounded mb-2 align-items-center justify-content-center placeholder-div"
                                           style="width: 200px; height: 120px; display: none; position: absolute; top: 0; left: 0;">
                                        <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                      </div>
                                    @else
                                      <div class="bg-light rounded mb-2 d-flex align-items-center justify-content-center placeholder-div" style="width: 200px; height: 120px;">
                                        <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                      </div>
                                    @endif
                                  </div>
                                  <div class="position-absolute top-0 end-0" style="z-index: 10;">
                                    <span class="badge bg-primary rounded-pill">
                                      <i class="bi bi-camera"></i>
                                    </span>
                                  </div>
                                </div>
                                <br>
                                <input type="file" name="image" class="d-none" id="sectionImageInput" accept="image/*">
                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('sectionImageInput').click();">
                                  <i class="bi bi-upload"></i> Choose Image
                                </button>
                                <small class="text-muted d-block">{{__('misc.image_optional_desc')}}</small>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-floating mb-3">
                              <input type="url" name="link_url" class="form-control" id="link_url" placeholder="{{__('misc.section_link_url')}}" value="{{ old('link_url', $customSection->link_url) }}">
                              <label for="link_url">{{__('misc.section_link_url')}}</label>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-floating mb-3">
                              <input type="text" name="link_text" class="form-control" id="link_text" placeholder="{{__('misc.section_link_text')}}" value="{{ old('link_text', $customSection->link_text) }}">
                              <label for="link_text">{{__('misc.section_link_text')}}</label>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-floating mb-3">
                              <select name="status" class="form-select @error('status') is-invalid @enderror" id="status" required>
                                <option value="active" {{ old('status', $customSection->status ?? 'active') == 'active' ? 'selected' : '' }}>{{ __('misc.active') }}</option>
                                <option value="inactive" {{ old('status', $customSection->status) == 'inactive' ? 'selected' : '' }}>{{ __('misc.inactive') }}</option>
                              </select>
                              <label for="status">{{ __('misc.status') }} <span class="text-danger">*</span></label>
                              @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                </div>

                <div class="text-center mt-4">
                  <button type="submit" class="btn btn-custom btn-lg">
                    <i class="fas fa-save me-1"></i> {{__('misc.update_custom_section')}}
                  </button>
                </div>

              </form>

					</div>
				</div>

			</div>
		</div>
	</div>
</section>

<script>
// Section Image Preview
document.getElementById('sectionImageInput').addEventListener('change', function(e) {
    console.log('Section image input changed');
    const file = e.target.files[0];
    if (file) {
        console.log('Section image file selected:', file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Section image file read successfully');
            const imagePreview = document.getElementById('sectionImagePreview');
            // Always replace the entire content with the new image
            imagePreview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded mb-2" style="width: 200px; height: 120px; object-fit: cover; display: block;">';
            console.log('Section image preview updated');
        }
        reader.readAsDataURL(file);
    }
});
</script>

@endsection
