<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserProvider;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSocial;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function social(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $githubUsername = UserSocial::getGithubUsername($socialUser->getName());

            $user = User::firstOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'first_name' => $provider === UserProvider::GITHUB ? $githubUsername[0] : $socialUser->user['given_name'],
                'last_name' => $provider === UserProvider::GITHUB ? $githubUsername[1] : $socialUser->user['family_name'],
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
            ]);

            $user->socials()->updateOrCreate([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ], [
                'provider_token' => $socialUser->token,
            ]);

            $user->addAvatarMedia($socialUser->getAvatar());

            Auth::login($user);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return redirect(RouteServiceProvider::HOME);
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
