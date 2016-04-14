<?php

namespace App\Http\Controllers;

use App\Models\System\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Auth;

class LoginController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginAction()
    {
        return view('login.form');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticateAction(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => User::STATUS_ACTIVE])) {
            $user = Auth::user();
            $user->last_access = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            return redirect()->intended('');
        }

        $request->session()->flash('message', "Dados inválidos, tente novamente.");
        return redirect('login');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logoutAction()
    {
        Auth::logout();
        return redirect('login');
    }
}
