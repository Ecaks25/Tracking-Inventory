<form method="GET" class="mb-3">
    <div class="d-flex gap-2">
        <select name="lot" class="form-select">
            <option value="">{{ __('Pilih Lot') }}</option>
            @foreach($lots as $lot)
                <option value="{{ $lot }}" {{ $lot == $selectedLot ? 'selected' : '' }}>{{ $lot }}</option>
            @endforeach
        </select>
        <button class="btn btn-secondary" type="submit">{{ __('Pilih') }}</button>
        @if($selectedLot)
            <a href="{{ url()->current() }}" class="btn btn-link">{{ __('Reset') }}</a>
        @endif
    </div>
</form>
