<div class="row col-md-12">
    <div class='form-group mb-4'>
        <label for='kategori_filter' class="form-label">Kategori Instrumen</label>
        <select id="kategori_filter" name="periode_year_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih kategori</option>
            @foreach ($kategori as $kat => $kat_name)
                <option value="{{ $kat }}">{{ $kat_name}}</option>
            @endforeach
        </select>
    </div>
    <div class='form-group mb-4'>
        <label for='aspek_filter' class="form-label">Aspek Instrumen</label>
        <select id="aspek_filter" name="aspek_filter" class='form-control form-select' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalFilter" >
            <option value=''>Pilih aspek</option>
            @foreach ($aspek as $aspk => $aspk_name)
                <option value="{{ $aspk }}">{{ $aspk_name }}</option>
            @endforeach
        </select>
    </div>
</div>