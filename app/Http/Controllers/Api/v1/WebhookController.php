<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Processors\ProcessorsFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function paymentwall(Request $request)
    {
        $paymentProcessor = ProcessorsFactory::getBySystemName($request->route()->getActionMethod());
        $response = $paymentProcessor->webhook($request->all());

        return response()->json([
            'success' => $response
        ]);
    }
}
