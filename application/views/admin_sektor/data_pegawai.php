<main class="container">
    <? //d($listUser); ?>
    <div class="bg-primary text-white mt-2 mb-2 px-5 py-3 rounded">
        <h5 class="card-title">Data Pegawai</h5>
        <h6 class="card-subtitle mb-2"><? echo str_replace('Admin', '', $username); ?></h6>
    </div>

    <div class="table-responsive mt-5">
    

        <table id="myTable" class="display list-pegawai-sektor" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NRK/ NIP</th>
                <th>Penugasan</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>%input</th>
                <th>%terverifikasi</th>
                <th>APD ditolak</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
    </div>

</main>