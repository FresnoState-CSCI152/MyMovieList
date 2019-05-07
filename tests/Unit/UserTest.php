<?php

namespace Tests\Feature;

use Tests\CreatesApplication;
use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

class UserTest extends BaseTestCase
{
    use CreatesApplication;
    public $baseUrl = 'http://localhost';
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
        $response->assertResponseStatus(200);
    }

	public function testGetMovieData()
	{
		$response = $this->json('GET', '/GetMovieData', ['user_id' => 1]);
		$response->assertResponseStatus(201);
		$response = $this->getJson('HomeController@GetMovieData', ['user_id' => 1]);
		$response->assertResponseStatus(201);
	}

	public function testGetRecommended()
	{
		$response = $this->json('GET','/GetRecommended', ['user_id' => 1]);
		$response->assertResponseStatus(201);
	}

	public function testPage()
	{
		$this->visit('login');
   		$this->assertResponseOK();
	}

	public function testPage1()
	{
		$this->visit('search');
   		$this->assertResponseOK();
	}

	public function testPage2()
	{
		$this->visit('homestead');
   		$this->assertResponseOK();

    	$this->call('post', 'owners');
    	$this->assertResponseStatus(200); // Method not allowed
	}

}
