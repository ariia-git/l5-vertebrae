@if (isset($alerts) && !empty($alerts))

<div class="row">
    <div class="col-md-12">

        @foreach ($alerts->messages() as $type => $alert)
        @php
        if ($type === 'success') {
            $alertType = 'success';
            $alertTitle = trans('common.success.success');
        } else if ($type === 'warning') {
            $alertType = 'warning';
            $alertTitle = trans('common.warning.warning');
        } else if ($type === 'error') {
            $alertType = 'danger';
            $alertTitle = trans('common.error.error');
        } else {
            $alertType = 'info';
            $alertTitle = trans('common.attention.attention');
        }
        @endphp

        @if (is_array($alert))
        @foreach ($alert as $message)
        <div class="alert alert-{{ $alertType }}">
            <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('common.close') }}</span></button>
            <strong>{{ $alertTitle }}:</strong> {!! $message !!}
        </div>
        @endforeach
        @else
        <div class="alert alert-{{ $alertType }}">
            <button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('common.close') }}</span></button>
            <strong>{{ $alertTitle }}:</strong> {!! $alert !!}
        </div>
        @endif
        @endforeach

    </div>
</div>

@endif
