<footer class="page-footer">
    <div class="pull-left">
        {{ config('app.name') }} &copy; {{ Carbon\Carbon::now()->year }}
    </div>

    <div class="pull-right">
        <a id="back_to_top" class="pull-right">{{ trans('common.back_to_top') }}</a><br>
        <span class="pull-right">v{{ config('app.version') }}</span>
    </div>
</footer>
