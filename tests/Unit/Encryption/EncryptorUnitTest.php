<?php

namespace Tests\Unit\Encryption;

use App\Services\Encryptor\Contracts\Encryptor;
use Tests\TestCase;

class EncryptorUnitTest extends TestCase
{
    protected Encryptor $encryptor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->encryptor = app()->make(Encryptor::class);
    }

    public function test_encryptor_returns_same_keys_as_payload(): void
    {
        $dataToEncrypt = [
            'name' => 'John Doe',
            'age' => 30,
            'phone' => '123-456-7890',
        ];

        $encryptedData = $this->encryptor->encrypt($dataToEncrypt);

        $this->assertEqualsCanonicalizing(array_keys($dataToEncrypt), array_keys($encryptedData));
    }

    public function test_encryptor_encrypts_only_at_depth_1(): void
    {
        $dataToEncrypt = [
            'name' => 'John Doe',
            'age' => 30,
            'contact' => [
                'email' => 'john@example.com',
                'phone' => '123-456-7890',
            ],
        ];

        $encryptedData = $this->encryptor->encrypt($dataToEncrypt);

        $isMultiDepth = collect($encryptedData)
            ->dot()
            ->hasAny([
                'contact.email',
                'contact.phone',
            ]);

        $this->assertFalse($isMultiDepth);
    }

    public function test_decryptor_keeps_types_unchanged(): void
    {
        $dataToEncrypt = [
            'age' => 30,
            'contact' => [
                'email' => 'john@example.com',
                'phone' => '123-456-7890',
            ],
        ];

        $encryptedData = $this->encryptor->encrypt($dataToEncrypt);

        $decryptedData = $this->encryptor->decrypt($encryptedData);

        $this->assertEqualsCanonicalizing($dataToEncrypt, $decryptedData);
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

        $encryptedData = $this->encryptor->encrypt($dataToEncrypt);

        $additionalProperty = [
            'birth_date' => '1998-11-19',
        ];
        $encryptedDataWithAdditionalProperty = collect($encryptedData)
            ->merge($additionalProperty)
            ->toArray();
        $dataToEncryptWithAdditionalProperty = collect($dataToEncrypt)
            ->merge($additionalProperty)
            ->toArray();

        $decryptedData = $this->encryptor->decrypt($encryptedDataWithAdditionalProperty);

        $this->assertEqualsCanonicalizing($dataToEncryptWithAdditionalProperty, $decryptedData);
    }
}
