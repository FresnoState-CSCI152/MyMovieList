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

	<!-- Display user Information -->
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
			<h4><strong>User Info</strong></h4>
				<div class="col-sm-4">
				</div>

				<div class="row">
					<div class="col-xs-1" id='infoTitles'>
						<p>Email:</p>
						<p>Gender:</p>
						<p>Birthday:</p>
						<p>Location:</p>
					</div>
					<div class="col-xs-2" id='userInfo'>
						<p>{{$user->email}}</p>
						<p>{{$user->gender}}</p>
						<p>{{$user->birthday}}</p>
						<p>{{$user->location}}</p>
					</div>

					<!-- Dropdown menu to select user Gender -->
					<div class="col-xs-s">
						<form action="/profile">
							<select name="Gender">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								<option value="-">-</option>
							</select>
							<br><br>
						</form>
					</div>
				</div>

				<div class="row">
					<button class="button" onclick="showEditForm()">Edit</button>
				</div>
			</div>

			<div class="col-sm-6">
				<h4><strong>About me</strong></h4>
					<div class="row">
						<div class="col-xs-4">
							<p>{{$user->about_me}}</p>
					 	</div>

					 	<div class="row">
					 		<button class="button">Edit</button>
					 	</div>
					</div>
			</div>
		</div>
	</div>

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

function showEditForm()
{
	var userInfo1 = $('#infoTitles');
	var userInfo2 = $('#userInfo')
	userInfo1.hide();
	userInfo2.hide();
}
</script>
@endsection