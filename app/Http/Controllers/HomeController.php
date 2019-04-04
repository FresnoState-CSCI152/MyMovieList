<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Movie_Data;
use App\Movie_Review;
use Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('homestead');
    }

    public function GetMovieData(Request $request){
        $user_id = request('user_id');
        $movie_data = DB::table('movie_reviews')->
                select('tmdb_id', 'user_score', 'review')->
                where('user_id', $user_id)->get();
        $merged = array();
        for ($i = 0; $i < count($movie_data); $i++) {
                $tmdb_data = DB::table('movie_data')->
                            where('tmdb_id', $movie_data[$i]->tmdb_id)->get();
                array_push($merged, $tmdb_data);
        }

        //tmdb_id: 268, user_score: 10, review: "I enjoyed the performances!",
        return response()->json([
            'data' => $movie_data,
            'movie_data' => $merged,
            'success' => true,
        ]);
    }
}
