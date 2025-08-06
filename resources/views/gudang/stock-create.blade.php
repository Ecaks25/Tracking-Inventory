@section('title', __('Input BPG'))
<x-layouts.app :title="__('Input BPG')">

@include('gudang.partials.stock-form', ['lotNumbers' => $lotNumbers])
</x-layouts.app>
