<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataSignatureController extends Controller
{
    public function sign(Request $request): JsonResponse
    {
        return response()->json([
            'signature' => '',
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        return response()->json();
    }
}
