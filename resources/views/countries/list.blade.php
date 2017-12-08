@extends('layouts.default')

@section('title', trans_choice('countries.countries', 2))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans_choice('countries.countries', 2) }}

                <div class="pull-right">
                    <a class="btn btn-default btn-xs" href="{{ url(trans('routes.admin') . '/' . trans('routes.countries') . '/' . trans('routes.create')) }}">{{ trans('common.create') }}</a>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>{{ trans_choice('common.codes', 1) }}</td>
                            <td>{{ trans_choice('common.names', 1) }}</td>
                            <td>{{ trans_choice('currency.currencies', 1) }}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($countries as $country)

                        <tr>
                            <td>{{ $country['iso_code'] }}</td>
                            <td>{{ $country['name'] }}</td>
                            <td>{{ $country['currency']['name'] }}</td>
                            <td width="75">

                                {!! \Form::open([
                                    'url' => url(trans('routes.admin') . '/' . trans('routes.countries') . '/' . $country['id']),
                                    'method' => 'delete',
                                    'class' => 'form-inline'
                                ]) !!}

                                    <a href="{{ url(trans('routes.admin') . '/' . trans('routes.countries') . '/' . $country['id'] . '/' . trans('routes.edit')) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                    {!! \Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}

                                {!! \Form::close() !!}

                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>

                <div class="text-center">
                    {{ $countries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
