<div class="row col-md-12">
    <div class='form-group mb-4'>
        <label for='periode_year_filter' class="form-label">Tahun Periode</label>
        <select id="periode_year_filter" name="periode_year_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih tahun</option>
            @foreach ($years as $year)
                <option value="{{ $year }}">{{ $year}}</option>
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
</div>