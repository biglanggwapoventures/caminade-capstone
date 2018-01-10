<a href="{{ MyHelper::resource('edit', compact('id')) }}" class="btn btn-secondary">
    <span class="fa fa-pencil"></span> Edit
</a>

@if(!isset($hideRemove))
{!! Form::open(['url'=> MyHelper::resource('destroy', compact('id')), 'method'=> 'DELETE', 'style' => 'display:inline-block']) !!}
    <a class="btn btn-danger trash-row" href="#">
        <span class="fa fa-trash"></span> Delete
    </a>
{!! Form::close()!!}
@endif
