@extends('templates/master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="/profile/updatePassword">
                        @csrf

                        <input type="hidden" name="token">

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="new_password" minlength="6" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" minlength="6" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" onclick="return checkPassword()">
                                    {{ __('Reset Password') }}
                                </button>
                                <span id="message"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
function checkPassword()
{
    var pass = document.getElementById("password").value;
    var confirm = document.getElementById("password-confirm").value;

    if (pass != confirm)
    {
        alert("Password mismatch");
        return false;
    }
    return true;
}

function updateCheck()
{
    var pass = $("#password").val();
    var pass_confirm = $("#password-confirm").val();

    if (pass != pass_confirm)
    {  $("#message").html("Passwords do not match");
        $("#message").css('color', 'red');
    }
    else
     {   
        $("#message").html("Passwords match");
        $("#message").css("color", 'green');
      }
}
$(document).ready(function () {
   $("#password, #password-confirm").keyup(updateCheck);
});
</script>
@endsection