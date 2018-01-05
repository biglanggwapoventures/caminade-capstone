@extends('admin.layouts.layout')

@section('content')
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Pet Care Admin</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">Maintain</a>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('admin.pet-category.index') }}">Pet Categories</a>
                          <a class="dropdown-item" href="{{ route('admin.pet-breed.index') }}">Pet Breeds</a>
                          <a class="dropdown-item" href="{{ route('admin.pet-reproductive-alteration.index') }}">Pet Reproductive Alterations</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="{{ route('product-category.index') }}">Product Categories</a>
                          <a class="dropdown-item" href="{{ route('supplier.index') }}">Product Suppliers</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pet.index') }}">Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.user.index') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('service.index') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('product.index') }}">Products</a>
                    </li>
                </ul>
            </div>
        </nav>
        @yield('body')
    </div>
@endsection
