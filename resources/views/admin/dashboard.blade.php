@extends('layouts.default')

@section('title', trans('common.admin'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('common.admin') }}
                </div>

                <div class="panel-body">
                    <ul>
                        @foreach ($sections as $section)
                        <li><a href="{{ \Localization::transRoute('routes.admin') . '/' . $section['link'] }}">{{ $section['text'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection