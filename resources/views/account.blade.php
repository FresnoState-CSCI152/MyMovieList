@extends ('templates/master')

@section ('content')

	<div class ="container">
		<div class="row">
			<div class="col-sm-4"> 
				<h2> {{ $user->name }}'s Profile </h2>
				<hr>
		    </div>
		</div>

			<div class="row">
				<div class="col-sm-2">
				<img src="/uploads/avatars/{{ $user->avatar }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
			    </div>

			    <!-- simple button to enable users to edit their profile pic -->
			    <div class="col-xs-1">
			    <div data-toggle="tooltip" title="Click to edit Image" style="color: grey">
			    <ion-icon name = "add-circle" class="col-xs img-responsive" id = 'editButton' 
			              onclick="showImageForm()" 
			              style="width:40px;height:40px;cursor:pointer;">
			              	
			    </ion-icon>
			    </div>
			    </div>

	  			    <div class="col-md-3" style="display:none" id="imgForm">
                         <div class="card">
                             <div class="card-body">
                             	<form enctype="multipart/form-data" action="/profile" method="POST">
	               					<label>Update Profile Image</label>
	               					<input type="file" name="avatar">
	               					<input type="hidden" name="_token" value="{{ csrf_token() }}">
	               					<input type="submit" class="mt-3 pull-right btn btn-sm btn-primary">
            					</form>
                             </div>
                         </div>
                </div>
			</div>
		</div>

	<hr>
	<!--
	<div class ="container">
		<div class="row">
			<a class="btn btn-primary" href="/friends" role="button">Friends</a>
		</div>
	</div>
    -->

<script>
function showImageForm()
{
	var editProfileImg = $('#imgForm');
	var editBtn = $('#editButton');

	editProfileImg.show();
	editBtn.hide();
}

</script>
@endsection