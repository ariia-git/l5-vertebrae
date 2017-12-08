@extends('layouts.default')

@section('title', trans('common.edit') . ' ' . trans_choice('locales.locales', 1))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.edit') . ' ' . trans_choice('locales.locales', 1) }}
                </div>

                <div class="panel-body">

                    {!! \Form::open([
                        'url' => url(trans('routes.admin') . '/' . trans('routes.locales') . '/' . $locale->id),
                        'method' => 'put'
                    ]) !!}

                        <!-- Code Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                {!! \Form::text('code', old('code') ?: $locale->code, [
                                    'class' => 'form-control',
                                    'placeholder' => trans_choice('common.codes', 1)
                                ]) !!}
                            </div>
                        </div>

                        <!-- Language Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('language_id') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-language"></i></span>
                                {!! \Form::select('language_id', $languages, old('language_id') ?: $locale->language_id, [
                                    'class' => 'form-control',
                                    'placeholder' => '- ' . trans('common.select') . ' ' . trans_choice('languages.languages', 1) . ' -'
                                ]) !!}
                            </div>
                        </div>

                        <!-- Country Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-map"></i></span>
                                {!! \Form::select('country_id', $countries, old('country_id') ?: $locale->country_id, [
                                    'class' => 'form-control',
                                    'placeholder' => '- ' . trans('common.select') . ' ' . trans_choice('countries.countries', 1) . ' -'
                                ]) !!}
                            </div>
                        </div>

                        <!-- Currency Symbol First Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('currency_symbol_first') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                {!! \Form::select('currency_symbol_first', [1 => trans('common.before'), 0 => trans('common.after')], old('currency_symbol_first') ?: $locale->currency_symbol_first, [
                                    'class' => 'form-control',
                                    'placeholder' => '- ' . trans('locales.currency_symbol_placement') . ' -'
                                ]) !!}
                            </div>
                        </div>

                        <!-- Thousands Separator Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('thousands_separator') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-comma"></i></span>
                                {!! \Form::text('thousands_separator', old('thousands_separator') ?: $locale->thousands_separator, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('locales.thousands_separator')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Decimal Mark Field -->
                        <div class="form-group">
                            <div class="input-group{{ $errors->has('decimal_mark') ? ' has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-period"></i></span>
                                {!! \Form::text('decimal_mark', old('decimal_mark') ?: $locale->decimal_mark, [
                                    'class' => 'form-control',
                                    'placeholder' => trans('locales.decimal_mark')
                                ]) !!}
                            </div>
                        </div>

                        <!-- Active Field -->
                        <div class="checkbox">
                            <label>
                                {!! \Form::checkbox('active', 1, old('active') ?: $locale->active) !!} Active?
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
