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
                            <li class="breadcrumb-item active" aria-current="page">Generate Lembar Persetujuan</li>
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
                        <h3 class="mb-0">Generate Lembar Persetujuan Berdasarkan Periode</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <form autocomplete="off" method="get" action="/IIB12/generate-approval-by-periode" class="needs-validation" enctype="multipart/form-data" novalidate>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-control-label">Pilih Periode</label>
                                    <select id="periode" name="periode" class="form-control" data-toggle="select">
                                        <option value="0" disabled selected>Pilih Periode</option>
                                        @foreach ($periodes as $periode)
                                        <option value="{{ $periode['json_value'] }}" {{ old('periode') == $periode['json_value'] ? 'selected' : '' }}>
                                            {{ $periode['value'][2] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('periode')
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

@endsection