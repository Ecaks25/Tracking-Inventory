@section('title', __('Edit TTPB'))
<x-layouts.app :title="__('Edit TTPB')">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Form TTPB') }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('ttpb.update', $record) }}">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">{{ __('Tanggal') }}</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal', $record->tanggal) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="no_ttpb" class="form-label">{{ __('No. TTPB') }}</label>
                        <input type="text" id="no_ttpb" name="no_ttpb" class="form-control" value="{{ old('no_ttpb', $record->no_ttpb) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="lot_number" class="form-label">{{ __('Lot Number') }}</label>
                        <input type="text" id="lot_number" name="lot_number" class="form-control" value="{{ old('lot_number', $record->lot_number) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="nama_barang" class="form-label">{{ __('Nama Barang') }}</label>
                        <input type="text" id="nama_barang" name="nama_barang" class="form-control" value="{{ old('nama_barang', $record->nama_barang) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="dari" class="form-label">{{ __('Dari') }}</label>
                        <input type="text" id="dari" name="dari" class="form-control" value="{{ old('dari', $record->dari) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="ke" class="form-label">{{ __('Ke') }}</label>
                        <input type="text" id="ke" name="ke" class="form-control" value="{{ old('ke', $record->ke) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="qty_awal" class="form-label">{{ __('QTY Awal') }}</label>
                        <input type="text" inputmode="decimal" id="qty_awal" name="qty_awal" class="form-control" value="{{ old('qty_awal', $record->qty_awal) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="qty_aktual" class="form-label">{{ __('QTY Aktual') }}</label>
                        <input type="text" inputmode="decimal" id="qty_aktual" name="qty_aktual" class="form-control" value="{{ old('qty_aktual', $record->qty_aktual) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="qty_loss" class="form-label">{{ __('Qty Loss') }}</label>
                        <input type="text" inputmode="decimal" id="qty_loss" name="qty_loss" class="form-control" value="{{ old('qty_loss', $record->qty_loss) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="persen_loss" class="form-label">{{ __('% Loss') }}</label>
                        <input type="number" step="0.01" id="persen_loss" name="persen_loss" class="form-control" value="{{ old('persen_loss', $record->persen_loss) }}" />
                    </div>
                    @if ($record->dari === 'pencucian')
                        <div class="col-md-6">
                            <label for="kadar_air" class="form-label">{{ __('Kadar Air') }}</label>
                            <input type="number" step="0.01" id="kadar_air" name="kadar_air" class="form-control" value="{{ old('kadar_air', $record->kadar_air) }}" />
                        </div>
                        <div class="col-md-6">
                            <label for="deviasi" class="form-label">{{ __('Deviasi') }}</label>
                            <input type="number" step="0.01" id="deviasi" name="deviasi" class="form-control" value="{{ old('deviasi', $record->deviasi) }}" />
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label for="coly" class="form-label">{{ __('Coly') }}</label>
                        <input type="text" id="coly" name="coly" class="form-control" value="{{ old('coly', $record->coly) }}" />
                    </div>
                    <div class="col-md-6">
                        <label for="spec" class="form-label">{{ __('Spec') }}</label>
                        <input type="text" id="spec" name="spec" class="form-control" value="{{ old('spec', $record->spec) }}" />
                    </div>
                    <div class="col-12">
                        <label for="keterangan" class="form-label">{{ __('Keterangan') }}</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="3">{{ old('keterangan', $record->keterangan) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
