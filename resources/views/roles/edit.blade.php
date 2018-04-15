@extends('layouts.default')

@section('title', trans('common.edit') . ' ' . trans_choice('roles.roles', 1))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.edit') . ' ' . trans_choice('roles.roles', 1) }}
                </div>

                <div class="panel-body">

                    {!! \Form::open([
                        'url' => url(trans('routes.admin') . '/' . trans('routes.roles') . '/' . $role->id),
                        'method' => 'put'
                    ]) !!}

                        <!-- Name Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                {!! \Form::text('name', old('name') ?: $role->name, [
                                    'class' => 'form-control',
                                    'placeholder' => trans_choice('common.name', 1)
                                ]) !!}
                            </div>
                        </div>

                        <!-- Key Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('key') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                {!! \Form::text('key', old('key') ?: $role->key, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('common.key')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-code"></i></span>
                                {!! \Form::textarea('description', old('description') ?: $role->description, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('common.description'),
                                    'style' => 'resize:vertical;',
                                    'rows' => 3
                                ]) !!}
                            </div>
                        </div>

                        <h4>Permissions</h4>

                        @foreach ($permissions as $permission)
                            <div class="checkbox">
                                <label>
                                    {{ \Form::checkbox('permissions[]', $permission->id, $permission->checked) }} {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach

                        <!-- Submit Form -->
                        <div class="form-group">
                            {!! \Form::button('<i class="fa fa-save"></i> ' . trans('common.save'), [
                                'type' => 'submit',
                                'class' => 'btn btn-primary pull-right'
                            ]) !!}
                        </div>

                    {!! \Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
