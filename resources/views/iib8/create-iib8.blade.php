@extends('main')

@section('stylesheet')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/assets/style.css">
<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/assets/vendor/quill/dist/quill.core.css">
<link rel="stylesheet" href="/assets/vendor/sweetalert/sweetalert2.min.css">
<link rel="stylesheet" href="/assets/vendor/datatables2/datatables.min.css" />
<link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/fontawesome.min.css" />
<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> -->
<link rel="stylesheet" href="/assets/vendor/dropzone2/dist/min/dropzone.min.css">
<link rel="stylesheet" href="/assets/vendor/quill2/quill.core.css">
<link rel="stylesheet" href="/assets/vendor/quill2/quill.snow.css">

@endsection

@section('container')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-bell"></i></a></li>
                            <li class="breadcrumb-item"><a href='{{url(str_replace('.', '', $butirkeg->code))}}'>{{$butirkeg->code}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card-wrapper">
                <!-- Custom form validation -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">Tambah Butir Kegiatan {{$butirkeg->code}} {{$butirkeg->name}}</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <form id="formupdate" autocomplete="off" method="post" action="/IIB8" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-control-label" for="title">Judul</label>
                                    <div class="mb-3 d-flex align-items-center">
                                        <label class="mr-3 custom-toggle">
                                            <input type="checkbox" checked name="automatic_title" id="automatic_title" onchange=changeAutomaticTitle()>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="Tidak" data-label-on="Ya"></span>
                                        </label>
                                        <span>Judul Otomatis</span>
                                    </div>
                                    <input readonly type="text" class="form-control @error('title') is-invalid @enderror" value="{{@old('title')}}" id="title" name="title">
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="maintenance_name">Jenis Pemeliharaan</label>
                                            <input type="text" class="form-control @error('maintenance_name') is-invalid @enderror" value="{{@old('maintenance_name')}}" id="maintenance_name" name="maintenance_name" onchange="refreshAutoTitle()">
                                            @error('maintenance_name')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label" for="exampleDatepicker">Tanggal</label>
                                        <input name="date" class="form-control @error('date') is-invalid @enderror" placeholder="Select date" type="date" value="{{ @old('date') }}">
                                        @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-control-label">Lokasi</label>
                                    <select id="room" name="room" class="form-control" data-toggle="select" onchange="refreshAutoTitle()">
                                        <option value="0" disabled selected>Pilih Ruangan</option>
                                        @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('room')
                                    <div class="error-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-12" id="detection_form">
                                    <h4 class="mt-3">Pemeliharaan Infrastruktur TI</h4>
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <div id="selection-container" class="container">
                                                <div class="table-responsive py-2 scrollable">
                                                    <table class="table" width="100%" id="infra-table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th width="10%">No</th>
                                                                <th>Jenis Infrastruktur</th>
                                                                <th>Nama Infrastruktur</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td class="px-1">
                                                                    <input type="hidden" name="infraid[]" @if (old('infraid.0')) value="{{ old('infraid.0') }}" @endif>
                                                                    <select id="infratypefirst" name="infratype[]" class="form-control" data-toggle="select">
                                                                        <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                        @foreach ($infratypes as $infratype)
                                                                        <option value="{{ $infratype->id }}" @if (old('infratype.0')) {{$infratype->id == old('infratype.0') ? 'selected' : ''}} @endif>
                                                                            {{ $infratype->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('infratype.0')
                                                                    <div class="error-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-1 pr-5">
                                                                    <input class="form-control" type="text" id="infranamefirst" name="infraname[]" @if (old('infraname.0')) value="{{ old('infraname.0') }}" @endif>
                                                                    @error('infraname.0')
                                                                    <div class="error-feedback">
                                                                        kosong
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                            @if (old('infraname'))
                                                            @for ($i = 1; $i < count(old('infraname')); $i++) <tr>
                                                                <td>{{$i + 1}}</td>
                                                                <td class="px-1">
                                                                    <input type="hidden" name="infraid[]" value="{{ old('infraid.'.$i) }}">
                                                                    <select id="{{$i}}" name="infratype[]" class="form-control" data-toggle="select">
                                                                        <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                        @foreach ($infratypes as $infratype)
                                                                        <option value="{{ $infratype->id }}" {{$infratype->id == old('infratype.'.$i) ? 'selected' : ''}}>
                                                                            {{ $infratype->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('infratype.'.$i)
                                                                    <div class="error-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-1 pr-5"><input class="form-control d-inline mr-2" type="text" name="infraname[]" value="{{ old('infraname.'.$i) }}"><button id="btnName{{ $i }}" onclick="removeinfra('btnName{{ $i }}')" class="btn btn-icon btn-sm btn-outline-danger d-inline" type="button"><span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span></button>
                                                                    @error('infraname.'.$i)
                                                                    <div class="error-feedback">
                                                                        kosong
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                </tr>
                                                                @endfor
                                                                @endif
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <button onclick="addinfra('', '')" type="button" class="btn btn-secondary btn-sm">
                                                                            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                                                            <span class="btn-inner--text">Tambah Infrastruktur</span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="step">Tahapan</label>
                                            <textarea class="form-control" id="step" name="step" rows="5">{{@old('step')}}</textarea>
                                            @error('step')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="result">Hasil</label>
                                            <textarea class="form-control" id="result" name="result" rows="5">{{@old('result')}}</textarea>
                                            @error('result')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="summary">Kesimpulan dan Rekomendasi</label>
                                            <textarea class="form-control" id="summary" name="summary" rows="5">{{@old('summary')}}</textarea>
                                            @error('summary')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="requester">Pemohon Pemeliharaan Infrastruktur</label><input type="text" class="form-control @error('requester') is-invalid @enderror" value="{{@old('requester')}}" id="requester" name="requester">
                                            @error('requester')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label" for="documentation">Dokumentasi</label>
                                    <img class="img-preview-documentation img-fluid mb-3 col-sm-5 image-preview" style="display:block">
                                    <div class="custom-file">
                                        <input name="documentation" type="file" class="custom-file-input" id="documentation" lang="en" accept="image/*" onchange="previewDocumentation()">
                                        <label class="custom-file-label" for="customFileLang" id="documentationLabel">Select file</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label" for="approval_letter">Surat Persetujuan</label>
                                    <img class="img-preview-approval-letter img-fluid mb-3 col-sm-5 image-preview" style="display:block">
                                    <div class="custom-file">
                                        <input name="approval_letter" type="file" class="custom-file-input" id="approval_letter" lang="en" accept="image/*" onchange="previewApprovalLetter()">
                                        <label class="custom-file-label" for="customFileLang" id="approvalLetterLabel">Select file</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-5 mt-2">
                                    <label class="form-control-label">Pejabat Penanda Tangan</label>
                                    <select id="supervisor" name="supervisor" class="form-control" data-toggle="select">
                                        <!-- <option value="0" disabled selected>Pilih Pejabat Penanda Tangan</option> -->
                                        @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" {{ old('supervisor', $preferredsp) == $supervisor->id ? 'selected' : '' }}>
                                            {{ $supervisor->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('supervisor')
                                    <div class="error-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <button class="btn btn-primary" id="sbmtbtn" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('optionaljs')
<script src="/assets/vendor/select2/dist/js/select2.min.js"></script>
<script src="/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
    function previewDocumentation(event) {
        var documentationLabel = document.getElementById('documentationLabel');
        const documentation = document.querySelector('#documentation');
        const imgPreview = document.querySelector('.img-preview-documentation');
        imgPreview.style.display = 'block';
        const oFReader = new FileReader();
        oFReader.readAsDataURL(documentation.files[0]);
        documentationLabel.innerText = documentation.files[0].name;
        oFReader.onload = function(OFREvent) {
            imgPreview.src = OFREvent.target.result;
        }
    }

    function previewApprovalLetter(event) {
        var approvalLetterLabel = document.getElementById('approvalLetterLabel');
        const approvalLetter = document.querySelector('#approval_letter');
        const imgPreview = document.querySelector('.img-preview-approval-letter');
        imgPreview.style.display = 'block';
        const oFReader = new FileReader();
        oFReader.readAsDataURL(approvalLetter.files[0]);
        approvalLetterLabel.innerText = approvalLetter.files[0].name;
        oFReader.onload = function(OFREvent) {
            imgPreview.src = OFREvent.target.result;
        }
    }

    function refreshAutoTitle() {
        var title = document.getElementById('title')
        var auto = document.getElementById('automatic_title')
        if (auto.checked) {
            var roomString = ''
            var room = document.getElementById('room')
            if (room.selectedIndex != 0)
                roomString = 'di ruang ' + room.options[room.selectedIndex].text;

            var maintenanceString = ''
            var maintenance = document.getElementById('maintenance_name')
            maintenanceString = maintenance.value + ' '

            var autoTitleString = 'Melakukan Pemeliharaan ' + maintenanceString + roomString
            var title = document.getElementById('title')
            title.value = autoTitleString ?? ''
        }
    }

    function changeAutomaticTitle() {
        var auto = document.getElementById('automatic_title')
        var title = document.getElementById('title')
        if (auto.checked) {
            title.readOnly = true
        } else {
            title.readOnly = false
        }
        refreshAutoTitle()
    }
</script>

<script>
    refreshAutoTitle()
</script>


<script>
    var infracount = @if(old('infraname')) {{count(old('infraname'))}} @else 1 @endif;

    function addinfra(infratypeid, infraname) {
        var infratable = document.getElementById('infra-table');

        infracount++;
        var row = infratable.insertRow(infracount);

        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);

        cell2.className = 'px-1';
        cell3.className = 'pl-1 pr-5';
        var buttonid = Date.now();

        cell1.innerHTML = infracount;
        cell2.innerHTML = "<input type='hidden' name='infraid[]'><select id=\"" + buttonid + "\" name=\"infratype[]\" class=\"form-control\" data-toggle=\"select\">@foreach ($infratypes as $infratype)<option value=\"{{ $infratype->id }}\" {{ old('infratype') == $infratype->id ? 'selected' : '' }}>{{ $infratype->name }}</option>@endforeach</select>";

        cell3.innerHTML =
            "<input class='form-control d-inline mr-2' type='text' name='infraname[]'><button id=\"btnName" + buttonid + "\" onclick=\"removeinfra('btnName" + buttonid + "')\" class=\"btn btn-icon btn-sm btn-outline-danger d-inline\" type=\"button\"><span class=\"btn-inner--icon\"><i class=\"fas fa-trash-alt\"></i></span></button>";
    }

    function removeinfra(btnName) {
        infracount--;
        var id;
        var table = document.getElementById('infra-table');
        var rowCount = table.rows.length;

        for (var i = 1; i < rowCount - 1; i++) {
            var row = table.rows[i];
            if (row.cells[2]) {
                var rowObj = row.cells[2].childNodes[1];
                var rowId = row.cells[1].childNodes[0];
                if (rowObj) {
                    if (rowObj.id == btnName) {
                        table.deleteRow(i);
                        id = rowId.value;
                        if (id) {
                            appendremovedactivity(id);
                        }
                        rowCount--;
                    }
                }
            }
        }
        reindex();
    }

    function reindex() {
        var table = document.getElementById('infra-table');
        var startmain = 1;
        for (var i = 1; i < table.rows.length - 1; i++) {
            var row = table.rows[i];
            row.cells[0].innerHTML = startmain++;
        }
    }

    function appendremovedactivity(id) {
        console.log(id)
        var form = document.getElementById('formupdate');
        var hidden = document.createElement("input");
        hidden.setAttribute("type", "hidden");
        hidden.setAttribute("name", "removedactivity[]");
        hidden.setAttribute("id", "removedactivity[]");
        hidden.setAttribute("value", id);

        form.appendChild(hidden);
    }
</script>

@endsection