<?php

namespace App\Http\Controllers;

use App\Services\DataSignatureProvider\Contracts\DataSignatureProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DataSignatureController extends Controller
{
    public function sign(Request $request): JsonResponse
    {
        $payload = $request->json()->all();
        /** @var DataSignatureProvider $dataSignatureProvider */
        $dataSignatureProvider = app()->make(DataSignatureProvider::class);

        return response()->json([
            'signature' => $dataSignatureProvider->sign($payload),
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validatedPayload = $request->validate([
            'signature' => 'required',
            'data' => 'required',
        ]);

        /** @var DataSignatureProvider $dataSignatureProvider */
        $dataSignatureProvider = app()->make(DataSignatureProvider::class);

        $isSignatureValid = $dataSignatureProvider->verify(
            $validatedPayload['signature'],
            $validatedPayload['data']
        );

        if ($isSignatureValid) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return response()->json(status: Response::HTTP_BAD_REQUEST);
    }
}
