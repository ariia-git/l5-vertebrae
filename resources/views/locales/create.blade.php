@extends('layouts.default')

@section('title', trans('common.create') . ' ' . trans_choice('locales.locales', 1))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.create') . ' ' . trans_choice('locales.locales', 1) }}
                </div>

                <div class="panel-body">

                    {!! \Form::open([
                        'url' => url(trans('routes.admin') . '/' . trans('routes.locales')),
                        'method' => 'post'
                    ]) !!}

                        <!-- Code Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                {!! \Form::text('code', old('code'), [
                                    'class' => 'form-control',
                                    'placeholder' => trans_choice('common.codes', 1)
                                ]) !!}
                            </div>
                        </div>

                        <!-- Language Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('language_id') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-language"></i></span>
                                {!! \Form::select('language_id', $languages, old('language_id'), [
                                    'class' => 'form-control',
                                    'placeholder' => '- ' . trans('common.select') . ' ' . trans_choice('languages.languages', 1) . ' -'
                                ]) !!}
                            </div>
                        </div>

                        <!-- Country Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-map"></i></span>
                                {!! \Form::select('country_id', $countries, old('country_id'), [
                                    'class' => 'form-control',
                                    'placeholder' => '- ' . trans('common.select') . ' ' . trans_choice('countries.countries', 1) . ' -'
                                ]) !!}
                            </div>
                        </div>

                        <!-- Active Field -->
                        <div class="checkbox">
                            <label>
                                {!! \Form::checkbox('active', 1, old('active') ?: 0) !!} Active?
                            </label>
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
