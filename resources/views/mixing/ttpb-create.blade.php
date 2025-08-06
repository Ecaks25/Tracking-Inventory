@section('title', __('Isi TTPB'))
<x-layouts.app :title="__('Isi TTPB')">

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Form TTPB') }}</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first('qty_awal') }}</div>
        @endif
        <form method="POST" action="{{ route($role.'.ttpb.store') }}">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">{{ __('Tanggal') }}</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="no_ttpb" class="form-label">{{ __('No. TTPB') }}</label>
                    <input type="text" id="no_ttpb" name="no_ttpb" class="form-control" placeholder="TTPB-001" />
                </div>
                <div class="col-md-6">
                    <label for="lot_number" class="form-label">{{ __('Lot Number') }}</label>
                    <select id="lot_number" name="lot_number" class="form-select">
                        <option value="">-- Pilih Lot Number --</option>
                        @foreach($stocks as $item)
                            <option value="{{ $item->lot_number }}" data-nama-barang="{{ $item->nama_barang }}">{{ $item->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="nama_barang" class="form-label">{{ __('Nama Barang') }}</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" />
                </div>
                
                <div class="col-md-6">
                    <label for="dari" class="form-label">{{ __('Dari') }}</label>
                    <select id="dari" name="dari" class="form-select">
                        <option value="">-- Pilih Asal --</option>
                        @foreach($roles as $item)
                            <option value="{{ $item }}" {{ $item === $role ? 'selected' : '' }}>{{ ucfirst($item) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="ke" class="form-label">{{ __('Ke') }}</label>
                    <select id="ke" name="ke" class="form-select">
                        <option value="">-- Pilih Tujuan --</option>
                        @foreach($roles as $item)
                            @if($item !== $role)
                                <option value="{{ $item }}">{{ ucfirst($item) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6" id="mix-option" style="display: none;">
                    <label for="di_mix" class="form-label">{{ __('Di Mix?') }}</label>
                    <select id="di_mix" name="di_mix" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>
                <div id="mix-details" style="display: none;" class="row g-4">
                    <div class="col-md-6">
                        <label for="lot_number_mix" class="form-label">{{ __('Lot Number Mix') }}</label>
                        <select id="lot_number_mix" name="lot_number_mix" class="form-select">
                            <option value="">-- Pilih Lot Number --</option>
                            @foreach($stocks as $item)
                                <option value="{{ $item->lot_number }}">{{ $item->lot_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="qty_awal_mix" class="form-label">{{ __('QTY Awal Mix') }}</label>
                        <input type="text" inputmode="decimal" id="qty_awal_mix" name="qty_awal_mix" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label for="qty_aktual_mix" class="form-label">{{ __('QTY Aktual Mix') }}</label>
                        <input type="text" inputmode="decimal" id="qty_aktual_mix" name="qty_aktual_mix" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label for="loss_gd_mix" class="form-label">{{ __('Loss GD Mix') }}</label>
                        <input type="number" id="loss_gd_mix" name="loss_gd_mix" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label for="loss_aktual_mix" class="form-label">{{ __('Loss Aktual Mix') }}</label>
                        <input type="text" inputmode="decimal" id="loss_aktual_mix" name="loss_aktual_mix" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label for="lot_number_baru" class="form-label">{{ __('Lot Baru') }}</label>
                        <input type="text" id="lot_number_baru" name="lot_number_baru" class="form-control" readonly />
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="qty_awal" class="form-label">{{ __('QTY Awal') }}</label>
                    <input type="text" inputmode="decimal" id="qty_awal" name="qty_awal" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="qty_aktual" class="form-label">{{ __('QTY Aktual') }}</label>
                    <input type="text" inputmode="decimal" id="qty_aktual" name="qty_aktual" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="qty_loss" class="form-label">{{ __('Qty Loss Mixing') }}</label>
                    <input type="text" inputmode="decimal" id="qty_loss" name="qty_loss" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="persen_loss" class="form-label">{{ __('% Loss Mixing') }}</label>
                    <input type="number" step="0.01" id="persen_loss" name="persen_loss" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="coly" class="form-label">{{ __('Coly') }}</label>
                    <input type="text" id="coly" name="coly" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="spec" class="form-label">{{ __('Spec') }}</label>
                    <input type="text" id="spec" name="spec" class="form-control" />
                </div>
                <div class="col-12">
                    <label for="keterangan" class="form-label">{{ __('Keterangan') }}</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="3"></textarea>
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
        function parseNumber(val) {
            val = (val || '').trim();
            if (!val) return 0;
            if (val.includes('.') && val.includes(',')) {
                val = val.replace(/\./g, '').replace(',', '.');
            } else {
                val = val.replace(',', '.');
            }
            return parseFloat(val) || 0;
        }

        document.getElementById('lot_number').addEventListener('change', function () {
            const option = this.options[this.selectedIndex];
            document.getElementById('nama_barang').value = option.dataset.namaBarang || '';
        });

        function updateLoss() {
            const qtyAwal = parseNumber(document.getElementById('qty_awal').value);
            const qtyAktual = parseNumber(document.getElementById('qty_aktual').value);
            const loss = qtyAwal - qtyAktual;
            const percent = qtyAwal ? (loss / qtyAwal) * 100 : 0;
            document.getElementById('qty_loss').value = loss;
            document.getElementById('persen_loss').value = percent.toFixed(2);
        }

        function toggleMixOption() {
            const keValue = document.getElementById('ke').value;
            document.getElementById('mix-option').style.display =
                keValue === 'grinding' || keValue === 'mixing' ? 'block' : 'none';
            if (keValue !== 'grinding' && keValue !== 'mixing') {
                document.getElementById('di_mix').value = '';
                toggleMixDetails();
            }
        }

        function toggleMixDetails() {
            const mixValue = document.getElementById('di_mix').value;
            document.getElementById('mix-details').style.display = mixValue === 'ya' ? 'block' : 'none';
            updateMixName();
        }

        function updateMixName() {
            if (document.getElementById('di_mix').value === 'ya') {
                const lot1 = document.getElementById('lot_number').value;
                const lot2 = document.getElementById('lot_number_mix').value;
                if (lot1 && lot2) {
                    document.getElementById('lot_number_baru').value = `MIX-${lot1}-${lot2}`;
                }
            } else {
                document.getElementById('lot_number_baru').value = '';
            }
        }

        document.getElementById('qty_awal').addEventListener('input', updateLoss);
        document.getElementById('qty_aktual').addEventListener('input', updateLoss);
        document.getElementById('ke').addEventListener('change', toggleMixOption);
        document.getElementById('di_mix').addEventListener('change', toggleMixDetails);
        document.getElementById('lot_number').addEventListener('change', updateMixName);
        document.getElementById('lot_number_mix').addEventListener('change', updateMixName);
        toggleMixOption();
        toggleMixDetails();
    </script>
@endsection
</x-layouts.app>
