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
Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::get('homestead', 'HomeController@index');
Route::get('movies','PageController@home');
Route::get('about','PageController@about');
Route::get('account','PageController@account');
Route::get('public/{publicId}', 'PageController@publicProfile');

// Search get and post methods
Route::get('search', 'PageController@search');
Route::get('GetMovieData', 'HomeController@GetMovieData');
Route::post('TMBD', 'PageController@getTMDBjson');
Route::post('TMBDdat', 'PageController@saveMovieData');
Route::post('MovieReview', 'PageController@saveMovieReview');
Route::post('EditReview', 'PageController@updateReview');
Route::get('reviews', 'PageController@getReviewCards');
Route::get('recommends', 'PageController@getRecommendCards');
Route::get('GetRecommended', 'HomeController@GetRecommended');
Route::get('GetUserRecommended', 'HomeController@GetUserRecommended');

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

// User functionality'
Route:: get('profile', 'UserController@profile');
Route:: get('profile/get_about_me', 'UserController@getAboutMe');
Route:: get('profile/get_user_info','UserController@getUserInfo');
Route:: post('profile/update_avatar/{id}', 'UserController@update_avatar')->name('update_avatar');
Route:: post('profile/update_personal_info/{id}', 'UserController@update_personal_info')->name('update_personal_info');
Route:: post('profile/update_about_me/{id}', 'UserController@update_about_me')->name('update_about_me');
Route::get('profile/changePasswordForm', 'UserController@showForm')->name('passwordForm');
Route::post('profile/updatePassword', 'UserController@updatePassword');

// Discussion and Comments functionality

Route::get('/discussion', 'PostsController@index'); //Show all post
Route::get('/discussion/create', 'PostsController@create'); //Show a form to create the post
Route::get('/discussion/{post}', 'PostsController@show'); //Display the post
Route::post('/discussion', 'PostsController@store'); //Store the post
Route::get('/discussion/{post}/edit', 'PostsController@edit'); //Show a form to edit existing post
Route::patch('/discussion/{post}', 'PostsController@update'); //Update the edited post
Route::delete('/discussion/{post}', 'PostsController@destroy'); //Delete a post
Route::post('/discussion/{post}/postUpdate', 'PostsController@votePost');

// comment functionality
Route::post('/discussion/{post}/comments', 'CommentsController@store'); //Comment on the post
Route::post('/discussion/{post}/commentVote/{id}', 'CommentsController@votePost'); // upvote/downvote comment
Route::delete('/discussion/{post}/deleteComment', 'CommentsController@deleteComment'); // delete a comment
Route::post('/discussion/{post}/editComment', 'CommentsController@editComment'); // edit a comment
Route::post('/comment/store', 'CommentsController@store')->name('add');
Route::post('/comment/replyStore', 'CommentsController@replyStore')->name('addReply');

// Recommend functionality
Route::post('recommends/create', 'PageController@recommendMovie');

// Private chat
Route::get('/chat/private', 'ChatController@show')->middleware('auth');
Route::post('/chat/private', 'ChatController@sendMessage')->middleware('auth');
