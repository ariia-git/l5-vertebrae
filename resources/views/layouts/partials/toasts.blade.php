@if (\Session::has('successes') || \Session::has('infos') || \Session::has('warnings') || \Session::has('errors')
    || isset($successes) || isset($errors) || isset($warnings) || isset($infos))

<script type="text/javascript">
    $(function () {
        toastr.options = {
            'positionClass': 'toast-bottom-right'
        };

        @if (\Session::has('successes'))
        @foreach (\Session::pull('successes') as $message)
        toastr.success('{{ $message }}');
        @endforeach
        @endif
        @if (isset($successes))
        @foreach ($successes->all() as $message)
        toastr.success('{{ $message }}');
        @endforeach
        @endif

        @if (\Session::has('infos'))
        @foreach (\Session::pull('infos') as $message)
        toastr.info('{{ $message }}');
        @endforeach
        @endif
        @if (isset($infos))
        @foreach ($infos->all() as $message)
        toastr.info('{{ $message }}');
        @endforeach
        @endif

        @if (\Session::has('warnings'))
        @foreach (\Session::pull('warnings') as $message)
        toastr.warning('{{ $message }}');
        @endforeach
        @endif
        @if (isset($warnings))
        @foreach ($warnings->all() as $message)
        toastr.warning('{{ $message }}');
        @endforeach
        @endif

        @if (\Session::has('errors'))
        @foreach (\Session::pull('errors') as $message)
        toastr.error('{{ $message }}');
        @endforeach
        @endif
        @if (isset($errors))
        @foreach ($errors->all() as $message)
        toastr.error('{{ $message }}');
        @endforeach
        @endif

    });
</script>

@endif
