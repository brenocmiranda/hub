<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UsersRoles;
use App\Models\Companies;

class SystemCtrl extends Controller
{

    // Function Login
    public function login()
    {
        if (Auth::check() && Auth::user()->active) {
            return redirect(route('home'));
        } else {
            return view('system.login');
        }
    }

    // Function Redirect
    public function redirect(Request $request)
    {
        Auth::logoutOtherDevices($request->password);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //$companies = Companies::factory()->create();
        //$usersroles = UsersRoles::factory()->create();
        //$users = Users::factory()->create();

        if (Auth::attempt($credentials, $request->conected)) {
            if (Users::where('email', $request->email)->where('active', 1)->first()) {
                Users::where('email', $request->email)->update(['attempts' => 0]);
                $request->session()->regenerate();
                return redirect()->intended(route('home'));
            } elseif (Users::where('email', $request->email)->where('active', 0)->first()) {
                return back()->withErrors([
                    'active' => 'O usuário está desativado, contate o administrador.',
                ])->onlyInput('email');
            }
        } elseif (Users::where('email', $request->email)->first()) {
            $user = Users::where('email', $request->email)->first();
            if ($user->attempts < 3) {
                Users::where('email', $request->email)->increment('attempts');
                return back()->withErrors([
                    'password' => 'A senha inserida não confere.',
                ])->onlyInput('password');
            } else {
                return back()->withErrors([
                    'active' => 'O seu usuário foi bloqueado devido a quantidade de tentativas falhas. Entre em contato com o administrador.',
                ])->onlyInput('email');
            }
        } else {
            return back()->withErrors([
                'email' => 'E-mail não cadastrado.',
            ])->onlyInput('email');
        }
    }

    // Function Home
    public function home()
    {
        if (Auth::check() && Auth::user()->active) {
            return view('system.home');
        } else {
            return redirect(route('login'));
        }
    }

    // Function Logout
    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
