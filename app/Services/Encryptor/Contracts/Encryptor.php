<?php

namespace App\Services\Encryptor\Contracts;

// While I know that in some frameworks we suffix interface names by "Interface",
// I followed Laravel's interface naming conventions for the interface below
interface Encryptor
{
    /**
     * @param  null|callable(string): string  $encryptionMethod
     */
    public function encrypt(array $data, ?callable $encryptionMethod = null): array;

    /**
     * @param  null|callable(string): string  $decryptionMethod
     */
    public function decrypt(array $data, ?callable $decryptionMethod = null): array;
}
