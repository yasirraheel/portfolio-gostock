<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Query;
use App\Models\Invoices;
use App\Models\TaxRates;
use App\Models\Followers;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\UsersReported;
use App\Models\CollectionsImages;
use App\Models\ReferralTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class UserController extends Controller
{

	use Traits\UserTrait;

	public function __construct(AdminSettings $settings)
	{
		$this->settings = $settings::first();
	}

	protected function validator(array $data, $id = null)
	{

		Validator::extend('ascii_only', function ($attribute, $value, $parameters) {
			return !preg_match('/[^\x00-\x7F\-]/i', $value);
		});

		// Validate if have one letter
		Validator::extend('letters', function ($attribute, $value, $parameters) {
			return preg_match('/[a-zA-Z0-9]/', $value);
		});

		return Validator::make($data, [
			'full_name' => 'required|min:3|max:25',
			'username'  => 'required|min:3|max:15|ascii_only|alpha_dash|letters|unique:pages,slug|unique:reserved,name|unique:users,username,' . $id,
			'email'     => 'required|email|unique:users,email,' . $id,
			'countries_id' => 'required',
			'paypal_account' => 'nullable|email',
			'website'   => 'nullable|url',
			'facebook'   => 'nullable|url',
			'twitter'   => 'nullable|url',
			'instagram'   => 'nullable|url',
			'linkedin'   => 'nullable|url',
			'description' => 'nullable|max:2000',
			'profession' => 'nullable|max:100',
			'phone' => 'nullable|max:20',
			'meta_title' => 'nullable|max:60',
			'meta_description' => 'nullable|max:160',
			'meta_keywords' => 'nullable|max:255',
			'portfolio_slug' => ['nullable', new \App\Rules\ValidPortfolioSlug(), 'unique:users,portfolio_slug,' . $id],
			'portfolio_password' => 'nullable|min:4|max:50',
			'portfolio_password_expiry' => 'nullable|integer|min:1|max:720',
			'og_image_url' => 'nullable|url',
			'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
			'hero_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
			'og_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:3072',
		]);
	} //<--- End Method

	public function profile($slug, Request $request)
	{
		// First check if slug matches a portfolio_slug
		$portfolioUser = User::where('portfolio_slug', '=', $slug)
			->whereStatus('active')
			->first();

		if ($portfolioUser) {
			// Check if portfolio is private
			if ($portfolioUser->portfolio_private) {
				// Check if user has provided correct password and if it's still valid
				$providedPassword = $request->session()->get('portfolio_password_' . $portfolioUser->id);
				$passwordTimestamp = $request->session()->get('portfolio_password_time_' . $portfolioUser->id);
				$expiryHours = $portfolioUser->portfolio_password_expiry ?? 24;

				// Check if password is correct and not expired
				$isPasswordValid = $providedPassword === $portfolioUser->portfolio_password;
				$isPasswordNotExpired = $passwordTimestamp && (time() - $passwordTimestamp) < ($expiryHours * 3600);

				if (!$isPasswordValid || !$isPasswordNotExpired) {
					// Clear expired session data
					if (!$isPasswordNotExpired) {
						$request->session()->forget('portfolio_password_' . $portfolioUser->id);
						$request->session()->forget('portfolio_password_time_' . $portfolioUser->id);
					}
					// Redirect to password entry page
					return redirect()->route('portfolio.password', ['slug' => $slug]);
				}
			}

			// Track portfolio view (only if not the portfolio owner)
			if (!auth()->check() || auth()->id() !== $portfolioUser->id) {
				$portfolioUser->increment('portfolio_views');
				$portfolioUser->update(['last_portfolio_view' => now()]);
			}

			// This is a portfolio slug - show portfolio view (PUBLIC ACCESS)
			$title = $portfolioUser->name ?: $portfolioUser->username;

			// Get all portfolio related data
			$skills = $portfolioUser->skills()->orderBy('id', 'desc')->get();
			$experiences = $portfolioUser->experiences()->orderBy('start_date', 'desc')->get();
			$educations = $portfolioUser->educations()->orderBy('start_date', 'desc')->get();
			$certifications = $portfolioUser->certifications()->orderBy('issue_date', 'desc')->get();
			$projects = $portfolioUser->projects()->where('visibility', 'public')->orderBy('id', 'desc')->get();
			$testimonials = $portfolioUser->testimonials()->active()->orderBy('id', 'desc')->get();
			$customSections = $portfolioUser->customSections()->active()->orderBy('id', 'desc')->get();

			// Calculate portfolio statistics
			$portfolioStats = [
				'totalViews' => $portfolioUser->portfolio_views,
				'totalProjects' => $projects->count(),
				'totalSkills' => $skills->where('status', 'active')->count(),
				'totalExperience' => $experiences->where('status', 'active')->count(),
				'totalEducations' => $educations->where('status', 'active')->count(),
				'totalCertifications' => $certifications->where('status', 'active')->count(),
				'totalTestimonials' => $testimonials->count(),
				'experienceYears' => $this->calculateExperienceYears($experiences->where('status', 'active')),
				'completedProjects' => $projects->where('status', 'completed')->count(),
				'totalSections' => $this->calculateTotalSections($skills, $experiences, $educations, $certifications, $projects, $testimonials, $customSections),
			];

			// Create empty images collection for portfolio (since we're not using stock photos)
			$images = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
				'path' => request()->url(),
			]);

			// Add other variables that might be needed by the view
			$categories = collect(); // Empty collection for categories
			$categoriesCount = 0;
			$settings = \App\Models\AdminSettings::first(); // Get actual settings
			$featured = collect(); // Empty collection for featured
			$categoryPopular = false; // No category popular for portfolio

			return view('index.user_portfolio', [
				'title' => $title,
				'user' => $portfolioUser,
				'skills' => $skills,
				'experiences' => $experiences,
				'educations' => $educations,
				'certifications' => $certifications,
				'projects' => $projects,
				'testimonials' => $testimonials,
				'customSections' => $customSections,
				'images' => $images,
				'categories' => $categories,
				'categoriesCount' => $categoriesCount,
				'settings' => $settings,
				'featured' => $featured,
				'categoryPopular' => $categoryPopular,
				'portfolioStats' => $portfolioStats, // Add portfolio stats
			]);
		}

		// If not a portfolio slug, treat as regular username profile
		$user = User::where('username', '=', $slug)
			->withCount(['followers', 'following'])
			->whereStatus('active')
			->firstOrFail();

		$title = $user->name ?: $user->username;

		// Images functionality removed - this is now a universal starter kit
				// Get user images - removed for universal starter kit
		$images = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);

		// Pagination check removed since we don't have images anymore

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username;

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
			$followActive = Followers::whereFollower(auth()->id())
				->where('following', $user->id)
				->where('status', '1')
				->first();

			if ($followActive) {
				$textFollow   = __('users.following');
				$icoFollow    = '-person-check';
				$activeFollow = 'btnFollowActive';
			} else {
				$textFollow   = __('users.follow');
				$icoFollow    = '-person-plus';
				$activeFollow = '';
			}
		}

		if (request()->ajax()) {
			return view('includes.images', ['images' => $images])->render();
		}

		return view('users.profile', [
			'title' => $title,
			'user' => $user,
			'images' => $images,
			'textFollow' => $textFollow ?? null,
			'icoFollow' => $icoFollow ?? null,
			'activeFollow' => $activeFollow ?? null
		]);
	} //<--- End Method

	public function portfolioPassword($slug, Request $request)
	{
		// Get the portfolio user
		$user = User::where('portfolio_slug', '=', $slug)
			->orWhere('username', '=', $slug)
			->whereStatus('active')
			->first();

		if (!$user) {
			abort(404);
		}

		// If portfolio is not private, redirect to portfolio
		if (!$user->portfolio_private) {
			return redirect()->route('profile', ['slug' => $slug]);
		}

		return view('portfolio.password', compact('user', 'slug'));
	} //<--- End Method

	public function verifyPortfolioPassword(Request $request)
	{
		$request->validate([
			'slug' => 'required|string',
			'password' => 'required|string'
		]);

		$slug = $request->input('slug');
		$password = $request->input('password');

		// Get the portfolio user
		$user = User::where('portfolio_slug', '=', $slug)
			->orWhere('username', '=', $slug)
			->whereStatus('active')
			->first();

		if (!$user) {
			return redirect()->back()->with('error', 'Portfolio not found');
		}

		// Check if portfolio is private
		if (!$user->portfolio_private) {
			return redirect()->route('profile', ['slug' => $slug]);
		}

		// Verify password
		if ($password === $user->portfolio_password) {
			// Store password and timestamp in session
			$request->session()->put('portfolio_password_' . $user->id, $password);
			$request->session()->put('portfolio_password_time_' . $user->id, time());
			return redirect()->route('profile', ['slug' => $slug]);
		} else {
			return redirect()->back()->with('error', 'Incorrect password. Please try again.');
		}
	} //<--- End Method

	public function followers($slug, Request $request)
	{

		$user  = User::where('username', '=', $slug)
			->withCount(['images', 'followers', 'following', 'collections'])
			->firstOrFail();
		$_title = $user->name ?: $user->username;
		$title  = $_title . ' - ' . __('users.followers');

		if ($user->status == 'suspended') {
			return view('errors.user_suspended');
		}

		$followers = User::where('users.status', 'active')
			->leftjoin('followers', 'users.id', '=', \DB::raw('followers.follower AND followers.status = "1"'))
			->leftjoin('images', 'users.id', '=', \DB::raw('images.user_id AND images.status = "active"'))
			->where('users.status', '=', 'active')
			->where('followers.following', $user->id)
			->groupBy('users.id')
			->orderBy('followers.id', 'DESC')
			->select(
				'users.id',
				'users.username',
				'users.name',
				'users.avatar',
				'users.cover',
				'users.status'
			)
			->with(['images' => function ($query) {
				$query->select('id', 'user_id', 'thumbnail')->orderByDesc('id');
			}])
			->withCount(['images', 'followers'])
			->paginate(10);

		if ($request->input('page') > $followers->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
			return view('includes.users', ['users' => $followers])->render();
		}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username . '/followers';

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
			$followActive = Followers::whereFollower(auth()->id())
				->where('following', $user->id)
				->where('status', '1')
				->first();

			if ($followActive) {
				$textFollow   = __('users.following');
				$icoFollow    = '-person-check';
				$activeFollow = 'btnFollowActive';
			} else {
				$textFollow   = __('users.follow');
				$icoFollow    = '-person-plus';
				$activeFollow = '';
			}
		}

		return view('users.profile', [
			'title' => $title,
			'followers' => $followers,
			'user' => $user,
			'textFollow' => $textFollow ?? null,
			'icoFollow' => $icoFollow ?? null,
			'activeFollow' => $activeFollow ?? null,

		]);
	} //<--- End Method

	public function following($slug, Request $request)
	{

		$user  = User::where('username', '=', $slug)
			->withCount(['images', 'followers', 'following', 'collections'])
			->firstOrFail();
		$_title = $user->name ?: $user->username;
		$title  = $_title . ' - ' . __('users.following');

		if ($user->status == 'suspended') {
			return view('errors.user_suspended');
		}

		$following = User::where('users.status', 'active')
			->leftjoin('followers', 'users.id', '=', \DB::raw('followers.following AND followers.status = "1"'))
			->leftjoin('images', 'users.id', '=', \DB::raw('images.user_id AND images.status = "active"'))
			->where('users.status', '=', 'active')
			->where('followers.follower', $user->id)
			->groupBy('users.id')
			->orderBy('followers.id', 'DESC')
			->select(
				'users.id',
				'users.username',
				'users.name',
				'users.avatar',
				'users.cover',
				'users.status'
			)
			->with(['images' => function ($query) {
				$query->select('id', 'user_id', 'thumbnail')->orderByDesc('id');
			}])
			->withCount(['images', 'followers'])
			->paginate(10);

		if ($request->input('page') > $following->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
			return view('includes.users', ['users' => $following])->render();
		}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username . '/following';

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
			$followActive = Followers::whereFollower(auth()->id())
				->where('following', $user->id)
				->where('status', '1')
				->first();

			if ($followActive) {
				$textFollow   = __('users.following');
				$icoFollow    = '-person-check';
				$activeFollow = 'btnFollowActive';
			} else {
				$textFollow   = __('users.follow');
				$icoFollow    = '-person-plus';
				$activeFollow = '';
			}
		}

		return view('users.profile', [
			'title' => $title,
			'following' => $following,
			'user' => $user,
			'textFollow' => $textFollow ?? null,
			'icoFollow' => $icoFollow ?? null,
			'activeFollow' => $activeFollow ?? null,
		]);
	} //<--- End Method

	public function account()
	{
		return view('users.account');
	} //<--- End Method

	public function update_account(Request $request)
	{
		$input = $request->all();
		$id    = auth()->user()->id;

		$validator = $this->validator($input, $id);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		$user = User::find($id);

		// Basic fields
		$user->name = $input['full_name'];
		$user->email = trim($input['email']);
		$user->username = $input['username'];
		$user->countries_id = $input['countries_id'];
		$user->bio = $input['description'] ?? '';
		$user->profession = $input['profession'] ?? '';
		$user->phone = $input['phone'] ?? '';

		// Social media and website
		$user->website = trim(strtolower($input['website'] ?? ''));
		$user->facebook = trim(strtolower($input['facebook'] ?? ''));
		$user->twitter = trim(strtolower($input['twitter'] ?? ''));
		$user->instagram = trim(strtolower($input['instagram'] ?? ''));
		$user->linkedin = trim(strtolower($input['linkedin'] ?? ''));

		// SEO/Meta fields
		$user->meta_title = $input['meta_title'] ?? '';
		$user->meta_description = $input['meta_description'] ?? '';
		$user->meta_keywords = $input['meta_keywords'] ?? '';

		// Portfolio URL slug
		$user->portfolio_slug = !empty(trim($input['portfolio_slug'] ?? '')) ? trim($input['portfolio_slug']) : null;

		// Portfolio settings
		$user->available_for_hire = $request->has('available_for_hire') ? 'yes' : 'no';
		$user->show_contact_form = $request->has('show_contact_form') ? 'yes' : 'no';
		$user->portfolio_private = $request->has('portfolio_private') ? 1 : 0;
		$user->portfolio_password = $request->has('portfolio_private') && !empty($input['portfolio_password']) ? $input['portfolio_password'] : null;
		$user->portfolio_password_expiry = $request->has('portfolio_private') ? ($input['portfolio_password_expiry'] ?? 24) : 24;
		$user->two_factor_auth = $request->has('two_factor_auth') ? 'yes' : 'no';

		// Handle file uploads
		$this->handleFileUploads($request, $user);

		// Handle OG image (either file upload or URL)
		if ($request->hasFile('og_image')) {
			$ogImage = $this->uploadImage($request->file('og_image'), 'og');
			if ($ogImage) {
				$user->og_image = $ogImage;
			}
		} elseif ($request->filled('og_image_url')) {
			$ogImageUrl = $input['og_image_url'];

			// Check if this is a local file URL that we should convert back to filename
			$localOgPattern = url('public/og') . '/';
			if (strpos($ogImageUrl, $localOgPattern) === 0) {
				// This is a local file URL, extract just the filename
				$user->og_image = basename($ogImageUrl);
			} else {
				// This is an external URL, store it as-is
				$user->og_image = $ogImageUrl;
			}
		}

		$user->save();

		\Session::flash('notification', __('auth.success_update'));

		return redirect('user/account');
	} //<--- End Method

	private function handleFileUploads(Request $request, User $user)
	{
		// Handle avatar upload
		if ($request->hasFile('avatar')) {
			$avatar = $this->uploadImage($request->file('avatar'), 'avatar');
			if ($avatar) {
				// Delete old avatar if it exists and is not default
				if ($user->avatar && $user->avatar != 'default.jpg' && file_exists(public_path('avatar/' . $user->avatar))) {
					unlink(public_path('avatar/' . $user->avatar));
				}
				$user->avatar = $avatar;
			}
		}

		// Handle hero image upload
		if ($request->hasFile('hero_image')) {
			$heroImage = $this->uploadImage($request->file('hero_image'), 'hero');
			if ($heroImage) {
				// Delete old hero image if it exists
				if ($user->hero_image && file_exists(public_path('cover/' . $user->hero_image))) {
					unlink(public_path('cover/' . $user->hero_image));
				}
				$user->hero_image = $heroImage;
			}
		}
	}

	private function uploadImage($file, $type)
	{
		$fileName = time() . '_' . $type . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

		try {
			switch ($type) {
				case 'avatar':
					// Ensure directory exists
					if (!is_dir(public_path('avatar'))) {
						mkdir(public_path('avatar'), 0755, true);
					}
					// Store directly in public/avatar directory
					$file->move(public_path('avatar'), $fileName);
					// Resize avatar to max 300x300 while maintaining aspect ratio
					try {
						$image = Image::read(public_path('avatar/' . $fileName));
						$image->scaleDown(300, 300);
						$image->save(public_path('avatar/' . $fileName));
					} catch (\Exception $e) {
						// If image processing fails, continue with original file
						\Log::warning('Avatar image processing failed: ' . $e->getMessage());
					}
					break;

				case 'hero':
					// Ensure directory exists
					if (!is_dir(public_path('cover'))) {
						mkdir(public_path('cover'), 0755, true);
					}
					// Store directly in public/cover directory
					$file->move(public_path('cover'), $fileName);
					// Resize hero image to max 1920x600 while maintaining aspect ratio
					try {
						$image = Image::read(public_path('cover/' . $fileName));
						$image->scaleDown(1920, 600);
						$image->save(public_path('cover/' . $fileName));
					} catch (\Exception $e) {
						// If image processing fails, continue with original file
						\Log::warning('Hero image processing failed: ' . $e->getMessage());
					}
					break;

				case 'og':
					// Ensure directory exists
					if (!is_dir(public_path('og'))) {
						mkdir(public_path('og'), 0755, true);
					}
					// Store directly in public/og directory
					$file->move(public_path('og'), $fileName);
					// Resize OG image to max 1200x630 while maintaining aspect ratio
					try {
						$image = Image::read(public_path('og/' . $fileName));
						$image->scaleDown(1200, 630);
						$image->save(public_path('og/' . $fileName));
					} catch (\Exception $e) {
						// If image processing fails, continue with original file
						\Log::warning('OG image processing failed: ' . $e->getMessage());
					}
					break;
			}

			// Log success
			\Log::info('Image uploaded successfully: ' . $fileName . ' to ' . $type . ' directory');
			return $fileName;

		} catch (\Exception $e) {
			\Log::error('Image upload failed: ' . $e->getMessage());
			return null;
		}
	}

	public function password()
	{
		return view('users.password');
	} //<--- End Method

	public function update_password(Request $request)
	{

		$input = $request->all();
		$id = auth()->user()->id;

		$validator = Validator::make($input, [
			'old_password' => 'required|min:6',
			'password'     => 'required|min:8',
		]);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		if (!\Hash::check($input['old_password'], auth()->user()->password)) {
			return redirect('user/account/password')->with(array('incorrect_pass' => __('misc.password_incorrect')));
		}

		$user = User::find($id);
		$user->password  = \Hash::make($input["password"]);
		$user->save();

		\Session::flash('notification', __('auth.success_update_password'));

		return redirect('user/account/password');
	} //<--- End Method

	public function delete()
	{
		if (auth()->user()->id == 1) {
			return redirect('user/account');
		}
		return view('users.delete');
	} //<--- End Method

	public function delete_account()
	{

		$id = auth()->user()->id;
		$user = User::findOrFail($id);

		if ($user->id == 1) {
			return redirect('user/account');
			exit;
		}

		$this->deleteUser($id);

		return redirect('user/account');
	} //<--- End Method

	public function notifications()
	{

		$sql = DB::table('notifications')
			->select(DB::raw('
			notifications.id id_noty,
			notifications.type,
			notifications.created_at,
			users.id userId,
			users.username,
			users.name,
			users.avatar,
			images.id,
			images.title
			'))
			->leftjoin('users', 'users.id', '=', DB::raw('notifications.author'))
			->leftjoin('images', 'images.id', '=', DB::raw('notifications.target AND images.status = "active"'))
			->leftjoin('comments', 'comments.images_id', '=', DB::raw('notifications.target
			AND comments.user_id = users.id
			AND comments.images_id = images.id
			AND comments.status = "1"
			'))
			->where('notifications.destination', '=',  auth()->user()->id)
			->where('notifications.trash', '=',  '0')
			->where('users.status', '=',  'active')
			->groupBy('notifications.id')
			->orderBy('notifications.id', 'DESC')
			->paginate(10);

		// Mark seen Notification
		Notifications::where('destination', auth()->user()->id)
			->update(array('status' => '1'));

		return view('users.notifications')->withSql($sql);
	} //<--- End Method

	public function notificationsDelete()
	{

		$notifications = Notifications::where('destination', auth()->user()->id)->get();

		if (isset($notifications)) {
			foreach ($notifications as $notification) {
				$notification->delete();
			}
		}

		return redirect('notifications');
	} //<--- End Method

	public function upload_avatar(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'photo' => [
				'required',
				'mimes:jpg,gif,png,jpe,jpeg',
				'dimensions:min_width=180,min_height=180',
				'max:' . $this->settings->file_size_allowed,
			]
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		}

		$path = config('path.avatar');
		$photo = $request->file('photo');
		$extension = $photo->extension();
		$avatar = strtolower(
			auth()->user()->username . '-' .
				auth()->user()->id .
				time() .
				str_random(10) .
				'.' . $extension
		);

		try {
			// Create image manager instance with desired driver
			$manager = Image::read($photo);

			// Process the image - maintain aspect ratio
			$imgAvatar = $manager->scaleDown(180, 180)->encodeByExtension($extension);

			// Store the image
			Storage::put($path . $avatar, $imgAvatar, 'public');

			// Delete old avatar if it's not the default
			if (auth()->user()->avatar != $this->settings->avatar) {
				Storage::delete($path . auth()->user()->avatar);
			}

			// Update user avatar in database
			auth()->user()->update(['avatar' => $avatar]);

			return response()->json([
				'success' => true,
				'avatar' => Storage::url($path . $avatar),
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'errors' => ['photo' => 'Error processing image: ' . $e->getMessage()],
			]);
		}
	}

	public function upload_cover(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'photo' => [
				'required',
				'mimes:jpg,gif,png,jpe,jpeg',
				'dimensions:min_width=800,min_height=600',
				'max:' . $this->settings->file_size_allowed,
			]
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->getMessageBag()->toArray(),
			]);
		}

		try {
			$path = config('path.cover');
			$photo = $request->file('photo');
			$extension = $photo->extension();
			$cover = strtolower(
				auth()->user()->username . '-' .
					auth()->user()->id .
					time() .
					str_random(10) .
					'.' . $extension
			);

			$image = Image::read($photo);
			$maxWidth = ($image->width() < $image->height()) ? 800 : 1500;

			// Process the image
			$imgCover = $image->scale(width: $maxWidth)
				->encodeByExtension($extension);

			// Store the image
			Storage::put($path . $cover, $imgCover, 'public');

			// Delete old cover if it's not the default
			if (auth()->user()->cover != $this->settings->cover) {
				Storage::delete($path . auth()->user()->cover);
			}

			// Update user cover in database
			auth()->user()->update(['cover' => $cover]);

			return response()->json([
				'success' => true,
				'cover' => Storage::url($path . $cover),
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'errors' => ['photo' => 'Error processing image: ' . $e->getMessage()],
			]);
		}
	}

	public function userLikes(Request $request)
	{
		$title = __('users.likes') . ' - ';

		// Removed for universal starter kit - return empty paginated collection
		$images = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);

		if ($request->input('page') > 1) {
			abort('404');
		}

		return view('users.likes', ['title' => $title, 'images' => $images]);
	} //<--- End Method

	public function followingFeed(Request $request)
	{

		$title = __('misc.feed') . ' - ';

		// Removed for universal starter kit - return empty paginated collection
		$images = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);

		if (request()->ajax()) {
			return view('includes.images', ['images' => $images])->render();
		}

		return view('users.feed', ['title' => $title, 'images' => $images]);
	} //<--- End Method

	public function collections($slug, Request $request)
	{
		$user  = User::where('username', '=', $slug)
			->withCount(['images', 'followers', 'following', 'collections'])
			->firstOrFail();
		$_title = $user->name ?: $user->username;
		$title  = $_title . ' - ' . __('misc.collections');

		if ($user->status == 'suspended') {
			return view('errors.user_suspended');
		}

		if (auth()->check()) {
			$AuthId = auth()->user()->id;
		} else {
			$AuthId = 0;
		}

		$collections = $user->collections()->where('user_id', $user->id)
			->where('type', 'public')
			->orWhere('user_id', $AuthId)
			->where('user_id', $user->id)
			->where('type', 'private')
			->orderBy('id', 'desc')
			->with([
				'collectionImages' => fn($q) =>
				$q->with(['stockCollection']),
				'creator'
			])
			->paginate(config('settings.result_request'));

		if ($request->input('page') > $collections->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
			return view('includes.collections-grid', ['data' => $collections])->render();
		}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username . '/collections';

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
			$followActive = Followers::whereFollower(auth()->id())
				->where('following', $user->id)
				->where('status', '1')
				->first();

			if ($followActive) {
				$textFollow   = __('users.following');
				$icoFollow    = '-person-check';
				$activeFollow = 'btnFollowActive';
			} else {
				$textFollow   = __('users.follow');
				$icoFollow    = '-person-plus';
				$activeFollow = '';
			}
		}

		return view('users.profile', [
			'title' => $title,
			'collections' => $collections,
			'user' => $user,
			'textFollow' => $textFollow ?? null,
			'icoFollow' => $icoFollow ?? null,
			'activeFollow' => $activeFollow ?? null,
		]);
	} //<--- End Method

	/* COMMENTED OUT - Collections functionality removed for universal starter kit
	public function collectionDetail(Request $request)
	{
		// Collections functionality removed for universal starter kit
		abort(404);
	}
	END COMMENTED OUT */ //<--- End Method

	public function report(Request $request)
	{

		$data = UsersReported::firstOrNew(['user_id' => auth()->user()->id, 'id_reported' => $request->id]);

		if ($data->exists) {
			\Session::flash('noty_error', 'error');
			return redirect()->back();
		} else {

			$data->reason = $request->reason;
			$data->save();
			\Session::flash('noty_success', 'success');
			return redirect()->back();
		}
	} //<--- End Method

	/* COMMENTED OUT - Photos functionality removed for universal starter kit
	public function photosPending(Request $request)
	{
		// Photos functionality removed for universal starter kit
		$images = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
			'path' => request()->url(),
		]);

		return view('users.photos-pending', ['images' => $images]);
	}
	END COMMENTED OUT */ //<--- End Method

	public function invoice($id)
	{
		$data = Invoices::whereId($id)
			->whereStatus('paid')
			->firstOrFail();

		if ($data->user_id != auth()->id() && ! auth()->user()->isSuperAdmin()) {
			abort(404);
		}

		$taxes = TaxRates::whereIn('id', collect(explode('_', $data->taxes)))->get();
		$totalTaxes = ($data->amount * $taxes->sum('percentage') / 100);

		$totalAmount = ($data->amount + $data->transaction_fee + $totalTaxes);

		return view('users.invoice', [
			'data' => $data,
			'amount' => $data->amount,
			'percentageApplied' => $data->percentage_applied,
			'transactionFee' => $data->transaction_fee,
			'totalAmount' => $totalAmount,
			'taxes' => $taxes
		]);
	}

	public function myReferrals()
	{
		$transactions = ReferralTransactions::whereReferredBy(auth()->id())
			->orderBy('id', 'desc')
			->paginate(20);

		return view('users.referrals', ['transactions' => $transactions]);
	} //<--- End Method

	public function subscription()
	{
		$subscription  = auth()->user()->mySubscription()->latest()->first();
		$subscriptions = auth()->user()->mySubscription()->latest()->paginate(10);

		return view('users.subscription')->with([
			'subscription' => $subscription,
			'subscriptions' => $subscriptions
		]);
	}

	// Theme methods for user portfolio
	public function theme()
	{
		return view('users.theme');
	}

	public function themeStore(Request $request)
	{
		$rules = [
			'portfolio_logo' => 'nullable|mimes:png|max:2048',
			'portfolio_logo_light' => 'nullable|mimes:png|max:2048',
			'portfolio_favicon' => 'nullable|mimes:png|max:1024',
			'portfolio_primary_color' => 'nullable|string|max:7',
			'portfolio_secondary_color' => 'nullable|string|max:7',
			'portfolio_theme' => 'nullable|in:light,dark,auto',
			'portfolio_font_family' => 'nullable|string|max:50',
			'portfolio_font_size' => 'nullable|integer|min:12|max:24'
		];

		$request->validate($rules);

		$user = auth()->user();
		$temp = public_path('temp/');
		$path = public_path('portfolio_assets/');

		// Create directory if it doesn't exist
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}

		// Handle portfolio logo upload
		if ($request->hasFile('portfolio_logo')) {
			$extension = $request->file('portfolio_logo')->getClientOriginalExtension();
			$filename = 'portfolio_logo_' . $user->id . '_' . time() . '.' . $extension;

			if ($request->file('portfolio_logo')->move($temp, $filename)) {
				// Resize and optimize - maintain aspect ratio
				$image = Image::read($temp . $filename);
				$image->scaleDown(400, 400);
				$image->save($path . $filename, 90);

				// Clean up temp file and old logo
				\File::delete($temp . $filename);
				if ($user->portfolio_logo && file_exists($path . $user->portfolio_logo)) {
					\File::delete($path . $user->portfolio_logo);
				}

				$user->portfolio_logo = $filename;
			}
		}

		// Handle portfolio logo light upload
		if ($request->hasFile('portfolio_logo_light')) {
			$extension = $request->file('portfolio_logo_light')->getClientOriginalExtension();
			$filename = 'portfolio_logo_light_' . $user->id . '_' . time() . '.' . $extension;

			if ($request->file('portfolio_logo_light')->move($temp, $filename)) {
				$image = Image::read($temp . $filename);
				$image->scaleDown(400, 400);
				$image->save($path . $filename, 90);

				\File::delete($temp . $filename);
				if ($user->portfolio_logo_light && file_exists($path . $user->portfolio_logo_light)) {
					\File::delete($path . $user->portfolio_logo_light);
				}

				$user->portfolio_logo_light = $filename;
			}
		}

		// Handle favicon upload
		if ($request->hasFile('portfolio_favicon')) {
			$extension = $request->file('portfolio_favicon')->getClientOriginalExtension();
			$filename = 'portfolio_favicon_' . $user->id . '_' . time() . '.' . $extension;

			if ($request->file('portfolio_favicon')->move($temp, $filename)) {
				$image = Image::read($temp . $filename);
				$image->scaleDown(32, 32);
				$image->save($path . $filename, 90);

				\File::delete($temp . $filename);
				if ($user->portfolio_favicon && file_exists($path . $user->portfolio_favicon)) {
					\File::delete($path . $user->portfolio_favicon);
				}

				$user->portfolio_favicon = $filename;
			}
		}

		// Update other theme settings
		$user->portfolio_primary_color = $request->portfolio_primary_color;
		$user->portfolio_secondary_color = $request->portfolio_secondary_color;
		$user->portfolio_theme = $request->portfolio_theme;
		$user->portfolio_font_family = $request->portfolio_font_family;
		$user->portfolio_font_size = $request->portfolio_font_size;
		$user->portfolio_custom_css = $request->portfolio_custom_css;
		$user->portfolio_custom_js = $request->portfolio_custom_js;

		$user->save();

		return redirect()->back()->withSuccessMessage(__('admin.success_update'));
	}

	// Professional Skills methods
	public function skills()
	{
		$skills = auth()->user()->skills()->orderBy('created_at', 'desc')->paginate(10);
		return view('users.skills', compact('skills'));
	}

	public function skillsStore(Request $request)
	{
		$rules = [
			'skill_name' => 'required|string|max:100',
			'description' => 'nullable|string|max:500',
			'fas_icon' => 'required|string|max:50',
			'status' => 'required|in:active,inactive',
			'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert'
		];

		$request->validate($rules);

		auth()->user()->skills()->create([
			'skill_name' => $request->skill_name,
			'description' => $request->description,
			'fas_icon' => $request->fas_icon,
			'status' => $request->status,
			'proficiency_level' => $request->proficiency_level
		]);

		return redirect()->back()->withSuccessMessage(__('misc.success_skill_added'));
	}

	public function skillsUpdate(Request $request)
	{
		$rules = [
			'id' => 'required|exists:user_skills,id',
			'skill_name' => 'required|string|max:100',
			'description' => 'nullable|string|max:500',
			'fas_icon' => 'required|string|max:50',
			'status' => 'required|in:active,inactive',
			'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert'
		];

		$request->validate($rules);

		$skill = auth()->user()->skills()->findOrFail($request->id);
		$skill->update([
			'skill_name' => $request->skill_name,
			'description' => $request->description,
			'fas_icon' => $request->fas_icon,
			'status' => $request->status,
			'proficiency_level' => $request->proficiency_level
		]);

		return redirect()->back()->withSuccessMessage(__('misc.success_skill_updated'));
	}

	public function skillsDestroy($id)
	{
		$skill = auth()->user()->skills()->findOrFail($id);
		$skill->delete();

		return redirect()->back()->withSuccessMessage(__('misc.success_skill_deleted'));
	}

	// Professional Experience methods
	public function experience()
	{
		$experiences = auth()->user()->experiences()->ordered()->paginate(10);
		return view('users.experience', compact('experiences'));
	}

	public function experienceStore(Request $request)
	{
		$rules = [
			'company_name' => 'required|string|max:200',
			'job_title' => 'required|string|max:200',
			'employment_type' => 'required|in:full_time,part_time,contract,freelance,internship,temporary',
			'location' => 'nullable|string|max:200',
			'start_date' => 'required|date',
			'end_date' => 'nullable|date|after:start_date',
			'is_current' => 'boolean',
			'description' => 'nullable|string|max:2000',
			'achievements' => 'nullable|string|max:2000',
			'technologies_used' => 'nullable|string|max:500',
			'company_website' => 'nullable|url|max:255',
			'company_logo' => 'nullable|mimes:png,jpg,jpeg|max:1024',
			'status' => 'required|in:active,inactive'
		];

		$request->validate($rules);

		$data = $request->except('company_logo');

		// Handle company logo upload
		if ($request->hasFile('company_logo')) {
			$extension = $request->file('company_logo')->getClientOriginalExtension();
			$filename = 'company_logo_' . auth()->id() . '_' . time() . '.' . $extension;
			$path = public_path('portfolio_assets/');

			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}

			if ($request->file('company_logo')->move($path, $filename)) {
				$data['company_logo'] = $filename;
			}
		}

		// If current job, set end_date to null
		if ($request->is_current) {
			$data['end_date'] = null;
		}

		auth()->user()->experiences()->create($data);

		return redirect()->back()->withSuccessMessage(__('misc.success_experience_added'));
	}

	public function experienceUpdate(Request $request)
	{
		$rules = [
			'id' => 'required|exists:user_experiences,id',
			'company_name' => 'required|string|max:200',
			'job_title' => 'required|string|max:200',
			'employment_type' => 'required|in:full_time,part_time,contract,freelance,internship,temporary',
			'location' => 'nullable|string|max:200',
			'start_date' => 'required|date',
			'end_date' => 'nullable|date|after:start_date',
			'is_current' => 'boolean',
			'description' => 'nullable|string|max:2000',
			'achievements' => 'nullable|string|max:2000',
			'technologies_used' => 'nullable|string|max:500',
			'company_website' => 'nullable|url|max:255',
			'company_logo' => 'nullable|mimes:png,jpg,jpeg|max:1024',
			'status' => 'required|in:active,inactive'
		];

		$request->validate($rules);

		$experience = auth()->user()->experiences()->findOrFail($request->id);
		$data = $request->except(['company_logo', 'id']);

		// Handle company logo upload
		if ($request->hasFile('company_logo')) {
			$extension = $request->file('company_logo')->getClientOriginalExtension();
			$filename = 'company_logo_' . auth()->id() . '_' . time() . '.' . $extension;
			$path = public_path('portfolio_assets/');

			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}

			if ($request->file('company_logo')->move($path, $filename)) {
				// Delete old logo
				if ($experience->company_logo && file_exists($path . $experience->company_logo)) {
					unlink($path . $experience->company_logo);
				}
				$data['company_logo'] = $filename;
			}
		}

		// If current job, set end_date to null
		if ($request->is_current) {
			$data['end_date'] = null;
		}

		$experience->update($data);

		return redirect()->back()->withSuccessMessage(__('misc.success_experience_updated'));
	}

	public function experienceDestroy($id)
	{
		$experience = auth()->user()->experiences()->findOrFail($id);

		// Delete company logo if exists
		if ($experience->company_logo) {
			$path = public_path('portfolio_assets/' . $experience->company_logo);
			if (file_exists($path)) {
				unlink($path);
			}
		}

		$experience->delete();

		return redirect()->back()->withSuccessMessage(__('misc.success_experience_deleted'));
	}

	public function experienceAdd()
	{
		return view('users.add-experience');
	}

	public function experienceEdit($id)
	{
		$experience = auth()->user()->experiences()->findOrFail($id);
		return view('users.edit-experience', compact('experience'));
	}

	// Education methods
	public function education()
	{
		$educations = auth()->user()->educations()->ordered()->paginate(10);
		return view('users.education', compact('educations'));
	}

	public function educationAdd()
	{
		return view('users.add-education');
	}

	public function educationEdit($id)
	{
		$education = auth()->user()->educations()->findOrFail($id);
		return view('users.edit-education', compact('education'));
	}

	// Certifications methods
	public function certifications()
	{
		$certifications = auth()->user()->certifications()->ordered()->paginate(10);
		return view('users.certifications', compact('certifications'));
	}

	public function certificationAdd()
	{
		return view('users.add-certification');
	}

	public function certificationEdit($id)
	{
		$certification = auth()->user()->certifications()->findOrFail($id);
		return view('users.edit-certification', compact('certification'));
	}

	public function educationStore(Request $request)
	{
		$rules = [
			'institution_name' => 'required|string|max:200',
			'degree' => 'required|string|max:200',
			'field_of_study' => 'nullable|string|max:200',
			'education_level' => 'required|in:high_school,associate,bachelor,master,doctorate,diploma,certificate,professional',
			'start_date' => 'required|date',
			'end_date' => 'nullable|date|after:start_date',
			'is_current' => 'boolean',
			'grade' => 'nullable|string|max:50',
			'description' => 'nullable|string|max:2000',
			'activities' => 'nullable|string|max:1000',
			'location' => 'nullable|string|max:200',
			'website' => 'nullable|url|max:255',
			'logo' => 'nullable|mimes:png,jpg,jpeg|max:1024',
			'status' => 'required|in:active,inactive'
		];

		$request->validate($rules);

		$data = $request->except('logo');

		// Handle logo upload
		if ($request->hasFile('logo')) {
			$extension = $request->file('logo')->getClientOriginalExtension();
			$filename = 'education_logo_' . auth()->id() . '_' . time() . '.' . $extension;
			$path = public_path('portfolio_assets/');

			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}

			if ($request->file('logo')->move($path, $filename)) {
				$data['logo'] = $filename;
			}
		}

		// If currently studying, set end_date to null
		if ($request->is_current) {
			$data['end_date'] = null;
		}

		auth()->user()->educations()->create($data);

		return redirect()->back()->withSuccessMessage(__('misc.success_education_added'));
	}

	public function educationUpdate(Request $request)
	{
		$rules = [
			'id' => 'required|exists:user_educations,id',
			'institution_name' => 'required|string|max:200',
			'degree' => 'required|string|max:200',
			'field_of_study' => 'nullable|string|max:200',
			'education_level' => 'required|in:high_school,associate,bachelor,master,doctorate,diploma,certificate,professional',
			'start_date' => 'required|date',
			'end_date' => 'nullable|date|after:start_date',
			'is_current' => 'boolean',
			'grade' => 'nullable|string|max:50',
			'description' => 'nullable|string|max:2000',
			'activities' => 'nullable|string|max:1000',
			'location' => 'nullable|string|max:200',
			'website' => 'nullable|url|max:255',
			'logo' => 'nullable|mimes:png,jpg,jpeg|max:1024',
			'status' => 'required|in:active,inactive'
		];

		$request->validate($rules);

		$education = auth()->user()->educations()->findOrFail($request->id);
		$data = $request->except(['logo', 'id']);

		// Handle logo upload
		if ($request->hasFile('logo')) {
			$extension = $request->file('logo')->getClientOriginalExtension();
			$filename = 'education_logo_' . auth()->id() . '_' . time() . '.' . $extension;
			$path = public_path('portfolio_assets/');

			if (!file_exists($path)) {
				mkdir($path, 0755, true);
			}

			if ($request->file('logo')->move($path, $filename)) {
				// Delete old logo
				if ($education->logo && file_exists($path . $education->logo)) {
					unlink($path . $education->logo);
				}
				$data['logo'] = $filename;
			}
		}

		// If currently studying, set end_date to null
		if ($request->is_current) {
			$data['end_date'] = null;
		}

		$education->update($data);

		return redirect()->back()->withSuccessMessage(__('misc.success_education_updated'));
	}

	public function educationDestroy($id)
	{
		$education = auth()->user()->educations()->findOrFail($id);

		// Delete logo if exists
		if ($education->logo) {
			$path = public_path('portfolio_assets/' . $education->logo);
			if (file_exists($path)) {
				unlink($path);
			}
		}

		$education->delete();

		return redirect()->back()->withSuccessMessage(__('misc.success_education_deleted'));
	}

	// Certification methods
	public function certificationStore(Request $request)
	{
		$rules = [
			'name' => 'required|string|max:200',
			'issuing_organization' => 'required|string|max:200',
			'issue_date' => 'required|date',
			'expiry_date' => 'nullable|date|after:issue_date',
			'does_not_expire' => 'boolean',
			'credential_id' => 'nullable|string|max:100',
			'credential_url' => 'nullable|url|max:255',
			'description' => 'nullable|string|max:2000',
			'skills_gained' => 'nullable|string|max:500',
			'certificate_image' => 'nullable|mimes:png,jpg,jpeg,pdf|max:2048',
			'organization_logo' => 'nullable|mimes:png,jpg,jpeg|max:1024',
			'status' => 'required|in:active,inactive'
		];

		$request->validate($rules);

		$data = $request->except(['certificate_image', 'organization_logo']);

		$path = public_path('portfolio_assets/');
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}

		// Handle certificate image upload
		if ($request->hasFile('certificate_image')) {
			$extension = $request->file('certificate_image')->getClientOriginalExtension();
			$filename = 'certificate_' . auth()->id() . '_' . time() . '.' . $extension;

			if ($request->file('certificate_image')->move($path, $filename)) {
				$data['certificate_image'] = $filename;
			}
		}

		// Handle organization logo upload
		if ($request->hasFile('organization_logo')) {
			$extension = $request->file('organization_logo')->getClientOriginalExtension();
			$filename = 'cert_org_logo_' . auth()->id() . '_' . time() . '.' . $extension;

			if ($request->file('organization_logo')->move($path, $filename)) {
				$data['organization_logo'] = $filename;
			}
		}

		// If doesn't expire, set expiry_date to null
		if ($request->does_not_expire) {
			$data['expiry_date'] = null;
		}

		auth()->user()->certifications()->create($data);

		return redirect()->back()->withSuccessMessage(__('misc.success_certification_added'));
	}

	public function certificationUpdate(Request $request)
	{
		$rules = [
			'id' => 'required|exists:user_certifications,id',
			'name' => 'required|string|max:200',
			'issuing_organization' => 'required|string|max:200',
			'issue_date' => 'required|date',
			'expiry_date' => 'nullable|date|after:issue_date',
			'does_not_expire' => 'boolean',
			'credential_id' => 'nullable|string|max:100',
			'credential_url' => 'nullable|url|max:255',
			'description' => 'nullable|string|max:2000',
			'skills_gained' => 'nullable|string|max:500',
			'certificate_image' => 'nullable|mimes:png,jpg,jpeg,pdf|max:2048',
			'organization_logo' => 'nullable|mimes:png,jpg,jpeg|max:1024',
			'status' => 'required|in:active,inactive'
		];

		$request->validate($rules);

		$certification = auth()->user()->certifications()->findOrFail($request->id);
		$data = $request->except(['certificate_image', 'organization_logo', 'id']);

		$path = public_path('portfolio_assets/');

		// Handle certificate image upload
		if ($request->hasFile('certificate_image')) {
			$extension = $request->file('certificate_image')->getClientOriginalExtension();
			$filename = 'certificate_' . auth()->id() . '_' . time() . '.' . $extension;

			if ($request->file('certificate_image')->move($path, $filename)) {
				// Delete old certificate image
				if ($certification->certificate_image && file_exists($path . $certification->certificate_image)) {
					unlink($path . $certification->certificate_image);
				}
				$data['certificate_image'] = $filename;
			}
		}

		// Handle organization logo upload
		if ($request->hasFile('organization_logo')) {
			$extension = $request->file('organization_logo')->getClientOriginalExtension();
			$filename = 'cert_org_logo_' . auth()->id() . '_' . time() . '.' . $extension;

			if ($request->file('organization_logo')->move($path, $filename)) {
				// Delete old organization logo
				if ($certification->organization_logo && file_exists($path . $certification->organization_logo)) {
					unlink($path . $certification->organization_logo);
				}
				$data['organization_logo'] = $filename;
			}
		}

		// If doesn't expire, set expiry_date to null
		if ($request->does_not_expire) {
			$data['expiry_date'] = null;
		}

		$certification->update($data);

		return redirect()->back()->withSuccessMessage(__('misc.success_certification_updated'));
	}

	public function certificationDestroy($id)
	{
		$certification = auth()->user()->certifications()->findOrFail($id);

		$path = public_path('portfolio_assets/');

		// Delete certificate image if exists
		if ($certification->certificate_image && file_exists($path . $certification->certificate_image)) {
			unlink($path . $certification->certificate_image);
		}

		// Delete organization logo if exists
		if ($certification->organization_logo && file_exists($path . $certification->organization_logo)) {
			unlink($path . $certification->organization_logo);
		}

		$certification->delete();

		return redirect()->back()->withSuccessMessage(__('misc.success_certification_deleted'));
	}

	// Professional Projects methods
	public function projects()
	{
		$projects = auth()->user()->projects()->orderBy('created_at', 'desc')->paginate(10);
		return view('users.projects', compact('projects'));
	}

	public function projectsCreate()
	{
		return view('users.add-project');
	}

	public function projectsEdit($id)
	{
		$project = auth()->user()->projects()->findOrFail($id);
		return view('users.edit-project', compact('project'));
	}

	public function projectsStore(Request $request)
	{
		$rules = [
			'project_name' => 'required|string|max:255',
			'description' => 'nullable|string|max:2000',
			'project_type' => 'required|in:personal,professional,open_source,freelance,startup,academic,other',
			'status' => 'required|in:planning,in_progress,completed,on_hold,cancelled',
			'start_date' => 'nullable|date',
			'end_date' => 'nullable|date|after_or_equal:start_date',
			'project_url' => 'nullable|url|max:500',
			'github_url' => 'nullable|url|max:500',
			'demo_url' => 'nullable|url|max:500',
			'client_name' => 'nullable|string|max:255',
			'role' => 'nullable|string|max:255',
			'team_size' => 'nullable|integer|min:1|max:1000',
			'key_features' => 'nullable|string|max:1000',
			'challenges_solved' => 'nullable|string|max:1000',
			'technologies' => 'nullable|string|max:500',
			'visibility' => 'required|in:public,private',
			'featured' => 'nullable|boolean',
			'project_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
		];

		$request->validate($rules);

		$projectData = $request->only([
			'project_name', 'description', 'project_type', 'status', 'start_date', 'end_date',
			'project_url', 'github_url', 'demo_url', 'client_name', 'role', 'team_size',
			'key_features', 'challenges_solved', 'visibility'
		]);

		$projectData['user_id'] = auth()->id();
		$projectData['featured'] = $request->has('featured') ? true : false;

		// Handle technologies
		if ($request->technologies) {
			$technologies = array_map('trim', explode(',', $request->technologies));
			$projectData['technologies'] = json_encode(array_filter($technologies));
		}

		// Handle image uploads
		$uploadedImages = [];
		if ($request->hasFile('project_images')) {
			foreach ($request->file('project_images') as $file) {
				if ($file->isValid()) {
					$filename = 'project_' . uniqid() . '.' . $file->getClientOriginalExtension();
					$file->move(public_path('portfolio_assets'), $filename);
					$uploadedImages[] = $filename;
				}
			}
		}

		if (!empty($uploadedImages)) {
			$projectData['project_images'] = json_encode($uploadedImages);
		}

		auth()->user()->projects()->create($projectData);

		return redirect()->route('user.projects')->withSuccessMessage(__('misc.success_project_added'));
	}

	public function projectsUpdate(Request $request)
	{
		$rules = [
			'id' => 'required|exists:user_projects,id',
			'project_name' => 'required|string|max:255',
			'description' => 'nullable|string|max:2000',
			'project_type' => 'required|in:personal,professional,open_source,freelance,startup,academic,other',
			'status' => 'required|in:planning,in_progress,completed,on_hold,cancelled',
			'start_date' => 'nullable|date',
			'end_date' => 'nullable|date|after_or_equal:start_date',
			'project_url' => 'nullable|url|max:500',
			'github_url' => 'nullable|url|max:500',
			'demo_url' => 'nullable|url|max:500',
			'client_name' => 'nullable|string|max:255',
			'role' => 'nullable|string|max:255',
			'team_size' => 'nullable|integer|min:1|max:1000',
			'key_features' => 'nullable|string|max:1000',
			'challenges_solved' => 'nullable|string|max:1000',
			'technologies' => 'nullable|string|max:500',
			'visibility' => 'required|in:public,private',
			'featured' => 'nullable|boolean',
			'project_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
		];

		$request->validate($rules);

		$project = auth()->user()->projects()->findOrFail($request->id);

		$projectData = $request->only([
			'project_name', 'description', 'project_type', 'status', 'start_date', 'end_date',
			'project_url', 'github_url', 'demo_url', 'client_name', 'role', 'team_size',
			'key_features', 'challenges_solved', 'visibility'
		]);

		$projectData['featured'] = $request->has('featured') ? true : false;

		// Handle technologies
		if ($request->technologies) {
			$technologies = array_map('trim', explode(',', $request->technologies));
			$projectData['technologies'] = json_encode(array_filter($technologies));
		}

		// Handle image deletions and uploads
		$currentImages = $project->project_images_list ?? [];

		// Handle image deletions
		if ($request->has('delete_images') && !empty($request->delete_images)) {
			$imagesToDelete = $request->delete_images;
			foreach ($imagesToDelete as $imageToDelete) {
				// Remove from file system
				$path = public_path('portfolio_assets/' . $imageToDelete);
				if (file_exists($path)) {
					unlink($path);
				}
				// Remove from current images array using array_diff
				$currentImages = array_values(array_diff($currentImages, [$imageToDelete]));
			}
		}

		// Handle new image uploads
		if ($request->hasFile('project_images')) {
			foreach ($request->file('project_images') as $file) {
				if ($file->isValid()) {
					$filename = 'project_' . uniqid() . '.' . $file->getClientOriginalExtension();
					$file->move(public_path('portfolio_assets'), $filename);
					$currentImages[] = $filename;
				}
			}
		}

		// Only update project_images if we have any changes (deletions or new uploads)
		if ($request->has('delete_images') || $request->hasFile('project_images')) {
			$projectData['project_images'] = !empty($currentImages) ? $currentImages : null;
		}

		$project->update($projectData);

		return redirect()->route('user.projects')->withSuccessMessage(__('misc.success_project_updated'));
	}

	public function projectsDestroy($id)
	{
		$project = auth()->user()->projects()->findOrFail($id);

		// Delete project images if exist
		$images = $project->project_images_list;
		foreach ($images as $image) {
			$path = public_path('portfolio_assets/' . $image);
			if (file_exists($path)) {
				unlink($path);
			}
		}

		$project->delete();

		return redirect()->back()->withSuccessMessage(__('misc.success_project_deleted'));
	}

	// Testimonials methods
	public function testimonials()
	{
		$testimonials = auth()->user()->testimonials()->orderBy('created_at', 'desc')->paginate(10);
		return view('users.testimonials', compact('testimonials'));
	}

	public function testimonialStore(Request $request)
	{
		$request->validate([
			'client_name' => 'required|string|max:255',
			'client_position' => 'nullable|string|max:255',
			'company_name' => 'nullable|string|max:255',
			'client_website' => 'nullable|url',
			'client_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
			'testimonial_text' => 'required|string',
			'rating' => 'nullable|integer|between:1,5',
			'date_received' => 'nullable|date',
			'project_type' => 'nullable|string|max:255',
			'project_details' => 'nullable|string',
			'is_featured' => 'nullable|boolean',
			'status' => 'required|in:active,inactive'
		]);

		$testimonial = new \App\Models\Testimonial();
		$testimonial->user_id = auth()->id();
		$testimonial->client_name = $request->client_name;
		$testimonial->client_position = $request->client_position;
		$testimonial->company_name = $request->company_name;
		$testimonial->client_website = $request->client_website;
		$testimonial->testimonial_text = $request->testimonial_text;
		$testimonial->rating = $request->rating;
		$testimonial->date_received = $request->date_received;
		$testimonial->project_type = $request->project_type;
		$testimonial->project_details = $request->project_details;
		$testimonial->is_featured = $request->has('is_featured') ? 1 : 0;
		$testimonial->status = $request->status;

		// Handle client photo upload
		if ($request->hasFile('client_photo')) {
			$file = $request->file('client_photo');
			$extension = $file->getClientOriginalExtension();
			$filename = 'client_' . uniqid() . '.' . $extension;
			$file->move(public_path('portfolio_assets'), $filename);
			$testimonial->client_photo = $filename;
		}

		$testimonial->save();

		return redirect()->route('user.testimonials')->withSuccessMessage(__('misc.success_testimonial_added'));
	}

	public function testimonialUpdate(Request $request)
	{
		$request->validate([
			'id' => 'required|exists:testimonials,id',
			'client_name' => 'required|string|max:255',
			'client_position' => 'nullable|string|max:255',
			'company_name' => 'nullable|string|max:255',
			'client_website' => 'nullable|url',
			'client_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
			'testimonial_text' => 'required|string',
			'rating' => 'nullable|integer|between:1,5',
			'date_received' => 'nullable|date',
			'project_type' => 'nullable|string|max:255',
			'project_details' => 'nullable|string',
			'is_featured' => 'nullable|boolean',
			'status' => 'required|in:active,inactive'
		]);

		$testimonial = auth()->user()->testimonials()->findOrFail($request->id);
		$testimonial->client_name = $request->client_name;
		$testimonial->client_position = $request->client_position;
		$testimonial->company_name = $request->company_name;
		$testimonial->client_website = $request->client_website;
		$testimonial->testimonial_text = $request->testimonial_text;
		$testimonial->rating = $request->rating;
		$testimonial->date_received = $request->date_received;
		$testimonial->project_type = $request->project_type;
		$testimonial->project_details = $request->project_details;
		$testimonial->is_featured = $request->has('is_featured') ? 1 : 0;
		$testimonial->status = $request->status;

		// Handle client photo upload
		if ($request->hasFile('client_photo')) {
			// Delete old photo if exists
			if ($testimonial->client_photo && file_exists(public_path('portfolio_assets/' . $testimonial->client_photo))) {
				unlink(public_path('portfolio_assets/' . $testimonial->client_photo));
			}

			$file = $request->file('client_photo');
			$extension = $file->getClientOriginalExtension();
			$filename = 'client_' . uniqid() . '.' . $extension;
			$file->move(public_path('portfolio_assets'), $filename);
			$testimonial->client_photo = $filename;
		}

		$testimonial->save();

		return redirect()->route('user.testimonials')->withSuccessMessage(__('misc.success_testimonial_updated'));
	}

	public function testimonialDestroy($id)
	{
		$testimonial = auth()->user()->testimonials()->findOrFail($id);

		// Delete client photo if exists
		if ($testimonial->client_photo && file_exists(public_path('portfolio_assets/' . $testimonial->client_photo))) {
			unlink(public_path('portfolio_assets/' . $testimonial->client_photo));
		}

		$testimonial->delete();

		return redirect()->back()->withSuccessMessage(__('misc.success_testimonial_deleted'));
	}

	public function testimonialAdd()
	{
		return view('users.add-testimonial');
	}

	public function testimonialEdit($id)
	{
		$testimonial = auth()->user()->testimonials()->findOrFail($id);
		return view('users.edit-testimonial', compact('testimonial'));
	}

	// Custom Sections Methods
	public function customSections()
	{
		$customSections = auth()->user()->customSections()->orderBy('order_position', 'ASC')->paginate(10);
		return view('users.custom-sections', compact('customSections'));
	}

	public function customSectionAdd()
	{
		return view('users.add-custom-section');
	}

	public function customSectionEdit($id)
	{
		$customSection = auth()->user()->customSections()->findOrFail($id);
		return view('users.edit-custom-section', compact('customSection'));
	}

	public function customSectionStore(Request $request)
	{
		$request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'order_position' => 'nullable|integer|min:0',
			'icon' => 'nullable|string|max:100',
			'link_url' => 'nullable|url|max:500',
			'link_text' => 'nullable|string|max:100',
			'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'status' => 'required|in:active,inactive'
		]);

		$data = $request->only(['title', 'content', 'order_position', 'icon', 'link_url', 'link_text', 'status']);
		$data['user_id'] = auth()->id();

		// Handle image upload
		if ($request->hasFile('image')) {
			$file = $request->file('image');
			$fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

			// Create directory if it doesn't exist
			$uploadPath = public_path('portfolio_assets/');
			if (!file_exists($uploadPath)) {
				mkdir($uploadPath, 0777, true);
			}

			$file->move(public_path('portfolio_assets'), $fileName);
			$data['image'] = $fileName;
		}

		auth()->user()->customSections()->create($data);

		return redirect()->route('user.customSections')->with('success_message', __('misc.success_custom_section_added'));
	}

	public function customSectionUpdate(Request $request, $id)
	{
		$customSection = auth()->user()->customSections()->findOrFail($id);

		$request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'order_position' => 'nullable|integer|min:0',
			'icon' => 'nullable|string|max:100',
			'link_url' => 'nullable|url|max:500',
			'link_text' => 'nullable|string|max:100',
			'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'status' => 'required|in:active,inactive'
		]);

		$data = $request->only(['title', 'content', 'order_position', 'icon', 'link_url', 'link_text', 'status']);

		// Handle image upload
		if ($request->hasFile('image')) {
			// Delete old image if exists
			if ($customSection->image && file_exists(public_path('portfolio_assets/' . $customSection->image))) {
				unlink(public_path('portfolio_assets/' . $customSection->image));
			}

			$file = $request->file('image');
			$fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

			// Create directory if it doesn't exist
			$uploadPath = public_path('portfolio_assets/');
			if (!file_exists($uploadPath)) {
				mkdir($uploadPath, 0777, true);
			}

			$file->move(public_path('portfolio_assets'), $fileName);
			$data['image'] = $fileName;
		}		$customSection->update($data);

		return redirect()->route('user.customSections')->with('success_message', __('misc.success_custom_section_updated'));
	}

	private function calculateExperienceYears($experiences)
	{
		$totalMonths = 0;

		foreach ($experiences as $experience) {
			if ($experience->start_date) {
				$startDate = \Carbon\Carbon::parse($experience->start_date);
				$endDate = $experience->end_date ? \Carbon\Carbon::parse($experience->end_date) : \Carbon\Carbon::now();
				$totalMonths += $startDate->diffInMonths($endDate);
			}
		}

		return round($totalMonths / 12, 1); // Convert to years with 1 decimal place
	}

	private function calculateTotalSections($skills, $experiences, $educations, $certifications, $projects, $testimonials, $customSections)
	{
		$sectionCount = 0;

		if ($skills->where('status', 'active')->count() > 0) $sectionCount++;
		if ($experiences->where('status', 'active')->count() > 0) $sectionCount++;
		if ($educations->where('status', 'active')->count() > 0) $sectionCount++;
		if ($certifications->where('status', 'active')->count() > 0) $sectionCount++;
		if ($projects->count() > 0) $sectionCount++;
		if ($testimonials->count() > 0) $sectionCount++;
		if ($customSections->count() > 0) $sectionCount++;

		return $sectionCount;
	}

	public function customSectionDestroy($id)
	{
		$customSection = auth()->user()->customSections()->findOrFail($id);

		// Delete image if exists
		if ($customSection->image && file_exists(public_path('portfolio_assets/' . $customSection->image))) {
			unlink(public_path('portfolio_assets/' . $customSection->image));
		}

		$customSection->delete();

		return redirect()->back()->with('success_message', __('misc.success_custom_section_deleted'));
	}
}
