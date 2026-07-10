@props([
    'divisionRequired' => false,
    'districtRequired' => false,
    'upazilaRequired'  => false,
    'unionRequired'    => false,
    'emitIds'          => false,   // also submit *_id hidden inputs (division_id, ...)
])

@php
    // Preserve values across validation errors.
    $oldDivision = old('division');
    $oldDistrict = old('district');
    $oldUpazila  = old('upazila');
    $oldUnion    = old('union');
@endphp

<div class="geo-picker"
     data-divisions-url="{{ route('geo.divisions') }}"
     data-children-url="{{ url('/geo/areas') }}"
     data-emit-ids="{{ $emitIds ? '1' : '0' }}"
     data-old-division="{{ $oldDivision }}"
     data-old-district="{{ $oldDistrict }}"
     data-old-upazila="{{ $oldUpazila }}"
     data-old-union="{{ $oldUnion }}">

    <div class="row g-3">
        {{-- Division --}}
        <div class="col-md-6">
            <label class="pub-form-label">Division @if($divisionRequired)<span class="text-danger">*</span>@endif</label>
            <div class="geo-level" data-level="division" data-child="district">
                <div class="geo-dd">
                    <input type="text" class="pub-form-control geo-input" placeholder="Select division…" autocomplete="off" @if($divisionRequired) data-required="1" @endif>
                    <div class="geo-menu"></div>
                </div>
                <input type="hidden" name="division" class="geo-name" value="{{ $oldDivision }}" @if($divisionRequired) required @endif>
                @if($emitIds)<input type="hidden" name="division_id" class="geo-id">@endif
            </div>
        </div>

        {{-- District --}}
        <div class="col-md-6">
            <label class="pub-form-label">District @if($districtRequired)<span class="text-danger">*</span>@endif</label>
            <div class="geo-level" data-level="district" data-child="upazila">
                <div class="geo-dd">
                    <input type="text" class="pub-form-control geo-input" placeholder="Select district…" autocomplete="off" disabled @if($districtRequired) data-required="1" @endif>
                    <div class="geo-menu"></div>
                </div>
                <input type="hidden" name="district" class="geo-name" value="{{ $oldDistrict }}" @if($districtRequired) required @endif>
                @if($emitIds)<input type="hidden" name="district_id" class="geo-id">@endif
            </div>
        </div>

        {{-- Upazila --}}
        <div class="col-md-6">
            <label class="pub-form-label">Upazila / City Corp. / Pourashava @if($upazilaRequired)<span class="text-danger">*</span>@endif</label>
            <div class="geo-level" data-level="upazila" data-child="union">
                <div class="geo-dd">
                    <input type="text" class="pub-form-control geo-input" placeholder="Select upazila / city corp…" autocomplete="off" disabled @if($upazilaRequired) data-required="1" @endif>
                    <div class="geo-menu"></div>
                </div>
                <input type="hidden" name="upazila" class="geo-name" value="{{ $oldUpazila }}" @if($upazilaRequired) required @endif>
                @if($emitIds)<input type="hidden" name="upazila_id" class="geo-id">@endif
            </div>
        </div>

        {{-- Union --}}
        <div class="col-md-6">
            <label class="pub-form-label">Union / Ward @if($unionRequired)<span class="text-danger">*</span>@endif</label>
            <div class="geo-level" data-level="union" data-child="">
                <div class="geo-dd">
                    <input type="text" class="pub-form-control geo-input" placeholder="Select union / ward…" autocomplete="off" disabled @if($unionRequired) data-required="1" @endif>
                    <div class="geo-menu"></div>
                </div>
                <input type="hidden" name="union" class="geo-name" value="{{ $oldUnion }}" @if($unionRequired) required @endif>
                @if($emitIds)<input type="hidden" name="union_id" class="geo-id">@endif
            </div>
        </div>
    </div>
</div>

@once
@push('styles')
<style>
    .geo-dd { position: relative; }
    .geo-menu {
        display: none; position: absolute; z-index: 50; top: 100%; left: 0; right: 0;
        max-height: 240px; overflow-y: auto; background: #fff;
        border: 1px solid #d1d5db; border-radius: 8px; margin-top: 4px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .geo-menu.open { display: block; }
    .geo-opt {
        padding: 8px 12px; cursor: pointer; font-size: 0.9rem; color: #111827;
        border-bottom: 1px solid #f3f4f6;
    }
    .geo-opt:last-child { border-bottom: 0; }
    .geo-opt:hover, .geo-opt.active { background: #eff6ff; color: #1d4ed8; }
    .geo-opt-empty { padding: 8px 12px; font-size: 0.85rem; color: #9ca3af; }
    .geo-input:disabled { background: #f9fafb; cursor: not-allowed; }
    .geo-input.loading { background-image: none; opacity: .7; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    function initPicker(root) {
        var childrenBase = root.dataset.childrenUrl;      // /geo/areas
        var divisionsUrl = root.dataset.divisionsUrl;     // /geo/divisions
        var order = ['division', 'district', 'upazila', 'union'];
        var levels = {};

        order.forEach(function (lvl) {
            var el = root.querySelector('.geo-level[data-level="' + lvl + '"]');
            if (!el) return;
            levels[lvl] = {
                el: el,
                input: el.querySelector('.geo-input'),
                nameInput: el.querySelector('.geo-name'),
                idInput: el.querySelector('.geo-id'),
                menu: el.querySelector('.geo-menu'),
                child: el.dataset.child || null,
                options: [],
                selectedId: null,
            };
        });

        function closeAllMenus() {
            order.forEach(function (l) { if (levels[l]) levels[l].menu.classList.remove('open'); });
        }

        function renderMenu(lvl, filter) {
            var L = levels[lvl];
            var q = (filter || '').toLowerCase();
            var items = L.options.filter(function (o) {
                return !q || o.name_en.toLowerCase().indexOf(q) !== -1;
            });
            L.menu.innerHTML = '';
            if (!L.options.length) {
                L.menu.innerHTML = (lvl === 'union')
                    ? '<div class="geo-opt-empty">No sub-area — optional</div>'
                    : '<div class="geo-opt-empty">No data available</div>';
                return;
            }
            if (!items.length) {
                L.menu.innerHTML = '<div class="geo-opt-empty">No match</div>';
                return;
            }
            items.forEach(function (o) {
                var d = document.createElement('div');
                d.className = 'geo-opt';
                d.textContent = o.name_en;
                d.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    selectOption(lvl, o);
                });
                L.menu.appendChild(d);
            });
        }

        function resetLevel(lvl) {
            var L = levels[lvl];
            if (!L) return;
            L.options = [];
            L.selectedId = null;
            L.input.value = '';
            L.nameInput.value = '';
            if (L.idInput) L.idInput.value = '';
            L.input.disabled = true;
            L.input.placeholder = 'Select ' + lvl + '…';
            L.menu.classList.remove('open');
            L.menu.innerHTML = '';
        }

        function resetDescendants(lvl) {
            var idx = order.indexOf(lvl);
            for (var i = idx + 1; i < order.length; i++) resetLevel(order[i]);
        }

        function loadOptions(lvl, url, onDone) {
            var L = levels[lvl];
            L.input.classList.add('loading');
            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    L.options = Array.isArray(data) ? data : [];
                    L.input.classList.remove('loading');
                    L.input.disabled = false;
                    if (!L.options.length) {
                        L.input.disabled = true;
                        // Pourashavas / metro-thana areas have no union or ward listed.
                        L.input.placeholder = (lvl === 'union')
                            ? 'No sub-area — optional'
                            : 'No ' + lvl + ' available';
                    }
                    renderMenu(lvl, '');
                    if (onDone) onDone();
                })
                .catch(function () {
                    L.input.classList.remove('loading');
                });
        }

        function selectOption(lvl, o) {
            var L = levels[lvl];
            L.selectedId = o.id;
            L.input.value = o.name_en;
            L.nameInput.value = o.name_en;
            if (L.idInput) L.idInput.value = o.id;
            L.menu.classList.remove('open');
            resetDescendants(lvl);
            if (L.child) {
                loadOptions(L.child, childrenBase + '/' + o.id + '/children');
            }
        }

        // Try to auto-select an option matching an old value (case-insensitive), returns matched option.
        function autoSelect(lvl, oldVal, onSelected) {
            if (!oldVal) return;
            var L = levels[lvl];
            var match = L.options.filter(function (o) {
                return o.name_en.toLowerCase() === String(oldVal).toLowerCase();
            })[0];
            if (!match) return;
            L.selectedId = match.id;
            L.input.value = match.name_en;
            L.nameInput.value = match.name_en;
            if (L.idInput) L.idInput.value = match.id;
            if (L.child && onSelected) {
                loadOptions(L.child, childrenBase + '/' + match.id + '/children', onSelected.bind(null, match));
            }
        }

        // Wire input events per level.
        order.forEach(function (lvl) {
            var L = levels[lvl];
            if (!L) return;
            L.input.addEventListener('focus', function () {
                if (L.input.disabled) return;
                closeAllMenus();
                renderMenu(lvl, '');
                L.menu.classList.add('open');
            });
            L.input.addEventListener('input', function () {
                if (L.input.disabled) return;
                // Typing invalidates prior selection until a new pick.
                L.selectedId = null;
                L.nameInput.value = '';
                if (L.idInput) L.idInput.value = '';
                renderMenu(lvl, L.input.value);
                L.menu.classList.add('open');
            });
            L.input.addEventListener('blur', function () {
                // Restore the committed selection text if user typed but didn't pick.
                setTimeout(function () {
                    L.menu.classList.remove('open');
                    L.input.value = L.nameInput.value || '';
                }, 150);
            });
        });

        document.addEventListener('click', function (e) {
            if (!root.contains(e.target)) closeAllMenus();
        });

        // Initial load: divisions, then cascade any old() values.
        loadOptions('division', divisionsUrl, function () {
            autoSelect('division', root.dataset.oldDivision, function () {
                autoSelect('district', root.dataset.oldDistrict, function () {
                    autoSelect('upazila', root.dataset.oldUpazila, function () {
                        autoSelect('union', root.dataset.oldUnion);
                    });
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.geo-picker').forEach(initPicker);
    });
})();
</script>
@endpush
@endonce
