<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    /**
     * @param $user
     * @return bool
     */
    protected function isAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('admin');
        }
        return false;
    }

    /**
     * @param $user
     * @return bool
     */
    protected function isWriter($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('writer');
        }
        return false;
    }

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param int $code
     * @param string $message
     * @return JsonResponse
     */
    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

    /**
     * @return string[]
     */
    protected function reviewValidationRules(): array
    {
        return [
            'review' => 'required|string',
        ];
    }

    /**
     * @return string[]
     */
    protected function answerValidationRules(): array
    {
        return [
            'answer' => 'required|string',
        ];
    }

    /**
     * @return string[][]
     */
    protected function userValidatedRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
