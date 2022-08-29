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
                        <h3 class="mb-0">Ubah Butir Kegiatan {{$butirkeg->code}} {{$butirkeg->name}}</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <form autocomplete="off" method="post" action="/IIIC8/{{$iiic8->id}}" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-control-label" for="title">Judul</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{@old('title', $iiic8->title)}}" id="title" name="title">
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label" for="exampleDatepicker">Tanggal</label>
                                        <input name="date" class="form-control @error('date') is-invalid @enderror" placeholder="Select date" type="date" value="{{ @old('date', $iiic8->time) }}">
                                        @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="mt-3">Objek Multimedia</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="background">Deskripsi dan Latar Belakang</label>
                                            <textarea class="form-control" id="background" name="background" rows="5">{{@old('background', $iiic8->background)}}</textarea>
                                            @error('background')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="data">Data dan Informasi yang Digunakan</label>
                                            <textarea class="form-control" id="data" name="data" rows="5">{{@old('data', $iiic8->data)}}</textarea>
                                            @error('data')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="tools">Hardware dan Software yang Digunakan</label>
                                            <textarea class="form-control" id="tools" name="tools" rows="5">{{@old('tools', $iiic8->tools)}}</textarea>
                                            @error('tools')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-control-label" for="link">Link Unduh</label>
                                            <textarea class="form-control" id="link" name="link" rows="5">{{@old('link', $iiic8->link)}}</textarea>
                                            @error('link')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-5 mt-2">
                                    <label class="form-control-label">Pejabat Penanda Tangan</label>
                                    <select id="supervisor" name="supervisor" class="form-control" data-toggle="select">
                                        <!-- <option value="0" disabled selected>Pilih Pejabat Penanda Tangan</option> -->
                                        @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" {{ old('supervisor', $iiic8->supervisorDetail->id) == $supervisor->id ? 'selected' : '' }}>
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

@endsection