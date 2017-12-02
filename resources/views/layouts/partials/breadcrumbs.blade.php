@if (isset($breadcrumbs) && $breadcrumbs !== false)

<ul class="breadcrumb">

    @if (isset($breadcrumbs))

    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>

    @foreach ($breadcrumbs as $breadcrumb)
    @if ($breadcrumb != end($breadcrumbs))
    <li><a href="{{ url($breadcrumb['link']) }}">{!! $breadcrumb['text'] !!}</a></li>
    @else
    <li class="active">{!! $breadcrumb['text'] !!}</li>
    @endif
    @endforeach

    @else

    <li class="active"><i class="fa fa-home"></i></li>

    @endif

</ul>

@endif
