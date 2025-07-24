@extends('layouts.admin')

@section('content')
<div class="container">
    <br>
    <h3 class="text-center">Operasional (Prediksi potensi risiko & delay)</h3>
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-3">
                <div class="card-header">
                    <h5>Input Data</h5>
                </div>
                <div class="card-body">
                    <form id="formInputData">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Pelabuhan</label>
                                    <select name="pelabuhan" id="pelabuhan" class="form-control">
                                        <option value="">--Pilih Pelabuhan--</option>
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
                                    <label for="">Jenis Kendala</label>
                                    <select name="jenis_kendala" id="jenis_kendala" class="form-control">
                                        <option value="">--Pilih Jenis Kendala--</option>
                                        <option value="Cuaca Buruk">Cuaca Buruk</option>
                                        <option value="Force Majeure">Force Majeure</option>
                                        <option value="Overload">Overload</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Cuaca</label>
                                    <select name="cuaca" id="cuaca" class="form-control">
                                        <option value="">--Pilih Cuaca--</option>
                                        <option value="Kabut Tebal">Kabut Tebal</option>
                                        <option value="Angin Kencang">Angin Kencang</option>
                                        <option value="Cerah">Cerah</option>
                                        <option value="Hujan Deras">Hujan Deras</option>
                                        <option value="Badai">Badai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Tanggal</label>
                                    <input type="date" name="date" id="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Volume Kargo (ton)</label>
                                    <input type="text" name="volume_kargo" id="volume_kargo" class="form-control"
                                        placeholder="Masukkan Volume Kargo">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <center>
                                        <button type="submit" class="btn btn-primary">Prediksi Data</button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#formInputData').on('submit', function (e) {
        e.preventDefault();

        const pelabuhan = $('#pelabuhan').val();
        const jenis_kendala = $('#jenis_kendala').val();
        const cuaca = $('#cuaca').val();
        const date = $('#date').val();
        const volume_kargo = $('#volume_kargo').val();

        fetch('/dataset/operational.csv') // ganti sesuai lokasi file CSV kamu
            .then(response => {
                if (!response.ok) throw new Error("CSV tidak ditemukan!");
                return response.blob();
            })
            .then(blob => {
                let formData = new FormData();
                formData.append("data_file", blob, "operational.csv");

                // Append data dari form
                formData.append("pelabuhan", pelabuhan);
                formData.append("jenis_kendala", jenis_kendala);
                formData.append("cuaca", cuaca);
                formData.append("date", date);
                formData.append("volume_kargo", volume_kargo);

                $.ajax({
                    url: 'https://28ab00e60b60.ngrok-free.app/operational', 
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false, 
                    success: function (res) {
                        
                        alert("Resiko Delay:" + res.prediction + 
                            
                        "\nRekomendasi: " + res.rekomendasi);

                    },
                    error: function (err) {
                        alert("Gagal upload: " + err.responseText);
                        console.error(err);
                    }
                });
            })
            .catch(err => {
                alert("Gagal ambil file CSV: " + err.message);
            });
    });
</script>


@endsection
