<div class='row justify-content-center mb-3'>
    <div class='col-md'>
        <div class='card shadow-sm bg-white rounded'>
            <div class='card-header'>
                <h4>{{ __('Recommended Movies') }}</h4>
                <div class="d-flex align-items-end">
                    @include('home/genre-select-menu', ['label' => 'recommends'])
                    <div class='d-inline-flex flex-column ml-2'>
                        <label class='text-nowrap' for='sort-creation-date_recommends'>Sort by recommend date:</label>
                        <select class='custom-select' name="sortCreationDate" id="sort-creation-date_recommends">
                            <option selected value="desc">Newest first</option>
                            <option value="asc">Oldest first</option>
                        </select>
                    </div>
                    <button class="btn btn-primary mt-2 ml-2" id="filter-and-sort-button_recommends" type="button" disabled>Filter and sort</button>
                </div>
            </div>
            <div class='card-body' id='recommend-cards'>
                @include('home/recommend-cards')
            </div>
        </div>
    </div>
</div>
