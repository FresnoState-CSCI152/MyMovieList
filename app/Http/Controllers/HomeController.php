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
        if (Auth::check()) return view('homestead', ['homestead'=>true]);
        else
            return view('home/home', ['homestead' => true]);
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
        //Query TMDB
        $reqString = '';
        if(count($movie_data) == 0) {
            $reqString = "https://api.themoviedb.org/3/movie/top_rated?api_key=".env("TMD_API_KEY","")."&language=en-US&page=1";
        } else {
            $reqString = "https://api.themoviedb.org/3/movie/".$movie_data[$randomInputIndex]->tmdb_id."/recommendations?api_key=".env("TMD_API_KEY","")."&language=en-US&page=2";
        }
        $json = json_decode(file_get_contents($reqString));
        $results = $json->results;
        //Filter and return results
        $related_results = array();
        $index = 0;
        while(count($related_results) != 10 && $index < count($results)) {
            if (!in_array($results[$index]->id, $all_tmdb_ids)) {
                 array_push($related_results, $results[$index]);
            }
            $index++;
        }
        return response()->json([
            'success' => $related_results
        ]);
    }

    public function GetUserRecommended(Request $request){
        $user_id = request('user_id');
        $genre = 'all_genres';
        $recommends = DB::table('movie_reviews')
            ->join('movie_data','movie_data.tmdb_id','=','movie_reviews.tmdb_id')
            ->join('recommends', 'recommends.movie_review_id', '=', 'movie_reviews.id')
            ->join('users', 'users.id', '=', 'recommends.recommender_id')
            ->select(
                'movie_reviews.id as movie_review_id',
                'movie_reviews.user_id as reviewer_id',
                'movie_reviews.user_score',
                'movie_reviews.review',
                'movie_reviews.tmdb_id',
                'movie_data.tmdb_score',
                'movie_data.title',
                'movie_data.img_path',
                'movie_data.release',
                'movie_data.description',
                'users.name as r_name',
                'recommends.id as r_id'
            )
            ->orderBy('movie_data.tmdb_score', 'DESC')
            ->where('recommends.recommendee_id', $user_id)
            ->get();
        if ($genre !== 'all_genres') {
            $recommends = $recommends->where("movie_data.$genre", true);
        }

        return response()->json([
            'success' => $recommends
        ]);
    }
}
