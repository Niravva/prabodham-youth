<?php

namespace App\Http\Controllers\Auth;

use App\Events\AdminLoginEvent;
use App\Http\Controllers\Controller;
use App\Models\AdminLoginLog;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use InteractionDesignFoundation\GeoIP\Facades\GeoIP;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->event_type = 'Logout';
        event(new AdminLoginEvent($user));

        Session::flush();
        Auth::logout();

        return redirect()->route('login');
    }

    protected function credentials(Request $request)
    {
        return ['email' => $request->{$this->username()}, 'password' => $request->password, 'status' => 'Active'];
    }

    protected function authenticated()
    {
        $user = Auth::user();
        $user->event_type = 'Login';
        event(new AdminLoginEvent($user));
    }
}
