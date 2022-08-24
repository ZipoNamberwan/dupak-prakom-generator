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
                                                <input onchange="countNumberBackup()" class="form-control" placeholder="Start date" type="text" id="start-date">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-control-label">Tanggal Berakhir</label>
                                                <input onchange="countNumberBackup()" class="form-control" placeholder="End date" type="text" id="end-date">
                                            </div>
                                        </div>
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
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="prefix">Prefix</label>
                                            <input type="text" class="form-control @error('prefix') is-invalid @enderror" value="{{@old('prefix')}}" id="prefix" name="prefix" onchange="countNumberBackup()">
                                            @error('prefix')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="suffix">Suffix</label>
                                            <input type="text" class="form-control @error('suffix') is-invalid @enderror" value="{{@old('suffix')}}" id="suffix" name="suffix" onchange="countNumberBackup()">
                                            @error('suffix')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <div class="table-responsive py-4">
                                                <table class="table" id="datatable-id" width="100%">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Tanggal Backup</th>
                                                            <th>Nama File</th>
                                                            <th>Jam Backup</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
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
    $('#start-date').datepicker({
        format: 'd M yyyy',
    });
    $('#end-date').datepicker({
        format: 'd M yyyy',
    });

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

    function getFileName(prefix, suffix, date) {
        return prefix + date.getDate() + '-' + date.getMonth() + '-' + date.getFullYear() + suffix;
    }

    function getStringDate(date) {
        const month = ["Januari", "Februari", "Naret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        return date.getDate() + ' ' + month[date.getMonth()] + ' ' + date.getFullYear();
    }

    function countNumberBackup() {
        if (document.getElementById('start-date').value != null && document.getElementById('end-date').value != null) {
            var startdate = Date.parse(document.getElementById('start-date').value)
            var enddate = Date.parse(document.getElementById('end-date').value)
            startdate = new Date(startdate)
            enddate = new Date(enddate)

            var dates = [];
            for (var d = startdate; d <= enddate; d.setDate(d.getDate() + 7)) {
                dates.push(new Date(d));
            }

            if (dates.length > 0) {
                document.getElementById('datatable-id').style.display = 'block'
            } else {
                document.getElementById('datatable-id').style.display = 'none'
            }

            // var table = document.getElementById('datatable-id')

            var body = document.getElementById('datatable-id').getElementsByTagName('tbody')[0]
            body.innerHTML = ''

            for (var i = 0; i < dates.length; i++) {
                var row = body.insertRow();

                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);

                cell1.className = 'px-1';
                cell2.className = 'px-1';
                cell3.className = 'px-1';
                cell4.className = 'px-1';

                cell1.innerHTML = i + 1;

                cell2.innerHTML = getStringDate(dates[i])

                var prefix = document.getElementById('prefix').value
                var suffix = document.getElementById('suffix').value
                if (prefix != '' || suffix != '') {
                    cell3.innerHTML = getFileName(prefix, suffix, dates[i])
                }
                cell4.innerHTML = "<input type=\"time\" class=\"form-control\" value=\"{{@old('hour')}}\" id=\"hour\" name=\"hour\" onchange=\"countNumberBackup()\">"

            }

            // dates.forEach(myFunction);
        }
    }
</script>

<script>
    refreshAutoTitle()
</script>




@endsection