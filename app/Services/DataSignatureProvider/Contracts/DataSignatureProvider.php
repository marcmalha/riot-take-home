<?php

namespace App\Services\DataSignatureProvider\Contracts;

interface DataSignatureProvider
{
    public function sign(mixed $data): string;

    public function verify(string $signature, mixed $data): bool;
}
