
{{-- Style check --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <div class="navbar-header">
      <a href="/" class="navbar-left"><img src="/logo.png" width="200px"></a>
    </div>

    {{-- if user is logged in, these become available --}}
    @if (Auth::check())

    {{-- general links --}}
    <ul class="navbar-nav navbar-left">
      <li class="nav-item"><a class="nav-link" href="/search">Search</a></li>
      <li class="nav-item"><a class="nav-link" href="/friends">Friends</a></li>
      <li class="nav-item"><a class="nav-link" href="/discussion">Discussions</a></li>
      <li class="nav-item"><a class="nav-link" href="https://github.com/FresnoStateCSCI150/MyMovieList">GitHub</a></li>
      <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
    </ul>

    {{-- user profile dropdown menu --}}
    <ul class="navbar-nav navbar-left">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="/account"> <img src="/uploads/avatars/{{ Auth::user()->avatar }}" style="width:32px; height:32px; position:absolute; top:5px; left:-40px; border-radius:50%"> {{ Auth::user()->name }} </a>
        <div class="dropdown-menu">     
        {{-- User profile --}}
          <a class="dropdown-item" href="{{ url('/profile') }}">Profile</a>
        {{-- Logout --}}
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout</a> 
        </div>
      </li>
    </ul>

    {{-- if user is not logged in, these are available --}}
    @else
    <ul class="navbar-nav navbar-left">
      <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
      <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
      <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
      <li class="nav-item"><a class="nav-link" href="https://github.com/FresnoStateCSCI150/MyMovieList">GitHub</a></li>
    </ul>
    @endif

  </div>
</nav>
