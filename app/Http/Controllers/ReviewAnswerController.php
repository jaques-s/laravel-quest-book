<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelpers;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewAnswerController extends Controller
{
    use ApiHelpers;

    /**
     * create a new answer for review
     *
     * @param Request $request
     * @param $reviewId
     * @return JsonResponse
     */
    public function createAnswer(Request $request, $reviewId): JsonResponse
    {
        $user = $request->user();
        if ($this->isAdmin($user)) {
            $review = DB::table('reviews')->where('id', $reviewId)->first();
            if (empty($review)) {
                return $this->onError(404, 'Review Not Found');
            }

            $validator = Validator::make($request->all(), $this->answerValidationRules());
            if ($validator->passes()) {
                $review = new Answer();
                $review->answer = $request->input('answer');
                $review->author = $user->id;
                $review->review = (int)$reviewId;
                $review->save();

                return $this->onSuccess($review, 'Answer Created');
            }
            return $this->onError(400, $validator->errors());
        }

        return $this->onError(401, 'Unauthorized Access');
    }
}
