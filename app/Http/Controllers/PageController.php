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
class PageController extends Controller
{
	public function home()
	{
		if (Auth::check())
		{
            list($reviews, $recommends) = $this->userReviews(Auth::user()->id);
            return view('home', ['reviews' => $reviews,
                                 'recommends' => $recommends,
                                 'userId' => Auth::user()->id,
                                 'friends' => Auth::user()->friends()->get(),]);
		}
		else
			return view('home');
    }
    public function friendsMovies($friendId)
    {
        $friend = \App\User::find($friendId);
        if (Gate::allows("go-to-user-reviews", $friendId)) {
            list($reviews, $recommends) = $this->userReviews($friendId);
            return view('home', ['reviews' => $reviews,
                                 'recommends' => $recommends,
                                 'userId' => $friendId,
                                 'friends' => $friend->friends()->get(),]);
        }
        else {
            return view("errors/unauthorized");
        }
    }
    private function userReviews($userId)
    {
        $reviews = DB::table('movie_reviews')
        ->join('movie_data','movie_data.tmdb_id','=','movie_reviews.tmdb_id')
        ->select('movie_reviews.id as movie_review_id', 'movie_reviews.review','movie_reviews.user_score', 'movie_data.tmdb_score','movie_data.title','movie_data.img_path', 'movie_data.release', 'movie_data.description')
        ->where('movie_reviews.user_id', $userId)->orderBy('movie_reviews.user_score', 'DESC')->get();
        $recommends = DB::table('movie_reviews')
            ->join('movie_data','movie_data.tmdb_id','=','movie_reviews.tmdb_id')
            ->join('recommends', 'recommends.movie_review_id', '=', 'movie_reviews.id')
            ->select('movie_reviews.id as movie_review_id',
                     'movie_reviews.review',
                     'movie_reviews.user_score',
                     'movie_reviews.tmdb_id',
                     'movie_data.tmdb_score',
                     'movie_data.title',
                     'movie_data.img_path',
                     'movie_data.release',
                     'movie_data.description',
                     'recommends.recommender_id',
                     'recommends.created_at',
                 	'recommends.id as r_id')
            ->orderBy('recommends.created_at', 'DESC')
            ->where('recommends.recommendee_id', $userId)
            ->get();
            return [$reviews, $recommends];
    }
    public function recommendMovie(Request $request)
    {
		$movieReviewId = request('movie_review_id');
        $validator = Validator::make($request->all(), [
            'recommendee_id' => [
				'bail',
                'required',
                function ($attribute, $value, $fail) use ($movieReviewId) {
                    $num = DB::table('recommends')->where([
                        ['recommender_id', '=', Auth::user()->id],
                        ['recommendee_id', '=', $value],
                        ['movie_review_id', '=', $movieReviewId],
                    ])->get()->count();
                    if ($num > 0) {
                        $fail('You have already recommended that movie to that friend.');
                    }
                },
                function ($attribute, $value, $fail) use ($movieReviewId) {
					$tmdbId = DB::table('movie_reviews')->find($movieReviewId)->tmdb_id;
					$num = DB::table('movie_reviews')
						->where('user_id', $value)
						->where('tmdb_id', $tmdbId)
						->get()
						->count();
                    if ($num > 0) {
                        $fail("You can't recommend to a friend a movie they've already reviewed.");
                    }
                },
            ]
        ]);
        if ($validator->fails()) {
            $viewFailure = view('flash-messages/alert-ajax')
                               ->with('failureMessage', $validator->errors()->first())
                               ->render();
            return response()->json([
                'html' => $viewFailure,
                'success' => false,
            ]);
        }
        $recommender = Auth::user();
        $recommendeeId = request('recommendee_id');
        $recommendedToOthers = $recommender->belongsToMany('App\User', 'recommends', 'recommender_id', 'recommendee_id')->withTimestamps();
        $recommendedToOthers->attach($recommendeeId, ['movie_review_id' => request('movie_review_id')]);
        $viewSuccess = view('flash-messages/alert-ajax')
                               ->with('successMessage', 'You have successfully recommended the movie.')
                               ->render();
        return response()->json([
            'html' => $viewSuccess,
            'success' => true,
        ]);
    }
	public function about()
	{
		return view('about');
	}
	public function account()
	{
		return view('account');
	}
	public function search()
	{
		if (Auth::check()) return view('search');
		else
			return view('home');
	}
	public function getTMDBjson(Request $request) {
		$searchString = $request->input('data');
		$reqString = "https://api.themoviedb.org/3/search/movie?api_key=".env("TMD_API_KEY","")."&language=en-US&query=".$searchString."&page=1";
		$json = json_decode(file_get_contents($reqString));
		return response()->json([
			'success' => $json
		]);
	}
	public function saveMovieReview(Request $request) {
		$validatedData = Validator::make($request->all(), [
			'user_id' => 'required|unique:movie_reviews,user_id,NULL,id,tmdb_id,'.$request->tmdb_id,
			'tmdb_id' => 'required|unique:movie_reviews,tmdb_id,NULL,id,user_id,'.$request->user_id,
		]);
		if ($validatedData->fails()) {
			return response()->json([
				'exists' => 'You already have a review for this movie!',
			]);
		}
		try {
			//Models
			$Review = new Movie_Review;
			$Review->user_id = $request->user_id;
			$Review->tmdb_id = $request->tmdb_id;
			$Review->user_score = $request->user_score;
			$Review->review = $request->user_review;
			if($Review->save()) {
				if(request('r_id')) {
					DB::table('recommends')->where('id',request('r_id'))->delete();
				}
				return response()->json([
					'success' => 'Review saved!'
				]);
			}
		}
		catch(\Exception $e) {
			return response()->json([
				'err' => $e->getMessage(),
			]);
		}
	}

	public function saveMovieData(Request $request){
		$validatedData = Validator::make($request->all(), [
			'tmdb_id' => 'required|unique:movie_data'
		]);
		if ($validatedData->fails()) {
			return response()->json([
				'Exists' => 'Data already exists in database',
			]);
		}
		try {
			//process data and submit
			$MovDat = new Movie_Data;
			$MovDat->tmdb_id = $request->input('tmdb_id');
			$MovDat->tmdb_score = $request->input('tmdb_score');
			$MovDat->title = $request->input('title');
			$MovDat->img_path = $request->input('img_path');
			$MovDat->release = $request->input('release');
			$MovDat->description = $request->input('description');
			//If successful return sucess!
			$MovDat->save();
			return response()->json([
				'success' => 'success',
			]);
		}
		catch(\Exception $e) {
				return response()->json([
				'err' => $e->getMessage(),
			]);
		}
	}
}
