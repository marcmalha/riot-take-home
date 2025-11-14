<?php

namespace App\Http\Controllers;

use App\Services\DataSignatureProvider\Contracts\DataSignatureProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DataSignatureController extends Controller
{
    protected DataSignatureProvider $dataSignatureProvider;

    public function __construct()
    {
        $this->dataSignatureProvider = app()->make(DataSignatureProvider::class);
    }

    public function sign(Request $request): JsonResponse
    {
        $payload = $request->json()->all();

        return response()->json([
            'signature' => $this->dataSignatureProvider->sign($payload),
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validatedPayload = $request->validate([
            'signature' => 'required',
            'data' => 'present|nullable',
        ]);

        $isSignatureValid = $this->dataSignatureProvider->verify(
            $validatedPayload['signature'],
            $validatedPayload['data']
        );

        if ($isSignatureValid) {
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        return response()->json(
            data: ['error' => 'Invalid Signature'],
            status: Response::HTTP_BAD_REQUEST
        );
    }
}
