@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush


@section('content')
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Pet Care</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    @if(Auth::user()->is(['admin', 'staff']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">Maintain</a>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('admin.pet-category.index') }}">Pet Categories</a>
                          <a class="dropdown-item" href="{{ route('admin.pet-breed.index') }}">Pet Breeds</a>
                          <a class="dropdown-item" href="{{ route('admin.pet-reproductive-alteration.index') }}">Pet Reproductive Alterations</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="{{ route('admin.product-category.index') }}">Product Categories</a>
                          <a class="dropdown-item" href="{{ route('admin.supplier.index') }}">Product Suppliers</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pet.index') }}">Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.service.index') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.product.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.index') }}">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.appointment.index') }}">Appointments</a>
                    </li>
                    @endif

                    @if(Auth::user()->is('admin'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.user.index') }}">Users</a>
                    </li>
                    @endif

                    @if(Auth::user()->is('doctor'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.appointment.index') }}">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pet.index') }}">Pets</a>
                    </li>
                    @endif
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           @include('components/blocks/user-icon')
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('home') }}">Homepage</a>
                            @if(auth()->user()->is('doctor'))
                            <a class="dropdown-item" href="{{ route('doctor.profile.show') }}">Profile</a>
                            @else
                            <a class="dropdown-item" data-toggle="modal" data-target="#profile" href="javascript:void()">Profile</a>
                            @endif

                            {!! Form::open(['url' => route('account.logout'), 'method' => 'post', 'class' => 'd-none', 'id' => 'logout-form']) !!}
                            {!! Form::close()  !!}
                            <a class="dropdown-item logout" href="#">Logout</a>

                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        @yield('body')
    </div>
@endsection


@push('modals')
@include('blocks.customer-modal')
@endpush
