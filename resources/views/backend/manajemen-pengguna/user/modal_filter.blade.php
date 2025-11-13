<div class="row col-md-12">
    <div class='form-group mb-4'>
        <label for='role_filter' class="form-label">Role</label>
        <select id="role_filter" name="role_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih role</option>
            @foreach ($role as $role_code => $role_name)
                <option value="{{ $role_code }}">{{ $role_name }}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group mb-4'>
        <label for='unit_filter' class="form-label">Unit</label>
        <select id="unit_filter" name="unit_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih Unit</option>
            @foreach ($unit as $u)
                <option value="{{ $u->id }}">{{ $u->name}}</option>
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