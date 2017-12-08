@extends('layouts.default')

@section('title', trans_choice('locales.locales', 2))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans_choice('locales.locales', 2) }}

                <div class="pull-right">
                    <a class="btn btn-default btn-xs" href="{{ url(trans('routes.admin') . '/' . trans('routes.locales') . '/' . trans('routes.create')) }}">{{ trans('common.create') }}</a>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>{{ trans_choice('common.codes', 1) }}</td>
                            <td>{{ trans_choice('languages.languages', 1) }}</td>
                            <td>{{ trans_choice('countries.countries', 1) }}</td>
                            <td>{{ trans('locales.number_style') }}</td>
                            <td>{{ trans('locales.regional') }}</td>
                            <td>{{ trans('locales.script') }}</td>
                            <td>{{ trans('common.active') }}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($locales as $locale)

                        <tr>
                            <td>{{ $locale['code'] }}</td>
                            <td>{{ $locale['language']['name'] }}</td>
                            <td>{{ $locale['country']['name'] }}</td>
                            <td>{{ '1' . $locale['thousands_separator'] . '000' . $locale['decimal_mark'] . '0' }}</td>
                            <td>{{ \Localization::getLocaleRegional($locale['code']) }}</td>
                            <td>{{ \Localization::getLocaleScript($locale['code']) }}</td>
                            <td>{{ $locale['active'] ? trans('common.yes') : trans('common.no') }}</td>
                            <td width="75">

                                {!! \Form::open([
                                    'url' => url(trans('routes.admin') . '/' . trans('routes.locales') . '/' . $locale['id']),
                                    'method' => 'delete',
                                    'class' => 'form-inline'
                                ]) !!}

                                    <a href="{{ url(trans('routes.admin') . '/' . trans('routes.locales') . '/' . $locale['id'] . '/' . trans('routes.edit')) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                    {!! \Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}

                                {!! \Form::close() !!}

                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>

                <div class="text-center">
                    {{ $locales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
