<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRqt;
use App\Http\Requests\RecoveryRqt;
use App\Http\Requests\VerifyRqt;
use App\Models\Users;
use App\Models\UsersRoles;
use App\Models\Companies;
use App\Models\Leads;
use App\Models\Buildings;
use App\Models\LeadsOrigins;
use App\Notifications\RecoveryPassword;
use App\Notifications\ResetPassword;

class SystemCtrl extends Controller
{

    /** Functions external **/
    public function login()
    {
        if (Auth::check() && Auth::user()->active) {
            return redirect(route('home'));
        } else {
            return view('system.login');
        }
    }

    public function authentication(LoginRqt $request)
    {
        Auth::logoutOtherDevices($request->password);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //$companies = Companies::factory()->create();
        //$usersroles = UsersRoles::factory()->create();
        //$users = Users::factory()->create();
        //$buildings = Buildings::factory()->create();
        //$leadsorigins = LeadsOrigins::factory()->create();

        if (Auth::attempt($credentials, $request->conected)) {
            if (Users::where('email', $request->email)->where('active', 1)->first()) {
                Users::where('email', $request->email)->update(['attempts' => 0]);
                $request->session()->regenerate();
                return redirect()->intended(route('home'));
            } elseif (Users::where('email', $request->email)->where('active', 0)->first()) {
                return back()->withErrors([
                    'active' => 'O usuário está desativado, contate o administrador.',
                ])->withInput($request->input());
            }
        } elseif (Users::where('email', $request->email)->first()) {
            $user = Users::where('email', $request->email)->first();
            if ($user->attempts < 3) {
                Users::where('email', $request->email)->increment('attempts');
                return back()->withErrors([
                    'password' => 'A senha inserida não confere.',
                ])->withInput($request->input());
            } else {
                return back()->withErrors([
                    'active' => 'O seu usuário foi bloqueado devido a quantidade de tentativas falhas. Entre em contato com o administrador.',
                ])->withInput($request->input());
            }
        } else {
            return back()->withErrors([
                'email' => 'E-mail não cadastrado.',
            ])->withInput($request->input());
        }
    }

    public function recovery()
    {
        return view('system.recovery');
    }

    public function recovering(RecoveryRqt $request)
    {   
        $user = Users::where('email', $request->email)->first();
        if(!empty($user->email)){ 
            $user->notify(new RecoveryPassword($user));
            return redirect()->route('login')->with('mailto', true);
        } else {
            return back()->withErrors([
                'email' => 'E-mail não cadastrado.',
            ])->withInput($request->input());
        }
    }

    public function verify($token)
    {   
        $user = Users::where('remember_token', $token)->first();
        if(!empty($user)){ 
            return view('system.verify')->with('user', $user);
        } else {
            return redirect(route('login'))->withErrors([
                'active' => 'Não foi possível identificar os seus dados, solicite novamente.',
            ]);
        }
    }

    public function reset(VerifyRqt $request) {
        
        if( Auth::check() ){
            Auth::logout();
        }

        $user = Users::where('remember_token', $request->token)->first();
        $dados = Users::find($user->id)->update([
            'password' => Hash::make($request->password), 
            'remember_token' => $request->_token,
            'email_verified_at' => date("Y-m-d H:i:s"),
            'active' => 1,
            'attempts' => 0,
        ]);

        $user->notify(new ResetPassword($user));
        return redirect()->route('login')->with('reset', true);
    }

    /** Functions internal **/
    public function home()
    {
        if (Auth::check() && Auth::user()->active) {
            $dateNow = date('Y-m-d H:i:s');
            $leadsDay = Leads::whereDate('created_at', date('Y-m-d'))->count();

            return view('system.home')->with('leadsDay', $leadsDay);
        } else {
            return redirect(route('login'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
