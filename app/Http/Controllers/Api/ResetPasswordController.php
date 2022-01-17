<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\SendMailreset;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use App\Jobs\SendMailPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Support\Facades\Queue as FacadesQueue;
use App\Models\PasswordResets;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request)  // this is most important function to send mail and inside of that there are another function
    {
        if (!$this->validateEmail($request->email)) {  // this is validate to fail send mail or true
            return $this->failedResponse();
        }
        $this->send($request->email);  //this is a function to send mail 
        return $this->successResponse();
    }

    public function send($email)  //this is a function to send mail 
    {
        $token = $this->createToken($email);
        FacadesQueue::later(Carbon::now()->addMinutes(10), new SendMailPassword($token, $email));  // token is important in send mail 
    }

    public function createToken($email)  // this is a function to get your request email that there are or not to send mail
    {
        $oldToken = PasswordResets::where('email', $email)->first();

        if ($oldToken) {
            return $oldToken->token;
        }
        $token = Str::random(40);
        $this->saveToken($token, $email);
        return $token;
    }

    public function saveToken($token, $email)  // this function save new password
    {
        PasswordResets::insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email)  //this is a function to get your email from database
    {
        return !!User::where('email', $email)->first();
    }

    public function failedResponse()
    {
        return response()->json([
            'error' => Lang::get('auth.failed'), 'status' => Response::HTTP_NOT_FOUND
        ],);
    }

    public function successResponse()
    {
        return response()->json([
            'data' => Lang::get('auth.success'), 'status' => Response::HTTP_OK
        ]);
    }
}
