<?php

namespace Tests\Unit\DataSignatureProvider;

use App\Services\DataSignatureProvider\Contracts\DataSignatureProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DataSignatureProviderUnitTest extends TestCase
{
    public function test_signature_depends_only_on_input_payload_value(): void
    {
        $payload = [
            'message' => 'Hello World',
            'timestamp' => 1616161616,
            'order' => [
                'hello' => [
                    '1' => 1,
                    '2' => 2,
                ],
                'world' => 'qwerqwer',
            ],
        ];
        $flippedKeysPayload = [
            'timestamp' => 1616161616,
            'order' => [
                'world' => 'qwerqwer',
                'hello' => [
                    '2' => 2,
                    '1' => 1,
                ],
            ],
            'message' => 'Hello World',
        ];

        /** @var DataSignatureProvider $dataSignatureProvider */
        $dataSignatureProvider = app()->make(DataSignatureProvider::class);

        $this->assertTrue(hash_equals($dataSignatureProvider->sign($payload), $dataSignatureProvider->sign($flippedKeysPayload)));
    }

    #[DataProvider('exhaustiveJsonProvider')]
    public function test_data_signature_provider_can_verify_signature($data): void
    {
        /** @var DataSignatureProvider $dataSignatureProvider */
        $dataSignatureProvider = app()->make(DataSignatureProvider::class);

        $signature = $dataSignatureProvider->sign($data);

        $this->assertTrue($dataSignatureProvider->verify($signature, $data));
    }

    public function test_data_signature_provider_can_reject_invalid_signature(): void
    {
        /** @var DataSignatureProvider $dataSignatureProvider */
        $dataSignatureProvider = app()->make(DataSignatureProvider::class);

        $data = [
            'message' => 'Hello World',
            'timestamp' => 1616161616,
            'order' => [
                'hello' => [
                    '1' => 1,
                    '2' => 2,
                ],
                'world' => 'qwerqwer',
            ],
        ];

        $this->assertFalse($dataSignatureProvider->verify('1nv4lid_s1gn4ture', $data));
    }

    public static function exhaustiveJsonProvider(): array
    {
        return [
            [[
                'message' => 'Hello World',
                'timestamp' => 1616161616,
                'order' => [
                    'hello' => [
                        '1' => 1,
                        '2' => 2,
                    ],
                    'world' => 'qwerqwer',
                ],
            ]],
            ['any_string_value'],
            [true],
            [false],
            [null],
            [[1, 2, 3, 4]],
            [123456],
        ];
    }
}
