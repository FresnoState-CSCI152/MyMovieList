<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testViews()
    {
        $response = $this->get('/movies');
        $response->assertViewHas('home');
        $response = $this->get('/about');
        $response->assertViewHas('about');
        $response = $this->get('/account');
        $response->assertViewHas('account');
        $response = $this->get('/search');
        $response->assertViewHas('search');
    }

}
