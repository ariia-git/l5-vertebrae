@extends('layouts.default')

@section('title', trans('common.edit') . ' ' . trans_choice('currencies.currencies', 1))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.edit') . ' ' . trans_choice('currencies.currencies', 1) }}
                </div>

                <div class="panel-body">

                    {!! \Form::open([
                        'url' => url(trans('routes.admin') . '/' . trans('routes.currencies') . '/' . $currency->id),
                        'method' => 'put'
                    ]) !!}

                        <!-- ISO Code Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('iso_code') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                {!! \Form::text('iso_code', old('iso_code') ?: $currency->iso_code, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('currencies.iso_code')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                {!! \Form::text('name', old('name') ?: $currency->name, [
                                    'class' => 'form-control',
                                    'placeholder' => trans_choice('common.names', 1)
                                ]) !!}
                            </div>
                        </div>

                        <!-- Symbol Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('symbol') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                {!! \Form::text('symbol', old('symbol') ?: $currency->symbol, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('currencies.symbol')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Precision Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('decimal_precision') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-hand-o-right"></i></span>
                                {!! \Form::text('decimal_precision', old('decimal_precision') ?: $currency->decimal_precision, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('currencies.precision')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Exchange Rate Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('exchange_rate') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                                {!! \Form::text('exchange_rate', old('exchange_rate') ?: $currency->exchange_rate, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('currencies.exchange_rate')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Submit Form -->
                        <div class="form-group">
                            {!! \Form::button('<i class="fa fa-save"></i> ' . trans('common.submit'), [
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
