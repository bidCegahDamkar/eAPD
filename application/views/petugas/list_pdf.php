<!-- App Capsule -->
<div id="appCapsule">
    <? //d($data_sektor) ?>
    <div class="section full mt-2">
        <div class="card">
            <div class="card-body">
                <h3 class="title">Table Laporan</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Jenis Laporan</th>
                                <th scope="col">Periode</th>
                                <th scope="col">Tanggal Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($list_report as $report) {
                                echo '
                                <tr>
                                    <th scope="row">'.$i.'</th>
                                    <td><a href="'.base_url().'upload/pdf/'.$report['filename'].'" target="_blank">'.$report['nama_laporan'].'</a></td>
                                    <td><a href="'.base_url().'upload/pdf/'.$report['filename'].'" target="_blank">'.$report['periode'].'</a></td>
                                    <td><a href="'.base_url().'upload/pdf/'.$report['filename'].'" target="_blank">'.sqlDate2htmlDate($report['create_at']).'</a></td>
                                </tr>
                                ';
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>