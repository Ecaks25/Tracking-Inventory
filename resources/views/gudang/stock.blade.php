@section('title', __('Gudang Stock'))
<x-layouts.app :title="__('Gudang Stock')">

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Stock') }}</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('gudang.stock.create') }}" class="btn btn-primary mb-4">{{ __('Input BPG') }}</a>
        @include('partials.month-filter')
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No. BPG</th>
                        <th>Tanggal</th>
                        <th>Lot Number</th>
                        <th>Supplier</th>
                        <th>Nomor Mobil</th>
                        <th>Nama Barang</th>
                        <th>QTY (kg)</th>
                        <th>QTY Aktual</th>
                        <th>Loss</th>
                        <th>Coly</th>
                        <th>Diterima Oleh</th>
                        <th>TTPB</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $item)
                        <tr>
                            <td>{{ $item->no_bpg }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->lot_number }}</td>
                            <td>{{ $item->supplier }}</td>
                            <td>{{ $item->nomor_mobil }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->qty_aktual }}</td>
                            <td>{{ $item->qty_loss }}</td>
                            <td>{{ $item->coly }}</td>
                            <td>{{ $item->diterima }}</td>
                            <td>{{ $item->ttpb }}</td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('bpg.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('bpg.destroy', $item) }}" onsubmit="return confirm('Hapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">{{ __('Belum ada data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.app>
