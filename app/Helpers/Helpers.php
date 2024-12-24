<?php

use App\Configurations\Constants;
use App\Models\Dealer;
use App\Models\Item;
use App\Models\ItemDetails;
use App\Models\ItemInstallment;
use App\Models\SaleOrder;
use App\Models\ShippingCharges;
use App\Models\UserDealerMapping;
use App\Models\WHT;
use App\Utilities\Utilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


if (!function_exists('catchErrorResponse')) {
    function catchErrorResponse($e)
    {
        $message = $e->getMessage();
        Log::error("Exception occurred: $message");

        $code = Constants::HTTP_INTERNAL_SERVER_ERROR;
        $response = Utilities::BuildBaseResponse(Constants::Error, $code, "Internal Server Error");
        return response()->json($response, $code);
    }
}
