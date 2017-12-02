@extends('layouts.default')

@section('title', trans_choice('languages.languages', 2))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans_choice('languages.languages', 2) }}

                <div class="pull-right">
                    <a class="btn btn-default btn-xs" href="{{ url(trans('routes.admin') . '/' . trans('routes.languages') . '/' . trans('routes.create')) }}">{{ trans('common.create') }}</a>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>{{ trans_choice('common.codes', 1) }}</td>
                            <td>{{ trans_choice('common.names', 1) }}</td>
                            <td>{{ trans('languages.script') }}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($languages as $language)

                        <tr>
                            <td>{{ $language['iso_code'] }}</td>
                            <td>{{ $language['name'] }}</td>
                            <td>{{ $language['script'] }}</td>
                            <td width="75">

                                {!! \Form::open([
                                    'url' => url(trans('routes.admin') . '/' . trans('routes.languages') . '/' . $language['id']),
                                    'method' => 'delete',
                                    'class' => 'form-inline'
                                ]) !!}

                                    <a href="{{ url(trans('routes.admin') . '/' . trans('routes.languages') . '/' . $language['id'] . '/' . trans('routes.edit')) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                    {!! \Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}

                                {!! \Form::close() !!}

                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>

                <div class="text-center">
                    {{ $languages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
