@extends('layouts.app')

@section('title') {{ trans('users.profile_settings') }} - @endsection

@section('content')
<section class="section section-sm">

<div class="container-custom container pt-5">
<div class="row">

  <div class="col-md-3">
    @include('users.navbar-settings')
  </div>

			<!-- Col MD -->
		<div class="col-md-9">

			@if (session('notification'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
            	<i class="bi bi-check2 me-1"></i>	{{ session('notification') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
            		</div>
            	@endif

			@include('errors.errors-forms')

      <h5 class="mb-4">{{ trans('users.profile_settings') }}</h5>

		<!-- ***** FORM ***** -->
       <form action="{{ url('user/account') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <h6 class="mb-3">Basic Information</h6>

        <div class="row">
        	<div class="col-md-6">
            <div class="form-floating mb-3">
             <input type="text" required class="form-control" id="inputname" value="{{old('full_name', auth()->user()->name)}}" name="full_name" placeholder="Full Name">
             <label for="inputname">Full Name *</label>
           </div>
           </div><!-- End Col MD-->

            <div class="col-md-6">
              <div class="form-floating mb-3">
               <input type="text" class="form-control" id="inputprofession" value="{{old('profession', auth()->user()->profession ?? '')}}" name="profession" placeholder="Professional Title">
               <label for="inputprofession">Professional Title</label>
             </div>
            </div><!-- End Col MD-->

        </div><!-- End row -->

			<div class="row">

				<div class="col-md-6">
          <div class="form-floating mb-3">
           <input type="email" required class="form-control" id="inputemail" value="{{old('email', auth()->user()->email)}}" name="email" placeholder="Email Address">
           <label for="inputemail">Email Address *</label>
         </div>
				</div><!-- End Col MD-->

				<div class="col-md-6">
          <div class="form-floating mb-3">
           <input type="tel" class="form-control" id="inputphone" value="{{old('phone', auth()->user()->phone ?? '')}}" name="phone" placeholder="Phone Number">
           <label for="inputphone">Phone Number</label>
         </div>
				</div><!-- End Col MD-->
			</div><!-- End row -->

			<div class="row">
				<div class="col-md-6">
          <div class="form-floating mb-3">
           <input type="text" required class="form-control" id="inputusername" value="{{old('username', auth()->user()->username)}}" name="username" placeholder="Username">
           <label for="inputusername">Username *</label>
         </div>
				</div><!-- End Col MD-->

				<div class="col-md-6">
          <div class="form-floating mb-3">
          <select name="countries_id" class="form-select" id="inputSelectCountry">
            <option value="">Select your location</option>

            @foreach (Countries::orderBy('country_name')->get() as $country)
              <option @if( old('countries_id', auth()->user()->countries_id) == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
              @endforeach
          </select>
          <label for="inputSelectCountry">Location *</label>
        </div>
				</div><!-- End Col MD-->
			</div><!-- End row -->

      <div class="form-floating mb-3">
       <textarea class="form-control" placeholder="Professional Bio" name="description" id="input-description" style="height: 120px">{{ old('description', auth()->user()->bio) }}</textarea>
       <label for="input-description">Professional Bio</label>
     </div>

     <div class="form-floating mb-3">
      <input type="url" class="form-control" id="input-website_misc" value="{{old('website', auth()->user()->website)}}" name="website" placeholder="Portfolio Website">
      <label for="input-website_misc">Portfolio Website</label>
    </div>

    <!-- Custom Portfolio URL -->
    <div class="mb-3">
      <label for="input-portfolio-slug" class="form-label">Custom Portfolio URL</label>
      <div class="input-group">
        <span class="input-group-text">{{ request()->getSchemeAndHttpHost() }}/</span>
        <div class="form-floating">
          <input type="text" class="form-control" id="input-portfolio-slug" value="{{ old('portfolio_slug', auth()->user()->portfolio_slug ?? '') }}" name="portfolio_slug" placeholder="your-custom-url" pattern="[a-zA-Z0-9_-]+" minlength="3" maxlength="100">
          <label for="input-portfolio-slug">Custom URL</label>
        </div>
      </div>
      <div class="form-text text-muted">
        <small>
          <i class="bi bi-info-circle me-1"></i>
          Create a custom URL for your portfolio. Only letters, numbers, hyphens, and underscores allowed (3-100 characters).
          <br>
          <strong>Your portfolio URL will be: <span id="portfolio-url-preview">{{ request()->getSchemeAndHttpHost() }}/<span class="text-muted">your-custom-url</span></span></strong>
        </small>
      </div>
      <div id="slug-feedback" class="mt-1"></div>
    </div>

    <!-- META DATA / SEO FIELDS -->
    <hr class="my-4">
    <h6 class="mb-3">Portfolio SEO & Sharing Settings</h6>

    <div class="form-floating mb-3">
     <input type="text" class="form-control" id="input-meta-title" value="{{old('meta_title', auth()->user()->meta_title ?? '')}}" name="meta_title" placeholder="Portfolio Meta Title" maxlength="60">
     <label for="input-meta-title">Portfolio Meta Title</label>
     <small class="text-muted">Used for search engines and social sharing (60 characters max)</small>
   </div>

   <div class="form-floating mb-3">
    <textarea class="form-control" placeholder="Portfolio Meta Description" name="meta_description" id="input-meta-description" style="height: 80px" maxlength="160">{{ old('meta_description', auth()->user()->meta_description ?? '') }}</textarea>
    <label for="input-meta-description">Portfolio Meta Description</label>
    <small class="text-muted">Brief description for search engines and social media (160 characters max)</small>
  </div>

  <div class="form-floating mb-3">
   <input type="text" class="form-control" id="input-meta-keywords" value="{{old('meta_keywords', auth()->user()->meta_keywords ?? '')}}" name="meta_keywords" placeholder="Portfolio Keywords">
   <label for="input-meta-keywords">Portfolio Keywords</label>
   <small class="text-muted">Comma-separated keywords related to your portfolio (e.g., designer, photographer, developer)</small>
 </div>

 <div class="row mb-4">
   <!-- Profile Picture -->
   <div class="col-md-4">
     <label class="form-label">Profile Picture</label>
     <div class="card">
       <div class="card-body text-center">
         <div class="position-relative d-inline-block" style="cursor: pointer;" onclick="document.getElementById('avatarInput').click();">
                      <div id="avatarPreview" style="width: 120px; height: 120px; margin: 0 auto; position: relative;">
             @if(auth()->user()->avatar)
               <div class="account-avatar-container" style="position: relative; width: 120px; height: 120px;">
                 <img src="{{ url('public/avatar', auth()->user()->avatar) }}"
                      class="img-fluid rounded-circle account-avatar-image"
                      style="width: 120px; height: 120px; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 2;"
                      onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                 <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center account-avatar-fallback"
                      style="width: 120px; height: 120px; position: absolute; top: 0; left: 0; z-index: 1; display: none;">
                   <span class="fw-bold" style="font-size: 2.5rem;">{{ substr(auth()->user()->name, 0, 2) }}</span>
                 </div>
               </div>
             @else
               <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center placeholder-div"
                    style="width: 120px; height: 120px;">
                 <span class="fw-bold" style="font-size: 2.5rem;">{{ substr(auth()->user()->name, 0, 2) }}</span>
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
         <input type="file" class="d-none" name="avatar" accept="image/*" id="avatarInput">
         <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('avatarInput').click();">
           <i class="bi bi-upload"></i> Choose Image
         </button>
         <small class="text-muted d-block">JPG, PNG, GIF (Max: 2MB)</small>
       </div>
     </div>
   </div>

   <!-- Hero Background Image -->
   <div class="col-md-4">
     <label class="form-label">Hero Background Image</label>
     <div class="card">
       <div class="card-body text-center">
         <div class="position-relative d-inline-block w-100" style="cursor: pointer;" onclick="document.getElementById('heroInput').click();">
           <div id="heroPreview" style="width: 100%; height: 120px; position: relative;">
             @if(auth()->user()->hero_image)
               <img src="{{ url('public/cover', auth()->user()->hero_image) }}"
                    class="img-fluid rounded mb-2"
                    style="width: 100%; height: 120px; object-fit: cover; display: block;"
                    onerror="this.style.display='none'; this.parentNode.querySelector('.placeholder-div').style.display='flex';">
               <div class="bg-light rounded mb-2 align-items-center justify-content-center placeholder-div"
                    style="width: 100%; height: 120px; display: none; position: absolute; top: 0; left: 0;">
                 <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
               </div>
             @else
               <div class="bg-light rounded mb-2 d-flex align-items-center justify-content-center placeholder-div"
                    style="width: 100%; height: 120px;">
                 <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
               </div>
             @endif
           </div>
           <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
             <span class="badge bg-primary rounded-pill">
               <i class="bi bi-camera"></i>
             </span>
           </div>
         </div>
         <input type="file" class="d-none" name="hero_image" accept="image/*" id="heroInput">
         <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('heroInput').click();">
           <i class="bi bi-upload"></i> Choose Image
         </button>
         <small class="text-muted d-block">JPG, PNG, GIF (Max: 5MB, 1920x600px recommended)</small>
       </div>
     </div>
   </div>

   <!-- Social Media Preview Image -->
   <div class="col-md-4">
     <label class="form-label">Social Media Preview Image</label>
     <div class="card">
       <div class="card-body text-center">
         <div class="position-relative d-inline-block w-100" style="cursor: pointer;" onclick="document.getElementById('ogImageInput').click();">
           <div id="ogImagePreview" style="width: 100%; height: 120px; position: relative;">
             @if(auth()->user()->og_image)
               @if(filter_var(auth()->user()->og_image, FILTER_VALIDATE_URL))
                 <img src="{{ auth()->user()->og_image }}"
                      class="img-fluid rounded mb-2"
                      style="width: 100%; height: 120px; object-fit: cover; display: block;"
                      onerror="this.style.display='none'; this.parentNode.querySelector('.placeholder-div').style.display='flex';">
               @else
                 <img src="{{ url('public/og', auth()->user()->og_image) }}"
                      class="img-fluid rounded mb-2"
                      style="width: 100%; height: 120px; object-fit: cover; display: block;"
                      onerror="this.style.display='none'; this.parentNode.querySelector('.placeholder-div').style.display='flex';">
               @endif
               <div class="bg-light rounded mb-2 align-items-center justify-content-center placeholder-div"
                    style="width: 100%; height: 120px; display: none; position: absolute; top: 0; left: 0;">
                 <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
               </div>
             @else
               <div class="bg-light rounded mb-2 d-flex align-items-center justify-content-center placeholder-div"
                    style="width: 100%; height: 120px;">
                 <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
               </div>
             @endif
           </div>
           <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
             <span class="badge bg-primary rounded-pill">
               <i class="bi bi-camera"></i>
             </span>
           </div>
         </div>
         <input type="file" class="d-none" name="og_image" accept="image/*" id="ogImageInput">
         <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="document.getElementById('ogImageInput').click();">
           <i class="bi bi-upload"></i> Choose Image
         </button>
         <small class="text-muted d-block">1200x630px recommended, Max: 3MB</small>

         <!-- Alternative URL input -->
         <div class="mt-2">
           <input type="url" class="form-control form-control-sm" id="input-og-image-url"
                  value="{{ old('og_image_url', auth()->user()->og_image ? (filter_var(auth()->user()->og_image, FILTER_VALIDATE_URL) ? auth()->user()->og_image : url('public/og', auth()->user()->og_image)) : '') }}"
                  name="og_image_url" placeholder="Or paste image URL">
         </div>
       </div>
     </div>
   </div>
 </div><hr class="my-4">
<h6 class="mb-3">Social Media Profiles</h6>

      <div class="form-floating mb-3">
       <input type="url" class="form-control" id="input-linkedin" value="{{old('linkedin', auth()->user()->linkedin ?? '')}}" name="linkedin" placeholder="LinkedIn Profile">
       <label for="input-linkedin">LinkedIn Profile</label>
     </div>

      <div class="form-floating mb-3">
       <input type="url" class="form-control" id="input-facebook" value="{{old('facebook', auth()->user()->facebook)}}" name="facebook" placeholder="Facebook Profile">
       <label for="input-facebook">Facebook Profile</label>
     </div>

       <div class="form-floating mb-3">
        <input type="url" class="form-control" id="input-twitter" value="{{old('twitter', auth()->user()->twitter)}}" name="twitter" placeholder="Twitter Profile">
        <label for="input-twitter">Twitter Profile</label>
      </div>

      <div class="form-floating mb-3">
       <input type="url" class="form-control" id="input-instagram" value="{{old('instagram', auth()->user()->instagram)}}" name="instagram" placeholder="Instagram Profile">
       <label for="input-instagram">Instagram Profile</label>
     </div>

    <hr class="my-4">
    <h6 class="mb-3">Portfolio Settings</h6>

    <div class="form-check form-switch form-switch-md mb-3">
      <input class="form-check-input" @if (old('available_for_hire', auth()->user()->available_for_hire) == 'yes') checked @endif name="available_for_hire" type="checkbox" value="yes" id="flexSwitchHire">
      <label class="form-check-label" for="flexSwitchHire">Available for Hire</label>
      <small class="text-muted d-block">Show visitors that you're available for freelance work</small>
    </div>

    <div class="form-check form-switch form-switch-md mb-3">
      <input class="form-check-input" @if (old('show_contact_form', auth()->user()->show_contact_form) == 'yes') checked @endif name="show_contact_form" type="checkbox" value="yes" id="flexSwitchContact">
      <label class="form-check-label" for="flexSwitchContact">Show Contact Form</label>
      <small class="text-muted d-block">Allow visitors to contact you directly through your portfolio</small>
    </div>

    <div class="form-check form-switch form-switch-md mb-3">
      <input class="form-check-input" @if (old('portfolio_private', auth()->user()->portfolio_private) == '1') checked @endif name="portfolio_private" type="checkbox" value="1" id="flexSwitchPrivate" onchange="togglePasswordField()">
      <label class="form-check-label" for="flexSwitchPrivate">{{ __('misc.portfolio_private') }}</label>
      <small class="text-muted d-block">By default, your portfolio is public. Enable this to require a password for access.</small>
    </div>

    <div id="password-field" class="mb-3" style="display: none;">
      <div class="row">
        <!-- Password Field -->
        <div class="col-md-6">
          <div class="form-floating">
            <input type="password" class="form-control" id="input-portfolio-password" value="{{ old('portfolio_password', auth()->user()->portfolio_password ?? '') }}" name="portfolio_password" placeholder="Enter password for portfolio access" minlength="4" maxlength="50">
            <label for="input-portfolio-password">{{ __('misc.portfolio_password') }}</label>
          </div>
        </div>

        <!-- Expiry Time Field -->
        <div class="col-md-6">
          <div class="form-floating">
            <select class="form-select" id="input-password-expiry" name="portfolio_password_expiry">
              <option value="1" @if(old('portfolio_password_expiry', auth()->user()->portfolio_password_expiry ?? 24) == 1) selected @endif>1 Hour</option>
              <option value="6" @if(old('portfolio_password_expiry', auth()->user()->portfolio_password_expiry ?? 24) == 6) selected @endif>6 Hours</option>
              <option value="12" @if(old('portfolio_password_expiry', auth()->user()->portfolio_password_expiry ?? 24) == 12) selected @endif>12 Hours</option>
              <option value="24" @if(old('portfolio_password_expiry', auth()->user()->portfolio_password_expiry ?? 24) == 24) selected @endif>24 Hours (1 Day)</option>
              <option value="72" @if(old('portfolio_password_expiry', auth()->user()->portfolio_password_expiry ?? 24) == 72) selected @endif>3 Days</option>
              <option value="168" @if(old('portfolio_password_expiry', auth()->user()->portfolio_password_expiry ?? 24) == 168) selected @endif>1 Week</option>
              <option value="720" @if(old('portfolio_password_expiry', auth()->user()->portfolio_password_expiry ?? 24) == 720) selected @endif>1 Month</option>
            </select>
            <label for="input-password-expiry">{{ __('misc.password_access_duration') }}</label>
          </div>
        </div>
      </div>

      <!-- Help Text -->
      <div class="form-text text-muted mt-2">
        <small>
          <i class="bi bi-info-circle me-1"></i>
          Set a password that visitors will need to enter to view your portfolio. Share this password with people you want to give access to.
        </small>
        <br>
        <small>
          <i class="bi bi-clock me-1"></i>
          How long should the password access remain valid? After this time, visitors will need to enter the password again.
        </small>
      </div>
    </div>

    <hr class="my-4">
    <h6 class="mb-3">Security Settings</h6>

    <div class="form-check form-switch form-switch-md mb-3">
      <input class="form-check-input" @if (old('two_factor_auth', auth()->user()->two_factor_auth) == 'yes') checked @endif name="two_factor_auth" type="checkbox" value="yes" id="flexSwitchCheckDefault">
      <label class="form-check-label" for="flexSwitchCheckDefault">Two-Factor Authentication</label>
      <small class="text-muted d-block">Add an extra layer of security to your account</small>
    </div>

           <button type="submit" id="buttonSubmit" class="btn w-100 btn-lg btn-custom">Save Profile</button>

         @if (auth()->id() != 1)
           <div class="d-block text-center mt-3">
           		<a href="{{url('user/account/delete')}}" class="text-danger">Delete Account</a>
           </div>
           @endif
       </form><!-- ***** END FORM ***** -->

  </div><!-- /COL MD -->
  </div><!-- row -->
</div><!-- container -->
</section>
@endsection

@section('javascript')
<script type="text/javascript">
// Fix account settings avatar display
document.addEventListener('DOMContentLoaded', function() {
    const accountAvatarImg = document.querySelector('.account-avatar-image');
    const accountAvatarFallback = document.querySelector('.account-avatar-fallback');

    if (accountAvatarImg && accountAvatarFallback) {
        // Check if image loaded successfully
        accountAvatarImg.addEventListener('load', function() {
            // Image loaded successfully, ensure fallback is hidden
            accountAvatarFallback.style.display = 'none';
        });

        accountAvatarImg.addEventListener('error', function() {
            // Image failed to load, show fallback
            this.style.display = 'none';
            accountAvatarFallback.style.display = 'flex';
        });

        // Additional check after a short delay
        setTimeout(function() {
            if (accountAvatarImg.complete && accountAvatarImg.naturalHeight === 0) {
                // Image failed to load
                accountAvatarImg.style.display = 'none';
                accountAvatarFallback.style.display = 'flex';
            }
        }, 100);
    }
});

// Toggle password field visibility
function togglePasswordField() {
    const privateSwitch = document.getElementById('flexSwitchPrivate');
    const passwordField = document.getElementById('password-field');

    if (privateSwitch.checked) {
        passwordField.style.display = 'block';
    } else {
        passwordField.style.display = 'none';
    }
}

// Initialize password field visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePasswordField();
});

// Profile Image Preview
document.getElementById('avatarInput').addEventListener('change', function(e) {
    console.log('Avatar input changed');
    const file = e.target.files[0];
    if (file) {
        console.log('Avatar file selected:', file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Avatar file read successfully');
            const avatarPreview = document.getElementById('avatarPreview');
            // Always replace the entire content with the new image
            avatarPreview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover; display: block;">';
            console.log('Avatar preview updated');
        }
        reader.readAsDataURL(file);
    }
});

// Hero Background Image Preview
document.getElementById('heroInput').addEventListener('change', function(e) {
    console.log('Hero input changed');
    const file = e.target.files[0];
    if (file) {
        console.log('Hero file selected:', file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Hero file read successfully');
            const heroPreview = document.getElementById('heroPreview');
            // Always replace the entire content with the new image
            heroPreview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded mb-2" style="width: 100%; height: 120px; object-fit: cover; display: block;">';
            console.log('Hero preview updated');
        }
        reader.readAsDataURL(file);
    }
});

// Social Media Preview Image Upload
document.getElementById('ogImageInput').addEventListener('change', function(e) {
    console.log('OG input changed');
    const file = e.target.files[0];
    if (file) {
        console.log('OG file selected:', file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('OG file read successfully');
            const ogPreview = document.getElementById('ogImagePreview');
            // Always replace the entire content with the new image
            ogPreview.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded mb-2" style="width: 100%; height: 120px; object-fit: cover; display: block;">';
            // Clear the URL input when file is uploaded
            document.getElementById('input-og-image-url').value = '';
            console.log('OG preview updated');
        }
        reader.readAsDataURL(file);
    }
});

// Social Media Preview Image URL
document.getElementById('input-og-image-url').addEventListener('input', function(e) {
    const url = e.target.value;
    if (url) {
        const ogPreview = document.getElementById('ogImagePreview');
        // Always replace the entire content with the new image
        ogPreview.innerHTML = '<img src="' + url + '" class="img-fluid rounded mb-2" style="width: 100%; height: 120px; object-fit: cover; display: block;" onerror="this.parentElement.innerHTML=\'<div class=\\\"bg-light rounded mb-2 d-flex align-items-center justify-content-center placeholder-div\\\" style=\\\"width: 100%; height: 120px;\\\"><i class=\\\"bi bi-image text-muted\\\" style=\\\"font-size: 2rem;\\\"></i></div>\'">';
        // Clear the file input when URL is entered
        document.getElementById('ogImageInput').value = '';
    }
});

// File validation
function validateFileSize(input, maxSize) {
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size;
        const maxSizeInBytes = maxSize * 1024 * 1024; // Convert MB to bytes

        if (fileSize > maxSizeInBytes) {
            alert('File size must be less than ' + maxSize + 'MB');
            input.value = '';
            return false;
        }
    }
    return true;
}

// Add file size validation to each input
document.getElementById('avatarInput').addEventListener('change', function(e) {
    if (!validateFileSize(this, 2)) return;
    // Rest of the code will run if validation passes
});

document.getElementById('heroInput').addEventListener('change', function(e) {
    if (!validateFileSize(this, 5)) return;
    // Rest of the code will run if validation passes
});

document.getElementById('ogImageInput').addEventListener('change', function(e) {
    if (!validateFileSize(this, 3)) return;
    // Rest of the code will run if validation passes
});

// Portfolio Slug Validation and Preview
let slugCheckTimeout;
const portfolioSlugInput = document.getElementById('input-portfolio-slug');
const slugFeedback = document.getElementById('slug-feedback');
const portfolioUrlPreview = document.getElementById('portfolio-url-preview');
const baseUrl = '{{ request()->getSchemeAndHttpHost() }}';

if (portfolioSlugInput && portfolioUrlPreview) {
    // Initialize preview with current value
    updateUrlPreview(portfolioSlugInput.value);

    portfolioSlugInput.addEventListener('input', function(e) {
        const slug = e.target.value.trim();

        // Update URL preview in real-time
        updateUrlPreview(slug);

        // Clear previous timeout
        clearTimeout(slugCheckTimeout);

        // Clear feedback if empty
        if (!slug) {
            slugFeedback.innerHTML = '';
            portfolioSlugInput.classList.remove('is-valid', 'is-invalid');
            return;
        }

        // Basic client-side validation
        if (slug.length < 3) {
            showSlugFeedback('Portfolio URL must be at least 3 characters long', 'danger');
            return;
        }

        if (slug.length > 100) {
            showSlugFeedback('Portfolio URL must not exceed 100 characters', 'danger');
            return;
        }

        if (!/^[a-zA-Z0-9_-]+$/.test(slug)) {
            showSlugFeedback('Portfolio URL can only contain letters, numbers, hyphens, and underscores', 'danger');
            return;
        }

        if (!/^[a-zA-Z0-9].*[a-zA-Z0-9]$/.test(slug) && slug.length > 1) {
            showSlugFeedback('Portfolio URL must start and end with a letter or number', 'danger');
            return;
        }

        if (/[-_]{2,}/.test(slug)) {
            showSlugFeedback('Portfolio URL cannot contain consecutive hyphens or underscores', 'danger');
            return;
        }

        // Show loading state and check availability after a delay
        showSlugFeedback('<i class="bi bi-clock-history"></i> Checking availability...', 'info');

        slugCheckTimeout = setTimeout(() => {
            checkSlugAvailability(slug);
        }, 500);
    });
}

function updateUrlPreview(slug) {
    if (portfolioUrlPreview) {
        if (slug && slug.length > 0) {
            portfolioUrlPreview.innerHTML = baseUrl + '/<span class="text-primary fw-bold">' + slug + '</span>';
        } else {
            portfolioUrlPreview.innerHTML = baseUrl + '/<span class="text-muted">your-custom-url</span>';
        }
    }
}

function showSlugFeedback(message, type) {
    const alertClass = type === 'success' ? 'text-success' :
                      type === 'danger' ? 'text-danger' :
                      'text-info';

    slugFeedback.innerHTML = `<small class="${alertClass}">${message}</small>`;

    portfolioSlugInput.classList.remove('is-valid', 'is-invalid');
    if (type === 'success') {
        portfolioSlugInput.classList.add('is-valid');
    } else if (type === 'danger') {
        portfolioSlugInput.classList.add('is-invalid');
    }
}

function checkSlugAvailability(slug) {
    // For now, just show success message
    // This will be implemented with AJAX in a later phase
    const currentUserSlug = '{{ auth()->user()->portfolio_slug ?? '' }}';

    if (slug === currentUserSlug) {
        showSlugFeedback('<i class="bi bi-check-circle"></i> This is your current portfolio URL', 'success');
    } else {
        showSlugFeedback('<i class="bi bi-check-circle"></i> Portfolio URL looks good! (Availability will be checked when you save)', 'success');
    }
}
</script>
@endsection
