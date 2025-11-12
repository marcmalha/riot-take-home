<?php

namespace App\Services\Encryptor;

class Encryptor implements Contracts\Encryptor
{
    protected function defaultEncryptionMethod(): callable
    {
        return fn (string $data) => base64_encode($data);
    }

    protected function defaultDecryptionMethod(): callable
    {
        return fn (string $data) => base64_decode($data, true);
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

    /**
     * @param  null|callable(string): string  $decryptionMethod  Should return false if input string is not encrypted
     * */
    public function decrypt(array $data, ?callable $decryptionMethod = null): array
    {
        $decryptionMethod ??= $this->defaultDecryptionMethod();

        return collect($data)
            ->map(function ($value) use ($decryptionMethod) {
                $decryptedValue = $decryptionMethod($value);

                if ($decryptedValue === false) {
                    return $value;
                }

                return json_decode($decryptedValue, true);
            })
            ->toArray();
    }
}
