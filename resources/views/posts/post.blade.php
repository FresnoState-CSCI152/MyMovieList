<div class="blog-post">
	<h2 class="blog-post-title">
		<a href="/discussion/{{ $post->id }}">{{ $post->title }}</a>
	</h2>
	<div class="row">
		<div class="col- ml-3">
        	<img src="/uploads/avatars/{{ $post->user->avatar }}" style="width:px; height:32px; position:relative; 	border-radius:50%">
    	</div>	
    	<div class="col d-flex align-items-center">
    		<p class="blog-post-meta">
<<<<<<< HEAD
    			<a href="/public/{{ $post->user->id }}">{{ $post->user->name }}</a> on {{ $post->created_at->tz('America/Los_Angeles')->toFormattedDateString() }}
			</p>
    	</div>
	</div>
	
	{{ str_limit($post->body) }}

<<<<<<< HEAD
	{{ html_entity_decode(strip_tags($post->body)) }}
=======
>>>>>>> master
=======
    			<a href="#{{-- add link to user profile here --}}">{{ $post->user->name }}</a> on {{ $post->created_at->toFormattedDateString() }}
			</p>
    	</div>
	</div>

	{{ html_entity_decode(strip_tags($post->body)) }}
>>>>>>> parent of 01c1afb... Merge branch 'master' into ernesto
</div>
<hr>