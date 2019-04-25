<div class='row justify-content-center mb-3'>
    <div class='col-md'>
        <div class='card shadow-sm bg-white rounded'>
            <div class='card-header'>
                @if ($userId == Auth::user()->id)
                    <h4>{{ __('Your Reviewed Movies') }}</h4>
                @else
                    <h4>{{ \App\User::find($userId)->name }}'s Reviewed Movies</h4>
                @endif
                <div class="d-flex align-items-end">
                    @include('home/genre-select-menu', ['label' => 'reviews'])
                    <div class='d-inline-flex flex-column ml-2'>
                        <label class='text-nowrap' for='sort-user-score_reviews'>Sort by reviewer score:</label>
                        <select class='custom-select' name="sortUserScore" id="sort-user-score_reviews">
                            <option selected value="desc">Descending</option>
                            <option value="asc">Ascending</option>
                        </select>
                    </div>
                    <button class="btn btn-primary mt-2 ml-2" id="filter-and-sort-button_reviews" type="button" disabled>Filter and sort</button>
                </div>
            </div>
            <div class='card-body' id='review-cards'>
                @include('home/review-cards')
            </div>
        </div>
    </div>
</div>
