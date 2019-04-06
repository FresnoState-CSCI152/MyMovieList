<div class='row justify-content-center mb-3'>
    <div class='col-md'>
        <div class='card shadow-sm bg-white rounded'>
            <div class='card-header'>
                <h4>{{ __('Recommended Movies') }}</h4>
                @include('home/genre-select-menu', ['label' => 'recommends'])
            </div>
            <div class='card-body' id='recommend-cards'>
                @include('home/recommend-cards')
            </div>
        </div>
    </div>
</div>
