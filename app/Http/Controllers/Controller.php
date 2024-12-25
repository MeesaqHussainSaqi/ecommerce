<?php

namespace App\Http\Controllers;

use App\Config\Constants;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
// use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected $model;
    protected function validateRequest($request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->model->Rules($request),
            $this->model->Messages($request)
        );

        $errors = $validator->errors()->all();

        if ($validator->fails()) {
            // $code = Constants::HTTP_BAD_REQUEST; // 400 couldn't process
            // $response = Utilities::BuildBadResponse(
            //     Constants::Error,
            //     $code,
            //     "Validation failed.",
            //     $errors
            // );

            return response()->json($errors);
        }
    }
}
