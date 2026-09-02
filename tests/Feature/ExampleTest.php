<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Homepage smoke test: the public landing page must render.
     */
    public function test_the_home_page_returns_a_successful_response(): void
    {
        $this->get('/')->assertStatus(200);
    }
}