<?php

namespace App\Services\Encryptor;

class Encryptor implements Contracts\Encryptor
{
    protected function defaultEncryptionMethod(): callable
    {
        return fn (string $data) => base64_encode($data);
    }

    public function encrypt(array $data, ?callable $encryptionMethod = null): array
    {
        $encryptionMethod ??= $this->defaultEncryptionMethod();

        return collect($data)
            ->map(function ($value) use ($encryptionMethod) {
                $jsonEncodedValue = json_encode($value);

                return $encryptionMethod($jsonEncodedValue);
            })
            ->toArray();
    }

    public function decrypt(array $data, ?callable $decryptionMethod = null): array
    {
        return [];
    }
}
