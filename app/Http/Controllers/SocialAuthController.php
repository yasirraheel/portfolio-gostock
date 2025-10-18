<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\SocialAccountService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    // redirect function
    public function redirect($provider){
      // Log configured redirect and the actual URL Socialite will send to
      $configured = config('services.' . $provider . '.redirect');
      $driver = Socialite::driver($provider);
      $response = $driver->redirect();
      // Try to get the target URL from the RedirectResponse if available
      $target = method_exists($response, 'getTargetUrl') ? $response->getTargetUrl() : null;
      Log::info('OAuth redirect initiated', [
        'provider' => $provider,
        'configured_redirect' => $configured,
        'target_url' => $target,
        'app_url_env' => env('APP_URL')
      ]);

      return $response;
    }
    // callback function
    public function callback(SocialAccountService $service ,Request $request, $provider){
      
      // Debug logging
      \Log::info('OAuth Callback Hit', [
        'provider' => $provider,
        'request_data' => $request->all(),
        'user_agent' => $request->userAgent()
      ]);

      try {
          $user = $service->createOrGetUser(Socialite::driver($provider)->user(), $provider);

          // Return Error missing Email User or other errors
          if( !$user || !isset($user->id) ) {
            return redirect('login')->with(['login_required' => 'Authentication failed. Please ensure your Google account has an email address and try again.']);
          } else {
            auth()->login($user);
            
            // Check if user is successfully logged in
            if (auth()->check()) {
              return redirect()->intended('/')->with(['success' => 'Successfully logged in with Google!']);
            } else {
              return redirect('login')->with(['login_required' => 'Login failed. Please try again.']);
            }
          }

      } catch (\Exception $e) {
           return redirect('login')->with(['login_required' => 'Authentication error: '.$e->getMessage() ]);
      }
    }// End callback

}//<-- End Class
