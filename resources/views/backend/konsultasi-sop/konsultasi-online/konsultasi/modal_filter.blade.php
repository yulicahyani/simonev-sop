<div class="row col-md-12">
    <div class='form-group mb-4'>
        <label for='status_filter' class="form-label">Status</label>
        <select id="status_filter" name="status_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih status</option>
            @foreach ($status as $sts => $sts_text)
                <option value="{{ $sts }}">{{ $sts_text }}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group mb-4'>
        <label for='created_by_filter' class="form-label">Created By</label>
        <select id="created_by_filter" name="created_by_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih Pengguna</option>
            @foreach ($user as $u)
                <option value="{{ $u->created_by }}">{{ $u->created_by}}</option>
            @endforeach
        </select>
    </div>
</div>