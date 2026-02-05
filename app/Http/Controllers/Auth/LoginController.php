<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\BreadcrumbImage;
use App\Models\GoogleRecaptcha;
use App\Models\User;
use App\Models\Vendor;
use App\Rules\Captcha;
use Hash;
use App\Mail\UserForgetPassword;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\SocialLoginInformation;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Mail;
use Str;
use Validator,Redirect,Response,File;
use Carbon\Carbon;
class LoginController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest:web')->except('user_logout');
    }

    public function login_page(){
        $recaptcha_setting = GoogleRecaptcha::first();
        $social_login = SocialLoginInformation::first();

        return view('login', compact('recaptcha_setting','social_login'));
    }

    public function login_auth_page(){
        $recaptcha_setting = GoogleRecaptcha::first();
        $social_login = SocialLoginInformation::first();

        return view('login_auth', compact('recaptcha_setting','social_login'));
    }

    public function store_page(Request $request){
        $request->merge([
            'password' => $request->password ?? "12345678",
        ]);
        $recaptcha_setting = GoogleRecaptcha::first();
        $rules = [
            'email'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $credential=[
            'email'=> $request->email,
            'password'=> $request->password
        ];
        $user = User::where('email',$request->email)->first();
        if($user){
            if($user->email_verified == 0){
                $notification = trans('user_validation.Please verify your acount.');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }
//            check if phone is empty then redirect to update phone page
            if($user->phone == ''){
                session()->put('user-phone', $user);
                return redirect()->route('auth.update-phone');
            }

            if($user->status==1){
                if(Hash::check($request->password,$user->password)){
                    if(Auth::guard('web')->attempt($credential,$request->remember)){
                        $notification= trans('user_validation.Login Successfully');
                        $notification=array('messege'=>$notification,'alert-type'=>'success');
                        return redirect()->route('dashboard')->with($notification);
                    }

                    $notification = trans('user_validation.Something went wrong');
                    $notification = array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->back()->with($notification);

                }else{
                    $notification = trans('user_validation.Credentials does not exist');
                    $notification = array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->back()->with($notification);
                }

            }else{
                $notification = trans('user_validation.Disabled Account');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }
        }else{
            $notification = trans('user_validation.Email does not exist');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return view('register_auth',compact('recaptcha_setting'))->with(['email' => $request->email]);;
        }
    }

    public function forget_page(){

        $recaptcha_setting = GoogleRecaptcha::first();

        return view('forget_password', compact('recaptcha_setting'));
    }

    public function send_reset_link(Request $request){
        $rules = [
            'email'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = User::where('email', $request->email)->first();
        if($user){
            $user->forget_password_token = Str::random(100);
            $user->save();

            MailHelper::setMailConfig();
            $template = EmailTemplate::where('id',1)->first();
            $subject = $template->subject;
            $message = $template->description;
            $message = str_replace('{{name}}',$user->name,$message);
            Mail::to($user->email)->send(new UserForgetPassword($message,$subject,$user));

            $notification = trans('user_validation.Reset password link send to your email.');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification = trans('user_validation.Email does not exist');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('forget-password')->with($notification);
        }
    }


    public function reset_password($token){
        $user = User::where('forget_password_token', $token)->first();
        if($user){
            $recaptcha_setting = GoogleRecaptcha::first();
            return view('reset_password', compact('recaptcha_setting','user','token'));
        }else{
            $notification = trans('user_validation.Invalid token');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('forget-password')->with($notification);
        }


    }

    public function store_reset_password(Request $request, $token){
        $rules = [
            'email'=>'required',
            'password'=>'required|min:4|confirmed',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password must be 4 characters'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = User::where(['email' => $request->email, 'forget_password_token' => $token])->first();
        if($user){
            $user->password=Hash::make($request->password);
            $user->forget_password_token=null;
            $user->save();

            $notification = trans('user_validation.Password Reset successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('login')->with($notification);
        }else{
            $notification = trans('user_validation.Email or token does not exist');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function user_logout(){

        Auth::guard('web')->logout();
        $notification= trans('user_validation.Logout Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('login')->with($notification);
    }







    function createUser($getInfo,$provider){
        $user = User::where('provider_id', $getInfo->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $getInfo->name,
                'email'    => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'phone' => '',
                'status' => 1,
                'email_verified' => 1,
            ]);
        }
        return $user;
    }

    public function google_callback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Google authentication failed. Please try again.']);
        }
    
        $existingUser = User::where('email', $user->email)->first();
    
        if ($existingUser) {
            Auth::login($existingUser);
            return redirect()->intended($this->redirectTo);
        } else {
            $createdUser = $this->createUser($user, 'google');
            if ($createdUser) {
                // Save user data to session if needed
                session()->put('user-phone', $user);
                return redirect()->route('auth.update-phone');
            } else {
                return redirect()->route('login')->withErrors(['msg' => 'Failed to create user. Please try again.']);
            }
        }
    }

    public function facebook_callback(){
        $user = Socialite::driver('facebook')->user();
        $existUser = User::where('email', $user->email)->first();
        if($existUser) {
            Auth::login($existUser);
            return redirect($this->redirectTo);
        }else {
            $this->createUser($user,'facebook');
//            save the user to session
            session()->put('user-phone', $user);
            return redirect()->route('auth.update-phone');
        }
    }





}
