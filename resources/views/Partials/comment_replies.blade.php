<!== display all replies for all comments -->

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
						<textarea name="body" placeholder="Your comment here." class="form-control" required></textarea>
                        <input type="hidden" name="post_id" value="{{$post->id}}">
                        <input type="hidden" name="comment_id" value="{{$comment->id}}">
                        <div class="form-group">
						<button type="submit" class="btn btn-primary">Reply</button>
					    </div>
					</div>
				</form>
                @include('Partials.comment_replies', ['comments' => $comment->replies])
			</div>
				</div>
		    </div>
		</div>

@endforeach