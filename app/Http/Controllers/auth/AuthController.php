<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->boolean('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }
    //  if (Auth::attempt($credentials, $remember)) {
    // dd('LOGIN SUCCESS');
//}

    return back()->withErrors([
        'auth' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:customer,owner,agent'], // Role validation
            'terms'    => ['accepted'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);
        if (!$user) {
            return back()->withErrors(['registration' => 'Registration failed. Please try again.'])->withInput();
        }
        //Auth::login($user);
        return redirect()->intended('/login')->with('success', 'Registration successful. Please log in.');
    }

    /**
     * Redirect to Google for authentication.
     */
    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // /**
    //  * Handle the Google callback.
    //  */
    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();
            
    //         // Check if user already exists by Google ID or Email
    //         $user = User::where('google_id', $googleUser->id)
    //                     ->orWhere('email', $googleUser->email)
    //                     ->first();

    //         if ($user) {
    //             // Link Google ID if only email matched
    //             if (!$user->google_id) {
    //                 $user->update(['google_id' => $googleUser->id]);
    //             }
    //         } else {
    //             // Create new user for first-time Google sign-in
    //             $user = User::create([
    //                 'name' => $googleUser->name,
    //                 'email' => $googleUser->email,
    //                 'google_id' => $googleUser->id,
    //                 'password' => Hash::make(Str::random(24)), // Random password for security
    //                 'role' => 'customer', // Default role for social login
    //                 'email_verified_at' => now(),
    //             ]);
    //         }

    //         Auth::login($user);
    //         return redirect()->intended('/dashboard');

    //     } catch (\Exception $e) {
    //         return redirect('/login')->with('error', 'Google authentication failed.');
    //     }
    // }


}
