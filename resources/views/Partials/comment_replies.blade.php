<!== class to display all replies for all comments -->

@foreach($comments as $comment)
	<div class="container">
    	<div class="row mb-3">
            <div class="col-xs">
                <ion-icon name="arrow-dropup" class="commUp" style="font-size: 25px" onclick="commentUpvote('up', {{$comment->id}})"></ion-icon>
                 	<div class="col-xs-1 offset-4">
                    <span class="comment_num">{{$comment->vote_count}}</span>
                    </div>
                    <div class="row-sm">
                    <ion-icon name="arrow-dropdown" class="commDown" style="font-size: 25px" onclick="commentUpvote('down', {{$comment->id}})"></ion-icon>
                    </div>
                </div>
        		<div class="col-md">
            		<div class="card shadow-sm bg-white rounded"> 
            			<div class="card-header">
            				<div class="row">
            					<div class="col- ml-2 d-flex align-items-center">
            						<img src="/uploads/avatars/{{ $comment->user->avatar }}" style="width:px; height:32px; position:relative; border-radius:50%">
            					</div>
            					<div class="col d-flex align-items-center">
            						<h6><strong><a href="/public/{{ $comment->user->id }}">{{ $comment->user->name }}</a></strong></h6>
            					</div>
            					<div class="col d-flex align-items-center justify-content-end">
            						<h6>
            							<strong>{{ $comment->created_at->diffForHumans() }}</strong> 
            							&nbsp; ⁝ &nbsp;
            							{{ $comment->created_at->tz('America/Los_Angeles')->toDayDateTimeString() }}
            						</h6>
            					</div>
            				</div>
            			</div>	
                		<div class="card-body">
							{!! $comment->body!!}
    				    </div>     
					</div>
					<div class="row">
                        <div class="col-sm-1">
                            <label>Reply</label>
                        </div>
                        @can('editComment', $comment)
                        <div class="col-sm-1">
                            <label>Edit</label>
                        </div>
                        @endcan
                        @can('deleteComment',$comment)
                        <div class="col-sm-2">
                           <div data-toggle="tooltip" title="Click to delete Comment" style="color: grey">
                               <ion-icon name="trash" style="width:25px;height:25px;" onclick="removeComment({{$comment->id}})"></ion-icon>
                                <label>Delete</label>
                            </div>
                        </div>
                        @endcan
                        </div>
			<div class="card-block m-3">
				<form method="POST" action="{{route('addReply')}}">
					{{ csrf_field() }}
					<div class="form-group">
						<textarea id="body" name="body" placeholder="Your comment here." class="form-control" required></textarea>
                        <input type="hidden" name="post_id" value="{{$post->id}}">
                        <input type="hidden" name="comment_id" value="{{$comment->id}}">
                        <div class="form-group">
						<button type="submit" class="btn btn-primary">Reply</button>
					    </div>
					</div>
				</form>

				@include('errors/errors')
			</div>
				</div>
		    </div>
		    @include('Partials.comment_replies', ['comments' => $comment->replies])
		</div>

@endforeach

<script type="text/javascript">
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
            alert(voteData);
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
             alert('done');
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