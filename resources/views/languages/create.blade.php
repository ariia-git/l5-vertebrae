@extends('layouts.default')

@section('title', trans('common.create') . ' ' . trans_choice('languages.languages', 1))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.create') . ' ' . trans_choice('languages.languages', 1) }}
                </div>

                <div class="panel-body">

                    {!! \Form::open([
                        'url' => url(trans('routes.admin') . '/' . trans('routes.languages')),
                        'method' => 'post'
                    ]) !!}

                        <!-- ISO Code Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('iso_code') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                {!! \Form::text('iso_code', old('iso_code'), [
                                    'class' => 'form-control',
                                    'placeholder' => trans('languages.iso_code')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-language"></i></span>
                                {!! \Form::text('name', old('name'), [
                                    'class' => 'form-control',
                                    'placeholder' => trans_choice('common.names', 1)
                                ]) !!}
                            </div>
                        </div>

                        <!-- Script Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('script') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-font"></i></span>
                                {!! \Form::text('script', old('script') ?: 'Latn', [
                                    'class' => 'form-control',
                                    'placeholder' => trans('languages.script')
                                ]) !!}
                            </div>
                        </div>

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
