@extends('layouts.admin')

@section('content')
<div class="container">
    <br>
    <h3 class="text-center">Logistic (Prediksi Permintaan Kontainer, ETA, dan Penjadwalan Otomatis)</h3>

    <div class="row">
        <div class="col-lg-12">
            <div class="card p-3">
                <div class="card-header">
                    <h5>Input Data</h5>
                </div>
                <div class="card-body">
                    <form id="formInputData">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="pelabuhan">Pelabuhan</label>
                                    <select name="pelabuhan" id="pelabuhan" class="form-control" required>
                                        <option value="">-- Pilih Pelabuhan --</option>
                                        <option value="Tanjung Perak">Tanjung Perak</option>
                                        <option value="Bitung">Bitung</option>
                                        <option value="Tanjung Priok">Tanjung Priok</option>
                                        <option value="Makassar">Makassar</option>
                                        <option value="Belawan">Belawan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date">Tanggal</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="ketersediaan">Ketersediaan Armada</label>
                                    <input type="number" name="ketersediaan" id="ketersediaan" class="form-control" placeholder="Masukkan Ketersediaan Armada"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="cuaca">Cuaca</label>
                                    <select name="cuaca" id="cuaca" class="form-control" required>
                                        <option value="">-- Pilih Cuaca --</option>
                                        <option value="Cerah">Cerah</option>
                                        <option value="Berawan">Berawan</option>
                                        <option value="Hujan Ringan">Hujan Ringan</option>
                                        <option value="Hujan Deras">Hujan Deras</option>
                                        <option value="Badai">Badai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="shift">Shift Kerja</label>
                                    <select name="shift" id="shift" class="form-control" required>
                                        <option value="">-- Pilih Shift --</option>
                                        <option value="Pagi">Pagi</option>
                                        <option value="Siang">Siang</option>
                                        <option value="Malam">Malam</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Prediksi Data</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="tableResult" style="display: none;">
            <div class="card">
                <div class="card-header">
                    <h5>Hasil Prediksi</h5>
                </div>
                <div class="card-body">
                    <div class="row" id="evaluation">

                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Permintaan</label>
                                <p id="permintaan">-</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">ETA</label>
                                <p id="eta">-</p>
                            </div>
                        </div>
                    </div>
                    <h5>Penjadwalan</h5>
                    <table class="myTable table table-bordered table-hover" style="width: 100%;">
                        <thead class="text-center">
                            <tr>
                                <th>Armada</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Kontainer</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        let table = $('.myTable').DataTable({
            paging: true,
            lengthChange: true,
            pageLength: 10,
            searching: true,
            ordering: true,
            responsive: true,
            autoWidth: true
        });

        $('#formInputData').on('submit', function (e) {
            e.preventDefault();

            const pelabuhan = $('#pelabuhan').val();
            const cuaca = $('#cuaca').val();
            const shift = $('#shift').val();
            const tanggal = $('#date').val();
            const ketersediaan = $('#ketersediaan').val();

            if (!pelabuhan || !cuaca || !shift || !tanggal || !ketersediaan) {
                alert("Lengkapi semua input!");
                return;
            }

            fetch('/dataset/logistic.csv') // Ganti path jika perlu
                .then(response => {
                    if (!response.ok) throw new Error("CSV tidak ditemukan!");
                    return response.blob();
                })
                .then(blob => {
                    let formData = new FormData();
                    formData.append("data_file", blob, "logistic.csv");
                    formData.append("pelabuhan", pelabuhan);
                    formData.append("cuaca", cuaca);
                    formData.append("shift", shift);
                    formData.append("tanggal", tanggal);
                    formData.append("ketersediaan", ketersediaan);

                    $.ajax({
                        url: 'https://1c6aea45057a.ngrok-free.app/jadwal', // Ubah jika URL berubah
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            $('#tableResult').show();

                            // Prediksi
                            $('#permintaan').text(res.prediksi.kontainer +
                                ' kontainer');
                            $('#eta').text(res.prediksi.eta_jam.toFixed(2) + ' jam');

                            // Jadwal
                            table.clear().draw();
                            res.jadwal.forEach(item => {
                                table.row.add([
                                    item.armada,
                                    item.mulai,
                                    item.selesai,
                                    item.kontainer
                                ]).draw(false);
                            });


                            $("#evaluation").empty();

                            if (res.evaluasi_model) {
                                const evalHTML = `
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">Evaluasi Prediksi Kontainer</label>
                                            <div id="eval_container">RMSE : ${res.evaluasi_model.kontainer.rmse}, R² : ${res.evaluasi_model.kontainer.r2}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">Evaluasi Prediksi ETA</label>
                                            <div id="eval_eta">RMSE : ${res.evaluasi_model.eta.rmse}, R² : ${res.evaluasi_model.eta.r2}</div>
                                        </div>
                                    </div>
                                `;
                                $('#evaluation').append(evalHTML);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert("Gagal mengambil prediksi: " + xhr.responseText);
                            console.error(xhr.responseText);
                        }
                    });
                })
                .catch(err => {
                    alert("Gagal ambil file CSV: " + err.message);
                    console.error(err);
                });
        });
    });

</script>

@endsection
