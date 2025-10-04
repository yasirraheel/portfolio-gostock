<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\SocialAccountService;
use Socialite; // socialite namespace

class SocialAuthController extends Controller
{
    // redirect function
    public function redirect($provider){
      return Socialite::driver($provider)->redirect();
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
