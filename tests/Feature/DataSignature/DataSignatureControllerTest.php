<?php

namespace Tests\Feature\DataSignature;

use Illuminate\Http\Response;
use Tests\TestCase;

class DataSignatureControllerTest extends TestCase
{
    public function test_sign_endpoint_returns_200(): void
    {
        $this->postJson(
            'sign',
            [
                'message' => 'Hello World',
                'timestamp' => 1616161616,
            ]
        )->assertStatus(Response::HTTP_OK);
    }

    public function test_sign_endpoint_returns_payload_with_correct_structure(): void
    {
        $this->postJson(
            'sign',
            [
                'message' => 'Hello World',
                'timestamp' => 1616161616,
            ]
        )->assertJsonStructure([
            'signature',
        ]);
    }

    public function test_verify_endpoint_returns_204_if_signature_valid(): void
    {
        $signature = $this->postJson(
            'sign',
            [
                'message' => 'Hello World',
                'timestamp' => 1616161616,
            ]
        )->json('signature');

        $this->postJson(
            'verify',
            [
                'signature' => $signature,
                'data' => [
                    'message' => 'Hello World',
                    'timestamp' => 1616161616,
                ],
            ]
        )->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_verify_endpoint_returns_422_if_input_json_has_invalid_structure(): void
    {
        $this->postJson(
            'verify',
            [
                'data' => [
                    'message' => 'Hello World',
                    'timestamp' => 1616161616,
                ],
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_verify_endpoint_returns_400_if_signature_invalid(): void
    {
        $this->postJson(
            'verify',
            [
                'signature' => 'r4nd0m_s1gn4ture_v4lue',
                'data' => [
                    'message' => 'Hello World',
                    'timestamp' => 1616161616,
                ],
            ]
        )->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
