<style>
    .highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}

#report {
    height: 1600px;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

.rtr {
    vertical-align: top;
    text-align: center;
}

.rcr {
    vertical-align: middle;
    text-align: center;
}

</style>
<!-- App Capsule -->
<div id="appCapsule">
    <? //d($result['jenis_apd']) ?>
    <div class="section full mt-2">
        <div class="card">
            <div class="card-body">
                <h3 class="title">Table Rekap Data APD</h3>
                <h4 class="subtitle mb-0">Periode : <?php echo $periode; ?></h4>
                <h4 class="subtitle mb-0"><?php echo $result['group']; ?></h4>
                <table class="table table-bordered table-sm mt-2">
                    <thead class="bg-secondary">
                        <?php
                        $loop1 = ['Jumlah APD Personil yang Tervalidasi', 'Jumlah Total APD', 'Persentase APD Tervalidasi'];
                        $loop2 = [$jmlAPDTervld.' APD', $jmltotalAPD.' APD', $Persentase.' %'];
                        for ($i=0; $i < count($loop1) ; $i++) { 
                            echo '
                            <tr>
                                <th class="text-white" colspan="2">'.$loop1[$i].'</th>
                                <th class="text-white">'.$loop2[$i].'</th>
                            </tr>
                            ';
                        }
                        ?>
                    </thead>
                    <tbody>
                        <?php
                        $num_jApd = count($result['jenis_apd']);
                        $loop = [ ['a. Belum Terima', 'belum'], ['b. Hilang', 'hilang'], ['Subtotal Kurang', 'stotk'], ['c. Baik', 'baik'], ['d. Rusak Ringan', 'rr'],
                                ['e. Rusak Sedang', 'rs'], ['f. Rusak Berat', 'rb'], ['Subtotal Existing', 'stote'], ['Subtotal', 'stot'] ];
                        for ($i=0; $i < $num_jApd ; $i++) { 
                            echo '
                            <tr>
                                <th class="rtr" rowspan="10">'.($i+1).'</th>
                                <th scope="col" class="rcr" colspan="2">'.$result['jenis_apd'][$i].'</th>
                            </tr>';
                            for ($j=0; $j < count($loop) ; $j++) { 
                                echo '
                                <tr>
                                    <td>'.$loop[$j][0].'</td>
                                    <td>'.$result[$loop[$j][1]][$i].' petugas</td>
                                </tr>
                                ';
                            }
                            echo '
                            ';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
