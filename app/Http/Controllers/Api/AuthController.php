<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Requests\User\RegisterRequest;
use Illuminate\Support\Facades\Lang;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $imageName = $this->userRepository->image($request->avatar);
        $data['avatar'] = $imageName;
        $data['lock_time'] = Carbon::now('Asia/Ho_Chi_Minh');
        $data['password'] = Hash::make($request->password);
        $user = $this->userRepository->create($data);

        return response()->json($user);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        $token = auth()->attempt($credentials);
        $user = auth()->user();
        if ($user->status == 1) {
            return $this->respondWithToken($token);
        } else {
            return response()->json(Lang::get('auth.lock-user'));
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Change password User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $data = $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        $user = auth()->user();
        if (!Hash::check($data['old_password'], $user->password)) {

            return response()->json(Lang::get('auth.failed-change-password'));
        } else {
            $user->password = Hash::make($data['new_password']);
            $user->save();
            return response()->json(Lang::get('auth.change-password'));
        }
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(Lang::get('auth.logout'));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
