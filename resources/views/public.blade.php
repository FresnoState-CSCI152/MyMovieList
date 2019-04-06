@extends ('templates/master')

@section ('content')

    <div class='flex-center position-ref full-height'>
        <div class='top-right links'>
            @auth
                <div class='container'>
                    <div class='row justify-content-between mb-3'>
                        <div class='col-10'>
                            <h1>{{ $publicUser = \App\User::find($userId)->name}}'s public profile.</h1>
                        </div>
                        <div class='col-2'>

                            {{-- need to add ability to check if already friend --}}

                            {{-- {{ Auth::user()->friends()->get() }} --}}
                            {{-- {{ \App\User::find($userId) }} --}}
                            {{-- if current user's friend_id == public user's user_id --}}
                            {{-- then they are friends --}}

                            <form method="POST" action="/friends/createrequest">

                                {{ csrf_field() }}

                                <input type="hidden" id="name" name="name" value="{{ $publicUser }}">
                                <button type="submit" class="btn btn-primary mb-2">Send Friend Request</button>

                                @include ("errors/fielderrors", ["fieldName" => "name"])
                                @include ("flash-messages/success", ["successVar" => "requestSuccess"])

                            </form>

                        </div>
                    </div>
                </div>

                <hr>

                <div class='container'>
                    <div id='reviews' user-id='{{$userId}}'>
                        @include('home/reviews')
                    </div>

                    {{-- if the user has no reviews, display hint --}}
                    <div class='row justify-content-center mt-4'>
                        <div class='col-md'>
                            @if(count($reviews) == 0)
                                <h3>This user has no reviews.</h3>
                                {{-- @if($friends->id) --}}
                                {{--     <h3>Recommend your friend some movies. 😊</h3> --}}
                                {{-- @else --}}
                                {{--     <h3>Add them as a friend to recommend them movies. 😊</h3> --}}
                                {{-- @endif --}}
                            @endif
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>

@endsection
