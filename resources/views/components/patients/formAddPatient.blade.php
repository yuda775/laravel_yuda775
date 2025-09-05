<div class="modal fade" id="modalAddPatient" tabindex="-1" aria-labelledby="modalAddPatientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('patients.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalAddHospitalLabel">Tambah Pasien</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="hospital_id" class="form-label fw-semibold">Rumah Sakit</label>
                        <select class="form-control" id="hospital_id" name="hospital_id" required>
                            <option value="">-- Pilih Rumah Sakit --</option>
                            @foreach ($hospitals as $hospital)
                                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukkan nama pasien" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat pasien" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">No. Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            placeholder="Masukkan no. telepon pasien" required>
                    </div>

                    <div class=" card-footerd-grid mt-4">
                        <button type="submit" class="btn btn-primary">Submit Data</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
