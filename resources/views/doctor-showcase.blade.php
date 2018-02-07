@extends('admin.layouts.layout')
@push('css')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
@section('content')
<div class="container">
    @include('blocks.home-header')
    <h3 class="text-center mt-3 mb-4"><i class="fas fa-user-md fa-2x"></i> <br>Our Doctors</h3>
    @foreach($doctors->chunk(3) AS $group)
        <div class="row">
            @foreach($group AS $doctor)
                <div class="col-sm-4">
                    <div class="card mb-2">
                        <div style="height:250px;background-position: center center;background-repeat: no-repeat;background-size: cover;background-image: url('{{ $doctor->doctorProfile->photo_filepath }}')"></div>
                        <div class="card-body">
                            <h4 class="card-title mb-0">{{ $doctor->fullname }}</h4>
                            <h5 class="text-info">{{ $doctor->doctorProfile->specialization }}</h5>
                            <p class="card-text">{{ $doctor->doctorProfile->bio }}</p>
                        </div>
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="bg-info text-white"></th>
                                    @foreach($doctor->doctorProfile->schedule AS $day => $time)
                                            <th class="bg-info text-white">{{ strtoupper($day)}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="bg-info text-white text-bold">IN</td>
                                    @foreach($doctor->doctorProfile->schedule AS $day => $time)
                                        <td>{!! isset($time['in']) ? date_create_from_format('H:i', $time['in'])->format('g:i A') : '<i class="fas fa-times text-danger"></i>' !!}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="bg-info text-white text-bold">OUT</td>
                                    @foreach($doctor->doctorProfile->schedule AS $day => $time)
                                        <td>{!! isset($time['out']) ? date_create_from_format('H:i', $time['out'])->format('h:i A') : '<i class="fas fa-times text-danger"></i>' !!}</td>
                                    @endforeach
                                </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
