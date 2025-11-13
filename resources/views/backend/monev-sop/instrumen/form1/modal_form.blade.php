<input name='id' type='hidden' value='{{ @$detail->id }}'>

<div class="row col-md-12">
    <div class='form-group mb-5'>
        <label for='instrumen' class="required">Instrumen</label>
        <textarea type="text" class="form-control" rows="2" placeholder="Instrumen" id="instrumen"
                    name="instrumen">{{ @$detail->instrumen }}</textarea>
        <span id='instrumen-error' class='error' for='instrumen'></span>
    </div>
    <div class='form-group mb-5'>
       <label for="kategori" class="required form-label">Kategori Instrumen</label>
       <select id="kategori" name="kategori" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddInstrumen" required >
            <option value=''>Pilih kategori</option>
            @foreach($kategori as $kat => $kat_name)
                @php
                    $opsiSelect = $kat == @$detail->kategori ? 'selected' : '';
                @endphp
                <option value="{{ $kat }}" {{ $opsiSelect }}> {{ $kat_name }}</option>
            @endforeach
        </select>
        <span id='kategori-error' class='error' for='kategori'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='aspek' class="required">Aspek Instrumen</label>
        <select id="aspek" name="aspek" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddInstrumen" required >
            <option value="">Pilih aspek</option>
            @foreach($aspek as $aspk => $aspk_name)
                @php
                    $opsiSelect = $aspk == @$detail->aspek ? 'selected' : '';
                @endphp
                <option value="{{$aspk}}" {{ $opsiSelect }}>{{$aspk_name}}</option>
            @endforeach
        </select>
        <span id='aspek-error' class='error' for='aspek'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='catatan_des' class="">Deskripsi Catatan</label>
        <textarea type="text" class="form-control" rows="2" placeholder="Deskripsi Catatan" id="catatan_des"
                    name="catatan_des">{{ @$detail->catatan_des }}</textarea>
        <span id='catatan_des-error' class='error' for='catatan_des'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='tindak_lanjut_des' class="">Deskripsi Tindak Lanjut</label>
        <textarea type="text" class="form-control" rows="2" placeholder="Deskripsi Tindak Lanjut" id="tindak_lanjut_des"
                    name="tindak_lanjut_des">{{ @$detail->tindak_lanjut_des }}</textarea>
        <span id='tindak_lanjut_des-error' class='error' for='tindak_lanjut_des'></span>
    </div>
</div>


