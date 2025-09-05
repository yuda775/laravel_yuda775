@extends('layouts.dashboard')

@section('content')
    @include('components.hospitals.formAddHospital')
    @include('components.hospitals.formEditHospital')

    <div>
        <h1 class="h3 mb-0 text-gray-800">Rumah Sakit</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elits.</p>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between py-3">
            <h5 class="m-0 font-weight-bold text-primary">Data Rumah Sakit</h5>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddHospital">
                <i class="fa fa-plus"></i> Tambah Rumah Sakit
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        {{-- Biarkan kosong. DataTables yang akan mengisi via AJAX. --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href="{{ asset('admin-template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
         $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        $(document).ready(function () {
            const table = $('#dataTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/hospitals/data',
                    type: 'GET',
                    dataSrc: function (json) {
                        return json.hospitals || [];
                    }
                },
                columns: [
                    { data: 'name',   name: 'name', defaultContent: '-' },
                    { data: 'email',  name: 'email', defaultContent: '-' },
                    { data: 'phone',  name: 'phone', defaultContent: '-' },
                    { data: 'address',name: 'address', defaultContent: '-' },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (id, type, row) {
                            return `
                                <button type="button"
                                        class="btn btn-warning btn-sm edit-btn"
                                        data-toggle="modal"
                                        data-target="#modalEditHospital"
                                        data-id="${row.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm btn-delete"
                                        data-id="${row.id}">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            `;
                        }
                    }
                ],
                order: [[0, 'asc']],
                language: {
                    emptyTable: "Data rumah sakit tidak ditemukan",
                    processing: "Memproses...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    paginate: {
                        first: "Awal", last: "Akhir", next: "Lanjut", previous: "Sebelum"
                    }
                }
            });

            $(document).on('click', '.edit-btn', function () {
                const hospitalId = $(this).data('id');

                $.ajax({
                    url: '/hospitals/' + hospitalId,
                    method: 'GET',
                    success: function (response) {
                        const hospital = response.data;
                        $('#editHospitalForm').attr('action', '/hospitals/' + hospital.id);
                        $('#edit_name').val(hospital.name);
                        $('#edit_email').val(hospital.email);
                        $('#edit_phone').val(hospital.phone);
                        $('#edit_address').val(hospital.address);
                        $('#modalEditHospital').modal('show');
                    },
                    error: function (err) {
                        console.error(err);
                        Swal.fire('Gagal', 'Tidak bisa mengambil data rumah sakit.', 'error');
                    }
                });
            });

            $(document).on('click', '.btn-delete', function () {
                const hospitalId = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/hospitals/' + hospitalId,
                            type: 'DELETE',
                            success: function () {
                                Swal.fire('Terhapus!', 'Data rumah sakit berhasil dihapus.', 'success');
                                table.ajax.reload(null, false);
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Data rumah sakit gagal dihapus.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script src="{{ asset('admin-template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endpush
