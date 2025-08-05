@section('title', __('Monitoring Gudang'))
<x-layouts.app :title="__('Monitoring Gudang')">
@include('partials.lot-filter', ['lots' => $lots, 'selectedLot' => $selectedLot])
@if($selectedLot)
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Monitoring Gudang') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Lot Number</th>
                        <th>Supplier</th>
                        <th>Nama Barang</th>
                        <th>Tgl Kedatangan</th>
                        <th>Qty IN (kg)</th>
                        <th>Qty IN TTPB (kg)</th>
                        <th>QTY OUT TTPB (kg)</th>
                        <th>Saldo (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['lot_number'] }}</td>
                            <td>{{ $item['supplier'] }}</td>
                            <td>{{ $item['nama_barang'] }}</td>
                            <td>{{ $item['tanggal'] }}</td>
                            <td>{{ $item['qty_in_bpg'] }}</td>
                            <td>{{ $item['qty_in_ttpb'] }}</td>
                            <td>{{ $item['qty_out_ttpb'] }}</td>
                            <td>{{ $item['saldo'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('Belum ada data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
</div>
</div>
</div>
@endif
</x-layouts.app>
