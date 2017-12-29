<?php

namespace App\Http\Controllers;
use App\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;

class AdminController extends Controller
{
    /** Redirect to G+ authenticate.
     * @return mixed
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->get();

            if ($user->count() > 0) {
                auth()->login($user->first(), true);
                session()->put('admin_token', $googleUser->token);
                return redirect('/admin');
            } else {
                flash()->error('Error', 'Invalid Credentials!');
                @file_get_contents('https://accounts.google.com/o/oauth2/revoke?token='. $googleUser->token);
                return redirect('notice');
            }
        } catch (Exception $e) {
            flash()->error('Error', $e->getMessage());
            return redirect('notice');
        }

    }

    public function logout()
    {
        @file_get_contents('https://accounts.google.com/o/oauth2/revoke?token='. session()->get('admin_token'));
        session()->forget('admin_token');
        auth()->logout();
        return redirect('notice');
    }

    public function index()
    {
        return view('v2.index');
    }

    public function notice()
    {
        return view('v2.notice');
    }

}
