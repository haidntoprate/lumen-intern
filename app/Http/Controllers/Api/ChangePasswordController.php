<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class ChangePasswordController extends Controller
{
    public function passwordResetProcess(Request $request)
    {
        $resutl = $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
        return response()->json($resutl);
    }
    // Verify if token is valid
    private function updatePasswordRow($request)
    {
        return User::where([
            'email' => $request->email,
            'token' => $request->resetToken
        ]);
    }
    // Token not found response  
    private function tokenNotFoundError()
    {
        return response()->json([
            'error' => Lang::get('auth.HTTP_UNPROCESSABLE_ENTITY')
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    // Reset password
    private function resetPassword($request)
    {
        // find email
        $userData = User::whereEmail($request->email)->first();
        // update password
        $userData->update([
            'password' => Hash::make($request->password)
        ]);
        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        // reset password response
        return response()->json([
            Lang::get('messages.update', ['model' => 'user'])
        ], Response::HTTP_CREATED);
    }
}
