<?php

namespace App\Http\Controllers;

use App\Services\Encryptor\Contracts\Encryptor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EncryptionController extends Controller
{
    protected Encryptor $encryptor;

    public function __construct()
    {
        $this->encryptor = app()->make(Encryptor::class);
    }

    public function encrypt(Request $request): Response|JsonResponse
    {
        $data = $request->json()->all();

        $encryptedData = $this->encryptor->encrypt($data);

        return response()->json($encryptedData);
    }

    public function decrypt(Request $request): Response|JsonResponse
    {
        $data = $request->json()->all();

        $this->encryptor = app()->make(Encryptor::class);
        $encryptedData = $this->encryptor->decrypt($data);

        return response()->json($encryptedData);
    }
}
