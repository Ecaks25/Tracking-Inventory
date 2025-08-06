@section('title', __('Edit BPG'))
<x-layouts.app :title="__('Edit BPG')">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Form BPG') }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('bpg.update', $record) }}">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">{{ __('Tanggal') }}</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal', $record->tanggal) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="no_bpg" class="form-label">{{ __('No. BPG') }}</label>
                        <input type="text" id="no_bpg" name="no_bpg" class="form-control" value="{{ old('no_bpg', $record->no_bpg) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="lot_number" class="form-label">{{ __('Lot Number') }}</label>
                        <input list="lot_numbers" id="lot_number" name="lot_number" class="form-control" value="{{ old('lot_number', $record->lot_number) }}" placeholder="-- Pilih Lot Number --" />
                        <datalist id="lot_numbers">
                            @foreach($lotNumbers as $lot)
                                <option value="{{ $lot }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-6">
                        <label for="supplier" class="form-label">{{ __('Supplier') }}</label>
                        <input type="text" id="supplier" name="supplier" class="form-control" value="{{ old('supplier', $record->supplier) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="nomor_mobil" class="form-label">{{ __('Nomor Mobil') }}</label>
                        <input type="text" id="nomor_mobil" name="nomor_mobil" class="form-control" value="{{ old('nomor_mobil', $record->nomor_mobil) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="nama_barang" class="form-label">{{ __('Nama Barang') }}</label>
                        <input type="text" id="nama_barang" name="nama_barang" class="form-control" value="{{ old('nama_barang', $record->nama_barang) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="qty" class="form-label">{{ __('QTY (kg)') }}</label>
                        <input type="text" inputmode="decimal" id="qty" name="qty" class="form-control" value="{{ old('qty', $record->qty) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="qty_aktual" class="form-label">{{ __('QTY Aktual') }}</label>
                        <input type="text" inputmode="decimal" id="qty_aktual" name="qty_aktual" class="form-control" value="{{ old('qty_aktual', $record->qty_aktual) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="qty_loss" class="form-label">{{ __('Loss') }}</label>
                        <input type="text" inputmode="decimal" id="qty_loss" name="qty_loss" class="form-control" value="{{ old('qty_loss', $record->qty_loss) }}" readonly />
                    </div>
                    <div class="col-md-6">
                        <label for="coly" class="form-label">{{ __('Coly') }}</label>
                        <input type="text" id="coly" name="coly" class="form-control" value="{{ old('coly', $record->coly) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="diterima" class="form-label">{{ __('Diterima Oleh') }}</label>
                        <input type="text" id="diterima" name="diterima" class="form-control" value="{{ old('diterima', $record->diterima) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="ttpb" class="form-label">{{ __('TTPB') }}</label>
                        <input type="text" id="ttpb" name="ttpb" class="form-control" value="{{ old('ttpb', $record->ttpb) }}" />
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

@section('page-script')
    <script>
        function updateLoss() {
            const qty = parseFloat(document.getElementById('qty').value) || 0;
            const qtyAktual = parseFloat(document.getElementById('qty_aktual').value) || 0;
            document.getElementById('qty_loss').value = qty - qtyAktual;
        }

        document.getElementById('qty').addEventListener('input', updateLoss);
        document.getElementById('qty_aktual').addEventListener('input', updateLoss);
    </script>
@endsection
