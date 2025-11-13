<div class="row col-md-12">
    <div class='form-group mb-4'>
        <label for='unit_filter' class="form-label">Unit OPD</label>
        <select id="unit_filter" name="unit_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih unit/PD</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->code }}">{{ $unit->name}}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group mb-4'>
        <label for='status_filter' class="form-label">Status</label>
        <select id="status_filter" name="status_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih status</option>
            @foreach ($status as $sts => $sts_text)
                <option value="{{ $sts }}">{{ $sts_text }}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group mb-5'>
        <label for='date_filter' class="form-label">Tanggal</label>
        <input type="text" class="form-control" placeholder="Tanggal Konsultasi" id="date_filter"
                    name="date_filter" value="" />
    </div>
</div>