<input name='id' type='hidden' value='{{ @$detail->id }}'>

<div class="row col-md-12">
    <div class='form-group mb-5'>
       <label for="is_parent" class="required form-label">Memiliki Sub Hak Akses</label>
       <select id="is_parent" name="is_parent" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddPermission" required >
            <option value=''>Pilih Is Parent</option>
            @foreach($is_parent as $key => $text)
                @php
                    $opsiSelect = $key == @$detail->is_parent ? 'selected' : '';
                @endphp
                <option value="{{ $key }}" {{ $opsiSelect }}> {{ $text }}</option>
            @endforeach
        </select>
        <span id='is_parent-error' class='error' for='is_parent'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='parent_id' class="required">Nama Induk Hak Akses</label>
        <select id="parent_id" name="parent_id" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddPermission" required >
            <option value="">Pilih Parent</option>
            @foreach($parent as $p)
                @php
                    $opsiSelect = $p->id == @$detail->parent_id ? 'selected' : '';
                @endphp
                <option value="{{$p->id}}" {{ $opsiSelect }}>{{$p->display_name}}</option>
            @endforeach
                <option value="{{'0'}}" {{ @$detail->parent_id == '0' ? 'selected' : '' }} >Tidak Memiliki Parent</option>
        </select>
        <span id='parent_id-error' class='error' for='parent_id'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='name' class="required">Nama Hak Akses</label>
        <input type="text" class="form-control" placeholder="Nama Hak Akses" id="name"
                    name="name" value="{{ @$detail->name }}" />
        <span id='name-error' class='error' for='name'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='display_name' class="required">Nama Ditampilkan</label>
        <input type="text" class="form-control" placeholder="Nama Ditampilkan Hak Akses" id="display_name"
                    name="display_name" value="{{ @$detail->display_name }}" />
        <span id='display_name-error' class='error' for='display_name'></span>
    </div>
</div>


