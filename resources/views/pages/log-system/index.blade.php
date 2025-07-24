@extends('layouts.admin')

@section('content')
<div class="container">
    <br>
    <h3 class="text-center">Log Sistem (Memprediksi failure berdasarkan log sistem)</h3>

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
                                    <label for="">CPU Usage (%)</label>
                                    <input type="text" class="form-control" name="cpu" id="cpu"
                                        placeholder="CPU Usage (%)">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Memori Usage (%)</label>
                                    <input type="text" class="form-control" name="memmory" id="memmory"
                                        placeholder="Memory Usage (%)">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Disk Usage (%)</label>
                                    <input type="text" class="form-control" name="disk" id="disk"
                                        placeholder="Disk Usage (%)">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Response Time (ms)</label>
                                    <input type="text" class="form-control" name="resp_time" id="resp_time"
                                        placeholder="Response Time (ms)">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Error Code</label>
                                    <select name="error_code" id="error_code" class="form-control">
                                        <option value="">--Pilih Error Code</option>
                                        <option value="200">200</option>
                                        <option value="403">403</option>
                                        <option value="404">404</option>
                                        <option value="500">500</option>
                                        <option value="502">502</option>
                                        <option value="503">503</option>
                                        <option value="504">504</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Server ID</label>
                                    <input type="text" name="server_id" id="server_id" class="form-control"
                                        placeholder="Server ID">
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

        const cpu = $('#cpu').val();
        const memmory = $('#memmory').val();
        const disk = $('#disk').val();
        const resp_time = $('#resp_time').val();
        const error_code = $('#error_code').val();
        const server_id = $('#server_id').val();

        fetch('/dataset/log_data.csv')
            .then(response => response.blob())
            .then(blob => {
                let formData = new FormData();
                formData.append("data_file", blob, "log_data.csv");
                formData.append("cpu", cpu);
                formData.append("memmory", memmory);
                formData.append("disk", disk);
                formData.append("resp_time", resp_time);
                formData.append("error_code", error_code);
                formData.append("server_id", server_id);

                $.ajax({
                    url: 'https://api-ml.testing-project.com/upload',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        alert("Hasil Prediksi :" + res.prediction);
                    },
                    error: function (err) {
                        alert("Gagal upload: " + err.statusText);
                    }
                });
            });
    });

</script>
@endsection
