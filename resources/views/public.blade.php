@extends ('templates/master')

@section ('content')

    <div class='flex-center position-ref full-height'>
        <div class='top-right links'>
            @auth
                <div class='container'>
                    <div class='row justify-content-between mb-3'>
                        <div class='col-10'>
                            <h1>{{ $publicUser = \App\User::find($userId)->name}}'s public profile.</h1>
                        </div>
                        <div class='col-2'>

                            {{-- need to add ability to check if already friend --}}

                            {{-- {{ Auth::user()->friends()->get() }} --}}
                            {{-- {{ \App\User::find($userId) }} --}}
                            {{-- if current user's friend_id == public user's user_id --}}
                            {{-- then they are friends --}}

                            {{-- @if(Gate::allows("go-to-user-reviews", $userId)) --}}
                                            
                            <form method="POST" action="/friends/createrequest">
                    
                                {{ csrf_field() }}                
    
                                <input type="hidden" id="name" name="name" value="{{ $publicUser }}">
                                <button type="submit" class="btn btn-primary mb-2">Send Friend Request</button>
                        
                                @include ("errors/fielderrors", ["fieldName" => "name"])
                                @include ("flash-messages/success", ["successVar" => "requestSuccess"])
                        
                            </form>
                    
                        </div>                           
                    </div>                                  
                </div>                 

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

                    {{-- if the user has no reviews and no recommends, display hint --}}
                    <div class='row justify-content-center mt-4'>
                        <div class='col-md'>
                            @if(count($reviews) == 0 && count($recommends) == 0)
                                <h3>This user has no reviews.</h3>
                                {{-- @if($friends->id) --}}
                                {{--     <h3>Recommend your friend some movies. 😊</h3> --}}
                                {{-- @else --}}
                                {{--     <h3>Add them as a friend to recommend them movies. 😊</h3> --}}
                                {{-- @endif --}}
                            @endif
                        </div>
                    </div>
                </div>
            @endauth
        </div>
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
