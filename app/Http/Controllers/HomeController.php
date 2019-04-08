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
                where('user_id', $user_id)->
                orderby('user_score', 'desc')->
                limit(10)->get();
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

    public function GetRecommended(Request $request) {
        $user_id = request('user_id');
        //Get All TMDB ids
        $tmbd_ids = DB::table('movie_reviews')->select('tmdb_id')->get();
        $all_tmdb_ids = array();
        for ($i=0; $i < count($tmbd_ids) ; $i++) { 
            array_push($all_tmdb_ids, $tmbd_ids[$i]->tmdb_id);
        }
        //select top 10 tmdb to get random recommended query
        $movie_data = DB::table('movie_reviews')->
                    select('tmdb_id')->
                    where('user_id', $user_id)->
                    orderby('user_score', 'desc')->
                    limit(10)->get();
        $randomInputIndex = rand(0, count($movie_data));
        $reqString = "https://api.themoviedb.org/3/movie/".$movie_data[$randomInputIndex]->tmdb_id."/similar?api_key=".env("TMD_API_KEY","")."&language=en-US&page=2";
        $json = json_decode(file_get_contents($reqString));
        $results = $json->results;
        $test = array();
        $index = 0;
        while(count($test) != 10 && $index < count($results)) {
            if (!in_array($results[$index]->id, $all_tmdb_ids)) {
                 array_push($test, $results[$index]);
            }
            $index++;
        }
        return response()->json([
            'success' => $test
        ]);
    }
}
