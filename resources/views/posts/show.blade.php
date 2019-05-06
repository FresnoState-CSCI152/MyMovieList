@extends ('templates/master')

@section ('content')

	<div class="col-sm-8">

		{{-- original poster --}}
		<div class="container">
    		<div class="row mb-3">
                <div class="col-xs">
                    <ion-icon name="arrow-dropup" class="postUp" style="font-size: 25px" onclick="upvote('up')"></ion-icon>
                        <div class="col-xs-1 offset-4">
                            <span id="post_vote_count">{{$post->vote_count}}</span>
                        </div>
                        <div class="row-sm">
                            <ion-icon name="arrow-dropdown" class="postDown" style="font-size: 25px" onclick="upvote('down')"></ion-icon>
                        </div>
                </div>
        		<div class="col-md">
            		<div class="card shadow-sm bg-white rounded">
            			<div class="card-header">
            				<h1>{{ $post->title }}</h1>
            				<hr>
            				<div class="row">
            					<div class="col- ml-2 d-flex align-items-center">
            						<img src="/uploads/avatars/{{ $post->user->avatar }}" style="width:px; height:32px; position:relative; border-radius:50%">
            					</div>
            					<div class="col d-flex align-items-center">
            						<h6><strong><a href="/public/{{ $post->user->id }}">{{ $post->user->name }}</a></strong></h6>
            					</div>
            					<div class="col d-flex align-items-center justify-content-end">
            						<h6><strong>{{ $post->created_at->diffForHumans() }}</strong>
            						&nbsp; ⁝ &nbsp;
            						{{ $post->created_at->tz('America/Los_Angeles')->toDayDateTimeString() }}</h6>
            					</div>
            				</div>             

                            <div class="col d-flex align-items-center justify-content-end">
                                <h6><strong>{{$post->created_at->diffForHumans()}}</strong>
                                    &nbsp; : &nbsp;
                                    {{$post->created_at->tz('America/Los_Angeles')->toDayDateTimeString()}}</h6>
                            </div>
                        </div>
            			</div>
                		  <div class="card-body">
							 {!! $post->body!!}
						  </div>
					</div>
                    <br>
                    {{--display comments--}}
                    @include('Partials.comment_replies', ['comments' => $post->comments, 'post_id' => $post->id])
                    @can('edit',$post)
                    <a href = "/discussion/{{$post->id}}/edit" class="btn btn-primary">Edit</a>
                    @endcan
				</div>
			</div>
		</div>
        
		<hr>

		{{-- Add a comment --}}
		<div class="card shadow-sm bg-white rounded mb-3">
			<div class="card-block m-3">
				<h5>Join the conversation.</h5>
				<form method="POST" action="{{route('add')}}">
					{{ csrf_field() }}
					<div class="form-group">
						<textarea id="body" name="body" placeholder="Your comment here." class="form-control" required></textarea>
                        <input type="hidden" name="post_id" value="{{$post->id}}">
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">Add Comment</button>
					</div>
				</form>

				@include('errors/errors')

			</div>
		</div>

<script type="text/javascript">
clicked = true;
$(".postUp").click(function(){
    if(clicked)
    {
        $(this).css('color', Cookies.get('.postUpColor'));
        Cookies.set('.postUpColor', 'red');
        clicked = false;
    }
    else
    {
        $(this).css('color', Cookies.get('.postDownColor'));
        Cookies.set('.postDownColor', 'black');
        clicked = true;
    }
});

function upvote(status)
{
    $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });

    var voteValue;

    // check current vote status
    // assign value based on if it's up or down
    if (status == 'up')
    {
        voteValue = 1;
    }
    else if(status == 'down')
    {
        voteValue = -1;
    }

    var voteData = 
    {
        'voteValue': voteValue
    }

    $.ajax({type:"POST",
            url: "/discussion/{{$post->id}}/postUpdate",
            data: voteData,
            success: function(voteData)
            {
                $("#post_vote_count").load("/discussion/{{$post->id}} #post_vote_count");
            },
            error: function(errorData)
            {
                alert('failed');
            },
            dataType: "json",
    });
}

function commentUpvote(status, id)
{
    $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });

    var voteValue;

    if (status == 'up')
    {
        voteValue = 1;
    }
    else if(status == 'down')
    {
        voteValue = -1;
    }

    var voteData = 
    {
        'voteValue': voteValue,
        'id': id
    }

    $.ajax({type:"POST",
            url: "/discussion/{{$post->id}}/commentVote/{id}",
            data: voteData,
            success: function(voteData)
            {
             location.reload();
            //$('.comment_num').load("/discussion/{{$post->id}} .comment_num");
            },
            error: function(errorData)
            {
                alert('failed');
            },
            dataType: "json",
    });
}

function removeComment(id)
{

    $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });

    var commentData = 
    {
        'id': id
    }

    $.ajax({type:"DELETE",
            url: "/discussion/{{$post->id}}/deleteComment",
            data: commentData,
            success: function(commentData)
            {
             location.reload();
            //$('.comment_num').load("/discussion/{{$post->id}} .comment_num");
            },
            error: function(errorData)
            {
                alert('failed');
            },
            dataType: "json",
    });
}
</script>
@endsection