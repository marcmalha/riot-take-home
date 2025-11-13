<?php

namespace App\Services\DataSignatureProvider;

use App\Services\DataSignatureProvider\Contracts\DataSignatureProvider as DataSignatureProviderContract;

class DataSignatureProvider implements DataSignatureProviderContract
{
    // Can be any of the values obtained from hash_hmac_algos()
    const HASHING_ALGORITHM = 'sha256';

    public function sign(array $data): string
    {
        $hashingAlgorithm = env('DATA_SIGNATURE_HASHING_ALGO') ?: self::HASHING_ALGORITHM;

        $normalizedData = collect($data)
            ->dot()
            ->sortKeys()
            ->toJson();

        return hash_hmac($hashingAlgorithm, $normalizedData, env('DATA_SIGNATURE_KEY'));
    }

    public function verify(string $signature, array $data): bool
    {
        $computedSignature = $this->sign($data);

        return hash_equals($computedSignature, $signature);
    }
}
