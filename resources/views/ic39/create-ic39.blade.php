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
                        <form autocomplete="off" method="post" action="/IC39" class="needs-validation" enctype="multipart/form-data" novalidate>
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

                                <div class="col-md-6 mb-3">
                                    <div class="row input-daterange datepicker align-items-center">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-control-label">Tanggal Mulai</label>
                                                <input class="form-control" placeholder="Start date" type="text" id="start-date">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-control-label">Tanggal Berakhir</label>
                                                <input class="form-control" placeholder="End date" type="text" id="end-date">
                                            </div>
                                        </div>
                                        <button onclick="generateField()" class="btn btn-primary" type="button">Generate</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-3">Informasi Backup/Restore</h4>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="dataset">Nama Dataset</label>
                                            <input onchange="refreshAutoTitle()" type="text" class="form-control @error('dataset') is-invalid @enderror" value="{{@old('dataset')}}" id="dataset" name="dataset">
                                            @error('dataset')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="storage">Media Penyimpanan</label>
                                            <input type="text" class="form-control @error('storage') is-invalid @enderror" value="{{@old('storage')}}" id="storage" name="storage">
                                            @error('storage')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-3">Nama File</h4>
                                    <div class="row align-items-center">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="prefix">Prefix</label>
                                            <input type="text" class="form-control @error('prefix') is-invalid @enderror" value="{{@old('prefix')}}" id="prefix" name="prefix">
                                            @error('prefix')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="suffix">Suffix</label>
                                            <input type="text" class="form-control @error('suffix') is-invalid @enderror" value="{{@old('suffix')}}" id="suffix" name="suffix">
                                            @error('suffix')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="title">Suffix Jam Acak</label>
                                            <div class="mb-3 d-flex align-items-center">
                                                <label class="mr-3 custom-toggle">
                                                    <input type="checkbox" name="random_time" id="random_time">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="Tidak" data-label-on="Ya"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="formula">Formula</label>
                                            <p>Gunakan field {prefix} {suffix} {date} {time} untuk membuat formula nama file backup</p>
                                            <input type="text" class="form-control @error('formula') is-invalid @enderror" value="{{@old('formula')}}" id="formula" name="formula">
                                            @error('formula')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="table-responsive py-4">
                                                <table class="table" id="datatable-id" width="100%">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="col-1">#</th>
                                                            <th class="col-2">Tanggal Backup</th>
                                                            <th class="col-3">Nama File</th>
                                                            <th class="col-2">Jam Backup</th>
                                                            <th class="col-2">Dokumentasi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (old('date'))
                                                        @for ($i = 0; $i < count(old('date')); $i++) <tr>
                                                            <td class="px-3 align-middle">{{$i + 1}}</td>
                                                            <td class="px-3"><input name="date[]" class="form-control" placeholder="Select date" type="date" value="{{old('date.'.$i)}}"></td>
                                                            <td class="px-3 align-middle"><input type="text" class="form-control" value="{{old('filename.'.$i)}}" name="filename[]"></td>
                                                            <td class="px-3 align-middle">
                                                                <p class="d-inline mr-2">{{old('time.'.$i)}}</p><input type="hidden" name="time[]" value="{{old('time.'.$i)}}">
                                                            </td>
                                                            <td class="pl-1 pr-5 nowrap"><div class="custom-file d-inline mr-2"><input name="documentation[]" type="file" class="custom-file-input" id="documentation{{$i}}" lang="en" accept="image/*" onchange="previewDocumentation('{{$i}}')"><label class="custom-file-label" for="customFileLang" id="documentationLabel{{$i}}">Select file</label></div><button id="btnName{{$i}}" onclick="removebackup('btnName{{$i}}')" class="btn btn-icon btn-sm btn-outline-danger d-inline" type="button"><span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span></button></td>
                                                            </tr>
                                                            @endfor
                                                            @endif
                                                            <tr>
                                                                <td colspan="3">
                                                                    <button onclick="addbackup('', '')" type="button" class="btn btn-secondary btn-sm">
                                                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                                                        <span class="btn-inner--text">Tambah Backup</span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
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
<script src="/assets/vendor/momentjs/moment-with-locales.js"></script>

<script>
    // var backupcount = 0;

    var backupcount = @if(old('date')) {{count(old('date'))}} @else 0 @endif;

    $('#start-date').datepicker({
        format: 'd M yyyy',
    });
    $('#end-date').datepicker({
        format: 'd M yyyy',
    });

    function previewDocumentation(id) {
        var documentationLabel = document.getElementById('documentationLabel' + id);
        const documentation = document.getElementById('documentation' + id);
        documentationLabel.innerText = documentation.files[0].name;
    }

    function refreshAutoTitle() {
        var title = document.getElementById('title')
        var auto = document.getElementById('automatic_title')
        if (auto.checked) {

            var datasetString = ''
            var dataset = document.getElementById('dataset')
            datasetString = dataset.value

            var autoTitleString = 'Melakukan Backup Data ' + datasetString
            var title = document.getElementById('title')
            if (datasetString != '')
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

    function getFileName(prefix, suffix, date, time) {
        var value = document.getElementById('formula').value
        if (value != '') {
            value = value.replace('{prefix}', prefix)
            value = value.replace('{suffix}', suffix)
            value = value.replace('{date}', String(date.getDate()).padStart(2, '0') + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + date.getFullYear())
            value = value.replace('{time}', time)
            return value
        } else {
            return prefix + String(date.getDate()).padStart(2, '0') + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + date.getFullYear() + time + suffix;
        }
    }

    function getStringDate(date) {
        return date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
    }

    function makeButtonId(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }

    function addbackup(date, time) {
        var backuptable = document.getElementById('datatable-id');

        backupcount++;
        var row = backuptable.insertRow(backupcount);

        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);

        cell1.className = 'px-3 align-middle';
        cell2.className = 'px-3';
        cell3.className = 'px-3 align-middle';
        cell4.className = 'px-3 align-middle';
        cell5.className = 'pl-1 pr-5 nowrap';
        var buttonid = makeButtonId(10);

        cell1.innerHTML = backupcount;
        var dateVal = date != '' ? 'value = "' + getStringDate(date) + '"' : ''
        cell2.innerHTML = '<input name="date[]" class="form-control" placeholder="Select date" type="date" ' + dateVal + '>'

        time = ''
        if (document.getElementById('random_time').checked) {
            hour = Math.floor(Math.random() * 5);
            minute = Math.floor(Math.random() * 60);
            time = String(hour).padStart(2, '0') + '-' + String(minute).padStart(2, '0')
        }

        var prefix = document.getElementById('prefix').value
        var suffix = document.getElementById('suffix').value
        var filename = ''
        if ((prefix != '' || suffix != '') && date != '') {
            filename = getFileName(prefix, suffix, date, time)
            // cell3.innerHTML = filename + "<input type=\"hidden\" name=\"filename[]\" value=\"" + filename + "\">"
        }
        cell3.innerHTML = "<input type=\"text\" class=\"form-control\" value=\"" + filename + "\" name=\"filename[]\">"

        cell4.innerHTML = "<p class=\"d-inline mr-2\">" + time + "</p>" + "<input type=\"hidden\" name=\"time[]\" value=\"" + time + "\">"
        cell5.innerHTML = "<div class=\"custom-file d-inline mr-2\"><input name=\"documentation[]\" type=\"file\" class=\"custom-file-input\" id=\"documentation" + buttonid + "\" lang=\"en\" accept=\"image/*\" onchange=\"previewDocumentation('" + buttonid + "')\"><label class=\"custom-file-label\" for=\"customFileLang\" id=\"documentationLabel" + buttonid + "\">Select file</label></div>" + "<button id=\"btnName" + buttonid + "\" onclick=\"removebackup('btnName" + buttonid + "')\" class=\"btn btn-icon btn-sm btn-outline-danger d-inline\" type=\"button\"><span class=\"btn-inner--icon\"><i class=\"fas fa-trash-alt\"></i></span></button>"
    }

    function generateField() {
        if (document.getElementById('start-date').value != null && document.getElementById('end-date').value != null) {
            var startdate = Date.parse(document.getElementById('start-date').value)
            var enddate = Date.parse(document.getElementById('end-date').value)
            startdate = new Date(startdate)
            enddate = new Date(enddate)

            for (var d = startdate; d <= enddate; d.setDate(d.getDate() + 7)) {
                addbackup(d, '')
            }
        }
    }

    function removebackup(btnName) {
        backupcount--;
        var id;
        var table = document.getElementById('datatable-id');
        var rowCount = table.rows.length;

        for (var i = 1; i < rowCount - 1; i++) {
            var row = table.rows[i];
            if (row.cells[2]) {
                var rowObj = row.cells[4].childNodes[1];
                var rowId = row.cells[1].childNodes[0];
                if (rowObj) {
                    if (rowObj.id == btnName) {
                        table.deleteRow(i);
                        id = rowId.value;
                        rowCount--;
                    }
                }
            }
        }
        reindex();
    }

    function reindex() {
        var table = document.getElementById('datatable-id');
        var startmain = 1;
        for (var i = 1; i < table.rows.length - 1; i++) {
            var row = table.rows[i];
            row.cells[0].innerHTML = startmain++;
        }
    }

    // function countNumberBackup() {
    //     if (document.getElementById('start-date').value != null && document.getElementById('end-date').value != null) {
    //         var startdate = Date.parse(document.getElementById('start-date').value)
    //         var enddate = Date.parse(document.getElementById('end-date').value)
    //         startdate = new Date(startdate)
    //         enddate = new Date(enddate)

    //         var dates = [];
    //         for (var d = startdate; d <= enddate; d.setDate(d.getDate() + 7)) {
    //             dates.push(new Date(d));
    //         }

    //         if (dates.length > 0) {
    //             document.getElementById('datatable-id').style.display = 'block'
    //         } else {
    //             document.getElementById('datatable-id').style.display = 'none'
    //         }

    //         // var table = document.getElementById('datatable-id')

    //         var body = document.getElementById('datatable-id').getElementsByTagName('tbody')[0]
    //         body.innerHTML = ''

    //         for (var i = 0; i < dates.length; i++) {
    //             var row = body.insertRow();

    //             var cell1 = row.insertCell(0);
    //             var cell2 = row.insertCell(1);
    //             var cell3 = row.insertCell(2);
    //             var cell4 = row.insertCell(3);

    //             cell1.className = 'px-1';
    //             cell2.className = 'px-1';
    //             cell3.className = 'px-1';
    //             cell4.className = 'px-1';

    //             cell1.innerHTML = i + 1;

    //             cell2.innerHTML = getStringDate(dates[i])

    //             var prefix = document.getElementById('prefix').value
    //             var suffix = document.getElementById('suffix').value
    //             if (prefix != '' || suffix != '') {
    //                 cell3.innerHTML = getFileName(prefix, suffix, dates[i])
    //             }
    //             cell4.innerHTML = "<input type=\"time\" class=\"form-control\" value=\"{{@old('hour')}}\" id=\"hour\" name=\"hour\" onchange=\"countNumberBackup()\">"

    //         }

    //         // dates.forEach(myFunction);
    //     }
    // }
</script>

<script>
    refreshAutoTitle()
</script>




@endsection