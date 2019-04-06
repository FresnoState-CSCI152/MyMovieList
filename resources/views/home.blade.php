@extends ('templates/master')

@section ('content')

    <div class='flex-center position-ref full-height'>
        @if (Route::has('login'))
            <div class='top-right links'>
                @auth
                    @if ($userId == Auth::user()->id)
                        <h1>Welcome, {{ Auth::user()->name }}!</h1>
                    @else
                        <h1>Welcome to {{ \App\User::find($userId)->name }}'s movie reviews</h1>
                    @endif
                    <hr>

                    <div class='container'>

                        <div class='row justify-content-center mb-3'>
                            <div class='col-md'>
                                <div class='card shadow-sm bg-white rounded'>
                                    @if ($userId == Auth::user()->id)
                                        <h4 class='card-header'>{{ __('Your Reviewed Movies') }}</h4>
                                    @else 
                                        <h4 class='card-header'>{{ \App\User::find($userId)->name }}'s Reviewed Movies</h4>
                                    @endif
                                    <div class='card-body'>

                                        @if(count($reviews))
                                        @foreach($reviews as $review)

                                        <div class='container mb-5'>
                                        <div class='row'>
                                        <div class='col-3'>
                                                <img src='http://image.tmdb.org/t/p/w200{{$review->img_path}}'>
                                        </div>

                                        <div class='col-9'>
                                        <table class='table table-bordered'>
                                            <thead>
                                            <tr>
                                                <th scope='col' style='width: 13%'>Movie Title</th>
                                                <th scope='col'>Movie Description</th>
                                                <th scope='col' style='width: 16%'>Movie Release</th>
                                                <th scope='col' style='width: 14%'>TMDB Score</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>
                                                <td>{{$review->title}}</td>
                                                <td>{{$review->description}}</td>
                                                <td>{{$review->release}}</td>
                                                <td>{{$review->tmdb_score}}</td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <table class='table table-bordered'>
                                            <thead>
                                            <tr>
                                                @if ($userId == Auth::user()->id)
                                                    <th scope='col' style='width: 12%'>My Score</th>
                                                    <th scope='col'>My Review</th>
                                                @else
                                                    <th scope='col' style='width: 12%'>{{ \App\User::find($userId)->name }}'s Score</th>
                                                    <th scope='col'>{{ \App\User::find($userId)->name }}'s Review</th>
                                                @endif
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>
                                                <td>{{$review->user_score}}</td>
                                                <td>{{$review->review}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        
                                        @if ($userId == Auth::user()->id)
                                            <button id={{ 'recommend_button_'.$review->movie_review_id }} onclick="showRecommendForm({{ $review->movie_review_id }})" class='btn btn-primary mb-2'>Recommend to a friend</button>
                                            <form class='form-inline' id={{ 'recommend_form_'.$review->movie_review_id }} style='display: none'>
                                                <label class='sr-only' for='recommendee_id'>Name</label>
                                                <select class='custom-select mb-2 mr-sm-2' id={{ 'recommendee_id_'.$review->movie_review_id }} name='recommendee_id' required>
                                                    @foreach ($friends as $friend)
                                                        <option value='{{ $friend->id }}'>{{ $friend->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input value='{{ $review->movie_review_id }}' id='movie_review_id' name='movie_review_id' style='display: none'>

                                                <button type='button' class='btn btn-danger mb-2' onclick="hideRecommendForm({{ $review->movie_review_id }})">Cancel</button>
                                                <button type='button' class='btn btn-primary mb-2 ml-2' onclick="recommendMovie({{ $review->movie_review_id }})">Recommend</button>

                                            </form>
                                            <div id={{ 'recommend_message_'.$review->movie_review_id }}></div>

                                            @include ("errors/fielderrors", ["fieldName" => "recommendee_id"])
                                            @include ("flash-messages/success", ["successVar" => "recommendSuccess"])
                                        @endif

                                        </div>
                                        </div>
                                        </div>
                                        @endforeach
                                        @else
                                            <h6>No reviewed movies.</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='row justify-content-center mb-3'>
                            <div class='col-md'>
                                <div class='card shadow-sm bg-white rounded'>
                                    <h4 class='card-header'>{{ __('Recommended Movies') }}</h4>
                                    <div class='card-body'>

                                        @if(count($recommends))
                                        @foreach($recommends as $recommend)

                                        <div class='container mb-5'>
                                        <div class='row'>
                                        <div class='col-3'>
                                                <img src='http://image.tmdb.org/t/p/w200{{$recommend->img_path}}'>
                                        </div>

                                        <div class='col-9'>
                                        <h5>Recommended by <a href="/friends/{{ \App\User::find($recommend->recommender_id)->id }}">{{ \App\User::find($recommend->recommender_id)->name }}</a></h5>
                                        <table class='table table-bordered'>
                                            <thead>
                                            <tr>
                                                <th scope='col' style='width: 13%'>Movie Title</th>
                                                <th scope='col'>Movie Description</th>
                                                <th scope='col' style='width: 16%'>Movie Release</th>
                                                <th scope='col' style='width: 14%'>TMDB Score</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>
                                                <td>{{$recommend->title}}</td>
                                                <td>{{$recommend->description}}</td>
                                                <td>{{$recommend->release}}</td>
                                                <td>{{$recommend->tmdb_score}}</td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <table class='table table-bordered'>
                                            <thead>
                                            <tr>
                                                <th scope='col' style='width: 13%'>{{ \App\User::find($recommend->recommender_id)->name }}'s Score</th>
                                                <th scope='col'>{{ \App\User::find($recommend->recommender_id)->name }}'s Review</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>
                                                <td>{{$recommend->user_score}}</td>
                                                <td>{{$recommend->review}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!--Submit Review-->
                                        @if ($userId == Auth::user()->id)
                                        <button id={{ 'recommended_review_button_'.$recommend->movie_review_id }} onclick="showRecommendReviewForm({{ $recommend->movie_review_id }})" class='btn btn-primary mb-2'>Review Movie</button>
                                        <div id="review_for_{{ $recommend->movie_review_id }}">
                                        <x-star-rating id="starRating_{{ $recommend->movie_review_id }}" value="0" number="10"></x-star-rating><div class="form-group"><label for="review">Your Review:</label><textarea class="form-control" id="recommended_review_form_{{ $recommend->movie_review_id }}" rows="3"></textarea>
                                        <button id={{ 'submit_review_button_'.$recommend->movie_review_id }} onclick="submit_reivew({{ Auth::user()->id}},{{ $recommend->movie_review_id }}, {{ $recommend->r_id }}, {{ $recommend->tmdb_id }})" class='btn btn-primary mb-2'>Submit Review</button>
                                        <button id={{ 'cancel_review_button_'.$recommend->movie_review_id }} onclick="hideRecommendReviewForm({{ $recommend->movie_review_id }})" class='btn btn-primary mb-2 btn btn-danger'>Cancel Review</button>
                                        </div>
                                        </div>
                                        <script type="text/javascript">
                                            var recommendForm = $('#review_for_'+{{ $recommend->movie_review_id }});
                                            recommendForm.hide();
                                        </script>
                                        @endif
                                        <!-- End Submit Review-->
                                        </div>
                                        </div>
                                        </div>
                                        @endforeach
                                        @else
                                            <h6>No recommended movies.</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- if the user has no reviews and no recommends, display hint --}}
                        <div class='row justify-content-center mt-4'>
                            <div class='col-md'>
                                @if(count($reviews) == 0 && count($recommends) == 0)
                                    <h3>Get started by <a href ="/search">searching</a> for a movie and writing a review! 😃</h3>
                                @endif
                            </div>
                        </div>

                    </div>


                @else
                    <h1>Welcome to MyMovieList!</h1>
                    <h2>Please register or login!</h2>
                    <div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address or Username') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('email') || $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                <a class="btn btn-link" href="{{ route('register') }}">
                                    {{ __('Register') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
                @endauth
            </div>
        @endif
    </div>


    <script type='text/javascript'>

        //toggle recommend review
        function showRecommendReviewForm(id) {
            var recommendButton = $('#recommended_review_button_'+id);
            var recommendForm = $('#review_for_'+id);
            console.log(id);
            recommendButton.hide();
            recommendForm.show();
        };

        function hideRecommendReviewForm(id) {
            var recommendButton = $('#recommended_review_button_'+id);
            var recommendForm = $('#review_for_'+id);
            recommendButton.show();
            recommendForm.hide();
        };

        function submit_reivew(user,id, r_id, tmdb_id){
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });

            var movRevData = {
                'user_id': user,
                'tmdb_id':tmdb_id,
                'user_score': $('#starRating_'+id).val(),
                'user_review': $('#recommended_review_form_'+id).val(),
                'r_id': r_id
            }

            console.log(movRevData);
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });
            $.ajax({type: "POST",
                    url: "/MovieReview",
                    data: movRevData,
                    success: function (data) {
                        location.reload();
                    },
                    error: function (errorData) {
                        console.log(errorData);
                    },
                    dataType: "json",
            });
        };
        //Recommend to a friend
        function showRecommendForm(id) {
            var recommendButton = $('#recommend_button_'+id);
            var recommendForm = $('#recommend_form_'+id);
            recommendButton.hide();
            recommendForm.show();
        };

        function hideRecommendForm(id) {
            var recommendButton = $('#recommend_button_'+id);
            var recommendForm = $('#recommend_form_'+id);
            recommendButton.show();
            recommendForm.hide();
        };

        function recommendMovie(movieReviewId) {
            friendId = parseInt($('#recommendee_id_'+movieReviewId).find(':selected').attr('value'));
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });
            $.ajax({type: "POST",
                    url: "/recommends/create",
                    data: {
                        "recommendee_id": friendId,
                        "movie_review_id": movieReviewId,
                    },
                    success: function (data) {
                        if (data["success"]) {
                            hideRecommendForm(movieReviewId);
                            $("#recommend_message_" + movieReviewId).append(data["html"]);
                            console.log(data["success"]);
                        }
                        else {
                            $("#recommend_message_" + movieReviewId).append(data["html"]);
                            console.log(data["success"]);
                        }
                    },
                    error: function (errorData) {
                        console.log(errorData);
                    },
                    dataType: "json",
            });
        }

        //Stars 
        class StarRating extends HTMLElement {
            get value () {
                return this.getAttribute('value') || 0;
            }

            set value (val) {
                this.setAttribute('value', val);
                this.highlight(this.value - 1);
            }

            get number () {
                return this.getAttribute('number') || 5;
            }

            set number (val) {
                this.setAttribute('number', val);

                this.stars = [];

                while (this.firstChild) {
                    this.removeChild(this.firstChild);
                }

                for (let i = 0; i < this.number; i++) {
                    let s = document.createElement('div');
                    s.className = 'star';
                    this.appendChild(s);
                    this.stars.push(s);
                }

                this.value = this.value;
            }

            highlight (index) {
                this.stars.forEach((star, i) => {
                    star.classList.toggle('full', i <= index);
                });
            }

            constructor () {
                super();

                this.number = this.number;

                this.addEventListener('mousemove', e => {
                    let box = this.getBoundingClientRect(),
                        starIndex = Math.floor((e.pageX - box.left) / box.width * this.stars.length);

                    this.highlight(starIndex);
                });

                this.addEventListener('mouseout', () => {
                    this.value = this.value;
                });

                this.addEventListener('click', e => {
                    let box = this.getBoundingClientRect(),
                        starIndex = Math.floor((e.pageX - box.left) / box.width * this.stars.length);

                    this.value = starIndex + 1;

                    let rateEvent = new Event('rate');
                    this.dispatchEvent(rateEvent);
                });
            }
        }

        customElements.define('x-star-rating', StarRating);

    </script>

@endsection
