<?php

namespace Tests\Feature;

use Tests\TestCase;

class VerifyApiKeyTest extends TestCase
{
    public function test_fail_without_api_key(): void
    {
        $response = $this->postJson('/api');

        $response->assertStatus(403);
    }

    public function test_fail_with_wrong_api_key(): void
    {
        $response = $this->withHeaders([
            'x-api-key' => 'a-wrong-key',
        ])->get('/api');

        $response->assertStatus(403);
    }

    public function test_success_with_correct_api_key(): void
    {
        $response = $this->withHeaders([
            'x-api-key' => config('services.api_key'),
        ])->get('/api');

        $response->assertStatus(200);
    }
}
