<input name='id' type='hidden' value='{{ @$detail->id }}'>

<div class="row col-md-12">
    <div class='form-group mb-5'>
        <label for='nama' class="required">Nama/Judul SOP</label>
        <textarea type="text" class="form-control" rows="2" placeholder="Nama SOP" id="nama"
                    name="nama" required>{{ @$detail->nama }}</textarea>
        <span id='nama-error' class='error' for='nama'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='nomor' class="required">Nomor SOP</label>
        <input type="text" class="form-control" placeholder="Nomor SOP" id="nomor"
                    name="nomor" value="{{ @$detail->nomor }}" required/>
        <span id='nomor-error' class='error' for='nomor'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='unit_code' class="required">Unit SOP</label>
        <select id="unit_code" name="unit_code" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddSOP" required >
            <option value="">Pilih Unit</option>
            @foreach($units as $unit)
                @php
                    $opsiSelect = $unit->code == $unit_active ? 'selected' : '';
                @endphp
                <option value="{{$unit->code}}" {{ $opsiSelect }}>{{$unit->name}}</option>
            @endforeach
        </select>
        <span id='unit_code-error' class='error' for='unit_code'></span>
    </div>
    <div class='form-group mb-1'>
        @php
            $isRequired = @$detail->filepath ? '' : 'required';
        @endphp
        <label for='file_sop' class="{{$isRequired}}">Upload File SOP</label>
        <input type="file" class="form-control has-upload-file" placeholder="Upload File SOP" id="file_sop"
                    name="file_sop" {{$isRequired}} accept="application/pdf"/>
        <div class="form-text text-muted text-end">
          Format yang diperbolehkan: <strong>PDF</strong> | Maksimal: <strong>2 MB</strong>
        </div>
        <span id='file_sop-error' class='error' for='file_sop'></span>
    </div>
</div>

<!-- Preview File Sebelumnya -->
@if(isset($detail->filepath) && $detail->filepath)
    <hr>
    <div class="alert alert-secondary text-black">
    <i class="bi bi-file-earmark-pdf-fill"></i> 
    File sebelumnya: 
    <a href="{{ route('file.view', ['filepath' => encrypt($detail->filepath)]) }}" 
        target="_blank">
        {{ $detail->filename }}
    </a>
    </div>
@endif


