<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\Rijndael;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Enums\Notification\NotificationCodeEnum;
use App\Services\Notification\EmailNotificationService;
use App\Jobs\EmailJob;
use App\Utility\Services\Email\EmailProperty;
use App\Modals\User;
use App\Modals\VerifyUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Config;
use Socialite;

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

    public function showLoginForm(Request $request)
    {
        return view('backend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required',
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1], $request->remember)){
            session()->flash('success', 'Logged in successfully!');
            return redirect()->intended(route('dashboard'));
        }else{
            session()->flash('error', 'Invalid username or password!');
            return back();
        }
    }
    public function logout(){  
        Auth::logout();
        if(Session::has('task.filter')){
            Session::forget('task.filter');
        }
        if(Session::has('user.filter')){
            Session::forget('user.filter');
        }
        return redirect()->route('login');
    }
    public function ssoResponse(Request $request){
        if($request->input('multipass')){

            $multipass = $request->input('multipass');
            $key = "sso.3l3tmptst.wb";
            // $multipass = urldecode($multipass);
            $ssoSubject = base64_decode($multipass);
            $ssoSubjectDecode = $this->decrypt($ssoSubject, $key, $key);
            $cleanData = preg_replace('/[\x00-\x1F\x7F]/', '', $ssoSubjectDecode);
            $cleanData = str_replace($key, '', $ssoSubjectDecode);
            // dd($ssoSubjectDecode);
            $splittedData = explode('|', $cleanData);
            $authenticatedData = explode(':', $splittedData[3]);
            $authenticated = $authenticatedData[1];
            if($authenticated == 'true'){
                $pinData = explode(':', $splittedData[0]);
                $pin = $pinData[1];
                // dd($pin);
                
                // HERE TO LOGIN AUTOMATICALLY FROM BACKEND USING pin ONLY
                // AND REDIRECT TO DASHBOARD
                
                if(Auth::attempt(['pin_number' => $pin, 'password' => 'Br@cTmp2021', 'status' => 1])){
                    session()->flash('success', 'Logged in successfully!');
                    return redirect()->intended(route('dashboard'));
                }
                
            }
        }
        session()->flash('error', 'Invalid username or password!');
        return redirect()->route('ssoLogin');
    }

    public function decrypt($data, $iv, $key) {
        $rijndael = new Rijndael('cbc');
        $rijndael->setKey($iv);
        $rijndael->setIV($key);
        $rijndael->setKeyLength(128);
        $rijndael->enablePadding();
        $rijndael->setBlockLength(128);

        return $rijndael->decrypt($data);
    }

    public function showForgetPasswordForm(){
        return view('backend.auth..email');
    }

    public function sendEmailForReset(Request $request){
        $check_exist = User::where('email', $request->email)->first();
        if($check_exist){
            $check_previous_entry = VerifyUser::where('user_id', $check_exist->id)
                                            ->latest('id')
                                            ->first();
            if(empty($check_previous_entry))
            {
                $verify = VerifyUser::create([
                    'token'   => Str::random(60),
                    'user_id' => $check_exist->id,
                ]);

                $name  = $check_exist->name;
                $token = $verify->token;
                $email = $check_exist->email;

                $emailNotificationCode = NotificationCodeEnum::SendEmailForReset()->getValue();
                $emailService = new EmailNotificationService($emailNotificationCode, 'email', $request->email);
                $emailService->replaceTempateVariable([
                    'Token'=> $token,
                ]);
                $emailService->sendEmail();

                Session::flash('success', 'Please check your email for a password reset link!');
            }
            else {
                $current_time                          = date("Y-m-d H:i:s");
                $ten_min_later_after_previous_attempt  = $check_previous_entry->created_at->addMinutes(10);

                if($ten_min_later_after_previous_attempt < $current_time) {

                    $delete_previous_entry = VerifyUser::where('user_id', $check_exist->id)
                                                        ->delete();
                    $verify = VerifyUser::create([
                        'token'   => Str::random(60),
                        'user_id' => $check_exist->id,
                    ]);

                    $name  = $check_exist->name;
                    $token = $verify->token;
                    $email = $check_exist->email;

                    $emailNotificationCode = NotificationCodeEnum::SendEmailForReset()->getValue();
                    $emailService = new EmailNotificationService($emailNotificationCode, 'email', $request->email);
                    $emailService->replaceTempateVariable([
                        'Token'=> $token,
                    ]);
                    $emailService->sendEmail();
                

                    Session::flash('success', 'Please check your email for a password reset link!');
                }
                else {
                    Session::flash('error', 'Please wait for 10min from the last attempt.');
                }
            }
        }
        else {
            Session::flash('error', 'Wrong email address. Please use correct email address.');
        }
        return redirect()->route('login');
    }

    public function passwordResetVerifyEmail($token)
    {
      try {
        $verifiedUser = VerifyUser::where('token', $token)->first();
        if (isset($verifiedUser)) {

          $today             = date("Y-m-d");
          $verification_date = date("Y-m-d", strtotime($verifiedUser->created_at . ' +1 day'));
          $user              = User::where('id', $verifiedUser->user_id)
                                   ->first();

          if($verification_date < $today)
          {
            $delete_token = VerifyUser::where('token', $token)->delete();

            Session::flash('error', 'Email Verification Timeout!');
            return redirect()->route('showForgetPasswordForm');
          }
          else {
            $delete_token = VerifyUser::where('token', $token)->delete();

            Session::flash('success', 'Your email has been verified!');
            return redirect()->route('resetPassword', ['userId' => $verifiedUser->user_id]);
          }
        } else {
          Session::flash('error', 'Email Verification Link is Expired!');
          return redirect()->route('login');
        }
      } catch (\Exception $e) {
        \Log::info($e);
        Session::flash('error', 'Something Went Wrong!');
        return redirect()->route('login');
      }
    }

    public function passwordResetForm(Request $request){
        // dd($request->all());
        return view('backend.auth.reset', ['userId' => $request->userId]);
    }

    public function passwordUpdate(Request $request){
        $request->validate([
            'userId' => 'required',
            'email' => 'required|max:100',
            'password' => 'required|max:20|confirmed',
        ]);

        $user = User::where(['id' => $request->userId, 'email' => $request->email])->first();
        if($user){
            $user->password = Hash::make($request->password);
            $user->save();

            Session::flash('success', 'Password has been updated successfully!');
        }
        else {
            Session::flash('error', 'Wrong email address!');
        }
        return redirect()->route('login');
    }

    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){
        $user = Socialite::driver('google')->user();
        if(strpos($user->email,'@bikroy.com') !== false){
            $this->_register0rLoginUser($user);

            return redirect()->route('dashboard');
        }
        Session::flash('error', 'Wrong email! Please try again!');
        return redirect()->route('login');
    }

    protected function _register0rLoginUser($data){
       
        $user = User::where('email','=',$data->email)->first();
        if(!$user){
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }

        Auth::login($user);
    
    }
}
