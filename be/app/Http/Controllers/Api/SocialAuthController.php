<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        return $this->handleSocialLogin($googleUser, 'google');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();
        return $this->handleSocialLogin($facebookUser, 'facebook');
    }

    private function handleSocialLogin($socialUser, $provider)
    {
        $user = User::where('Email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'Name'     => $socialUser->getName(),
                'Email'    => $socialUser->getEmail(),
                'Password' => bcrypt(Str::random(10)), // tạo mật khẩu ngẫu nhiên
            ]);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        // Redirect về frontend React kèm token và user_id
        $frontendUrl = "http://localhost:3000/social-login-success"; // <-- sửa thành domain FE nếu cần
        return redirect()->away("$frontendUrl?token={$token}&user_id={$user->id}");
    }
}
