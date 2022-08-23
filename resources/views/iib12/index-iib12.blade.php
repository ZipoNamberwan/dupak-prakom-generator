@extends('main')
@section('stylesheet')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- <link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css"> -->
<link rel="stylesheet" href="/assets/vendor/datatables2/datatables.min.css" />
<link rel="stylesheet" href="/assets/vendor/@fortawesome/fontawesome-free/css/fontawesome.min.css" />

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
                            <li class="breadcrumb-item active" aria-current="page">Daftar Kegiatan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->

<div class="container-fluid mt--6">
    @if (session('success-edit') || session('success-create'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
        <span class="alert-text"><strong>Sukses! </strong>{{ session('success-create') }} {{ session('success-edit') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    @endif

    @if (session('success-delete'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
        <span class="alert-text"><strong>Sukses! </strong>{{ session('success-delete') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    @endif

    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="mb-0">Daftar Kegiatan</h3>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{url('/IIB12/generate-by-periode')}}" target="_blank" class="btn btn-outline-primary btn-round btn-icon mb-2" data-toggle="tooltip" data-original-title="Generate">
                                <span class="btn-inner--icon"><i class="fas fa-file-export"></i></span>
                                <span class="btn-inner--text">Generate Bukti Fisik Periode Terakhir</span>
                            </a>
                            <a href="{{url('/IIB12/create')}}" class="btn btn-primary btn-round btn-icon mb-2" data-toggle="tooltip" data-original-title="Tambah Kegiatan">
                                <span class="btn-inner--icon"><i class="fas fa-plus-circle"></i></span>
                                <span class="btn-inner--text">Tambah Kegiatan</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive py-4">
                    <table class="table" id="datatable-id" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Judul</th>
                                <th>Foto</th>
                                <th>Lembar Persetujuan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('optionaljs')
<script src="/assets/vendor/datatables2/datatables.min.js"></script>
<script src="/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/vendor/sweetalert2/dist/sweetalert2.js"></script>
<script src="/assets/vendor/momentjs/moment-with-locales.js"></script>

<script>
    var table = $('#datatable-id').DataTable({
        "responsive": true,
        // "fixedColumns": true,
        "fixedHeader": true,
        "order": [],
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": '/IIB12/data',
            "type": 'GET'
        },
        "columns": [{
            "responsivePriority": 8,
            "width": "2.5%",
            "orderable": false,
            "data": "index",
        }, {
            "responsivePriority": 3,
            "width": "5%",
            "orderable": true,
            "data": "time",
            "render": function(data, type, row) {
                if (type === 'display') {
                    const today = new Date(data);
                    const month = ["Januari", "Februari", "Naret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    return today.getDate() + ' ' + month[today.getMonth()] + ' ' + today.getFullYear();
                }
                return data;
            }
        }, {
            "responsivePriority": 1,
            "width": "12%",
            "data": "title",
        }, {
            "responsivePriority": 4,
            "width": "7%",
            "data": "documentation",
            "render": function(data, type, row) {
                if (type === 'display') {
                    if (data == true) {
                        return '<span class="btn-inner--icon btn-icon btn-sm btn-success"><i class="fas fa-check"></i></span>'
                    } else {
                        return '<span class="btn-inner--icon btn-icon btn-sm btn-danger"><i class="fas fa-minus"></i></span>'
                    }
                }
                return data;
            }
        }, {
            "responsivePriority": 5,
            "width": "5%",
            "data": "approval_letter",
            "render": function(data, type, row) {
                if (type === 'display') {
                    if (data == true) {
                        return '<span class="btn-inner--icon btn-icon btn-sm btn-success"><i class="fas fa-check"></i></span>'
                    } else {
                        return '<span class="btn-inner--icon btn-icon btn-sm btn-danger"><i class="fas fa-minus"></i></span>'
                    }
                }
                return data;
            }
        }, {
            "responsivePriority": 7,
            "width": "7%",
            "orderable": false,
            "data": "id",
            "render": function(data, type, row) {
                return "<a target=\"_blank\" href=\"/IIB12/" + data + "/generate\" class=\"btn btn-outline-success  btn-sm\" role=\"button\" aria-pressed=\"true\" data-toggle=\"tooltip\" data-original-title=\"Ubah Data\">" +
                    "<span class=\"btn-inner--icon\"><i class=\"fas fa-file-export\"></i></span></a>" +
                    "<a target=\"_blank\" href=\"/IIB12/" + data + "/generate-approval\" class=\"btn btn-outline-warning  btn-sm\" role=\"button\" aria-pressed=\"true\" data-toggle=\"tooltip\" data-original-title=\"Ubah Data\">" +
                    "<span class=\"btn-inner--icon\"><i class=\"fas fa-file-invoice\"></i></span></a>" +
                    "<a href=\"/IIB12/" + data + "/edit\" class=\"btn btn-outline-info  btn-sm\" role=\"button\" aria-pressed=\"true\" data-toggle=\"tooltip\" data-original-title=\"Ubah Data\">" +
                    "<span class=\"btn-inner--icon\"><i class=\"fas fa-edit\"></i></span></a>" +
                    "<form class=\"d-inline\" id=\"formdelete" + data + "\" name=\"formdelete" + data + "\" onsubmit=\"deleteactivity('" + data + "','" + row.title + "')\" method=\"POST\" action=\"/IIB12/" + data + "\">" +
                    '@method("delete")' +
                    '@csrf' +
                    "<button class=\"btn btn-icon btn-outline-danger btn-sm\" type=\"submit\" data-toggle=\"tooltip\" data-original-title=\"Hapus Data\">" +
                    "<span class=\"btn-inner--icon\"><i class=\"fas fa-trash-alt\"></i></span></button></form>";
            }
        }],
        "language": {
            'paginate': {
                'previous': '<i class="fas fa-angle-left"></i>',
                'next': '<i class="fas fa-angle-right"></i>'
            }
        }
    });
</script>

<script>
    function deleteactivity($id, $name) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin Hapus Kegiatan Ini?',
            text: $name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formdelete' + $id).submit();
            }
        })
    }
</script>
@endsection