<div class="modal fade" id="modalAddHospital" tabindex="-1" aria-labelledby="modalAddHospitalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('hospitals.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalAddHospitalLabel">Tambah Rumah Sakit Baru</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Rumah Sakit</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukkan nama rumah sakit" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Masukkan email rumah sakit" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat rumah sakit" rows="3"
                            required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">No. Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            placeholder="Masukkan no. telepon rumah sakit" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" aria-label="Close">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
