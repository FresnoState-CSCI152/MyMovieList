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

						{{-- user info --}}
						<div class="col" id="userInfo">
							<p>Email: {{$user->email}}</p>
							<p>Gender: {{$user->gender}}</p>
							<p>Birthday: {{$user->birth_month}}/{{$user->birth_day}}/{{$user->birth_year}}</p>
							<p>Location: {{$user->location}}</p>
							<button class="btn btn-secondary" onclick="updateUserInfo()">Edit</button>
						</div>

						<div class="col" id="userForm" style="display: none">
							<form method="POST" action="{{route('update_personal_info',Auth::user()->id)}}">
								{{csrf_field()}}
								<label for="gender">Gender</label>
								<select name="gender">
									<option value="-">-</option>
									<option value="male">Male</option>
									<option value="female">Female</opion>
								</select>
								
								<div class="row m-3">
								  <div class="col">
								  	<label for="Month">Month</label>
								    <input id="b_month" type="text" name="b_month" style="width:23px;" maxlength="2">
								  </div>
								  <div class="col">
								  	<label for="Day">Day</label>
								    <input id="b_day" type="text" name="b_day" style="width:23px;" maxlength="2">
							      </div>
								  <div class="col">
								  	<label for="Year">Year</label>
								    <input id="b_year" type="text" name="b_year" style="width:40px;" maxlength="4" minlength="4">
								  </div>
								</div>
								<label for="location">Location</label>
								<input id="local" type="text" name="location">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<button type ="submit" name = "userForm" class="btn btn-secondary">Update</button>
							</form>
						</div>
			</div>
			</div>

			<!-- About Me -->
			<div class="col">
				<h3>About Me</h3>
				<div class="row">
					
					{{-- user bio --}}
					<div class="col" id="currentAboutMe">
						<p>{{$user->about_me}}</p>
						<button type="button" class="btn btn-secondary" onclick="showAboutMe()">Edit</button>
					</div>

					{{-- editing user bio --}}
					<div class="col" id="updateAboutMe" style="display: none;">
						<form action="{{route('update_about_me',Auth::user()->id)}}" method="POST">
							{{csrf_field()}}
							<textarea name ="about_me" id="aboutMeText" class="form-control" rows=5 style="width:300px"></textarea>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<button class="btn btn-secondary" type="submit" name="aboutMeForm">Update</button>
						</form>
					</div>

				</div>
			</div>

			{{-- settings, change password --}}
			<div class="col">
				<h3>Settings</h3>
				<div class="row">
					<a class="btn btn-link" href="{{route('passwordForm')}}">
                                    {{ __('Change Password') }}
                    </a>
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
	loadPersonalInfo();
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
	$.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });

	var aboutMe = "";
	$.ajax({type:"GET",
		    url: "/profile/get_about_me",
			data : {aboutMe:aboutMe},
			success : function(aboutMe){
				document.getElementById("aboutMeText").innerHTML = aboutMe;
			},
			error : function(errorData){
				console.log(errorData);
			},
			dataType : "text",
		});
}

function loadPersonalInfo()
{
	$.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
            });

	var day, month, year, location;
	var userInfo = {
		'day':day,
		'month':month,
		'year':year,
		'local':location 
	}
	$.ajax({type:"GET",
		    url: "/profile/get_user_info",
			data : userInfo,
			success : function(userInfo){
				document.getElementById("b_day").value = userInfo.day;
				document.getElementById("b_month").value = userInfo.month;
				document.getElementById("b_year").value = userInfo.year;
				document.getElementById("local").value = userInfo.local;
				},
			error : function(errorData){
				console.log(errorData);
				alert('error');
			},
			dataType : "json",
		});
}


</script>
@endsection