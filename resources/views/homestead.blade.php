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

	<div class="my_review_table table-responsive">
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

	<button class="btn btn-primary" onclick="location.href='home';">Go To Full List</button>
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
			<div id="top-recommended">
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
	//change later
	user_id = {{Auth::user()->id}};

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
				}
			});
		});

		//Arguments to send for overlay

		onButtonArguments = '\'' + String(movie_title) + '\', \'' +  String(movie_img) + '\', \'' + String(movie_description.replace('\'', '')) + '\', \'' + String(movie_release) + '\', \'' + String(tmbd_score) + '\'';

		completeButtonArgs = onButtonArguments + ', \'' + String(movie_list.user_score) + '\', \'' + String(movie_list.review.replace('\'', '')) + '\'';

		list_string = '<div class="card"><div class="card-body"><h4 class="card-title border border-dark">' + (filter_num++) + '. &nbsp;&nbsp;' +  movie_title + '</h4><img src="http://image.tmdb.org/t/p/w200'+ movie_img + '" alt="Card image cap" style="height: 10rem; float: left; padding-right: 10px;"><p><b>Your Score: ' + movie_list.user_score + '</b></p><button class="btn btn-primary" onclick="overlay_on(' + completeButtonArgs + ')">Review Details</button></div></div>';

		$('#top-10-list').append(list_string);
	}


	//Fill recommended
	function fill_my_recommended(movie_data) {
		movie_title = movie_data.title;
		movie_img = movie_data.poster_path;
		tmdb_score = movie_data.vote_average;
		completeButtonArgs = '';
		list_string = '<div class="card"><div class="card-body"><h4 class="card-title border border-dark">&nbsp;&nbsp;' +  movie_title + '</h4><img src="http://image.tmdb.org/t/p/w200'+ movie_img + '" alt="Card image cap" style="height: 10rem; float: left; padding-right: 10px;"><p><b>TMDB Score: ' + tmdb_score + '</b></p><button class="btn btn-primary" onclick="overlay_on(' + completeButtonArgs + ')">Review</button></div></div>';

		$('#recommended_from_tmdb').append(list_string);
	}

	//Movie data from database
	function get_movie_data() {
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
	function auto_recommend(){
		$('#recommended_from_tmdb').empty();
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
			    	console.log(obj);
			    	fill_my_recommended(obj);
			    });
			},
			error: function(errorData) {
				console.log(errorData);
			},
			dataType: "json",
		});
	}

	get_movie_data();
	auto_recommend();

	//Send information to overlay
	function overlay_on(title, img, description, release, tmbd_score, user_score, review) {
		$("#overlay").css('display', 'block');
  		$("#movie_image").attr("src",'http://image.tmdb.org/t/p/w200' + img);
  		$("#review_title").text(title);
  		$("#review_description").text(description);
  		$("#review_release").text(release);
  		$("#review_tmdb_score").text(tmbd_score);
  		$("#review_user_score").text(user_score);
  		$("#review_user_review").text(review);
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
</script>

@endsection