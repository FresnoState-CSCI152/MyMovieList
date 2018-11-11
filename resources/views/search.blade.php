@extends ('template')

@section ('content')

<main class = "py-4">	
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Search') }}</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label text-md-right">{{ __('Search For Movie') }}</label>
							<div class="col-md-6">
								<input type="search" class="form-control" id="search" placeholder="Movie Name">
							</div> 
						</div>

						<div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="searchButton">
                                    {{ __('Search') }}
                                </button>
                            </div>
                        </div>
					</div>
				</div>
				<div id="appendSearch"></div>
			</div>
		</div>
	</div>
</main>


<script type="text/javascript">

	$('#searchButton').click(function() {
		searchQuery();
	});

	$(".form-group").on('keyup', function (e) {
    	if (e.keyCode == 13) {
       		searchQuery();
    	}
	});

	function searchQuery() {
		$("#appendSearch").empty();
		var searchMovie = $('#search').val();
		searchMovie = searchMovie.replace(/\s/g, "%20");
		var obj;
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: 'POST',
			url: '/TMBD',
			data: {data: searchMovie},
			success: function (data) { 
					obj = data['success']['results']; console.log(obj); showSearchResults(obj)},
			error: function() { console.log(searchMovie);}
		});
	};

		
	function showSearchResults(obj) {

		for (var i = 0; i < obj.length; i++) {
			$("#appendSearch").append("<hr />");
			var makeCard ="<div class=\"card\">";
			var currentMovie = obj[i];
			var movieTitle = "<div class=\"card-header\"><div style=\"float:left;\"><b>" + currentMovie['title'] + "</b> (" + currentMovie['release_date'].substring(0,4) + ")</div> <div style=\"float:right;\"><b>TMDB average score:</b> " + currentMovie['vote_average'] + "</div><div class=\"clearfix\"></div></div><div class=\"card-body\"><div class=\"container\"><div class=\"row\">";
			var posterUrl = "http://image.tmdb.org/t/p/w200" + currentMovie['poster_path'];
			var posterImage = "<div class=\"col-sm-6 col-md-6 col-xs-12 image-container\"><img src=\"" + posterUrl + "\" alt=\"movie poster\"></div>";
			var description =  "<div class=\"col-sm-6 col-md-6 col-xs-12\"><p class=\"card-text\"><b>Description:</b> " + currentMovie['overview'] + "</p><div id=\"div" + currentMovie['id'] + "\"></div>"
			var buttonAdd = "<div id=\"theButtons" + currentMovie['id'] +"\"></div></div>";

			$('#appendSearch').append(makeCard + movieTitle + posterImage + description + buttonAdd + "</div></div></div>");
				visbleAddMovieButton(true, currentMovie['id']);
		}
	}	

	function visbleAddMovieButton(on, id){
		$("#theButtons"+ id).empty();
		var buttonText = "";
		if (on) {
			buttonText = "<button onclick=\"appendForm(" + id + ")\" class=\"btn btn-primary\">Add Movie</button>";
			$("#theButtons"+id).append(buttonText);
		} else {
			buttonText = "<button onclick=\"cancelReview(" + id + ")\" class=\"btn btn-danger\">Cancel</button><button onclick=\"confirmReview(" + id + ")\" class=\"btn btn-primary\">Submit Review</button>";
			$("#theButtons"+id).append(buttonText);
		}
	}

	function appendForm(id) {
		var movieReviewText = "<x-star-rating value=\"5\" number=\"10\"></x-star-rating><div class=\"form-group\"><label for=\"review\">Your Review:</label><textarea class=\"form-control\" id=\"exampleFormControlTextarea1\" rows=\"3\"></textarea></div>";
		$('#div' + id).append(movieReviewText);
		visbleAddMovieButton(false, id);
	}

	function cancelReview(id) {
		$('#div' + id).empty();
		visbleAddMovieButton(true, id);
	}

	/*Implement later: Submit Review */
	function confirmReview(id){
		console.log(id);
	}

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