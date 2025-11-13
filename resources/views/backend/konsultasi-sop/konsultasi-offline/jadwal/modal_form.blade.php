<input name='id' type='hidden' value='{{ @$detail->id }}'>
<input name='user_id' type='hidden' value='{{ @$detail? @$detail->id : session('id') }}'>
<input name='role_code' type='hidden' value='{{ @$detail? @$detail->role_code : session('defaultRoleCode') }}'>
<input name='created_by' type='hidden' value='{{ @$detail? @$detail->created_by : session('nama') }}'>

<div class="row col-md-12">
    <div class='form-group mb-5'>
        <label for='title' class="required">Kegiatan</label>
        <textarea type="text" class="form-control" rows="2" placeholder="Kegiatan Konsultasi" id="title"
                    name="title">{{ @$detail->title }}</textarea>
        <span id='title-error' class='error' for='title'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='date' class="required">Tanggal</label>
        <input type="text" class="form-control" placeholder="Tanggal" id="date"
                    name="date" value="{{ @$detail->date }}" />
        <span id='date-error' class='error' for='date'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='time' class="required">Waktu</label>
        <input type="text" class="form-control" placeholder="Waktu" id="time"
                    name="time" value="{{ @$detail->time }}" />
        <span id='time-error' class='error' for='time'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='location' class="required">Lokasi Konsultasi</label>
        <textarea type="text" class="form-control" rows="2" placeholder="Lokasi Konsultasi" id="location"
                    name="location">{{ @$detail->location }}</textarea>
        <span id='location-error' class='error' for='location'></span>
    </div>
    <div class='form-group mb-5'>
       <label for="unit_code" class="required form-label">Unit/PD</label>
       <select id="unit_code" name="unit_code" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddJadwal" required >
            <option value=''>Pilih Tahun</option>
            @foreach($units as $unit)
                @php
                    $opsiSelect = $unit->code == @$detail->unit_code ? 'selected' : '';
                @endphp
                <option value="{{ $unit->code }}" {{ $opsiSelect }}> {{ $unit->name }}</option>
            @endforeach
        </select>
        <span id='unit_code-error' class='error' for='unit_code'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='status' class="required">Status</label>
        <select id="status" name="status" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddJadwal" required >
            <option value="">Pilih Status</option>
            @foreach($status as $st => $text)
                @php
                    $opsiSelect = $st == @$detail->status ? 'selected' : '';
                @endphp
                <option value="{{$st}}" {{ $opsiSelect }}>{{$text}}</option>
            @endforeach
        </select>
        <span id='status-error' class='error' for='status'></span>
    </div>
</div>


