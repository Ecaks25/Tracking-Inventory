@section('title', __('Input BPG'))
<x-layouts.app :title="__('Input BPG')">

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Form BPG') }}</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">{{ __('Tanggal') }}</label>
                    <input type="date" id="tanggal" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="no_bpg" class="form-label">{{ __('No. BPG') }}</label>
                    <input type="text" id="no_bpg" class="form-control" placeholder="BPG-001" />
                </div>
                <div class="col-md-6">
                    <label for="lot_number" class="form-label">{{ __('Lot Number') }}</label>
                    <input type="text" id="lot_number" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="supplier" class="form-label">{{ __('Supplier') }}</label>
                    <input type="text" id="supplier" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="nama_barang" class="form-label">{{ __('Nama Barang') }}</label>
                    <input type="text" id="nama_barang" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="qty" class="form-label">{{ __('QTY (kg)') }}</label>
                    <input type="text" inputmode="decimal" id="qty" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="coly" class="form-label">{{ __('Coly') }}</label>
                    <input type="text" id="coly" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="diterima" class="form-label">{{ __('Diterima Oleh') }}</label>
                    <input type="text" id="diterima" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="ttpb" class="form-label">{{ __('TTPB') }}</label>
                    <input type="text" id="ttpb" class="form-control" />
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
