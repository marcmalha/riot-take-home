<?php

namespace Tests\Feature\Encryption;

use Tests\TestCase;

use function PHPUnit\Framework\assertEqualsCanonicalizing;
use function PHPUnit\Framework\assertFalse;

class EncryptionControllerTest extends TestCase
{
    public function test_encryption_endpoint_returns_same_keys_as_payload(): void
    {
        $dataToEncrypt = [
            'name' => 'John Doe',
            'age' => 30,
            'phone' => '123-456-7890',
        ];

        $response = $this->postJson(
            'encrypt',
            data: $dataToEncrypt,
        );

        assertEqualsCanonicalizing(array_keys($dataToEncrypt), array_keys($response->json()));
    }

    public function test_encryption_endpoint_encrypts_only_at_depth_1(): void
    {
        $dataToEncrypt = [
            'name' => 'John Doe',
            'age' => 30,
            'contact' => [
                'email' => 'john@example.com',
                'phone' => '123-456-7890',
            ],
        ];

        $response = $this->postJson(
            'encrypt',
            data: $dataToEncrypt,
        );

        assertFalse(collect($response->json())->dot()->hasAny([
            'contact.email',
            'contact.phone',
        ]));
    }

    public function test_decryption_endpoint_keeps_types_unchanged(): void
    {
        $dataToEncrypt = [
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

        $decryptedData = $this->postJson(
            'decrypt',
            $encryptedData,
        )->json();

        assertEqualsCanonicalizing($dataToEncrypt, $decryptedData);
    }

    public function test_decryption_keeps_unencyrpted_data_unchanged(): void
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
        )->collect();

        $additionalProperty = [
            'birth_date' => '1998-11-19',
        ];
        $encryptedDataWithAdditionalProperty = $encryptedData
            ->merge($additionalProperty)
            ->toArray();
        $dataToEncryptWithAdditionalProperty = collect($dataToEncrypt)
            ->merge($additionalProperty)
            ->toArray();

        $decryptedData = $this->postJson(
            'decrypt',
            $encryptedDataWithAdditionalProperty,
        )->json();

        assertEqualsCanonicalizing($dataToEncryptWithAdditionalProperty, $decryptedData);
    }
}
