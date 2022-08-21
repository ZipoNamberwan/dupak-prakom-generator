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
                            <li class="breadcrumb-item active" aria-current="page">Ubah</li>
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
                        <form autocomplete="off" method="post" action="/IIB12/{{$iib12->id}}" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @method('PUT')
                            @csrf
                            <div class="row">

                                <div class="col-md-12 mb-3">
                                    <label class="form-control-label" for="title">Tipe Butir Kegiatan</label>
                                    <div class="custom-control custom-radio mb-2">
                                        <input onchange="refreshAutoTitle()" name="type" class="custom-control-input" id="type_radio1" value="detect" type="radio" {{ old('type', $iib12->type) == 'detect' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="type_radio1">Deteksi</label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input onchange="refreshAutoTitle()" name="type" class="custom-control-input" id="type_radio2" value="fix" type="radio" {{ old('type', $iib12->type) == 'fix' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="type_radio2">Perbaikan</label>
                                    </div>
                                    @error('type')
                                    <div class="error-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-control-label" for="title">Judul</label>
                                    <div class="mb-3 d-flex align-items-center">
                                        <label class="mr-3 custom-toggle">
                                            <input type="checkbox" checked name="automatic_title" id="automatic_title" onchange=changeAutomaticTitle()>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="Tidak" data-label-on="Ya"></span>
                                        </label>
                                        <span>Judul Otomatis</span>
                                    </div>
                                    <input readonly type="text" class="form-control @error('title') is-invalid @enderror" value="{{@old('title', $iib12->title)}}" id="title" name="title">
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label" for="exampleDatepicker">Tanggal</label>
                                        <input name="date" class="form-control @error('date') is-invalid @enderror" placeholder="Select date" type="date" value="{{ @old('date', $iib12->time) }}">
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
                                        <option value="{{ $room->id }}" {{ old('room', $iib12->roomDetail->id) == $room->id ? 'selected' : '' }}>
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
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="problem_summary">Ringkasan Singkat Permasalahan</label>
                                            <input type="text" class="form-control @error('problem_summary') is-invalid @enderror" value="{{@old('problem_summary', $iib12->problem_summary)}}" id="problem_summary" name="problem_summary">
                                            @error('problem_summary')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-3">Informasi Infrastruktur</h4>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label">Jenis Infrastruktur</label>
                                            <select id="infratype" name="infratype" class="form-control" data-toggle="select" onchange="refreshAutoTitle()">
                                                <option value="0" disabled selected>Pilih Jenis Infrastruktur</option>
                                                @foreach ($infratypes as $infratype)
                                                <option value="{{ $infratype->id }}" {{ old('infratype', $iib12->infraTypeDetail->id) == $infratype->id ? 'selected' : '' }}>
                                                    {{ $infratype->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('infratype')
                                            <div class="error-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="infraname">Nama Infrastruktur</label>
                                            <input onchange="refreshAutoTitle()" type="text" class="form-control @error('infraname') is-invalid @enderror" value="{{@old('infraname', $iib12->infra_name)}}" id="infraname" name="infraname">
                                            @error('infraname')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="infrafunc">Fungsi Infrastruktur</label>
                                            <input type="text" class="form-control @error('infrafunc') is-invalid @enderror" value="{{@old('infrafunc', $iib12->infra_func)}}" id="infrafunc" name="infrafunc">
                                            @error('infrafunc')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" id="detection_form" style="display: none;">
                                    <h4 class="mt-3">Identifikasi Masalah</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="background">Latar Belakang Masalah</label>
                                            <textarea class="form-control" id="background" name="background" rows="5">{{@old('background', $iib12->background)}}</textarea>
                                            @error('background')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="problem_ident">Identifikasi Masalah</label>
                                            <textarea class="form-control" id="problem_ident" name="problem_ident" rows="5">{{@old('problem_ident', $iib12->problem_ident)}}</textarea>
                                            @error('problem_ident')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="problem_analysis">Analisis Permasalahan</label>
                                            <textarea class="form-control" id="problem_analysis" name="problem_analysis" rows="5">{{@old('problem_analysis', $iib12->problem_analysis)}}</textarea>
                                            @error('problem_analysis')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" id="fix_form" style="display: none;">
                                    <h4 class="mt-3">Identifikasi Masalah</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="result_ident">Hasil Identifikasi Masalah</label>
                                            <textarea class="form-control" id="result_ident" name="result_ident" rows="5">{{@old('result_ident', $iib12->result_ident)}}</textarea>
                                            @error('result_ident')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="solution">Solusi/Alternatif Solusi</label>
                                            <textarea class="form-control" id="solution" name="solution" rows="5">{{@old('solution', $iib12->solution)}}</textarea>
                                            @error('solution')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="action">Langkah Perbaikan</label>
                                            <textarea class="form-control" id="action" name="action" rows="5">{{@old('action', $iib12->action)}}</textarea>
                                            @error('action')
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
                                            <label class="form-control-label" for="requester">Pemohon/Pemegang Infrastruktur</label>
                                            <input type="text" class="form-control @error('requester') is-invalid @enderror" value="{{@old('requester', $iib12->requester)}}" id="requester" name="requester">
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
                                    <img class="img-preview-documentation img-fluid mb-3 col-sm-5 image-preview" style="display:block" src="@if($iib12->documentation != null) {{asset('storage/' . $iib12->documentation)}} @endif">
                                    <div class="custom-file">
                                        <input name="documentation" type="file" class="custom-file-input" id="documentation" lang="en" accept="image/*" onchange="previewDocumentation()">
                                        <label class="custom-file-label" for="customFileLang" id="documentationLabel">Select file</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-control-label" for="approval_letter">Surat Persetujuan</label>
                                    <img class="img-preview-approval-letter img-fluid mb-3 col-sm-5 image-preview" style="display:block" src="@if($iib12->approval_letter != null) {{asset('storage/' . $iib12->approval_letter)}} @endif">
                                    <div class="custom-file">
                                        <input name="approval_letter" type="file" class="custom-file-input" id="approval_letter" lang="en" accept="image/*" onchange="previewApprovalLetter()">
                                        <label class="custom-file-label" for="customFileLang" id="approvalLetterLabel">Select file</label>
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
            var typeString = ''
            var radios = document.getElementsByName('type');
            if (radios[0].checked) {
                typeString = 'Melakukan Deteksi Masalah '
            } else if (radios[1].checked) {
                typeString = 'Melakukan Perbaikan Masalah '
            }
            var infraTypeString = ''
            var infraType = document.getElementById("infratype");
            if (infraType.selectedIndex != 0)
                infraTypeString = infraType.options[infraType.selectedIndex].text + ' ';

            var infraNameString = ''
            var infraName = document.getElementById('infraname')
            infraNameString = infraName.value + ' '

            var roomString = ''
            var room = document.getElementById('room')
            if (room.selectedIndex != 0)
                roomString = 'di ruang ' + room.options[room.selectedIndex].text;

            if (typeString != '')
                var autoTitleString = typeString + infraTypeString + infraNameString + roomString
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

    function toggleFormType(type) {
        var detectionForm = document.getElementById('detection_form');
        var fixForm = document.getElementById('fix_form');
        if (type == 'detect') {
            detectionForm.style.display = 'block'
            fixForm.style.display = 'none'
        } else if (type == 'fix') {
            detectionForm.style.display = 'none'
            fixForm.style.display = 'block'
        }
    }
</script>

<script>
    var radios = document.getElementsByName('type');
    radios[0].onclick = function() {
        toggleFormType('detect')
    }
    radios[1].onclick = function() {
        toggleFormType('fix')
    }
    if (radios[0].checked) {
        toggleFormType('detect')
    } else if (radios[1].checked) {
        toggleFormType('fix')
    }

    // refreshAutoTitle()
</script>

@endsection