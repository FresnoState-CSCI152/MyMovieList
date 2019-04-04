<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Route::get('/', function () {
    return view('home');
})->name('home');*/
Route::get('/', 'PageController@home');
Route::get('home','PageController@home');
Route::get('about','PageController@about');
Route::get('account','PageController@account');
Route::get('public/{publicId}', 'PageController@publicProfile');
Route::get('homestead', 'HomeController@index');

// Search get and post methods
Route::get('search', 'PageController@search');
Route::get('GetMovieData', 'HomeController@GetMovieData');
Route::post('TMBD', 'PageController@getTMDBjson');
Route::post('TMBDdat', 'PageController@saveMovieData');
Route::post('MovieReview', 'PageController@saveMovieReview');
Route::post('EditReview', 'PageController@updateReview');

// Login and Register
Auth::routes();

// Friends functionality
Route::get('friends', 'FriendsController@friends')->middleware('auth')->name('friends');
Route::get('friends/{friendId}', 'PageController@friendsMovies')->middleware('auth');
Route::post('friends/createrequest', 'FriendsController@createFriendRequest')->middleware('auth');
Route::post('friends/cancelrequest', 'FriendsController@cancelFriendRequest')->middleware('auth');
Route::post('friends/create', 'FriendsController@createFriendship')->middleware('auth');
Route::post('friends/declinerequest', 'FriendsController@declineFriendRequest')->middleware('auth');
Route::post('friends/delete', 'FriendsController@deleteFriendship')->middleware('auth');

// User functionality
Route:: get('profile', 'UserController@profile');
Route:: post('profile', 'UserController@update_avatar');

// Discussion and Comments functionality
Route::get('/discussion', 'PostsController@index');
Route::get('/discussion/create', 'PostsController@create');
Route::get('/discussion/{post}', 'PostsController@show');
Route::post('/discussion', 'PostsController@store');
Route::post('/discussion/{post}/comments', 'CommentsController@store');

// Recommend functionality
Route::post('recommends/create', 'PageController@recommendMovie');
