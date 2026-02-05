<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\GoogleRecaptcha;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\Captcha;
use Auth;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;
use Str;

class RegisterController extends Controller
{

    use RegistersUsers;


    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest:web');
    }

    public function register_page(Request $request)
    {
        $recaptcha_setting = GoogleRecaptcha::first();

        // Retrieve the email from the request or set it to null
        $email = $request->input('email', null);

        // if($email == null)
        //     return view('auth', compact('recaptcha_setting'));
        // else
        return view('register', compact('recaptcha_setting', 'email'));
    }


    //new auth part
    public function auth_page(Request $request)
    {

        $recaptcha_setting = GoogleRecaptcha::first();

        return view('auth', compact('recaptcha_setting'));
    }

    public function loading_page(Request $request)
    {

        $recaptcha_setting = GoogleRecaptcha::first();
        $emails = User::select('email')->get()->pluck('email')->toArray();

        return view('loading', compact('recaptcha_setting'))->with(['emails' => $emails]);
    }

    public function verifyRegister(Request $request)
    {
        // Validate the incoming request
        console . log('verifyRegister');
        try {
            console . log('verifyRegister');
            $email = $request->input('email');

            $user = User::where('email', $email)->first();

            if ($user) {
                // Perform your verification process here
                $user->status = 1;
                $user->email_verified = 1;
                $user->save();

                $notification = 'Verification Successfully';
                return ['message' => $notification, 'alert-type' => 'success'];
            } else {
                $notification = 'Invalid email'; // Changed from 'Invalid token'
                return ['message' => $notification, 'alert-type' => 'error'];
            }
        } catch (Exception $e) {
            // Log or return the exception
            cosole . log($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //new code ends here

    public function store_register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|min:10|max:10',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
        ];
        $this->validate($request, $rules, $customMessages);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password ?? "12345678");
        $user->phone = $request->phone;
        $user->verify_token = null;
        $user->status = 1;
        $user->mobile = $request->phone;
        $user->email_verified = 1;
        $user->save();

        // MailHelper::setMailConfig();

        // $template=EmailTemplate::where('id',4)->first();
        // $subject=$template->subject;
        // $message=$template->description;
        // $message = str_replace('{{user_name}}',$request->name,$message);
        // $message = str_replace('{{user_token}}',$user->verify_token,$message);

        // $url = url('/');
        // $message = str_replace('{{app_url}}',$url,$message);
        // Mail::to($user->email)->send(new UserRegistration($message,$subject,$user));
        $credential = [
            'email' => $request->email,
            'password' => $request->password ?? "12345678"
        ];
        if (Auth::guard('web')->attempt($credential, true)) {
            $notification = trans('user_validation.Login Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('dashboard')->with($notification);
        }
        $notification = trans('user_validation.Register Successfully.');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('dashboard')->with($notification);
    }

    public function verify_register($token)
    {
        $user = User::where('verify_token', $token)->first();
        if ($user) {
            $user->verify_token = null;
            $user->status = 1;
            $user->email_verified = 1;
            $user->save();
            $notification = trans('user_validation.Verification Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('login')->with($notification);
        } else {
            $notification = trans('user_validation.Invalid token');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('login')->with($notification);
        }
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'min:10', 'max:10'],
        ]);
    }


    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
