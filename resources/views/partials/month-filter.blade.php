<form method="GET" class="mb-3">
    <div class="d-flex gap-2">
        <input type="month" name="month" value="{{ request('month') }}" class="form-control" />
        <input type="text" name="lot" value="{{ request('lot') }}" class="form-control" placeholder="{{ __('Lot Number') }}" />
        <button class="btn btn-secondary" type="submit">{{ __('Filter') }}</button>
        <a href="{{ url()->current() }}" class="btn btn-link">{{ __('Reset') }}</a>
    </div>
</form>
