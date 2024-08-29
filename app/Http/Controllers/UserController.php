<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{

    public function LoginPage()
    {
        return view('pages.auth.login-page');
       // return "jksdioi";
    }

    public function RegistrationPage()
    {
        return view('pages.auth.registration-page');
    }

    public function SendOtpPage()
    {
        return view('pages.auth.send-otp-page');
    }

    public function VerifyOTPPage()
    {
        return view('pages.auth.verify-otp-page');
    }

    public function ResetPasswordPage()
    {
        return view('pages.auth.reset-pass-page');
    }







   //ajax diye call korbo
    function UserRegistration(Request $request){
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully'
            ],200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Registration Failed'
            ],200);

        }
    }

    function UserLogin(Request $request){
        $count=User::where('email','=',$request->input('email'))
            ->where('password','=',$request->input('password'))
            ->select('id')->first();

        if($count!==null){
            // User Login-> JWT Token Issue
            $token=JWTToken::CreateToken($request->input('email'),$count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful'
            ],200)->cookie( 'token',$token,60*24*30);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);

        }

    }

    function SendOTPCode(Request $request)
    {
        $email=$request->input('email');
        $otp = rand(1000,9999);

        $count = User::where('email','=',$email)->count();

        if ($count==1)
        {
            //otp email address
            Mail::to($email)->send(new OTPMail($otp));
            //otp code table insert update
            User::where('email','=',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' => '4 digit Otp code  send successfully'
            ],200);

        }
        else
        {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);
        }
    }

    function VerifyOTP(Request $request)
    {
        $email = $request->input('email');
        $otp= $request->input('otp');

        $count = User::where('email','=',$email)
            ->where('otp','=',$otp)
            ->count();

        if ($count==1)
        {
            //database otp update
            User::where('email','=',$email)->update(['otp'=>'0']);

            //password reset issue given with validity time
            $token=JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'Otp verification Successful'

            ],200)->cookie( 'token',$token,60*24*30);
        }

        else
            {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Your otp code invalid '
                ],200);
            }

    }

    function PasswordReset(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully '
            ],200);

        }
        catch(Exception $e)
        {
            return response()->json([
                'status' => 'Failed ',
                'message' => ' Something went wrong '
            ],200);
        }
    }


    function UserLogout(){
        return redirect('/')->cookie('token','',-1);
    }





}
