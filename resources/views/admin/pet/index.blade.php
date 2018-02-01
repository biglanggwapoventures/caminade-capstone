@extends('admin.layouts.main')
@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Pets</h2>
    </div>
    @if(auth()->user()->is(['admin', 'staff']))
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new pet</a>
    </div>
    @endif
</div>
{!! Form::open(['url' => route('admin.pet.index'), 'method' => 'GET', 'class' => 'form-inline mb-2 mt-2']) !!}
  <div class="form-group">
    <label for="inputPassword2">Customer</label>
    {!! Form::select('customer_id', $customerList, null, ['class' => 'custom-select ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">Pet Name</label>
    {!! Form::text('pet_name', null, ['class' => 'form-control ml-1', '']) !!}
  </div>
  <button type="submit" class="btn btn-danger ml-2">Search</button>
{!! Form::close() !!}
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
            <td>{{ date_create($row->birthdate)->format('m/d/Y') }}</td>
            <td>
                @includeWhen(auth()->user()->is('admin'), 'components.form.index-actions', ['id' => $row->id])
                <a href="{{ route('admin.pet.show', ['id' => $row->id]) }}" class="btn btn-info">View</a>
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
