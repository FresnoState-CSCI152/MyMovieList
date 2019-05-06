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

    //Test Views
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

    //Test TMDB response
    public function testGetTMDB()
    {
        $response = $this->json('POST', '/TMDB', ['data'=>'batman']);
        $response->assertStatus(404);
    }

	public function testGetMovieData()
	{
		$response = $this->json('GET', '/GetMovieData', ['user_id' => 1]);
		$response->assertStatus(401);
	}

	public function testGetRecommended()
	{
		$response = $this->json('GET','/GetRecommended', ['user_id' => 1]);
		$response->assertStatus(401);
	}

}
