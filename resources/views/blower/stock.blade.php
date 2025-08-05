@section('title', __('Blower Stock'))
<x-layouts.app :title="__('Blower Stock')">

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Stock') }}</h5>
    </div>
    <div class="card-body">
@include('partials.month-filter')
        <a href="{{ route($role.'.ttpb.create') }}" class="btn btn-primary mb-4">{{ __('Isi TTPB') }}</a>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Tanggal</th>
                        <th>No. TTPB</th>
                        <th>Lot Number</th>
                        <th>Nama Barang</th>
                        <th>QTY Awal</th>
                        <th>QTY Aktual</th>
                        <th>Qty Loss</th>
                        <th>% Loss</th>
                        <th>Coly</th>
                        <th>Spec</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $item)
                        <tr>
                            <td>{{ ucfirst($item->dari) }}</td>
                            <td>{{ ucfirst($item->ke) }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->no_ttpb }}</td>
                            <td>{{ $item->lot_number }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->qty_awal }}</td>
                            <td>{{ $item->qty_aktual }}</td>
                            <td>{{ $item->qty_loss }}</td>
                            <td>{{ $item->persen_loss }}</td>
                            <td>{{ $item->coly }}</td>
                            <td>{{ $item->spec }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('ttpb.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('ttpb.destroy', $item) }}" onsubmit="return confirm('Hapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">{{ __('Belum ada data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</x-layouts.app>
