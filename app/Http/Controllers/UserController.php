<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiHelpers;

    /**
     * create user with writer role
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createWriter(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
                User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'role' => 2,
                    'password' => Hash::make($request->input('password')),
                ]);
                $writerToken = $user->createToken('auth_token', ['writer'])->plainTextToken;
                return $this->onSuccess($writerToken, 'User Created With Writer Privilege');
            }
            return $this->onError(400, $validator->errors());
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * delete user
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteUser(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $user = User::find($id);
            if (!empty($user) && $user->role !== 1) {
                $user->delete();
                return $this->onSuccess('', 'User Deleted');
            }
            return $this->onError(401, 'User Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
