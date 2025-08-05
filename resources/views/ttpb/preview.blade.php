@section('title', __('TTPB Preview'))
<x-layouts.app :title="__('TTPB Preview')">
    <style>
        .ttpb-paper {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 2rem;
            padding: 10mm;
            background: #fff;
            border: 1px solid #000;
        }
        .ttpb-paper .header p {
            margin: 0;
        }
        .ttpb-paper table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .ttpb-paper th,
        .ttpb-paper td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 0.875rem;
        }
    </style>

    <h5 class="mb-4">{{ __('TTPB Preview') }}</h5>

    @php
        $groups = $records->groupBy('no_ttpb');
    @endphp

    @forelse ($groups as $number => $items)
        @php
            $first = $items->first();
        @endphp
        <div class="ttpb-paper">
            <div class="header mb-3">
                <p>No.TTPB : {{ $number }}</p>
                <p>Tanggal : {{ $first->tanggal }}</p>
                <p>Dari : {{ ucfirst($first->dari) }}</p>
                <p>Ke : {{ ucfirst($first->ke) }}</p>
            </div>
            <table>
                <thead>
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
    @empty
        <p class="text-center">{{ __('Belum ada data') }}</p>
    @endforelse

    <a href="{{ route($role.'.ttpb') }}" class="btn btn-primary mt-3">{{ __('Kembali') }}</a>
</x-layouts.app>
