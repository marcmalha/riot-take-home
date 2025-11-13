<?php

namespace App\Services\DataSignatureProvider\Contracts;

interface DataSignatureProvider
{
    public function sign(array $data): string;

    public function verify(string $signature, array $data): bool;
}
