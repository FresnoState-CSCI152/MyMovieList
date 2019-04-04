@extends ('templates/master')
        
<!-- JQuery -->
 <script 	
 		src="https://code.jquery.com/jquery-3.3.1.min.js"
 		integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
</script>

{{-- Script --}}
<script>

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
			    console.log(data);
			},
			error: function(errorData) {
				return {};
			},
			dataType: "json",
		});
	};

	var movie_data = get_movie_data({{Auth::user()->id}});

	console.log(movie_data)
</script>