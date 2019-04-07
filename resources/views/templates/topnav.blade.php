
{{-- Style check --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  
    <div class="navbar-header">
      <a href="/" class="navbar-left"><img src="/logo.png" width="200px"></a>
    </div>

    {{-- hamburger/three-line toggler menu icon (for mobile/non-full-sized windows) --}}
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    {{-- if user is logged in, these become available --}}
    @if (Auth::check())

    {{-- general links --}}  
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="/search"><ion-icon name="search"></ion-icon> Search</a></li>
        <li class="nav-item"><a class="nav-link" href="/friends"><ion-icon name="people"></ion-icon> Friends</a></li>
        <li class="nav-item"><a class="nav-link" href="/discussion"><ion-icon name="chatboxes"></ion-icon> Discussions</a></li>
        <li class="nav-item"><a class="nav-link" href="https://github.com/FresnoState-CSCI152/MyMovieList"><ion-icon name="logo-github"></ion-icon> GitHub</a></li>
        <li class="nav-item"><a class="nav-link" href="/about"><ion-icon name="information-circle-outline"></ion-icon> About</a></li>
      </ul>     
    


    {{-- user profile dropdown menu --}}
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="/account"> <img src="/uploads/avatars/{{ Auth::user()->avatar }}" style="width:32px; height:32px; position:relative; right:5px; border-radius:50%"> {{ Auth::user()->name }} </a>
        <div class="dropdown-menu shadow">

        {{-- User profile --}}
          <a class="dropdown-item" href="{{ url('/profile') }}"><ion-icon name="person"></ion-icon> Profile</a>

        {{-- Logout --}}
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><ion-icon name="log-out"></ion-icon> Logout</a> 
        
        </div>
      </li>
    </ul>

    {{-- if user is not logged in, these are available --}}
    @else
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="/login"><ion-icon name="log-in"></ion-icon> Login</a></li>
      <li class="nav-item"><a class="nav-link" href="/register"><ion-icon name="clipboard"></ion-icon> Register</a></li>
      <li class="nav-item"><a class="nav-link" href="/about"><ion-icon name="information-circle-outline"></ion-icon> About</a></li>
      <li class="nav-item"><a class="nav-link" href="https://github.com/FresnoStateCSCI150/MyMovieList"><ion-icon name="logo-github"></ion-icon> GitHub</a></li>
    </ul>
    @endif
  </div>

</nav>
