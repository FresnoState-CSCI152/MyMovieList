@extends("templates/master")

@section("content")
<div class="m-0">
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-md-auto">
				<h1><strong>Error 403: Forbidden</strong></h1>			
			</div>
		</div>
		<div class="row justify-content-md-center">
	    	<div class="col-md-auto">
				<h2><strong>You are unauthorized to view this content.</strong></h2>
	    		<img src="/unauthorized.jpg" class="shadow rounded m-4 mx-auto d-block" width="250px" alt="You shouldn't be here.">
	    	</div>
		</div>	
		<div class="row justify-content-md-center">
			<div class="col-md-auto">
				<h2><strong>Turn back immediately.</strong></h2>
				<img src="/unauthorized2.jpg" class="shadow rounded m-4 mx-auto d-block" width="250px" alt="You shouldn't be here either.">
			</div>
		</div>
	</div>
</div>
@endsection
