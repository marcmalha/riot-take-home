<?php

namespace App\Http\Controllers;

use App\Services\Encryptor\Contracts\Encryptor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EncryptionController extends Controller
{
    public function encrypt(Request $request): Response|JsonResponse
    {
        $data = $request->json()->all();

        $encryptor = app()->make(Encryptor::class);
        $encryptedData = $encryptor->encrypt($data);

        return response()->json($encryptedData);
    }

    public function decrypt(Request $request): Response|JsonResponse
    {
        $data = $request->json()->all();

        $encryptor = app()->make(Encryptor::class);
        $encryptedData = $encryptor->decrypt($data);

        return response()->json($encryptedData);
    }
}
