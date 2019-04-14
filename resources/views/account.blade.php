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
                             	<form enctype="multipart/form-data" action="{{route('update_avatar',Auth::user()->id)}}" method="POST">
                             		{{csrf_field()}}
	               					<label>Update Profile Image</label>
	               					<input type="file" name="avatar">
	               					<input type="hidden" name="_token" value="{{ csrf_token() }}">
	               					<input type="submit" class="mt-3 pull-right btn btn-sm btn-primary" name="avatarForm">
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
			<div class="col-sm-4">
				<h3>User Information</h3>
					<div class="row">
						<div class="col-xs-1" id="userInfo">
							<p>Email: {{$user->email}}</p>
							<p>Gender: {{$user->gender}}</p>
							<p>Birthday: {{$user->birthday}}</p>
							<p>Location: {{$user->location}}</p>
							<button class="button" onclick="updateUserInfo()">Edit</button>
						</div>

							<div class="col-sm-4" id="userForm" style="display: none">
								<form method="POST" action="{{route('update_personal_info',Auth::user()->id)}}">
									{{csrf_field()}}
									<label for="gender">Gender</label>
									<select name="gender">
										<option value="-">-</option>
										<option value="male">Male</option>
										<option value="female">Female</opion>
									</select>
									<label for="birthday">BirthDay</label>
									<input type="text" name="birthday">
									<label for="location">Location</label>
									<input type="text" name="location">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<button type ="submit" name = "userForm" class="button">Update</button>
								</form>
							</div>
			</div>
			</div>

			<!-- About Me -->
			<div class="col-sm-4">
				<h3>About Me</h3>

				<div class="row">
					<div class="col-sm-10" id="currentAboutMe">
						<p>{{$user->about_me}}</p>
						<button class="button" onclick="showAboutMe()">Edit</button>
					</div>


					<div class="col-sm-4" id="updateAboutMe" style="display: none;">
						<form action="{{route('update_about_me',Auth::user()->id)}}" method="POST">
							{{csrf_field()}}
							<textarea name ="about_me" id="txtArea" class="form-control" rows=5 style="width:300px"></textarea>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<button class="button" type="submit" name="aboutMeForm">Update</button>
						</form>
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<h3>Settings</h3>
					<div class="row">
						<div class="col-sm-4">
							<a href="#">ChangePassword</a>
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

function updateUserInfo()
{
	var showUserForm = $('#userForm');
	var currentUserInfo = $('#userInfo');

	currentUserInfo.hide();
	showUserForm.show();
}

function showAboutMe()
{
	var updateForm = $('#updateAboutMe');
	var currentInfo = $('#currentAboutMe');

	currentInfo.hide();
	updateForm.show();
	loadAboutMe();
}

function loadAboutMe()
{
	var aboutMe = "";
	$.ajax({type:"GET",
		    url:"/Profile.php",
			data : aboutMe,
			success : function(aboutMe){
				alert('success');
			},
			error : function(){
				alert('failure');
			},
			dataType : "json",
		});

}

function loadPersonalInfo()
{

}

</script>
@endsection