<?php

namespace Tests\Feature\Encryption;

use Illuminate\Http\Response;
use Tests\TestCase;

class EncryptionControllerTest extends TestCase
{
    public function test_encrypt_endpoint_returns_200(): void
    {
        $this->postJson(
            'encrypt',
            data: [
                'name' => 'John Doe',
                'age' => 30,
                'phone' => '123-456-7890',
            ],
        )->assertStatus(Response::HTTP_OK);
    }

    public function test_decrypt_endpoint_returns_200(): void
    {
        $dataToEncrypt = [
            'name' => 'John Doe',
            'age' => 30,
            'contact' => [
                'email' => 'john@example.com',
                'phone' => '123-456-7890',
            ],
        ];

        $encryptedData = $this->postJson(
            'encrypt',
            data: $dataToEncrypt,
        )->json();

        $this->postJson(
            'decrypt',
            data: $encryptedData,
        )->assertStatus(Response::HTTP_OK);
    }
}
