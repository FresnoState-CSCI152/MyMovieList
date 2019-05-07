{{-- display all replies for all comments --}}
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
            		<div class="card shadow-sm bg-white rounded mb-2"> 
            			<div class="card-header">
            				<div class="row">
            					<div class="col- ml-2 d-flex align-items-center">
            						<a href="/public/{{ $comment->user->id }}"><img src="/uploads/avatars/{{ $comment->user->avatar }}" style="width:px; height:32px; position:relative; border-radius:50%"></a>
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
							{!! $comment->body !!}
    				    </div>     
					</div>

					<div class="row">

                        {{-- delete button --}}
                        @can('deleteComment',$comment)
                        <div class="col-sm-1">
                            <div data-toggle="tooltip" title="Click to delete comment" style="color: grey">
                                <button type="button" class="btn btn-light btn-sm" onclick="removeComment({{$comment->id}})"><ion-icon name="trash"></ion-icon> Delete</button>
                            </div>                        
                        </div>
                        @endcan

                        {{-- reply button with dropdown box --}}
                        <div class="col-sm-1">
                            <a class="btn btn-light btn-sm" data-toggle="collapse" href="#collapseReply" role="button" aria-expanded="false" aria-controls="collapseReply">
                              <ion-icon name="undo"></ion-icon> Reply
                            </a>
                        </div>

                        <div class="col-4 collapse" id="collapseReply">
                          <div class="card shadow-sm">
                            <div class="card-header">Reply</div>
                            <div class=" card-body">
                             <form method="POST" action="{{route('addReply')}}">
                                 {{ csrf_field() }}
                                 <div class="form-group">
                                     <textarea name="body" placeholder="Your comment here." class="form-control" required></textarea>
                                     <input type="hidden" name="post_id" value="{{$post->id}}">
                                     <input type="hidden" name="comment_id" value="{{$comment->id}}">
                                     <div class="form-group mt-2">
                                       <button type="submit" class="btn btn-secondary btn-sm">Submit</button>
                                     </div>
                                 </div>
                             </form>
                            </div>
                          </div>
                        </div>

                    </div>

			        <div class="card-block m-3">
                              @include('Partials.comment_replies', ['comments' => $comment->replies])
			        </div>

				</div>
		    </div>
		</div>

@endforeach