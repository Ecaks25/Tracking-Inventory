<div class="card mt-3" id="createFormContainer">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Form BPG') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('gudang.stock.store') }}">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">{{ __('Tanggal') }}</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="no_bpg" class="form-label">{{ __('No. BPG') }}</label>
                    <input type="text" id="no_bpg" name="no_bpg" class="form-control" placeholder="BPG-001" />
                </div>
                <div class="col-md-6">
                    <label for="lot_number" class="form-label">{{ __('Lot Number') }}</label>
                    <input type="text" id="lot_number" name="lot_number" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="supplier" class="form-label">{{ __('Supplier') }}</label>
                    <input type="text" id="supplier" name="supplier" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="nama_barang" class="form-label">{{ __('Nama Barang') }}</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="qty" class="form-label">{{ __('QTY (kg)') }}</label>
                    <input type="text" inputmode="decimal" id="qty" name="qty" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="qty_aktual" class="form-label">{{ __('QTY Aktual') }}</label>
                    <input type="text" inputmode="decimal" id="qty_aktual" name="qty_aktual" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="qty_loss" class="form-label">{{ __('Loss') }}</label>
                    <input type="text" inputmode="decimal" id="qty_loss" name="qty_loss" class="form-control" readonly />
                </div>
                <div class="col-md-6">
                    <label for="coly" class="form-label">{{ __('Coly') }}</label>
                    <input type="text" id="coly" name="coly" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="diterima" class="form-label">{{ __('Diterima Oleh') }}</label>
                    <input type="text" id="diterima" name="diterima" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="ttpb" class="form-label">{{ __('TTPB') }}</label>
                    <input type="text" id="ttpb" name="ttpb" class="form-control" />
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
            </div>
        </form>
    </div>
</div>

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
