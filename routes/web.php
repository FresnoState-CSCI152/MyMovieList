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
Route::get('reviews/{userId}/{genre}', 'PageController@getReviewCards');
Route::get('recommends/{userId}/{genre}', 'PageController@getRecommendCards');

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

Route::get('/discussion', 'PostsController@index'); //Show all post
Route::get('/discussion/create', 'PostsController@create'); //Show a form to create the post
Route::get('/discussion/{post}', 'PostsController@show'); //Display the post
Route::post('/discussion', 'PostsController@store'); //Store the post
Route::get('/discussion/{post}/edit', 'PostsController@edit'); //Show a form to edit existing post
Route::patch('/discussion/{post}', 'PostsController@update'); //Update the edited post
Route::delete('/discussion/{post}', 'PostsController@destroy'); //Delete a post

Route::post('/discussion/{post}/comments', 'CommentsController@store'); //Comment on the post

// Recommend functionality
Route::post('recommends/create', 'PageController@recommendMovie');
