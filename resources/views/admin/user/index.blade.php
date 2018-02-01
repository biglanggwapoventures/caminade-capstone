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
{!! Form::open(['url' => route('admin.user.index'), 'method' => 'GET', 'class' => 'form-inline mb-2 mt-2']) !!}
<div class="form-group">
    <label for="inputPassword2">User Type</label>
    {!! Form::select('role', ['' => '** ALL **', 'DOCTOR' => 'Doctor', 'CUSTOMER' => 'Customer', 'STAFF' => 'Staff'], null, ['class' => 'custom-select ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">Name</label>
    {!! Form::text('name', null, ['class' => 'form-control ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">Email</label>
    {!! Form::text('email', null, ['class' => 'form-control ml-1', '']) !!}
  </div>
  <div class="form-group">
    <label for="inputPassword2" class="ml-1">Status</label>
    {!! Form::select('status', ['' => '** ALL **', 'blocked' => 'Blocked Only', 'unblocked' => 'Unblocked Only'], null, ['class' => 'custom-select ml-1', '']) !!}
  </div>
  <button type="submit" class="btn btn-danger ml-2">Search</button>
{!! Form::close() !!}
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Role</th>
            <th>Status</th>
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
                @if($row->is_blocked)
                <span class="badge badge-pill badge-warning">BLOCKED</span> <br>
                <small>By: {{ $row->blocked->fullname }} <br> On: {{ date_create($row->blocked_at)->format('m/d/Y h:i A') }}</small>
                @else
                    <span class="badge badge-pill badge-success">ACTIVE</span>
                @endif
            </td>
            <td>
                @include('components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])
                @if($row->is_blocked)
                    {!! Form::open(['url' =>  route('admin.user.unblock', ['userId' => $row->id]) , 'method' => 'post', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-success" onclick="javascript:return confirm('Are you sure?')">Unblock</button>

                @else
                    {!! Form::open(['url' =>  route('admin.user.block', ['userId' => $row->id]) , 'method' => 'post', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-warning" onclick="javascript:return confirm('Are you sure?')">Block</button>
                @endif
                {!! Form::close() !!}
                @if($row->is_blocked)
                @endif
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
