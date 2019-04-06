@extends ('templates/master')

@section ('content')
<div class="container">
	<div class="row">
		<div class="col">
			<div class="mx-auto" style="width: 25%;">
				Your List
			</div>
			<div id="top-10-list">
			</div>
		</div>
		<div class="col">
			<div class="mx-auto" style="width: 50%;">
				Top Recommended
			</div>
			<div id="top-recommended">
			</div>
		</div>
		<div class="col">
			<div class="mx-auto" style="width: 75%;">
				Top From TMDB
			</div>
			<div id="top-from-tmdb">
			</div>
		</div>
	</div>
</div>
     
{{-- Script --}}
<script>

	//Top Ten List
	function fill_my_top_ten(movie_list, tmbd_data){
		var movie_img, movie_title;
		tmbd_data.forEach(function(data){
			data.forEach(function(fields){
				if(fields.tmdb_id == movie_list.tmdb_id){
					movie_img = fields.img_path;
					movie_title = fields.title;
				}
			});
		});

		list_string = '<div class="card"> <div class="card-body"><img src="http://image.tmdb.org/t/p/w200'+ movie_img + '" alt="Card image cap" style="height: 10rem; float: left;"><h5 class="card-title" style="float: auto;">' + movie_title + '</h5><p>' + movie_list.user_score + '</p><p class="card-text">' + movie_list.tmdb_id + '</p></div></div>'

		$('#top-10-list').append(list_string);
	}


	function get_movie_data(user_id) {
		$.ajaxSetup({          
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            }
        });

		$.ajax({
			type: "GET",
			url: '/GetMovieData',
			data: {
				'user_id': user_id,
			},
			success: function(data) {
			    console.log(data.data);
			    console.log(data.movie_data);
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

	get_movie_data({{Auth::user()->id}});
</script>

@endsection