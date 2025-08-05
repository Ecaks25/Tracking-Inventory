@section('title', __('TTPB Preview'))
<x-layouts.app :title="__('TTPB Preview')">
    <style>
        .ttpb-card th,
        .ttpb-card td {
            font-size: 0.875rem;
        }
    </style>

    <h5 class="mb-4">{{ __('TTPB Preview') }}</h5>

    @php
        $groups = $records->groupBy('no_ttpb');
    @endphp

    @forelse ($groups as $number => $items)
        @php $first = $items->first(); @endphp
        <div class="card mb-4">
            <div class="card-body ttpb-card">
                <div class="mb-3">
                    <p>No.TTPB : {{ $number }}</p>
                    <p>Tanggal : {{ $first->tanggal }}</p>
                    <p>Dari : {{ ucfirst($first->dari) }}</p>
                    <p>Ke : {{ ucfirst($first->ke) }}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No. Lot</th>
                                <th>Qty Awal</th>
                                <th>Qty Aktual</th>
                                <th>Qty Loss Gudang</th>
                                <th>% Loss Gudang</th>
                                <th>Coly</th>
                                <th>Spec</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->lot_number }}</td>
                                    <td>{{ $item->qty_awal }}</td>
                                    <td>{{ $item->qty_aktual }}</td>
                                    <td>{{ $item->qty_loss }}</td>
                                    <td>{{ $item->persen_loss }}</td>
                                    <td>{{ $item->coly }}</td>
                                    <td>{{ $item->spec }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center">{{ __('Belum ada data') }}</p>
    @endforelse

    <a href="{{ route($role.'.ttpb') }}" class="btn btn-primary mt-3">{{ __('Kembali') }}</a>
</x-layouts.app>
