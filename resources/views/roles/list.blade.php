@extends('layouts.default')

@section('title', trans_choice('roles.roles', 2))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans_choice('roles.roles', 2) }}

                <div class="pull-right">
                    <a class="btn btn-default btn-xs" href="{{ url(trans('routes.admin') . '/' . trans('routes.roles') . '/' . trans('routes.create')) }}">{{ trans('common.create') }}</a>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>{{ trans_choice('common.names', 1) }}</td>
                            <td>{{ trans('common.key') }}</td>
                            <td>{{ trans('common.description') }}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($roles as $role)

                        <tr>
                            <td>{{ $role['name'] }}</td>
                            <td>{{ $role['key'] }}</td>
                            <td>{!! $role['description'] !!}</td>
                            <td width="75">

                                {!! \Form::open([
                                    'url' => url(trans('routes.admin') . '/' . trans('routes.roles') . '/' . $role['id']),
                                    'method' => 'delete',
                                    'class' => 'form-inline'
                                ]) !!}

                                    <a href="{{ url(trans('routes.admin') . '/' . trans('routes.roles') . '/' . $role['id'] . '/' . trans('routes.edit')) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                    {!! \Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs']) !!}

                                {!! \Form::close() !!}

                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>

                <div class="text-center">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
