<input name='id' type='hidden' value='{{ @$detail->id }}'>

<div class="row col-md-12">
    <div class='form-group mb-5'>
        <label for='name' class="required">Nama Periode</label>
        <input type="text" class="form-control" placeholder="Nama Periode" id="name"
                    name="name" value="{{ @$detail->name }}" />
        <span id='name-error' class='error' for='name'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='start_date' class="required">Tanggal Mulai</label>
        <input type="text" class="form-control" placeholder="Tanggal Mulai" id="start_date"
                    name="start_date" value="{{ @$detail->start_date }}" />
        <span id='start_date-error' class='error' for='start_date'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='end_date' class="required">Tanggal Berakhir</label>
        <input type="text" class="form-control" placeholder="Tanggal Berakhir" id="end_date"
                    name="end_date" value="{{ @$detail->end_date }}" />
        <span id='end_date-error' class='error' for='end_date'></span>
    </div>
    <div class='form-group mb-5'>
       <label for="periode_year" class="required form-label">Tahun Periode</label>
       <select id="periode_year" name="periode_year" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddPeriode" required >
            <option value=''>Pilih Tahun</option>
            @foreach($years as $year)
                @php
                    $opsiSelect = $year == @$detail->periode_year ? 'selected' : '';
                @endphp
                <option value="{{ $year }}" {{ $opsiSelect }}> {{ $year }}</option>
            @endforeach
        </select>
        <span id='periode_year-error' class='error' for='periode_year'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='description' class="required">Deksripsi</label>
        <textarea type="text" class="form-control" rows="2" placeholder="Deskripsi Periode" id="description"
                    name="description">{{ @$detail->description }}</textarea>
        <span id='description-error' class='error' for='description'></span>
    </div>
    <div class='form-group mb-5'>
        <label for='status' class="required">Status Periode</label>
        <select id="status" name="status" class='form-control form-select has-input-group' style="width: 100%;" data-control="select2" data-dropdown-parent="#modalAddPeriode" required >
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


