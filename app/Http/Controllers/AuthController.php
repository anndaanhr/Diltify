<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Check if email already exists (since email is encrypted, we need to check manually)
            $existingUser = User::whereEmail($request->email);
            if ($existingUser) {
                return redirect()->back()
                    ->withErrors(['email' => 'The email has already been taken.'])
                    ->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Auto login after registration
            session(['user_id' => $user->id]);
            session(['user_name' => $user->name]);
            session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to Diltify.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::whereEmail($request->email);

            if (!$user || !Hash::check($request->password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Invalid email or password.')
                    ->withInput();
            }

            session(['user_id' => $user->id]);
            session(['user_name' => $user->name]);
            session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Login failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        session()->forget(['user_id', 'user_name']);
        session()->regenerate();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}

