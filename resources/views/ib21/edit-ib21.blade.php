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
                        <form id="formupdate" autocomplete="off" method="post" action="/IB21/{{$ib21->id}}" class="needs-validation" enctype="multipart/form-data" novalidate>
                            @method('PUT')
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
                                    <input readonly type="text" class="form-control @error('title') is-invalid @enderror" value="{{@old('title', $ib21->title)}}" id="title" name="title">
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label" for="exampleDatepicker">Tanggal</label>
                                        <input id="date" name="date" class="form-control @error('date') is-invalid @enderror" placeholder="Select date" type="date" value="{{ @old('date', $ib21->time) }}" onchange="refreshAutoTitle()">
                                        @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-control-label" for="step">Daftar Layanan</label>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="table-responsive py-2 scrollable">
                                                <table class="table" width="100%" id="service-table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="col-1">No</th>
                                                            <th class="col-2">Tanggal</th>
                                                            <th class="col-3">Deskripsi Layanan</th>
                                                            <th class="col-2">Jenis Layanan</th>
                                                            <th class="col-2">Media Layanan</th>
                                                            <th class="col-3">Cara Pemenuhan Layanan</th>
                                                            <th class="col-2">Pemohon Layanan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="px-1">1</td>
                                                            <td class="px-1"><input id="serviceidfirst" type="hidden" name="serviceid[]" value="{{ old('serviceid.0', $ib21->services[0]->id) }}">
                                                                <input id="servicedatefirst" name="servicedate[]" class="form-control @error('servicedate.0') is-invalid @enderror" placeholder="Select servicedate" type="date" value="{{ @old('servicedate.0', $ib21->services[0]->time) }}">
                                                                @error('servicedate.0')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </td>
                                                            <td class="px-1">
                                                                <textarea id="descriptionfirst" class="form-control" name="description[]" rows="5">{{@old('description.0', $ib21->services[0]->description)}}</textarea>
                                                                @error('description.0')
                                                                <div class="error-feedback">
                                                                    {{$message}}
                                                                </div>
                                                                @enderror
                                                            </td>
                                                            <td class="px-1">
                                                                <select id="servicetypefirst" name="servicetype[]" class="form-control" data-toggle="select">
                                                                    <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                    @foreach ($servicetypes as $servicetype)
                                                                    <option value="{{ $servicetype->id }}" @if (old('servicetype.0', $ib21->services[0]->serviceTypeDetail->id)) {{$servicetype->id == old('servicetype.0', $ib21->services[0]->serviceTypeDetail->id) ? 'selected' : ''}} @endif>
                                                                        {{ $servicetype->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('servicetype.0')
                                                                <div class="error-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </td>
                                                            <td class="px-1">
                                                                <select id="servicemediafirst" name="servicemedia[]" class="form-control" data-toggle="select">
                                                                    <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                    @foreach ($servicemedias as $servicemedia)
                                                                    <option value="{{ $servicemedia->id }}" @if (old('servicemedia.0', $ib21->services[0]->serviceMediaDetail->id)) {{$servicemedia->id == old('servicemedia.0', $ib21->services[0]->serviceMediaDetail->id) ? 'selected' : ''}} @endif>
                                                                        {{ $servicemedia->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('servicemedia.0')
                                                                <div class="error-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                                @enderror
                                                            </td>
                                                            <td class="px-1">
                                                                <textarea id="servicefirst" class="form-control" name="service[]" rows="5">{{@old('service.0', $ib21->services[0]->service)}}</textarea>
                                                                @error('service.0')
                                                                <div class="error-feedback">
                                                                    {{$message}}
                                                                </div>
                                                                @enderror
                                                            </td>
                                                            <td class="px-1 pr-5">
                                                                <input class="form-control" type="text" id="requesterfirst" name="requester[]" value="{{ old('requester.0', $ib21->services[0]->requester) }}">
                                                                @error('requester.0')
                                                                <div class="error-feedback">
                                                                    kosong
                                                                </div>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        @if (old('servicetype'))
                                                        @for ($i = 1; $i < count(old('servicetype')); $i++) <tr>
                                                                <td class="px-1">{{$i+1}}</td>
                                                                <td class="px-1"><input type="hidden" name="serviceid[]" @if(old('serviceid.'.$i)) value="{{ old('serviceid.'.$i) }}" @endif>
                                                                    <input name="servicedate[]" class="form-control @error('servicedate.'.$i) is-invalid @enderror" placeholder="Select servicedate" type="date" value="{{ @old('servicedate.'.$i) }}">
                                                                    @error('servicedate.'.$i)
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-1">
                                                                    <textarea id="descriptionfirst" class="form-control" name="description[]" rows="5">{{@old('description.'.$i)}}</textarea>
                                                                    @error('description.'.$i)
                                                                    <div class="error-feedback">
                                                                        {{$message}}
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-1">
                                                                    <select id="servicetypefirst" name="servicetype[]" class="form-control" data-toggle="select">
                                                                        <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                        @foreach ($servicetypes as $servicetype)
                                                                        <option value="{{ $servicetype->id }}" @if (old('servicetype.'.$i)) {{$servicetype->id == old('servicetype.'.$i) ? 'selected' : ''}} @endif>
                                                                            {{ $servicetype->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('servicetype.'.$i)
                                                                    <div class="error-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-1">
                                                                    <select id="servicemediafirst" name="servicemedia[]" class="form-control" data-toggle="select">
                                                                        <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                        @foreach ($servicemedias as $servicemedia)
                                                                        <option value="{{ $servicemedia->id }}" @if (old('servicemedia.'.$i)) {{$servicemedia->id == old('servicemedia.'.$i) ? 'selected' : ''}} @endif>
                                                                            {{ $servicemedia->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('servicemedia.'.$i)
                                                                    <div class="error-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-1">
                                                                    <textarea id="servicefirst" class="form-control" name="service[]" rows="5">{{@old('service.'.$i)}}</textarea>
                                                                    @error('service.'.$i)
                                                                    <div class="error-feedback">
                                                                        {{$message}}
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="px-1 pr-5 nowrap"><input class="form-control d-inline mr-2" type="text" id="requesterfirst" name="requester[]" @if (old('requester.'.$i)) value="{{ old('requester.'.$i) }}" @endif><button id="btnName{{ $i }}" onclick="removeservice('btnName{{ $i }}')" class="btn btn-icon btn-sm btn-outline-danger d-inline" type="button"><span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span></button>
                                                                    @error('requester.'.$i)
                                                                    <div class="error-feedback">
                                                                        kosong
                                                                    </div>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                            @endfor
                                                            @else
                                                            @for ($i = 1; $i < count($ib21->services); $i++)
                                                            <tr>
                                                            <td class="px-1">{{$i+1}}</td>
                                                                <td class="px-1"><input type="hidden" name="serviceid[]" value="{{ $ib21->services[$i]->id }}">
                                                                    <input name="servicedate[]" class="form-control @error('servicedate.'.$i) is-invalid @enderror" placeholder="Select servicedate" type="date" value="{{ $ib21->services[$i]->time }}">
                                                                </td>
                                                                <td class="px-1">
                                                                    <textarea id="descriptionfirst" class="form-control" name="description[]" rows="5">{{$ib21->services[$i]->description}}</textarea>
                                                                </td>
                                                                <td class="px-1">
                                                                    <select id="servicetypefirst" name="servicetype[]" class="form-control" data-toggle="select">
                                                                        <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                        @foreach ($servicetypes as $servicetype)
                                                                        <option value="{{ $servicetype->id }}"{{$servicetype->id == $ib21->services[$i]->serviceTypeDetail->id ? 'selected' : ''}}>
                                                                            {{ $servicetype->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="px-1">
                                                                    <select id="servicemediafirst" name="servicemedia[]" class="form-control" data-toggle="select">
                                                                        <!-- <option value="0" disabled selected>Pilih Jenis Infrastruktur</option> -->
                                                                        @foreach ($servicemedias as $servicemedia)
                                                                        <option value="{{ $servicemedia->id }}" {{$servicemedia->id == $ib21->services[$i]->serviceMediaDetail->id ? 'selected' : ''}}>
                                                                            {{ $servicemedia->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="px-1">
                                                                    <textarea id="servicefirst" class="form-control" name="service[]" rows="5">{{$ib21->services[$i]->service}}</textarea>
                                                                </td>
                                                                <td class="px-1 pr-5 nowrap"><input class="form-control d-inline mr-2" type="text" id="requesterfirst" name="requester[]" value="{{ $ib21->services[$i]->requester}}"><button id="btnName{{ $i }}" onclick="removeservice('btnName{{ $i }}')" class="btn btn-icon btn-sm btn-outline-danger d-inline" type="button"><span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span></button>
                                                                </td>
                                                            </tr>
                                                            @endfor
                                                            @endif
                                                            <tr>
                                                                <td colspan="3">
                                                                    <button onclick="addservice()" type="button" class="btn btn-secondary btn-sm">
                                                                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                                                        <span class="btn-inner--text">Tambah Layanan </span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-5 mt-2">
                                    <label class="form-control-label">Pejabat Penanda Tangan</label>
                                    <select id="supervisor" name="supervisor" class="form-control" data-toggle="select">
                                        <!-- <option value="0" disabled selected>Pilih Pejabat Penanda Tangan</option> -->
                                        @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" {{ old('supervisor', $ib21->supervisorDetail->id) == $supervisor->id ? 'selected' : '' }}>
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
    var servicecount = @if(old('servicetype', $ib21->services)) {{count(old('servicetype', $ib21->services))}}@else 1 @endif;

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

    function addservice() {
        var servicetable = document.getElementById('service-table');

        servicecount++;
        var row = servicetable.insertRow(servicecount);

        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);

        cell1.className = 'px-1';
        cell2.className = 'px-1';
        cell3.className = 'px-1';
        cell4.className = 'px-1';
        cell5.className = 'px-1';
        cell6.className = 'px-1';
        cell7.className = 'pl-1 pr-5 nowrap';

        var buttonid = makeButtonId(10);

        cell1.innerHTML = servicecount;
        cell2.innerHTML = '<input type="hidden" name="serviceid[]"><input name="servicedate[]" class="form-control" placeholder="Select servicedate" type="date">'
        cell3.innerHTML = '<textarea id="descriptionfirst" class="form-control" name="description[]" rows="5"></textarea>'
        cell4.innerHTML = '<select name="servicetype[]" class="form-control" data-toggle="select">@foreach ($servicetypes as $servicetype)<option value="{{ $servicetype->id }}">{{ $servicetype->name }}</option>@endforeach</select>'
        cell5.innerHTML = '<select name="servicemedia[]" class="form-control" data-toggle="select">@foreach ($servicemedias as $servicemedia)<option value="{{ $servicemedia->id }}">{{ $servicemedia->name }}</option>@endforeach</select>'
        cell6.innerHTML = '<textarea id="servicefirst" class="form-control" name="service[]" rows="5"></textarea>'
        cell7.innerHTML =
            "<input class=\"form-control d-inline mr-2\" type=\"text\" name=\"requester[]\"><button id=\"btnName" + buttonid + "\" onclick=\"removeservice('btnName" + buttonid + "')\" class=\"btn btn-icon btn-sm btn-outline-danger d-inline\" type=\"button\"><span class=\"btn-inner--icon\"><i class=\"fas fa-trash-alt\"></i></span></button>";
    }

    function removeservice(btnName) {
        servicecount--;
        var id;
        var table = document.getElementById('service-table');
        var rowCount = table.rows.length;

        for (var i = 1; i < rowCount - 1; i++) {
            var row = table.rows[i];
            if (row.cells[2]) {
                var rowObj = row.cells[6].childNodes[1];
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
        var table = document.getElementById('service-table');
        var startmain = 1;
        for (var i = 1; i < table.rows.length - 1; i++) {
            var row = table.rows[i];
            row.cells[0].innerHTML = startmain++;
        }
    }

    function appendremovedactivity(id) {
        var form = document.getElementById('formupdate');
        var hidden = document.createElement("input");
        hidden.setAttribute("type", "hidden");
        hidden.setAttribute("name", "removedservice[]");
        hidden.setAttribute("value", id);

        form.appendChild(hidden);
    }
</script>

<script>
    function refreshAutoTitle() {
        var title = document.getElementById('title')
        var auto = document.getElementById('automatic_title')
        if (auto.checked) {
            var dateString = ''
            var date = document.getElementById("date");
            if (date.value != '') {
                const dateObj = new Date(date.value);
                const month = ["Januari", "Februari", "Naret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                dateString = month[dateObj.getMonth()] + ' ' + dateObj.getFullYear();
            }

            var autoTitleString = 'Mengelola Permintaan Dan Layanan Teknologi Informasi Bulan ' + dateString
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

@endsection