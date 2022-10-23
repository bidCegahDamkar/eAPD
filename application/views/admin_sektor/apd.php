<main class="container">

    <div class="bg-<? echo $title[2]; ?> text-white mt-2 px-5 py-3 rounded">
        <h5 class="card-title"><? echo $title[0]; ?></h5>
        <p><? echo $title[1]; ?></p>
    </div>

    <div class="table-responsive mt-5">
    <table id="myTable" class="display home-myTable" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NRK/ NIP</th>
                <th>Penugasan</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            if (count($ApdUser)>0) {
                foreach ($ApdUser as $user) {
                    echo '
                    <tr>
                        <td>'.$i.'</td>
                        <td>'.$user['nama'].'</td>
                        <td>'.$user['NRK'].'/ '.$user['NIP'].'</td>
                        <td>'.$user['nama_pos'].'</td>
                        <td>'.$user['nama_jabatan'].'</td>
                        <td>'.$user['status'].'</td>
                        <td><a class="btn btn-primary " href="'.base_url().'admin_sektor/'.$controller.'/'.$user['id'].'" role="button"><ion-icon name="navigate"></ion-icon></a></td>
                    </tr>
                    ';
                    $i++;
                    }
            }
            
            ?>
        </tbody>
        </table>
    </div>

</main>