@section('title', __('TTPB Preview'))
<x-layouts.app :title="__('TTPB Preview')">
    <h5 class="mb-4">{{ __('TTPB Preview') }}</h5>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ __('Tanggal') }}</th>
                    <th>{{ __('No. TTPB') }}</th>
                    <th>{{ __('Lot Number') }}</th>
                    <th>{{ __('Nama Barang') }}</th>
                    <th>{{ __('Dari') }}</th>
                    <th>{{ __('Ke') }}</th>
                    <th>{{ __('QTY Awal') }}</th>
                    <th>{{ __('QTY Aktual') }}</th>
                    <th>{{ __('Qty Loss Gudang') }}</th>
                    <th>{{ __('% Loss Gudang') }}</th>
                    @if ($role === 'pencucian')
                        <th>{{ __('Kadar Air') }}</th>
                        <th>{{ __('Deviasi') }}</th>
                    @endif
                    <th>{{ __('Coly') }}</th>
                    <th>{{ __('Spec') }}</th>
                    <th>{{ __('Keterangan') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->no_ttpb }}</td>
                        <td>{{ $item->lot_number }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ ucfirst($item->dari) }}</td>
                        <td>{{ ucfirst($item->ke) }}</td>
                        <td>{{ $item->qty_awal }}</td>
                        <td>{{ $item->qty_aktual }}</td>
                        <td>{{ $item->qty_loss }}</td>
                        <td>{{ $item->persen_loss }}</td>
                        @if ($role === 'pencucian')
                            <td>{{ $item->kadar_air }}</td>
                            <td>{{ $item->deviasi }}</td>
                        @endif
                        <td>{{ $item->coly }}</td>
                        <td>{{ $item->spec }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $role === 'pencucian' ? 16 : 14 }}" class="text-center">{{ __('Belum ada data') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route($role.'.ttpb') }}" class="btn btn-primary mt-3">{{ __('Kembali') }}</a>
</x-layouts.app>

