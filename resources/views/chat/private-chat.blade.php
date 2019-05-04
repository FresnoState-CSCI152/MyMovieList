@extends('templates/master')

@section('content')
    <div class="mx-auto" id="private-chat" curr-user-id={{ $currUserId }} other-user-id={{ $otherUserId }} curr-user-name={{ $currUserName }} other-user-name={{ $otherUserName }}>
        <div id="private-chat-message-display" class="d-flex flex-column-reverse bg-white border pl-3 pt-2">
        </div>
        <form id="private-chat-input">
            <input class="form-control mt-3" id="private-chat-input-box">
            <button type="submit" class="btn btn-primary mt-2">Send message</button>
        </form>
    </div>
@endsection