@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Pets</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new pet</a>
    </div>
</div>
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>Owner</th>
            <th>Category</th>
            <th>Breed</th>
            <th>Gender</th>
            <th>Reproductive Alteration</th>
            <th>Birthdate</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList as $row)
        <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->owner->fullname }}</td>
            <td>{{ $row->breed->category->description }}</td>
            <td>{{ $row->breed->description }}</td>
            <td>{{ $row->gender }}</td>
            <td>{{ $row->reproductiveAlteration->description }}</td>
            <td>{{ date_create($row->birthdate)->format('F d, Y') }}</td>
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
