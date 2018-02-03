<header>
    <nav class="navbar navbar-expand-md  navbar-dark bg-info">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="fas fa-paw"></i> Pet Care</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="navbar-item">
                    <a href="{{ route('service-showcase') }}" class="nav-link">Our Services</a>
                </li>
                <li>
                    <a href="{{ route('product-showcase') }}" class="nav-link">Our Products</a>
                </li>
                <li>
                    <a href="{{ route('doctor-showcase') }}" class="nav-link">Our Doctors</a>
                </li>
            </ul>
            @guest
            <ul class="navbar-nav ml-auto">
                <li class="navbar-item">
                    <a data-toggle="modal" data-target="#login"  href="#" class="nav-link"><i class="fas fa-sign-in-alt"></i> Sign in</a>
                </li>
                <li><a class="nav-link">&middot;</a></li>
                <li>
                    <a data-toggle="modal" data-target="#register" href="#" class="nav-link"><i class="fas fa-edit"></i> Create an account</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 d-none">
                {!! Form::text('username', null, ['class' => 'form-control mr-sm-2', 'placeholder' => 'Your username']) !!}
                {!! Form::password('password', ['class' => 'form-control mr-sm-2', 'placeholder' => 'Then your password']) !!}
                <button class="btn btn-success my-2 my-sm-0" type="submit">Login</button>
                <a class="btn btn-secondary ml-2 text-white my-2 my-sm-0">Create an account</a>
            </form>
            @endguest
            @auth
            <ul class="navbar-nav ml-auto">
                @if(Auth::user()->is('customer'))
                <li class="navbar-item">
                    <a href="{{ route('user.pet.index') }}" class="nav-link"><i class="fas fa-paw"></i> My Pets</a>
                </li>
                <li>
                    <a href="{{ route('user.appointment.index') }}" class="nav-link"><i class="fas fa-calendar-alt "></i> Appointments</a>
                </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       @include('components/blocks/user-icon')
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @if(Auth::user()->is(['admin', 'staff']))
                        <a class="dropdown-item" href="{{ route('admin.appointment.index') }}">Admin Page</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#profile" href="javascript:void()">Profile</a>
                        @elseif(Auth::user()->is('doctor'))
                        <a class="dropdown-item" href="{{ route('doctor.appointment.index') }}">Admin Page</a>
                        <a class="dropdown-item" href="{{ route('doctor.profile.show') }}">Profile</a>
                        @elseif(Auth::user()->is('customer'))
                        <a class="dropdown-item" href="{{ route('user.order-history.show') }}">Order History</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#profile" href="javascript:void()">Profile</a>
                        @endif
                        {!! Form::open(['url' => route('account.logout'), 'method' => 'post', 'class' => 'd-none', 'id' => 'logout-form']) !!}
                        {!! Form::close()  !!}
                        <a class="dropdown-item logout" href="#">Logout</a>

                    </div>
                </li>
            </ul>
            @endauth
        </div>
    </nav>
</header>


@guest
    @push('modals')
    <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="register-title" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="register-title">Create an account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          {!! Form::open(['url' => route('account.register'), 'method' => 'POST','class' => 'ajax']) !!}
          <div class="modal-body">

            <div class="row">
                <div class="col">
                    {!! Form::bsText('firstname', 'First Name', null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col">
                  {!! Form::bsText('lastname', 'Last Name', null, ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    {!! Form::bsText('username', 'Desired Username', null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col">
                   {!! Form::bsText('email', 'Email Address', null, ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE'], null, ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col">
                    {!! Form::bsText('contact_number', 'Contact Number', null, ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col">
                    {!! Form::bsPassword('password', 'Desired Password', ['class' => 'form-control form-control-sm']) !!}
                </div>
                <div class="col">
                    {!! Form::bsPassword('password_confirmation', 'Confirm Password', ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Sign up!</button>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="register-title" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="register-title">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['url' => route('account.login'), 'method' => 'POST','class' => 'ajax']) !!}
                    <div class="modal-body">



                        {!! Form::bsText('username', 'Your username') !!}
                        {!! Form::bsPassword('password', 'Then your password') !!}
                        <div class="text-center">

                            <a href="{{ route('auth:facebook') }}" class="text-info"><i class="fab fa-facebook-square fa-2x"></i></a>
                            <a href="{{ route('auth:google') }}" class="text-danger"><i class="fab fa-google-plus-square fa-2x"></i></a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Log me in!</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endpush
@endauth

@push('modals')
@include('blocks.customer-modal')
@endpush
