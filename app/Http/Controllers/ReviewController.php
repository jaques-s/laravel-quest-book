<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelpers;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApiHelpers;

    /**
     * get reviews
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reviews(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $review = Review::with('answers')->get();
            return $this->onSuccess($review, 'Review Retrieved');
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * get one review
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function oneReview(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $review = Review::with('answers')->find($id);
            if (!empty($review)) {
                return $this->onSuccess($review, 'Review Retrieved');
            }
            return $this->onError(404, 'Review Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * create a new review
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createReview(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $validator = Validator::make($request->all(), $this->reviewValidationRules());
            if ($validator->passes()) {
                $review = new Review();
                $review->review = $request->input('review');
                $review->author = $user->id;
                $review->save();

                return $this->onSuccess($review, 'Review Created');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * update review
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateReview(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $validator = Validator::make($request->all(), $this->reviewValidationRules());
            if ($validator->passes()) {
                $review = Review::find($id);
                if (empty($review)) {
                    return $this->onError(404, 'Review Not Found');
                }
                $review->review = $request->input('review');
                $review->author = $user->id;
                $review->save();

                return $this->onSuccess($review, 'Review Updated');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }

    /**
     * delete review
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteReview(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user) || $this->isWriter($user)) {
            $review = Review::find($id);
            if (!empty($review)) {
                $review->delete();
                return $this->onSuccess($review, 'Review Deleted');
            }
            return $this->onError(404, 'Review Not Found');
        }
        return $this->onError(401, 'Unauthorized Access');
    }
}
