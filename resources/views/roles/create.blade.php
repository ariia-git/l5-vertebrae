@extends('layouts.default')

@section('title', trans('common.create') . ' ' . trans_choice('roles.roles', 1))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.create') . ' ' . trans_choice('roles.roles', 1) }}
                </div>

                <div class="panel-body">

                    {!! \Form::open([
                        'url' => url(trans('routes.admin') . '/' . trans('routes.roles')),
                        'method' => 'post'
                    ]) !!}

                        <!-- Name Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                {!! \Form::text('name', old('name'), [
                                    'class' => 'form-control',
                                    'placeholder' => trans_choice('common.names', 1)
                                ]) !!}
                            </div>
                        </div>

                        <!-- Key Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('key') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                {!! \Form::text('key', old('key'), [
                                    'class' => 'form-control',
                                    'placeholder' => trans('common.key')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <div class="input-group{{ $errors->has('key') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-code"></i></span>
                                {!! \Form::textarea('description', old('description'), [
                                    'class' => 'form-control',
                                    'placeholder' => trans('common.description'),
                                    'style' => 'resize:vertical;',
                                    'rows' => 3
                                ]) !!}
                            </div>
                        </div>

                        {{-- todo: permission selection --}}

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
