@extends('layouts.default')

@section('title', trans('common.edit') . ' ' . trans_choice('countries.countries', 1))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.edit') . ' ' . trans_choice('countries.countries', 1) }}
                </div>

                <div class="panel-body">

                    {!! \Form::open([
                        'url' => url(trans('routes.admin') . '/' . trans('routes.countries') . '/' . $country->id),
                        'method' => 'put'
                    ]) !!}

                        <!-- ISO Code Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('iso_code') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                {!! \Form::text('iso_code', old('iso_code') ?: $country->iso_code, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('countries.iso_code')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-map"></i></span>
                                {!! \Form::text('name', old('name') ?: $country->name, [
                                    'class' => 'form-control',
                                    'placeholder' => trans_choice('common.names', 1)
                                ]) !!}
                            </div>
                        </div>

                        <!-- Currency Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                {!! \Form::select('currency_id', $currencies, old('currency_id') ?: $country->currency_id, [
                                    'class' => 'form-control',
                                    'placeholder' => '- ' . trans('common.select') . ' ' . trans_choice('currencies.currencies', 1) . ' -'
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
