<header>
    <nav class="navbar navbar-expand-md  navbar-dark bg-info">
        <a class="navbar-brand" href="#"><i class="fas fa-paw"></i> Pet Care</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="navbar-item">
                    <a href="{{ route('auth:facebook') }}" class="nav-link">Our Services</a>
                </li>
                <li>
                    <a href="{{ route('auth:google') }}" class="nav-link">Our Products</a>
                </li>
            </ul>
            @guest
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Username" aria-label="Search">
                <input class="form-control mr-sm-2" type="search" placeholder="Password" aria-label="Search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">Login</button>
                <a class="btn btn-secondary ml-2 text-white my-2 my-sm-0" data-toggle="modal" data-target="#register" href="#">Create an account</a>
            </form>
            @endguest
            @auth
            <span class="navbar-text ml-auto">Hello, {{ Auth::user()->fullname }}!</span>
            @endauth
        </div>
    </nav>
</header>
