<div class='row justify-content-center mb-3'>
    <div class='col-md'>
        <div class='card shadow-sm bg-white rounded'>
            <div class='card-header'>
                @if ($userId == Auth::user()->id)
                    <h4>{{ __('Your Reviewed Movies') }}</h4>
                @else
                    <h4>{{ \App\User::find($userId)->name }}'s Reviewed Movies</h4>
                @endif
                @include('home/genre-select-menu', ['label' => 'reviews'])
            </div>
            <div class='card-body' id='review-cards'>
                @include('home/review-cards')
            </div>
        </div>
    </div>
</div>
