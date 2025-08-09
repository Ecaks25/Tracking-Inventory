@section('title', __('Isi TTPB'))

<x-layouts.app :title="__('Isi TTPB')">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Form TTPB') }}</h5>
        </div>

        <div class="card-body">
            {{-- Alert global jika ada error --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    {{ __('Periksa kembali input Anda.') }}
                </div>
            @endif

            <form method="POST" action="{{ route($role . '.ttpb.store') }}" novalidate>
                @csrf

                <div class="row g-4">
                    {{-- Tanggal & No TTPB --}}
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">{{ __('Tanggal') }}</label>
                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal') }}"
                            required
                        />
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_ttpb" class="form-label">{{ __('No. TTPB') }}</label>
                        <input
                            type="text"
                            id="no_ttpb"
                            name="no_ttpb"
                            class="form-control @error('no_ttpb') is-invalid @enderror"
                            placeholder="TTPB-001"
                            value="{{ old('no_ttpb') }}"
                            required
                        />
                        @error('no_ttpb')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lot Number & Nama Barang --}}
                    <div class="col-md-6">
                        <label for="lot_number" class="form-label">{{ __('Lot Number') }}</label>
                        <select
                            id="lot_number"
                            name="lot_number"
                            class="form-select @error('lot_number') is-invalid @enderror"
                            required
                        >
                            <option value="">-- {{ __('Pilih Lot Number') }} --</option>
                            @foreach ($stocks as $item)
                                <option
                                    value="{{ $item->lot_number }}"
                                    data-nama-barang="{{ $item->nama_barang }}"
                                    {{ old('lot_number') == $item->lot_number ? 'selected' : '' }}
                                >
                                    {{ $item->lot_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('lot_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nama_barang" class="form-label">{{ __('Nama Barang') }}</label>
                        <input
                            type="text"
                            id="nama_barang"
                            name="nama_barang"
                            class="form-control @error('nama_barang') is-invalid @enderror"
                            value="{{ old('nama_barang') }}"
                            required
                            readonly
                        />
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dari & Ke --}}
                    <div class="col-md-6">
                        <label for="dari" class="form-label">{{ __('Dari') }}</label>
                        <select
                            id="dari"
                            name="dari"
                            class="form-select @error('dari') is-invalid @enderror"
                            required
                        >
                            <option value="">-- {{ __('Pilih Asal') }} --</option>
                            @foreach ($roles as $item)
                                <option
                                    value="{{ $item }}"
                                    {{ old('dari', $role) === $item ? 'selected' : '' }}
                                >
                                    {{ ucfirst($item) }}
                                </option>
                            @endforeach
                        </select>
                        @error('dari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="ke" class="form-label">{{ __('Ke') }}</label>
                        <select
                            id="ke"
                            name="ke"
                            class="form-select @error('ke') is-invalid @enderror"
                            required
                        >
                            <option value="">-- {{ __('Pilih Tujuan') }} --</option>
                            @foreach ($roles as $item)
                                @if ($item !== $role)
                                    <option
                                        value="{{ $item }}"
                                        {{ old('ke') === $item ? 'selected' : '' }}
                                    >
                                        {{ ucfirst($item) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('ke')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Opsi Mix --}}
                    <div class="col-md-6" id="mix-option" style="display:none;">
                        <label for="di_mix" class="form-label">{{ __('Di Mix?') }}</label>
                        <select
                            id="di_mix"
                            name="di_mix"
                            class="form-select @error('di_mix') is-invalid @enderror"
                        >
                            <option value="">-- {{ __('Pilih') }} --</option>
                            <option value="ya" {{ old('di_mix') === 'ya' ? 'selected' : '' }}>{{ __('Ya') }}</option>
                            <option value="tidak" {{ old('di_mix') === 'tidak' ? 'selected' : '' }}>{{ __('Tidak') }}</option>
                        </select>
                        @error('di_mix')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Detail Mix --}}
                    <div id="mix-details" class="row g-4" style="display:none;">
                        <div class="col-md-6">
                            <label for="lot_number_mix" class="form-label">{{ __('Lot Number Mix') }}</label>
                            <select
                                id="lot_number_mix"
                                name="lot_number_mix"
                                class="form-select @error('lot_number_mix') is-invalid @enderror"
                            >
                                <option value="">-- {{ __('Pilih Lot Number') }} --</option>
                                @foreach ($stocks as $item)
                                    <option
                                        value="{{ $item->lot_number }}"
                                        {{ old('lot_number_mix') == $item->lot_number ? 'selected' : '' }}
                                    >
                                        {{ $item->lot_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lot_number_mix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="qty_awal_mix" class="form-label">{{ __('QTY Awal Mix') }}</label>
                            <input
                                type="number"
                                id="qty_awal_mix"
                                name="qty_awal_mix"
                                class="form-control @error('qty_awal_mix') is-invalid @enderror"
                                value="{{ old('qty_awal_mix') }}"
                                step="0.01"
                            />
                            @error('qty_awal_mix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="qty_aktual_mix" class="form-label">{{ __('QTY Aktual Mix') }}</label>
                            <input
                                type="number"
                                id="qty_aktual_mix"
                                name="qty_aktual_mix"
                                class="form-control @error('qty_aktual_mix') is-invalid @enderror"
                                value="{{ old('qty_aktual_mix') }}"
                                step="0.01"
                            />
                            @error('qty_aktual_mix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="loss_gd_mix" class="form-label">{{ __('Loss GD Mix') }}</label>
                            <input
                                type="number"
                                id="loss_gd_mix"
                                name="loss_gd_mix"
                                class="form-control @error('loss_gd_mix') is-invalid @enderror"
                                value="{{ old('loss_gd_mix') }}"
                                step="0.01"
                            />
                            @error('loss_gd_mix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="loss_aktual_mix" class="form-label">{{ __('Loss Aktual Mix') }}</label>
                            <input
                                type="number"
                                id="loss_aktual_mix"
                                name="loss_aktual_mix"
                                class="form-control @error('loss_aktual_mix') is-invalid @enderror"
                                value="{{ old('loss_aktual_mix') }}"
                                step="0.01"
                            />
                            @error('loss_aktual_mix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="lot_number_baru" class="form-label">{{ __('Lot Baru') }}</label>
                            <input
                                type="text"
                                id="lot_number_baru"
                                name="lot_number_baru"
                                class="form-control @error('lot_number_baru') is-invalid @enderror"
                                value="{{ old('lot_number_baru') }}"
                                readonly
                            />
                            @error('lot_number_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- QTY & Loss --}}
                    <div class="col-md-6">
                        <label for="qty_awal" class="form-label">{{ __('QTY Awal') }}</label>
                        <input
                            type="number"
                            id="qty_awal"
                            name="qty_awal"
                            class="form-control @error('qty_awal') is-invalid @enderror"
                            value="{{ old('qty_awal') }}"
                            step="0.01"
                            required
                        />
                        @error('qty_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="qty_aktual" class="form-label">{{ __('QTY Aktual') }}</label>
                        <input
                            type="number"
                            id="qty_aktual"
                            name="qty_aktual"
                            class="form-control @error('qty_aktual') is-invalid @enderror"
                            value="{{ old('qty_aktual') }}"
                            step="0.01"
                            required
                        />
                        @error('qty_aktual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="qty_loss" class="form-label">{{ __('Qty Loss Gudang') }}</label>
                        <input
                            type="number"
                            id="qty_loss"
                            name="qty_loss"
                            class="form-control @error('qty_loss') is-invalid @enderror"
                            value="{{ old('qty_loss') }}"
                            step="0.01"
                            readonly
                        />
                        @error('qty_loss')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="persen_loss" class="form-label">{{ __('% Loss Gudang') }}</label>
                        <input
                            type="number"
                            id="persen_loss"
                            name="persen_loss"
                            class="form-control @error('persen_loss') is-invalid @enderror"
                            value="{{ old('persen_loss') }}"
                            step="0.01"
                            readonly
                        />
                        @error('persen_loss')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Coly & Spec --}}
                    <div class="col-md-6">
                        <label for="coly" class="form-label">{{ __('Coly') }}</label>
                        <input
                            type="text"
                            id="coly"
                            name="coly"
                            class="form-control @error('coly') is-invalid @enderror"
                            value="{{ old('coly') }}"
                        />
                        @error('coly')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="spec" class="form-label">{{ __('Spec') }}</label>
                        <input
                            type="text"
                            id="spec"
                            name="spec"
                            class="form-control @error('spec') is-invalid @enderror"
                            value="{{ old('spec') }}"
                        />
                        @error('spec')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">
                        <label for="keterangan" class="form-label">{{ __('Keterangan') }}</label>
                        <textarea
                            id="keterangan"
                            name="keterangan"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            rows="3"
                        >{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Simpan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @section('page-script')
        <script>
            // Cache elemen
            const el = {
                lotNumber: document.getElementById('lot_number'),
                lotNumberMix: document.getElementById('lot_number_mix'),
                namaBarang: document.getElementById('nama_barang'),
                qtyAwal: document.getElementById('qty_awal'),
                qtyAktual: document.getElementById('qty_aktual'),
                qtyLoss: document.getElementById('qty_loss'),
                persenLoss: document.getElementById('persen_loss'),
                ke: document.getElementById('ke'),
                diMix: document.getElementById('di_mix'),
                mixOption: document.getElementById('mix-option'),
                mixDetails: document.getElementById('mix-details'),
                lotBaru: document.getElementById('lot_number_baru'),
                qtyAwalMix: document.getElementById('qty_awal_mix'),
                qtyAktualMix: document.getElementById('qty_aktual_mix'),
                lossGdMix: document.getElementById('loss_gd_mix'),
                lossAktualMix: document.getElementById('loss_aktual_mix'),
            };

            // Set nama barang sesuai lot terpilih
            function handleLotChange() {
                const option = el.lotNumber.options[el.lotNumber.selectedIndex];
                el.namaBarang.value = option?.dataset?.namaBarang || '';
                updateMixName();
            }

            // Hitung loss & persen
            function updateLoss() {
                const qtyAwal = parseFloat(el.qtyAwal.value) || 0;
                const qtyAktual = parseFloat(el.qtyAktual.value) || 0;
                const loss = qtyAwal - qtyAktual;
                const percent = qtyAwal ? (loss / qtyAwal) * 100 : 0;

                el.qtyLoss.value = loss;
                el.persenLoss.value = percent.toFixed(2);
            }

            // Tampilkan opsi mix berdasarkan tujuan
            function toggleMixOption() {
                const keValue = el.ke.value;
                const show = (keValue === 'grinding' || keValue === 'mixing');
                el.mixOption.style.display = show ? 'block' : 'none';

                // Reset jika tidak show
                if (!show) {
                    el.diMix.value = '';
                    toggleMixDetails();
                }
            }

            // Detail mix & required dinamis
            function toggleMixDetails() {
                const show = el.diMix.value === 'ya';
                el.mixDetails.style.display = show ? 'block' : 'none';

                // Required dinamis untuk field mix
                [el.lotNumberMix, el.qtyAwalMix, el.qtyAktualMix, el.lossGdMix, el.lossAktualMix].forEach(field => {
                    if (!field) return;
                    if (show) {
                        field.setAttribute('required', 'required');
                    } else {
                        field.removeAttribute('required');
                    }
                });

                updateMixName();
            }

            // Bentuk nama lot baru jika mix aktif
            function updateMixName() {
                if (el.diMix.value === 'ya') {
                    const lot1 = el.lotNumber.value;
                    const lot2 = el.lotNumberMix?.value || '';
                    el.lotBaru.value = (lot1 && lot2) ? `MIX-${lot1}-${lot2}` : '';
                } else {
                    el.lotBaru.value = '';
                }
            }

            // Event listeners
            el.lotNumber?.addEventListener('change', handleLotChange);
            el.lotNumberMix?.addEventListener('change', updateMixName);
            el.qtyAwal?.addEventListener('input', updateLoss);
            el.qtyAktual?.addEventListener('input', updateLoss);
            el.ke?.addEventListener('change', toggleMixOption);
            el.diMix?.addEventListener('change', toggleMixDetails);

            // ===== INIT STATE AWAL (ikuti old()) =====
            // 1) Set nama barang dari lot jika kosong
            handleLotChange();

            // 2) Hitung ulang loss jika qty ada di old()
            updateLoss();

            // 3) Tampilkan opsi mix dan detail mix sesuai old('ke') & old('di_mix')
            toggleMixOption();
            toggleMixDetails();

            // Jika halaman datang dari validasi gagal dan di_mix=ya namun lot baru belum terisi, generate lagi
            updateMixName();
        </script>
    @endsection
</x-layouts.app>
