@extends('layouts.admin')

@section('content')
<div class="container">
    <br>
    <h3 class="text-center">Deteksi Anomali Data Payment</h3>

    <div class="row">
        <div class="col-lg-12">
            <div class="card p-3">
                <div class="card-title">
                    <h5>Upload Data</h5>
                </div>
                <div class="card-body">
                    <form id="uploadDataset" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Upload Dataset (.csv)</label>
                                    <input type="file" name="files" id="files" class="form-control">
                                </div>
                                <center>
                                    <button type="submit" class="btn btn-primary">Proses Data</button>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12" style="display: none" id="tableResult">
            <div class="card">
                <div class="card-header">
                    <h5>Hasil Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <table class="myTable table table-bordered table-hover" style="width: 100%;">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 10%">No</th>
                                <th>ID Transaksi</th>
                                <th>Tanggal & Waktu</th>
                                <th>User ID</th>
                                <th>Lokasi IP</th>
                                <th>Perangkat</th>
                                <th>Nominal</th>
                                <th>Kategori</th>
                                <th>Alasan</th>
                                <th>Tindakan</th>
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

        $('#uploadDataset').on('submit', function (e) {
            e.preventDefault();

            const file = $('#files')[0].files[0];
            if (!file) return alert("Pilih file terlebih dahulu!");

            let formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: 'https://api-ml.testing-project.com/deteksi', // ganti dengan URL Flask kamu
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    console.log(res); // Debug: pastikan array

                    if (!Array.isArray(res)) {
                        alert("Respons API tidak sesuai");
                        return;
                    }

                    // Bersihkan data lama
                    table.clear();

                    // Tambahkan baris baru
                    res.forEach((row, i) => {
                        table.row.add([
                            i + 1,
                            row['ID Transaksi'],
                            row['Tanggal'] + ' ' + row['Waktu Transaksi'],
                            row['User ID'],
                            row['Lokasi IP'],
                            row['Perangkat'],
                            'Rp' + parseInt(row['Nominal Transaksi'])
                            .toLocaleString(),
                            row['Anomali'] == 1 ? 'Anomali' : 'Normal',
                            row['Alasan Anomali'],
                            `<span class="badge bg-${row['Keputusan Sistem Pakar'] === 'Blokir' ? 'danger' : (row['Keputusan Sistem Pakar'] === 'Monitor' ? 'warning' : 'success')}">
                                ${row['Keputusan Sistem Pakar']}
                            </span>`
                        ]);
                    });

                    table.draw(); // Render tabel ulang
                    $('#tableResult').show(); // Tampilkan div hasil
                },
                error: function (xhr, status, error) {
                    alert("Gagal: " + xhr.responseText);
                }
            });
        });
    });

</script>

@endsection
