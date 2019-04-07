@extends ('templates/master')

@section ('content')
<div class="col-sm-8">
	<h1 class ="title">Edit Post </h1>

	<form method ="POST" action ="/discussion/{{$post->id}}">	
		{{method_field('PATCH')}}
		{{csrf_field()}}

			<div class="form-group">
				<label for="title">Title:</label>
				<input type="text" id="title" name="title" class="form-control" placeholder="Title" value="{{$post->title}}" required >
			</div>

			<div class="form-group">
				<label for="body">Body:</label>
				<textarea id="body" name="body" class="form-control"  required> {{$post->body}} </textarea>
			</div>

		
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Update Post</button>
			</div>

			@include('errors/errors')
	</form>
</div>

@endsection