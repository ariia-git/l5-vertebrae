@extends('layouts.default')

@section('title', trans_choice('currencies.currencies', 2))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans_choice('currencies.currencies', 2) }}

                <div class="pull-right">
                    <a class="btn btn-default btn-xs" href="{{ url(trans('routes.admin') . '/' . trans('routes.currencies') . '/' . trans('routes.create')) }}">{{ trans('common.create') }}</a>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>{{ trans('common.id') }}</td>
                            <td>{{ trans_choice('common.codes', 1) }}</td>
                            <td>{{ trans_choice('common.names', 1) }}</td>
                            <td>{{ trans('currencies.symbol') }}</td>
                            <td>{{ trans('currencies.precision') }}</td>
                            <td>{{ trans('currencies.exchange_rate') }}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($currencies as $currency)

                        <tr>
                            <td>{{ $currency['id'] }}</td>
                            <td>{{ $currency['iso_code'] }}</td>
                            <td>{{ $currency['name'] }}</td>
                            <td>{{ $currency['symbol'] }}</td>
                            <td>{{ $currency['decimal_precision'] }}</td>
                            <td>{{ $currency['exchange_rate'] }}</td>
                            <td width="75">

                                {!! \Form::open([
                                    'url' => url(trans('routes.admin') . '/' . trans('routes.currencies') . '/' . $currency['id']),
                                    'method' => 'delete',
                                    'class' => 'form-inline'
                                ]) !!}

                                <a href="{{ url(trans('routes.admin') . '/' . trans('routes.currencies') . '/' . $currency['id'] . '/' . trans('routes.edit')) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                {!! \Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}

                                {!! \Form::close() !!}

                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>

                <div class="text-center">
                    {{ $currencies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
