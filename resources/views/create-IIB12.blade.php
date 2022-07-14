@extends('main')

@section('stylesheet')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/assets/style.css">
<link rel="stylesheet" href="/assets/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/assets/vendor/quill/dist/quill.core.css">
<link rel="stylesheet" href="/assets/vendor/sweetalert/sweetalert2.min.css">
<link rel="stylesheet" href="/assets/vendor/datatables2/datatables.min.css" />
<link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/fontawesome.min.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
                        <form autocomplete="off" method="post" onsubmit="return onSubmit()" action="/hdds" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">

                                <div class="col-md-12 mb-3">
                                    <label class="form-control-label" for="title">Tipe Butir Kegiatan</label>
                                    <div class="custom-control custom-radio mb-2">
                                        <input name="type" class="custom-control-input" id="type_radio1" value="detect" type="radio" {{ old('type') == 'L' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="type_radio1">Deteksi</label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input name="type" class="custom-control-input" id="type_radio2" value="fix" type="radio" {{ old('type') == 'P' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="type_radio2">Perbaikan</label>
                                    </div>
                                    @error('type')
                                    <div class="text-valid">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-control-label" for="title">Judul</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{@old('title')}}" id="title" name="title">
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label" for="exampleDatepicker">Tanggal</label>
                                        <input name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" placeholder="Select date" type="date" value="{{ @old('birthdate') }}">
                                        @error('birthdate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                </div>




                                <div class="col-12">
                                    <h4 class="mt-3">Informasi Infrastruktur</h4>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="infra_name">Nama Infrastruktur</label>
                                            <input type="text" class="form-control @error('infra_name') is-invalid @enderror" value="{{@old('infra_name')}}" id="infra_name" name="infra_name">
                                            @error('infra_name')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label">Jenis Infrastruktur</label>
                                            <select name="infratype" class="form-control" data-toggle="select">
                                                <option value="0" disabled selected>Pilih Jenis Infrastruktur</option>
                                                @foreach ($infratypes as $infratype)
                                                <option value="{{ $infratype->id }}" {{ old('infratype') == $infratype->id ? 'selected' : '' }}>
                                                    {{ $infratype->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('infratype')
                                            <div class="text-valid">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-control-label" for="infra_func">Fungsi Infrastruktur</label>
                                            <input type="text" class="form-control @error('infra_func') is-invalid @enderror" value="{{@old('infra_func')}}" id="infra_func" name="infra_func">
                                            @error('infra_func')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-3">Identifikasi Masalah</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="background">Latar Belakang Masalah</label>
                                            <textarea class="form-control" id="background" name="background" rows="5"></textarea>
                                            @error('background')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="problem_ident">Identifikasi Masalah</label>
                                            <textarea class="form-control" id="problem_ident" name="problem_ident" rows="5"></textarea>
                                            @error('problem_ident')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="problem_analysis">Analisis Permasalahan</label>
                                            <textarea class="form-control" id="problem_analysis" name="problem_analysis" rows="5"></textarea>
                                            @error('problem_analysis')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-3">Identifikasi Masalah</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="result_ident">Hasil Identifikasi Masalah</label>
                                            <textarea class="form-control" id="result_ident" name="result_ident" rows="5"></textarea>
                                            @error('result_ident')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="solution">Solusi/Alternatif Solusi</label>
                                            <textarea class="form-control" id="solution" name="solution" rows="5"></textarea>
                                            @error('solution')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="action">Langkah Perbaikan</label>
                                            <textarea class="form-control" id="action" name="action" rows="5"></textarea>
                                            @error('action')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFileLang" lang="en">
                                        <label class="custom-file-label" for="customFileLang">Select file</label>
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
    function previewimage(event) {
        var fileInput = document.getElementById('file');
        var filename = fileInput.files[0].name;
        document.getElementById('filelabel').innerHTML = filename;
    }
</script>
@endsection