<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
      return Socialite::driver($provider)->redirect();
    }

    public function callback($provider) 
    {
      $socialUser = Socialite::driver($provider)->user();
      
      if ($provider == 'github') {
        $name = $socialUser->nickname;
      }elseif ($provider == 'google') {
        $name = $socialUser->name;
      }
      
      $user = User::updateOrCreate([
          'provider_id' => $socialUser->id,
          'provider' => $provider
      ], [
          'name' => $name,
          'email' => $socialUser->email,
          'provider_token' => $socialUser->token,
      ]);
  
      Auth::login($user);
  
      return redirect('/dashboard');
    }
}
