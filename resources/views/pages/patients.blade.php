@extends('layouts.dashboard')

@section('content')
    @include('components.patients.formAddPatient')
    @include('components.patients.formEditPatient')

    <div>
        <h1 class="h3 mb-0 text-gray-800">Pasien</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elits.</p>
    </div>

    <div class="my-3">
        <label class="form-label fw-bolder" for="filterHospital">Filter berdasarkan Rumah Sakit:</label>
        <select class="form-control" id="filterHospital">
            <option selected value="">-- Pilih Rumah Sakit --</option>
            @foreach ($hospitals as $hospital)
                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between py-3">
            <h5 class="m-0 font-weight-bold text-primary">Data Pasien</h5>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAddPatient">
                <i class="fa fa-plus"></i> Tambah Pasien
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>No Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
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
                    url: '/patients/filter',
                    type: 'GET',
                    data: function (d) {
                        d.hospital_id = $('#filterHospital').val() || '';
                    },
                    dataSrc: function (json) {
                        return json.patients || [];
                    },
                    error: function () {
                        return {
                            data: []
                        };
                    }
                },
                columns: [
                    { data: 'name',   name: 'name', defaultContent: '-' },
                    { data: 'phone',  name: 'phone', defaultContent: '-' },
                    { data: 'address',name: 'address', defaultContent: '-' },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-warning btn-sm edit-btn"
                                        data-toggle="modal"
                                        data-target="#modalEditPatient"
                                        data-id="${row.id}">
                                    <i class="fa fa-edit"></i> Edit
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
                    emptyTable: "Data pasien tidak ditemukan",
                    processing: "Memproses...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    paginate: { first: "Awal", last: "Akhir", next: "Lanjut", previous: "Sebelum" }
                }
            });

            $('#filterHospital').on('change', function () {
                table.ajax.reload(null, false);
            });
            $(document).on('click', '.edit-btn', function () {
                const patientId = $(this).data('id');

                $.ajax({
                    url: '/patients/' + patientId,
                    method: 'GET',
                    success: function (response) {
                        const patient = response.data;
                        $('#editPatientForm').attr('action', '/patients/' + patient.id);
                        $('#edit_hospital_id').val(patient.hospital_id).trigger('change');
                        $('#edit_name').val(patient.name);
                        $('#edit_phone').val(patient.phone);
                        $('#edit_address').val(patient.address);

                        $('#modalEditPatient').modal('show');
                    },
                    error: function (err) {
                        console.error(err);
                        Swal.fire('Gagal', 'Tidak bisa mengambil data pasien.', 'error');
                    }
                });
            });

            $(document).on('click', '.btn-delete', function () {
                const patientId = $(this).data('id');

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
                            url: '/patients/' + patientId,
                            type: 'DELETE',
                            success: function () {
                                Swal.fire('Terhapus!', 'Data pasien berhasil dihapus.', 'success');
                                table.ajax.reload(null, false);
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Data pasien gagal dihapus.', 'error');
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
