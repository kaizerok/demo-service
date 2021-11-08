<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\LedgerTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityRuleController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getEntitiesByTransactionReference(Request $request)
    {
        $ledgerTransactionService = new LedgerTransactionService($request->input('reference'));

        return response()->json($ledgerTransactionService->getPaymentEntities());
    }

}
