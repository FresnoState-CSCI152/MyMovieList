@extends ('templates/master')

@section ('content')

    <div class='flex-center position-ref full-height'>
        @if (Route::has('login'))
            <div class='top-right links'>
                @auth
                <div class='container'>
                    <div class='row justify-content-between mb-3'>
                        <div class='col-10'>
                            @if ($userId == Auth::user()->id)
                                <h1>Welcome, {{ Auth::user()->name }}!</h1>
                            @else
                                <h1>Welcome to {{ \App\User::find($userId)->name }}'s movie reviews</h1>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class='container'>
                    <div id='reviews' user-id='{{$userId}}'>
                        @include('home/reviews')
                    </div>
                    <div id='recommends' user-id='{{$userId}}'>
                        @include('home/recommends')
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
            recommendButton.hide();
            recommendForm.show();
        };

        function hideRecommendReviewForm(id) {
            var recommendButton = $('#recommended_review_button_'+id);
            var recommendForm = $('#review_for_'+id);
            recommendButton.show();
            recommendForm.hide();
        };

        //Toggle Edit Form
        function showEdit(id){
            var updateForm = $('#update_for_'+id);
            var editButton = $('#edit_button_'+id);
            updateForm.show();
            editButton.hide();
        };

        function hideEdit(id){
            var updateForm = $('#update_for_'+id);
            var editButton = $('#edit_button_'+id);
            updateForm.hide();
            editButton.show();
        };

        //Save edits to review
        function saveEdit(movie_id, user){
            //TODO: SAVE EDITS
            var starVal = $('#starRating_'+movie_id).val();
            var newReview = $('#edit_review_form_'+movie_id).val();

            var editedData = {
                'id': movie_id,
                'user_score': starVal,
                'user_review': newReview
            };

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });

            $.ajax({type: "POST",
                    url: "/EditReview",
                    data: editedData,
                    success: function (data) {
                        location.reload();
                    },
                    error: function (errorData) {
                        console.log(errorData);
                    },
                    dataType: "json",
            });
        };

        //Submit review for recommended movie
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
