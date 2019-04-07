@if(count($recommends))
@foreach($recommends as $recommend)

<div class='container mb-5'>
<div class='row'>
<div class='col-3'>
        <img src='http://image.tmdb.org/t/p/w200{{$recommend->img_path}}'>
</div>

<div class='col-9'>
<h5>Recommended by <a href="/friends/{{ $recommend->reviewer_id }}">{{ \App\User::find($recommend->reviewer_id)->name }}</a></h5>

<div class="table-responsive">
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
</div>

<div class="table-responsive">
<table class='table table-bordered'>
    <thead>
    <tr>
        <th scope='col' style='width: 13%'>{{ \App\User::find($recommend->reviewer_id)->name }}'s Score</th>
        <th scope='col'>{{ \App\User::find($recommend->reviewer_id)->name }}'s Review</th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>{{$recommend->user_score}}</td>
        <td>{{$recommend->review}}</td>
    </tr>
    </tbody>
</table>
</div>

{{-- Submit Review --}}
@if ($userId == Auth::user()->id)

{{-- Recommend List Review Buttons --}}
<button id={{ 'recommended_review_button_'.$recommend->movie_review_id }} onclick="showRecommendReviewForm({{ $recommend->movie_review_id }})" class='btn btn-primary mb-2'>Review Movie</button>

{{-- Stars --}}
<div id="review_for_{{ $recommend->movie_review_id }}">
<x-star-rating id="starRating_{{ $recommend->movie_review_id }}" value="0" number="10"></x-star-rating><div class="form-group"><label for="review">Your Review:</label><textarea class="form-control" id="recommended_review_form_{{ $recommend->movie_review_id }}" rows="3"></textarea>

{{-- Submit Review Button --}}
<button id={{ 'submit_review_button_'.$recommend->movie_review_id }} onclick="submit_reivew({{ Auth::user()->id}},{{ $recommend->movie_review_id }}, {{ $recommend->r_id }}, {{ $recommend->tmdb_id }})" class='btn btn-primary mb-2'>Submit Review</button>

{{-- Cancel Review for Recommended Movie Button --}}
<button id={{ 'cancel_review_button_'.$recommend->movie_review_id }} onclick="hideRecommendReviewForm({{ $recommend->movie_review_id }})" class='btn btn-primary mb-2 btn btn-danger'>Cancel Review</button>
</div>
</div>

{{-- Hide Form Default--}}
<script type="text/javascript">
    var recommendForm = $('#review_for_'+{{ $recommend->movie_review_id }});
    recommendForm.hide();
</script>
@endif

{{-- End Submit Review --}}

</div>
</div>
</div>
@endforeach
@else
    <h6>No recommended movies.</h6>
@endif
