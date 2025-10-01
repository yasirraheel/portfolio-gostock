<?php

namespace App\Http\Controllers;

use DB;
use Lang;
use Mail;
use App\Models\User;
use App\Models\Plans;
use App\Models\Query;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      // Check Datebase access
      AdminSettings::select('id')->first();
    } catch (\Exception $e) {
      // Redirect to Installer
      return redirect('installer/script');
    }

    $categories = Categories::select(['name', 'slug', 'thumbnail'])->where('mode', 'on')->orderBy('name')->simplePaginate(4);
    $images     = Query::latestImagesHome();
    $featured   = in_array(config('settings.show_images_index'), ['featured', 'both']) ? Query::featuredImages() : null;

    // Simplified for universal starter kit - just get top categories without image count
    $popularCategories = Categories::where('mode', 'on')->take(5)->get();

    if ($popularCategories->count() != 0) {
      foreach ($popularCategories as $popularCategorie) {
        $categoryName = Lang::has('categories.' . $popularCategorie->slug) ? __('categories.' . $popularCategorie->slug) : $popularCategorie->name;

        $popularCategorieArray[]  = '<a style="color:#FFF;" href="' . url('category', $popularCategorie->slug) . '">' . $categoryName . '</a>';
      }
      $categoryPopular = implode(', ', $popularCategorieArray);
    } else {
      $categoryPopular = false;
    }

    return view(
      'index.home',
      [
        'categories' => $categories,
        'images' => $images,
        'featured' => $featured,
        'categoryPopular' => $categoryPopular
      ]
    );
  }

  public function portfolios()
  {
    $settings = AdminSettings::first();

    // Get all public portfolios with pagination
    $portfolios = \App\Models\User::where('status', 'active')
        ->whereNotNull('portfolio_slug')
        ->where('portfolio_slug', '!=', '')
        ->where('portfolio_private', 0)
        ->select(['id', 'name', 'username', 'avatar', 'profession', 'bio', 'portfolio_slug', 'countries_id', 'date'])
        ->with(['country:id,country_name'])
        ->orderBy('date', 'desc')
        ->paginate(12);

    return view('index.portfolios', compact('settings', 'portfolios'));
  }

  public function getVerifyAccount($confirmation_code)
  {
    if (
      Auth::guest()
      || Auth::check()
      && Auth::user()->activation_code == $confirmation_code
      && Auth::user()->status == 'pending'
    ) {
      $user = User::where('activation_code', $confirmation_code)->where('status', 'pending')->first();

      if ($user) {

        $update = User::where('activation_code', $confirmation_code)
          ->where('status', 'pending')
          ->update(array('status' => 'active', 'activation_code' => ''));


        Auth::loginUsingId($user->id);

        return redirect('/')
          ->with([
            'success_verify' => true,
          ]);
      } else {
        return redirect('/')
          ->with([
            'error_verify' => true,
          ]);
      }
    } else {
      return redirect('/');
    }
  }

  public function getSearch()
  {
    $q = request()->get('q');
    $images = Query::searchImages();

    //<--- * If $q is empty or is minus to 1 * ---->
    if ($q == '' || strlen($q) <= 2) {
      return redirect('/latest');
    }

    if (request()->ajax()) {
      return view('includes.images')->with($images)->render();
    }

    return view('default.search')->with($images);
  }

  public function members()
  {
    $users = Query::users();

    if (request()->ajax()) {
      return view('includes.users')->withUsers($users)->render();
    }

    return view('default.members')->withUsers($users);
  }

  public function premium()
  {
    if (config('settings.sell_option') == 'off') {
      abort(404);
    }

    $images = Query::premiumImages();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.premium'),
      'description' => __('misc.premium_desc'),
    ]);
  }

  public function latest()
  {
    $images = Query::latestImages();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.latest'),
      'description' => __('misc.latest_desc'),
    ]);
  }

  public function featured()
  {
    $images = Query::featuredImages();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.featured'),
      'description' => __('misc.featured_desc'),
    ]);
  }


  public function popular()
  {
    $images = Query::popularImages();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.popular'),
      'description' => __('misc.popular_desc'),
    ]);
  }

  public function commented()
  {
    $images = Query::commentedImages();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.most_commented'),
      'description' => __('misc.most_commented_desc'),
    ]);
  }

  public function viewed()
  {
    $images = Query::viewedImages();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.most_viewed'),
      'description' => __('misc.most_viewed_desc'),
    ]);
  }

  public function downloads()
  {
    $images = Query::downloadsImages();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.most_downloads'),
      'description' => __('misc.most_downloads_desc'),
    ]);
  }

  public function categories()
  {
    $categories = Categories::whereMode('on')->orderBy('name')->get();
    return view('default.categories')->withCategories($categories);
  }

  public function category($slug)
  {
    $images = Query::categoryImages($slug);

    if (request()->ajax()) {
      return view('includes.images')->with($images)->render();
    }

    return view('default.category')->with($images);
  }

  public function subcategory($slug, $subcategory)
  {
    $images = Query::subCategoryImages($slug, $subcategory);

    if (request()->ajax()) {
      return view('includes.images')->with($images)->render();
    }

    return view('default.subcategory')->with($images);
  }

  public function cameras($slug)
  {
    if (strlen($slug) > 3) {
      $images = Query::camerasImages($slug);

      if (request()->ajax()) {
        return view('includes.images')->with($images)->render();
      }

      return view('default.cameras')->with($images);
    } else {
      abort('404');
    }
  }

  public function colors($slug)
  {
    if (strlen($slug) == 6) {
      $images = Query::colorsImages($slug);

      if (request()->ajax()) {
        return view('includes.images')->with($images)->render();
      }

      return view('default.colors')->with($images);
    } else {
      abort('404');
    }
  }

  public function collections(Request $request)
  {
    $title = __('misc.collections') . ' - ';

    $data = Collections::has('collectionImages')
      ->where('type', 'public')
      ->orderBy('id', 'desc')
      ->with(['collectionImages' => function($q) {
        $q->with(['stockCollection', 'images']);
      } , 'creator'])
      ->paginate(config('settings.result_request'));

    if ($request->input('page') > $data->lastPage()) {
      abort('404');
    }

    if (request()->ajax()) {
      return view('includes.collections-grid', ['data' => $data])->render();
    }

    return view('default.collections', ['title' => $title, 'data' => $data]);
  } //<--- End Method

  public function contact()
  {
    $settings = AdminSettings::first();
    return view('default.contact', compact('settings'));
  }

  public function contactStore(Request $request)
  {
    $input = $request->all();
    $settings = AdminSettings::first();
    $input['_captcha'] = $settings->captcha;

    $errorMessages = [
      'g-recaptcha-response.required_if' => 'reCAPTCHA Error',
      'g-recaptcha-response.captcha' => 'reCAPTCHA Error',
    ];

    $validator = Validator::make($input, [
      'full_name' => 'min:3|max:25',
      'email'     => 'required|email',
      'subject'     => 'required',
      'message' => 'min:10|required',
      'g-recaptcha-response' => 'required_if:_captcha,==,on|captcha'
    ], $errorMessages);

    if ($validator->fails()) {
      return redirect('contact')
        ->withInput()->withErrors($validator);
    }

    // Get portfolio user if portfolio_slug is provided
    $portfolioUser = null;
    $isHireInquiry = isset($input['hire_inquiry']) && $input['hire_inquiry'];
    if (isset($input['portfolio_slug']) && $input['portfolio_slug']) {
      $portfolioUser = User::where('portfolio_slug', $input['portfolio_slug'])
                          ->orWhere('username', $input['portfolio_slug'])
                          ->first();
    }

    // SEND EMAIL TO ADMIN
    $fullname    = $input['full_name'];
    $email_user  = $input['email'];
    $title_site  = config('settings.title');
    $subject     = $input['subject'];
    $email_reply = config('settings.email_admin');
    $smtp_from_email = config('mail.from.address'); // Use the authenticated SMTP email

    $emailData = array(
      'full_name' => $input['full_name'],
      'email' => $input['email'],
      'subject' => $input['subject'],
      '_message' => $input['message'],
      'ip' => request()->ip(),
      'portfolio_user' => $portfolioUser,
      'is_portfolio_contact' => $portfolioUser ? true : false,
      'is_hire_inquiry' => $isHireInquiry
    );

    // Send email to admin
    Mail::send(
      'emails.contact-email',
      $emailData,
      function ($message) use (
        $fullname,
        $email_user,
        $title_site,
        $email_reply,
        $subject,
        $portfolioUser,
        $smtp_from_email,
        $isHireInquiry
      ) {
        $message->from($smtp_from_email, $fullname);
        $emailSubject = __('misc.message') . ' - ' . $subject . ' - ' . $email_user;
        if ($portfolioUser) {
          if ($isHireInquiry) {
            $emailSubject = 'Hire Inquiry: ' . $portfolioUser->name . ' - ' . $subject . ' - ' . $email_user;
          } else {
            $emailSubject = 'Portfolio Contact: ' . $portfolioUser->name . ' - ' . $subject . ' - ' . $email_user;
          }
        }
        $message->subject($emailSubject);
        $message->to($email_reply, $title_site);
        $message->replyTo($email_user);
      }
    );

    // Send email to portfolio owner if portfolio contact
    if ($portfolioUser && $portfolioUser->email) {
      Mail::send(
        'emails.contact-email',
        $emailData,
        function ($message) use (
          $fullname,
          $email_user,
          $subject,
          $portfolioUser,
          $smtp_from_email,
          $isHireInquiry
        ) {
          $message->from($smtp_from_email, $fullname);
          if ($isHireInquiry) {
            $message->subject('New Hire Inquiry from Your Portfolio - ' . $subject);
          } else {
            $message->subject('New Contact from Your Portfolio - ' . $subject);
          }
          $message->to($portfolioUser->email, $portfolioUser->name);
          $message->replyTo($email_user);
        }
      );
    }

    return redirect('contact')->with(['notification' => __('misc.send_contact_success')]);
  }

  public function pricing()
  {
    $plans = Plans::whereStatus('1');

    if ($plans->count() == 0 || config('settings.sell_option') == 'off') {
      abort(404);
    }

    return view('default.pricing')->with([
      'plans' => $plans,
      'getSubscription' => auth()->check() ? auth()->user()->getSubscription() : null
    ]);
  }

  public function tags()
  {
    //abort(404);
    $data = Images::select(DB::raw('GROUP_CONCAT(tags SEPARATOR ",") as tags'))->where('status', 'active')->get();
    return view('default.tags')->withData($data);
  }

  public function tagsShow($slug)
  {
    $slug = str_replace('_', ' ', $slug);

    if (strlen($slug) > 1) {
      $images = Query::tagsImages($slug);

      if (request()->ajax()) {
        return view('includes.images')->with($images)->render();
      }

      return view('default.tags-show')->with($images);
    } else {
      abort('404');
    }
  }

  public function vectors()
  {
    $images = Query::vectors();

    if (request()->ajax()) {
      return view('includes.images', ['images' => $images])->render();
    }

    return view('index.explore', [
      'images' => $images,
      'title' => __('misc.vectors'),
      'description' => __('misc.vectors_desc'),
    ]);
  }

  /**
   * Generate dynamic favicon with user initials
   */
  public function generateFavicon($slug)
  {
    try {
      // Find user by portfolio slug
      $user = User::where('portfolio_slug', $slug)
                  ->where('status', 'active')
                  ->first();

      if (!$user) {
        abort(404);
      }

      // Get user initials
      $initials = strtoupper(substr($user->name, 0, 2));
      
      // Get user's primary color or default
      $primaryColor = $user->portfolio_primary_color ?? '#268707';
      
      // Create SVG favicon
      $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
  <rect width="32" height="32" rx="6" fill="' . $primaryColor . '"/>
  <text x="16" y="22" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="white">' . $initials . '</text>
</svg>';

      return response($svg)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour

    } catch (\Exception $e) {
      // Return a default favicon if something goes wrong
      $defaultSvg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
  <rect width="32" height="32" rx="6" fill="#268707"/>
  <text x="16" y="22" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="white">P</text>
</svg>';

      return response($defaultSvg)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'public, max-age=3600');
    }
  }
}
