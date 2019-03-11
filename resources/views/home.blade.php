@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush
@section('content')
    <div class="container">
    @include('blocks.home-header')
    <main role="main">

      <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="first-slide" src="{{ asset('images/sign-up.jpg') }}" alt="Sign up now!">
            <div class="container">
              <div class="carousel-caption">
                <h1>Make your appointment easier!</h1>
                <p>Register an account to book an appointment online with our doctors..</p>
                @guest
                <p><a class="btn btn-lg btn-primary" data-toggle="modal" data-target="#register" role="button">Sign Up Today</a></p>
                @endguest
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="second-slide" src="{{ asset('images/our-products.jpg') }}" alt="Our products">
            <div class="container">
              <div class="carousel-caption text-left">
                <h1>Keep your pets healthy and happy!</h1>
                <p>We provide a wide variety of good quality products for you to maintain your pet's health.</p>
                <p><a class="btn btn-lg btn-primary" href="{{ route('product-showcase') }}" role="button">View products</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="third-slide" src="{{ asset('images/our-services.jpg') }}" alt="Our services">
            <div class="container">
              <div class="carousel-caption text-right">
                <h1>Check out what can we offer for your pets!</h1>
                <p>We provide a dozens of services to keep your pet healthy and happy.</p>
                <p><a class="btn btn-lg btn-primary" href="{{ route('service-showcase') }}" role="button">View services</a></p>
              </div>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

      <div class="row">
        <div class="col-md-6 offset-md-3">

          <div class="card mb-3">
            <div class="card-body p-2">
              <div class="row align-items-center">
                <div class="col-md-2 text-center">
                  <i class="fas fa-paw fa-3x text-info"></i>
                </div>
                <div class="col-md-9 pl-0">
                  <h4>We specialize in <span class="text-info">dogs</span> and <span class="text-info">cats</span>. We cater to all your pet needs from grooming, boarding and over all health check. We have a spacious clinic area with a very good parking area for your convenience.</span>
                  </h4>
                </div>
              </div>
            </div>
          </div>
          <div class="card  mb-3">
            <div class="card-body p-2">
              <div class="row align-items-center">
                <div class="col-md-2 text-center">
                  <i class="fas fa-clock fa-3x text-info"></i>
                </div>
                <div class="col-md-9  pl-0">
                  <h4>
                    We are open Mondays to Saturdays at 9:00 AM - 6:00 PM
                  </h4>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-body p-2">
              <div class="row align-items-center">
                <div class="col-md-2 text-center">
                  <i class="fas fa-phone fa-3x text-info"></i>
                </div>
                <div class="col-md-9 pl-0">
                  <h4>Do you have any inquiries? You can contact us at <span class="text-info">416-1651</span>
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


  <hr class="featurette-divider">
      <!-- FOOTER -->
      <footer class="container">
        <p class="float-right"><a href="#">Back to top</a></p>
        <p>&copy; 2019 Pet Care &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
    </main>
    </div>
@endsection
