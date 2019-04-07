@if(count($reviews))
@foreach($reviews as $review)

<div class='container-fluid mb-5'>
<div class='row'>
<div class='col-3 pb-4'>
        <img class='img-fluid shadow' src='http://image.tmdb.org/t/p/w200{{$review->img_path}}'>
</div>

<div class='col-9'>
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
        <td>{{$review->title}}</td>
        <td>{{$review->description}}</td>
        <td>{{$review->release}}</td>
        <td>{{$review->tmdb_score}}</td>
    </tr>
    </tbody>
</table>
</div>

<div class="table-responsive">
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
</div>

{{-- User Reviews Default --}}
@if ($userId == Auth::user()->id)
    {{-- Recommend to Friend Button Default --}}
    <button id={{ 'recommend_button_'.$review->movie_review_id }} onclick="showRecommendForm({{ $review->movie_review_id }})" class='btn btn-primary mb-2'>Recommend to a friend</button>
    {{-- Edit Button Default --}}
    <button type="button" id={{ 'edit_button_'.$review->movie_review_id }} class='btn btn-danger mb-2 float-right' onclick="showEdit({{$review->movie_review_id}})">Edit</button>
    {{-- Edit Review --}}
    <div id="update_for_{{ $review->movie_review_id }}">
    <x-star-rating id="starRating_{{ $review->movie_review_id }}" value="{{$review->user_score}}" number="10"></x-star-rating>

    <div class="form-group"><label for="review">Edit Review:</label><textarea class="form-control" id="edit_review_form_{{ $review->movie_review_id }}" rows="3">{{$review->review}}</textarea></div>

    {{-- Cancel and Save Edit Buttons --}}
    <button type="button" id={{ 'save_edit_button_'.$review->movie_review_id }} class='btn btn-primary mb-2 float-left' onclick="saveEdit({{$review->movie_review_id}}, {{ Auth::user()->id }})">Save</button>
    <button type="button" id={{ 'cancel_button_'.$review->movie_review_id }} class='btn btn-danger mb-2 float-right' onclick="hideEdit({{$review->movie_review_id}})">Cancel</button>
    </div>
    {{-- Hide Edit Review Default --}}
    <script type="text/javascript">
        var updateform = $('#update_for_'+{{ $review->movie_review_id }});
        updateform.hide();
    </script>
    {{-- Recommend Movie Form --}}
    <form class='form-inline' id={{ 'recommend_form_'.$review->movie_review_id }} style='display: none'>
        <label class='sr-only' for='recommendee_id'>Name</label>
        <select class='custom-select mb-2 mr-sm-2' id={{ 'recommendee_id_'.$review->movie_review_id }} name='recommendee_id' required>
            @foreach ($friends as $friend)
                <option value='{{ $friend->id }}'>{{ $friend->name }}</option>
            @endforeach
        </select>
        <input value='{{ $review->movie_review_id }}' id='movie_review_id' name='movie_review_id' style='display: none'>

        {{-- Recommend Movie Form Buttons --}}
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
