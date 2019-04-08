@extends ('templates/master')

@section ('content')

{{--Overlay for list display --}}
<div id="overlay">
	<br />
	<br />
	<br />
	<br />

	<div class='container-fluid mb-5'>
	<div class='row'>
	<div class='col-3 pb-4'>
	        <img id="movie_image" class='img-fluid shadow' src=''>
	</div>
	{{-- Items on side of movie poster image--}}
  	<div class='col-9'>
	<div class="table-responsive">
		<table class='table table-bordered' style="color: white; background-color: rgba(0,0,0,1)">
		    <thead>
		    <tr>
		        <th scope='col' style='width: 13%;'>Movie Title</th>
		        <th scope='col'>Movie Description</th>
		        <th scope='col' style='width: 16%;'>Movie Release</th>
		        <th scope='col' style='width: 14%;'>TMDB Score</th>
		    </tr>
		    </thead>

		    <tbody>
		    <tr>
		        <td id="review_title"></td>
		        <td id="review_description"></td>
		        <td id="review_release"></td>
		        <td id="review_tmdb_score"></td>
		    </tr>
		    </tbody>
		</table>
	</div>{{--/table-repsonsive --}}

	<x-star-rating id="starRating_for_review" value="5" number="10"></x-star-rating>
	<textarea class="form-control" id="submit_review_textarea" rows="3"></textarea>
	<div id="my_review_table" class="table-responsive">
	<table class='table table-bordered' style='color: white; background-color: rgba(0,0,0,1)'>
	    <thead>
	    <tr>
	        <th scope='col' style='width: 12%;'>My Score</th>
	        <th scope='col'>My Review</th>
	    </tr>
	    </thead>
	    <tbody>
	    <tr>
	        <td id="review_user_score"></td>
	        <td id="review_user_review"></td>
	    </tr>
	    </tbody>
	</table>
	</div>{{--/my_reivew_table--}}

	<button id="overlay_button_home" class="btn btn-primary" onclick="location.href='home';">Go To Full List</button>
	<button id="overlay_button_submit" class="btn btn-primary" onclick="confirmReview()">Submit</button>
	<button class="btn btn-danger" onclick="overlay_off()">Cancel</button>

	</div> {{--/Col-9 --}}
	</div> {{--/Row--}}
	</div>{{--/Contaier --}}
</div>

{{--three lists --}}
<div class="container">
	<div class="row">
		<div class="col">
			<div class="mx-auto" style="width: 50%;">
				<b>Top of Your List</b>
			</div>
			<div id="top-10-list">
			</div>
		</div>
		<div class="col">
			<div class="mx-auto" style="width: 50%;">
				<b>Top Recommended</b>
			</div>
			<div id="top_recommended">
			</div>
		</div>
		<div class="col">
			<div class="mx-auto" style="width: 50%;float:left;">
				<b>Movies You May Enjoy</b>
			</div>
			<button class="btn btn-primary" onclick="auto_recommend()">Refresh</button>
			<div id="recommended_from_tmdb">
			</div>
		</div>
	</div>
</div>
     
{{-- Script --}}
<script>
	filter_num = 1;
	tmbd_num = 0;
	r_num = 0;
	//change later
	user_id = {{Auth::user()->id}};
	cur_r_id = 0;
	r_tmdb_id = 0;

	//Send for review
	var movRevData = {};
	var movdata = [];
	var current_title = '';
	var description_string = [];

	//Top Ten List
	function fill_my_top_ten(movie_list, tmbd_data){

		var movie_img, movie_title, movie_description, movie_release, tmdb_score;

		tmbd_data.forEach(function(data){
			data.forEach(function(fields){
				if(fields.tmdb_id == movie_list.tmdb_id){
					movie_img = fields.img_path;
					movie_title = fields.title;
					movie_description = fields.description;
					movie_release  = fields.release;
					tmbd_score = fields.tmdb_score;
					description_string.push({'id': fields.tmdb_id, 'description': movie_description,});
				}
			});
		});

		//Arguments to send for overlay

		onButtonArguments =  movie_list.tmdb_id + ',\'' + String(movie_title) + '\', \'' +  String(movie_img) + '\', \'' + String(movie_release) + '\', \'' + String(tmbd_score) + '\'';

		completeButtonArgs = onButtonArguments + ', \'' + String(movie_list.user_score) + '\', \'' + String(movie_list.review.replace('\'', '')) + '\'';

		list_string = '<div class="card"><div class="card-body"><h4 class="card-title border border-dark">' + (filter_num++) + '. &nbsp;&nbsp;' +  movie_title + '</h4><img src="http://image.tmdb.org/t/p/w200'+ movie_img + '" alt="Card image cap" style="height: 10rem; float: left; padding-right: 10px;"><p><b>Your Score: ' + movie_list.user_score + '</b></p><button class="btn btn-primary" onclick="overlay_on(' + completeButtonArgs + ', false)">Review Details</button></div></div>';

		$('#top-10-list').append(list_string);
	}

	//Fill user_recommended
	function fill_user_recommended(movie_data) {
		r_num++;
		movie_title = movie_data.title;
		movie_img = movie_data.img_path;
		tmdb_score = movie_data.tmdb_score;
		release_date = movie_data.release;
		description_string.push({'id': movie_data.tmdb_id, 'description': movie_data.description,});

		//title, img, description, release, tmbd_score, user_score, review,
		completeButtonArgs = movie_data.tmdb_id + ',\'' + String(movie_title) + '\', \'' + movie_img + '\', \'' + release_date + '\',\'' + tmdb_score + '\',\'0\',\'placeholder\', true, ' + movie_data.r_id;
		list_string = '<div class="card"><div class="card-body"><h4 class="card-title border border-dark">&nbsp;&nbsp;' +  movie_title + '</h4><img src="http://image.tmdb.org/t/p/w200'+ movie_img + '" alt="Card image cap" style="height: 10rem; float: left; padding-right: 10px;"><p>Recommended by <b>' + movie_data.r_name + '<b></p><button class="btn btn-primary" onclick="overlay_on(' + completeButtonArgs + ')">Review</button></div></div>';

		$('#top_recommended').append(list_string);
	}


	//Fill recommended
	function fill_my_recommended(movie_data) {
		tmbd_num++;
		movie_title = movie_data.title;
		movie_img = movie_data.poster_path;
		tmdb_score = movie_data.vote_average;
		release_date = movie_data.release_date;

		//Set For Submission
		movdata.push({
			'title': movie_title,
			'tmdb_id': movie_data.id,
			'img_path': movie_img,
			'release': release_date,
			'tmdb_score': tmdb_score,
			'description': movie_data.overview,
			'genre_ids': movie_data.genre_ids,
		});
		description_string.push({'id': movie_data.id, 'description': movie_data.overview,});

		//title, img, description, release, tmbd_score, user_score, review,
		completeButtonArgs = movie_data.id + ',\'' + String(movie_title) + '\', \'' + movie_img + '\', \'' + release_date + '\',\'' + tmdb_score + '\',\'0\',\'placeholder\'';
		list_string = '<div class="card"><div class="card-body"><h4 class="card-title border border-dark">&nbsp;&nbsp;' +  movie_title + '</h4><img src="http://image.tmdb.org/t/p/w200'+ movie_img + '" alt="Card image cap" style="height: 10rem; float: left; padding-right: 10px;"><p><b>TMDB Score: ' + tmdb_score + '</b></p><button class="btn btn-primary" onclick="overlay_on(' + completeButtonArgs + ', true)">Review</button></div></div>';

		$('#recommended_from_tmdb').append(list_string);
	}

	//Movie data from database
	function get_movie_data() {
		$('#top-10-list').empty();
		$.ajaxSetup({          
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });

		//Get Movie Data
		$.ajax({
			type: "GET",
			url: '/GetMovieData',
			data: {
				'user_id': user_id,
			},
			success: function(data) {
			    data.data.forEach(function(obj){
			    	fill_my_top_ten(obj, data.movie_data);
			    });
			},
			error: function(errorData) {
				return {};
			},
			dataType: "json",
		});

	};

	//Auto Recommend Feature
	function auto_recommend() {
		$('#recommended_from_tmdb').empty();
		tmbd_num = 0;
		$.ajaxSetup({          
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });
		//Get all TMDB Id's
		$.ajax({
			type: "GET",
			url: '/GetRecommended',
			data: {
				'user_id': user_id,
			},
			success: function(data) {
			    data.success.forEach(function(obj){
			    	fill_my_recommended(obj);
			    });
			},
			error: function(errorData) {
				auto_recommend();
			},
			dataType: "json",
		});
	}

	//Get recommended Data
	function recommended_data() {
		$('#top_recommended').empty();
		$.ajaxSetup({          
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });
		//Get all TMDB Id's
		$.ajax({
			type: "GET",
			url: '/GetUserRecommended',
			data: {
				'user_id': user_id,
			},
			success: function(data) {
			    data.success.forEach(function(obj){
			    	fill_user_recommended(obj);
			    });
			},
			error: function(errorData) {
				recommended_data();
			},
			dataType: "json",
		});
	}

	//Submit Review Information
	function confirmReview(){
		movRevData = {};
		$.ajaxSetup({          
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });

		var movieDataSubmit = {};
		movdata.forEach(function(obj){
			if(obj.title == current_title){
				movieDataSubmit = obj;
			}
		})

		if (cur_r_id != 0) {
			movRevData = {
				'user_id': user_id,
				'tmdb_id': r_tmdb_id,
				'user_score': $('#starRating_for_review').val(),
				'user_review': $('#submit_review_textarea').val(),
				'r_id': cur_r_id,
			}
		} else {
			movRevData = {
				'user_id': user_id,
				'tmdb_id': movieDataSubmit.tmdb_id,
				'user_score': $('#starRating_for_review').val(),
				'user_review': $('#submit_review_textarea').val()
			}
		}

		console.log(movRevData);
		$.ajax({
			type: 'POST',
			url: '/MovieReview',
			data: movRevData,
			success: function (data) {
					if (data['success']) {
						//If submission successful, refresh screen data;
						filter_num = 1;
						r_num = 1;
						cur_r_id = 0;
						description_string = [];
						movdata = [];
						overlay_off();
						get_movie_data();
						auto_recommend();
						recommended_data();
					}
				},
			error: function() { 
				console.log("Error trying to submit!");
			}
		});
		$.ajax({
			type: 'POST',
			url: '/TMBDdat',
			data: movieDataSubmit,
			success: function (data) {
					console.log(data);
				},
			error: function(error) { 
				console.log("error posting TMDBdat");
			}
		});
	}

	get_movie_data();
	auto_recommend();
	recommended_data();

	//Send information to overlay
	function overlay_on(tmdb_id, title, img, release, tmbd_score, user_score, review, submitting, r_id = 0) {
		cur_r_id = r_id;
		current_title = title;
		description = '';
		r_tmdb_id = tmdb_id;
		description_string.forEach(function(obj){
			if(obj.id == tmdb_id){
				description = obj.description;
			}
		})
		$("#overlay").css('display', 'block');
  		$("#movie_image").attr("src",'http://image.tmdb.org/t/p/w200' + img);
  		$("#review_title").text(title);
  		$("#review_description").text(description);
  		$("#review_release").text(release);
  		$("#review_tmdb_score").text(tmbd_score);
  		$("#review_user_score").text(user_score);
  		$("#review_user_review").text(review);
  		if(submitting) {
  			$('#overlay_button_submit').show();
  			$('#overlay_button_home').hide();
  			$('#starRating_for_review').show();
  			$('#submit_review_textarea').show();
  			$('#submit_review_textarea').val('');
  			$('#my_review_table').hide();
  		} else {
  			$('#overlay_button_submit').hide();
  			$('#overlay_button_home').show();
  			$('#starRating_for_review').hide();
  			$('#submit_review_textarea').hide();
  			$('#my_review_table').show();
  		}
	}

	function overlay_off() {
   		$("#movie_image").attr("src",'');
  		$("#review_title").empty();
  		$("#review_description").empty();
  		$("#review_release").empty();
  		$("#review_tmdb_score").empty();
  		$("#overlay").css('display', 'none');
   		$('#review_user_score').empty();
  		$('#review_user_review').empty();
	}


	//Stars for now

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