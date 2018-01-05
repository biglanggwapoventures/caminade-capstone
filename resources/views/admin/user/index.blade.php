@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Users</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new user</a>
    </div>
</div>
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList as $row)
        <tr>
            <td>{{ $row->fullname }}</td>
            <td>{{ $row->gender }}</td>
            <td>{{ $row->email}} <br> <small>Using: <span class="text-info">{{ $row->registration_method }}</span></small></td>
            <td>{{ $row->contact_number }}</td>
            <td>{{ $row->role }}</td>
            <td>
                @include('components.form.index-actions', ['id' => $row->id])
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-danger text-center">Emtpty table</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
