<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Parceiro;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
class ResetPasswordController extends Controller
{
  public function getPassword($token) { 
    return view('pages.auth.password-reset', ['token' => $token]);
  }
  public function updatePassword(Request $request)
  {
    $request->validate([
        'email' => 'required|email|exists:users',
        'email' => 'required|email|exists:parceiros',
        'password' => 'required|string|min:5|confirmed',
        'password_confirmation' => 'required',
    ]);

    
    $updatePassword = DB::table('password_resets')
                        ->where(['email' => $request->email, 'token' => $request->token])
                        ->first();

    if(!$updatePassword)
        return back()->with('toast_error', 'Invalid token!');

        $user = User::where('email', $request->email)
                    ->update(['password' => bcrypt($request->password)]);
                    
        $parceiro = Parceiro::where('email', $request->email)
                    ->update(['password' => bcrypt($request->password)]);            

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('success', trans('auth.message_password_change'));

  }
}
