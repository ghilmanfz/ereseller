<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_successful(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
