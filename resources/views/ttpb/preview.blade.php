@section('title', __('TTPB Preview'))
<x-layouts.app :title="__('TTPB Preview')">

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('TTPB Preview') }}</h5>
    </div>
    <div class="card-body">
        @forelse ($records->groupBy('no_ttpb') as $noTtpb => $items)
            @php $first = $items->first(); @endphp
            <div class="mb-4">
                <p><strong>{{ __('No. TTPB') }}:</strong> {{ $first->no_ttpb }}</p>
                <p><strong>{{ __('Tanggal') }}:</strong> {{ $first->tanggal }}</p>
                <p><strong>{{ __('Dari') }}:</strong> {{ ucfirst($first->dari) }}</p>
                <p><strong>{{ __('Ke') }}:</strong> {{ ucfirst($first->ke) }}</p>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('No. Lot') }}</th>
                                <th>{{ __('QTY Awal') }}</th>
                                <th>{{ __('QTY Aktual') }}</th>
                                <th>{{ __('Qty Loss Gudang') }}</th>
                                <th>{{ __('% Loss Gudang') }}</th>
                                <th>{{ __('Coly') }}</th>
                                <th>{{ __('Spec') }}</th>
                                <th>{{ __('Keterangan') }}</th>
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
        @empty
            <div class="text-center">{{ __('Belum ada data') }}</div>
        @endforelse
        <a href="{{ route($role.'.ttpb') }}" class="btn btn-primary mt-3">{{ __('Kembali') }}</a>
    </div>
</div>
</x-layouts.app>
