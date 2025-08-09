@section('title', __('Isi TTPB'))
<x-layouts.app :title="__('Isi TTPB')">

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ __('Form TTPB') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route($role.'.ttpb.store') }}">
            @csrf
            <datalist id="lot_numbers">
                @foreach($stocks as $item)
                    <option value="{{ $item->lot_number }}" data-nama-barang="{{ $item->nama_barang }}"></option>
                @endforeach
            </datalist>
            <div id="current-item"></div>
            <div id="items-container" class="d-none"></div>
            <button type="button" class="btn btn-secondary mt-3" id="add-row">{{ __('Tambah Baris') }}</button>

            <div class="mt-4" id="preview-container"></div>

            <div class="mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
            </div>
        </form>

        <template id="item-template">
            <div class="item-row border rounded p-3 mb-3">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Tanggal') }}</label>
                        <input type="date" class="form-control" data-name="tanggal" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('No. TTPB') }}</label>
                        <input type="text" class="form-control" placeholder="TTPB-001" data-name="no_ttpb" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Lot Number') }}</label>
                        <input list="lot_numbers" class="form-control lot-number" data-name="lot_number" placeholder="-- Pilih Lot Number --" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Nama Barang') }}</label>
                        <input type="text" class="form-control nama-barang" data-name="nama_barang" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Dari') }}</label>
                        <select class="form-select" data-name="dari">
                            <option value="">-- Pilih Asal --</option>
                            @foreach($roles as $item)
                                <option value="{{ $item }}" {{ $item === $role ? 'selected' : '' }}>{{ ucfirst($item) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Ke') }}</label>
                        <select class="form-select" data-name="ke">
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($roles as $item)
                                @if($item !== $role)
                                    <option value="{{ $item }}">{{ ucfirst($item) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('QTY Awal') }}</label>
                        <input type="text" inputmode="decimal" class="form-control qty-awal" data-name="qty_awal" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('QTY Aktual') }}</label>
                        <input type="text" inputmode="decimal" class="form-control qty-aktual" data-name="qty_aktual" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Qty Loss') }}</label>
                        <input type="text" inputmode="decimal" class="form-control qty-loss" data-name="qty_loss" readonly />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('% Loss') }}</label>
                        <input type="number" step="0.01" class="form-control persen-loss" data-name="persen_loss" readonly />
                    </div>
                    @if ($role === 'pencucian')
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Kadar Air') }}</label>
                            <input type="number" step="0.01" class="form-control" data-name="kadar_air" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Deviasi') }}</label>
                            <input type="number" step="0.01" class="form-control" data-name="deviasi" />
                        </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Coly') }}</label>
                        <input type="text" class="form-control" data-name="coly" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Spec') }}</label>
                        <input type="text" class="form-control" data-name="spec" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Keterangan') }}</label>
                        <textarea class="form-control" rows="3" data-name="keterangan"></textarea>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const template = document.getElementById('item-template').content.firstElementChild;
    const currentContainer = document.getElementById('current-item');
    const itemsContainer = document.getElementById('items-container');
    const previewContainer = document.getElementById('preview-container');
    const form = document.querySelector('form');
    let index = 0;
    const stickyFields = ['tanggal', 'no_ttpb', 'dari', 'ke'];

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

    const currentRow = template.cloneNode(true);
    currentContainer.appendChild(currentRow);
    attachEvents(currentRow);
    refreshPreview();

    // Add a new row when clicked
    document.getElementById('add-row').addEventListener('click', function () {
        const clone = currentRow.cloneNode(true);
        copyValues(currentRow, clone);
        updateNames(clone, index);
        itemsContainer.appendChild(clone);
        index++;
        resetRow(currentRow);
        refreshPreview();
    });

    form.addEventListener('submit', function () {
        const hasValue = Array.from(currentRow.querySelectorAll('[data-name]'))
            .some(el => el.value);
        if (hasValue) {
            updateNames(currentRow, index);
        }
    });

    // Function to update name attributes of form elements
    function updateNames(row, i) {
        row.querySelectorAll('[data-name]').forEach(el => {
            el.name = `items[${i}][${el.dataset.name}]`;
        });
    }

    // Function to copy values from one row to another
    function copyValues(source, target) {
        source.querySelectorAll('[data-name]').forEach(el => {
            const dest = target.querySelector(`[data-name="${el.dataset.name}"]`);
            if (dest) {
                dest.value = el.value;
            }
        });
    }

    // Function to attach event listeners to elements in each row
    function attachEvents(row) {
        row.querySelector('.lot-number').addEventListener('input', function () {
            const option = document.querySelector(`#lot_numbers option[value="${this.value}"]`);
            row.querySelector('.nama-barang').value = option ? option.dataset.namaBarang || '' : '';
            refreshPreview();
        });
        row.querySelectorAll('.qty-awal, .qty-aktual').forEach(el => {
            el.addEventListener('input', function () {
                const awal = parseNumber(row.querySelector('.qty-awal').value);
                const aktual = parseNumber(row.querySelector('.qty-aktual').value);
                const loss = awal - aktual;
                const percent = awal ? (loss / awal) * 100 : 0;
                row.querySelector('.qty-loss').value = loss;
                row.querySelector('.persen-loss').value = percent.toFixed(2);
                refreshPreview();
            });
        });
        row.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('input', refreshPreview);
        });
    }

    // Function to reset a row's input fields
    function resetRow(row) {
        row.querySelectorAll('[data-name]').forEach(el => {
            if (!stickyFields.includes(el.dataset.name)) {
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;
                } else {
                    el.value = '';
                }
            }
            if (el.classList.contains('qty-loss') || el.classList.contains('persen-loss')) {
                el.value = '';
            }
        });
    }

    // Function to update the preview section
    function refreshPreview() {
        previewContainer.innerHTML = '';
        const dataRows = Array.from(itemsContainer.querySelectorAll('.item-row'))
            .map(row => collectData(row));

        const currentData = collectData(currentRow);
        if (Object.values(currentData).some(v => v)) {
            dataRows.push(currentData);
        }

        const groups = dataRows.reduce((acc, item) => {
            if (!acc[item.no]) acc[item.no] = [];
            acc[item.no].push(item);
            return acc;
        }, {});

        Object.keys(groups).forEach(number => {
            previewContainer.appendChild(buildCard(number, groups[number]));
        });
    }

    // Function to collect data from a row
    function collectData(row) {
        return {
            tanggal: row.querySelector('[data-name="tanggal"]').value,
            no: row.querySelector('[data-name="no_ttpb"]').value,
            lot: row.querySelector('.lot-number').value,
            nama: row.querySelector('.nama-barang').value,
            dari: row.querySelector('[data-name="dari"]').value,
            ke: row.querySelector('[data-name="ke"]').value,
            awal: row.querySelector('.qty-awal').value,
            aktual: row.querySelector('.qty-aktual').value,
            loss: row.querySelector('.qty-loss').value,
            persen: row.querySelector('.persen-loss').value,
            coly: row.querySelector('[data-name="coly"]').value,
            spec: row.querySelector('[data-name="spec"]').value,
            ket: row.querySelector('[data-name="keterangan"]').value,
            @if ($role === 'pencucian')
            kadar: row.querySelector('[data-name="kadar_air"]').value,
            deviasi: row.querySelector('[data-name="deviasi"]').value,
            @endif
        };
    }

    // Function to generate preview card
    function buildCard(number, items) {
        const first = items[0];
        let html = `<div class="card mb-3"><div class="card-body"><div class="mb-3">`;
        html += `<p>No.TTPB : ${number}</p>`;
        html += `<p>Tanggal : ${first.tanggal}</p>`;
        html += `<p>Dari : ${first.dari}</p>`;
        html += `<p>Ke : ${first.ke}</p>`;
        html += `</div><div class="table-responsive"><table class="table table-bordered"><thead class="table-light"><tr>`;
        html += `<th>No. Lot</th><th>Qty Awal</th><th>Qty Aktual</th><th>Qty Loss Gudang</th><th>% Loss Gudang</th><th>Coly</th><th>Spec</th><th>Keterangan</th>`;
        html += `</tr></thead><tbody>`;
        items.forEach(item => {
            html += `<tr><td>${item.lot}</td><td>${item.awal}</td><td>${item.aktual}</td><td>${item.loss}</td><td>${item.persen}</td><td>${item.coly}</td><td>${item.spec}</td><td>${item.ket}</td></tr>`;
        });
        html += `</tbody></table></div></div></div>`;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        return wrapper.firstChild;
    }
});
</script>
@endsection

</x-layouts.app>
