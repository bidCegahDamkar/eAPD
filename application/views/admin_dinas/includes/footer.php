 <!-- Javascripts -->
 <script src="<?php echo base_url(); ?>assets/vendor/admin-circle/plugins/jquery/jquery-3.4.1.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/admin-circle/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/admin-circle/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/admin-circle/js/main-min.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/admin-circle/js/toast-box.js"></script>

        <?php
            if($this->uri->segment(2) == 'home'){
                echo '
                    <script>var listNamaJenisAPD = '.json_encode($listNamaJenisAPD).';</script>
                    <script>var list_sudin = '.json_encode($list_sudin).';</script>
                    <script>var title_input = "'.$title_input.'";</script>
                    <script>var title_verif = "'.$title_verif.'";</script>
                ';
            }
        ?>

        <!-- select2 -->
        <?php
        if(isset($select2)){
            echo '
                <script src="'.base_url().'assets/vendor/select2/js/select2.min.js"></script>
                <script src="'.base_url().'assets/vendor/select2/js/costum.js"></script>
                ';
        }
        ?>

        <!-- Apex Chart -->
        <?php
        if(isset($apexcharts)){
            echo '
                <script src="'.base_url().'assets/vendor/admin-circle/plugins/apexcharts/apexcharts.min.js"></script>
                <script src="'.base_url().'assets/vendor/admin-circle/js/pages/dashboard.js"></script>
                ';
        }
        ?>

        <!-- Datatable -->
        <?php
        if(isset($datatable)){
            echo '
            <script src="https://datatables.net/release-datatables/media/js/jquery.dataTables.js"></script>
            <script src="'.base_url().'assets/vendor/admin-circle/plugins/DataTables/datatables.min.js"></script>
            <script src="'.base_url().'assets/vendor/datatable/js/dataTables.buttons.min.js"></script>
            <script src="'.base_url().'assets/vendor/datatable/js/jszip.min.js"></script>
            <script src="'.base_url().'assets/vendor/datatable/js/pdfmake.min.js"></script>
            <script src="'.base_url().'assets/vendor/datatable/js/vfs_fonts.js"></script>
            <script src="'.base_url().'assets/vendor/datatable/js/buttons.html5.min.js"></script>
            <script src="'.base_url().'assets/vendor/datatable/js/buttons.print.min.js"></script>
            <script src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
            <script src="'.base_url().'assets/vendor/datatable/js/kabid_sarana.js"></script>
            ';
        }
        ?>

        <!-- check nrk -->
        <?php
        if(isset($check_nrk)){
            echo '
                <script src="'.base_url().'assets/petugas/js/check_nrk.js"></script>
                ';
        }
        ?>

        <!-- modal crud -->
        <?php
        if(isset($crud_master_pos)){
            echo '
                <script src="'.base_url().'assets/admin_dinas/crud_master_pos.js"></script>
                ';
        }
        if(isset($crud_master_kondisi)){
            echo '
                <script src="'.base_url().'assets/admin_dinas/crud_master_kondisi.js"></script>
                ';
        }
        if(isset($crud_master_merk)){
            echo '
                <script src="'.base_url().'assets/admin_dinas/crud_master_merk.js"></script>
                ';
        }
        if(isset($crud_jenis_apd)){
            echo '
                <script src="'.base_url().'assets/admin_dinas/crud_jenis_apd.js"></script>
                ';
        }
        if(isset($crud_delete_user)){
            echo '
                <script src="'.base_url().'assets/admin_dinas/crud_delete_user.js"></script>
                ';
        }
        if(isset($crud_reset_apd)){
            echo '
                <script src="'.base_url().'assets/admin_dinas/crud_reset_apd.js"></script>
                ';
        }
        ?>

        <!-- Lapor APD -->
        <?php
        if(isset($laporAPD)){
            echo '
                <script src="'.base_url().'assets/admin_sudin/newLaporAPDver1.js"></script>
            ';
        }
        ?>

        <!-- toast notifikasi -->
        <?php
        $color = $info_message = '';
        if($this->session->flashdata('flash_message')=='sukses'){
        //if(true){
        $color = 'bg-success';
        $info_message = 'Sukses';
            echo'<script>
            window.onload = function(){
                toastbox("notif", 2000);
            };
            </script>';
            $this->session->set_flashdata('flash_message', '');
        }
        elseif ($this->session->flashdata('flash_message')=='gagal')
        {
        $color = 'bg-danger';
        $info_message = 'Maaf, gagal';
            echo'<script>
            window.onload = function(){
                toastbox("notif", 2000);
            };
            </script>';
            $this->session->set_flashdata('flash_message', '');
        }
        
        echo '
        <div class="modal fade" id="notif" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content '.$color.'" >
                    <div class="modal-body text-white">
                        '.$info_message.'
                    </div>
                </div>
            </div>
        </div>';
        ?>
        <!-- toast notifikasi -->

        <!-- Modal pos -->
        <div class="modal fade" id="modalPos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="#" id="form" class="needs-validation" novalidate>
                            <?php
                            if (isset($csrf_name)) {
                                echo '<input type="hidden" name="'.$csrf_name.'" id="csrf_token" />';
                            }
                            ?>
                            <input type="hidden" name="id" id="id"/>
                            <div class="row mb-3">
                                <label for="kodePos" class="col-sm-2 col-form-label">Kode Pos</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="kodePos" id="kodePos" required>
                                    <div class="invalid-feedback">
                                        error duplikasi, kosong atau terdapat spasi
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Kode Sektor</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="kodeSektor" id="kodeSektor" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="namaPos" class="col-sm-2 col-form-label">Nama Pos/Sektor/Seksi</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="namaPos" id="namaPos" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="alamat" id="alamat" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="telp" class="col-sm-2 col-form-label">No Telp</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="telp" id="telp">
                                </div>
                            </div>
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" id="status">
                                        <label class="form-check-label" for="status">Aktif</label>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnSave" onclick="validasi()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal kondisi -->
        <div class="modal fade" id="modalKondisi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="#" id="form_kondisi" class="needs-validation" novalidate>
                            <?php
                            if (isset($csrf_name)) {
                                echo '<input type="hidden" name="'.$csrf_name.'" id="csrf_token_kondisi" />';
                            }
                            ?>
                            <input type="hidden" name="id_mk" id="id_mk"/>
                            <div class="row mb-3">
                                <label for="kodePos" class="col-sm-2 col-form-label">Nama Kondisi</label>
                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Default select example" name="nama_kondisi" id="nama_kondisi" required>
                                        <option value="0" selected disabled>Pilih Salah Satu</option>
                                        <option value="1">Baik</option>
                                        <option value="2">Rusak Ringan</option>
                                        <option value="3">Rusak Sedang</option>
                                        <option value="4">Rusak Berat</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="keterangan" id="keterangan">
                                </div>
                            </div>
                            <fieldset class="row mb-3">
                                <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" id="statusKondisi">
                                        <label class="form-check-label" for="statusKondisi">Aktif</label>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnSaveKondisi" onclick="validasi()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Merk -->
        <div class="modal fade" id="modalMerk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="#" id="form_merk" class="needs-validation" novalidate>
                            <?php
                            if (isset($csrf_name)) {
                                echo '<input type="hidden" name="'.$csrf_name.'" id="csrf_token_merk" />';
                            }
                            ?>
                            <input type="hidden" name="id_mm" id="id_mm"/>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Merk</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="merk" id="merk" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnSaveMerk" onclick="validasi()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal user -->
        <div class="modal fade" id="modalUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                            $user_page = ($this->uri->segment(2) == 'crud_modal_user_setting') ? true : false ;
                            if ($user_page) {
                                $foto = (is_null($userData['photo'])) ? base_url().'assets/img/default-red.png' : base_url().'upload/petugas/profil/'.$userData['photo'] ;
                                echo '
                                <div class="text-center my-4">
                                    <img src="'.$foto.'" class="rounded-circle" width="240" height="240" alt="...">
                                </div>
                                ';
                            }       
                        ?> 
                        

                        <form action="#" id="form_master_user" class="needs-validation" novalidate>
                            <?php
                            if (isset($csrf_name)) {
                                echo '<input type="hidden" name="'.$csrf_name.'" id="csrf_token_user" />';
                            }
                            ?>
                            <input type="hidden" name="id_user" id="id_user"/>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">NRK/ NPJLP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nrk" id="nrk" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">NIP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nip" id="nip" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($user_page) {
                                echo '
                                <div class="row mb-3">
                                    <label for="statusid" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <select class="" name="status_id" id="statusid" style="width: 100%" required>';
                                            foreach ($list_status as $stat) {
                                                $selected = ($stat['id_stat'] == $userData['status_id']) ? 'selected' : '' ;
                                                echo '
                                                <option value="'.$stat['id_stat'].'" '.$selected.'>'.$stat['status'].'</option>
                                                ';
                                            }
                                echo '
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="jabatanid" class="col-sm-2 col-form-label">Jabatan</label>
                                    <div class="col-sm-10">
                                        <select class="" name="jabatan_id" id="jabatanid" style="width: 100%" required>
                                            <option value="'.$userData['jabatan_id'].'" selected> '.$userData['nama_jabatan'].'</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="kodepos" class="col-sm-2 col-form-label">Tempat Tugas</label>
                                    <div class="col-sm-10">
                                        <select class="select2-basic-single" name="kode_pos_id" id="kodepos" style="width: 100%" required>';
                                            foreach ($list_pos as $pos) {
                                                $retVal = (is_null($pos['sektor']) ) ? '' : ', Sektor '.$pos['sektor'] ;
                                                $selected = ($pos['id_mp'] == $userData['kode_pos_id']) ? 'selected' : '' ;
                                                echo '
                                                <option value="'.$pos['id_mp'].'" '.$selected.'>'.$pos['kode_pos'].', '.$pos['nama_pos'].$retVal.'</option>
                                                ';
                                            }
                                echo '
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="grouppiket" class="col-sm-2 col-form-label">Group Piket</label>
                                    <div class="col-sm-10">
                                        <select class="select2-basic-single" name="group_piket_id" id="grouppiket" style="width: 100%" required>';
                                            foreach ($list_group_piket as $gp) {
                                                $selected = ($gp['id'] == $userData['group_piket_id']) ? 'selected' : '' ;
                                                echo '
                                                <option value="'.$gp['id'].'" '.$selected.'>'.$gp['deskripsi_group'].'</option>
                                                ';
                                            }
                                echo '
                                        </select>
                                    </div>
                                </div>
                                ';

                                $checked = ($userData['active'] == 1) ? ['checked', ''] : ['', 'checked'] ;
                                echo '
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Akun</legend>
                                    <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="active" id="gridRadios1" value="1" '.$checked[0].'>
                                        <label class="form-check-label" for="gridRadios1">
                                        Aktif
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="active" id="gridRadios2" value="0" '.$checked[1].'>
                                        <label class="form-check-label" for="gridRadios2">
                                        Non-aktif
                                        </label>
                                    </div>
                                    </div>
                                </fieldset>';
                            }
                            ?>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnSaveUser" onclick="validasi()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal delete user -->
        <div class="modal fade" id="modalDeleteUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center my-4">
                            <img id="user_img" src="<?php echo base_url().'assets/img/default-red.png'; ?>" class="rounded-circle" width="240" height="240" alt="...">
                        </div>
                        

                        <form action="#" id="form_delete_user" class="needs-validation" novalidate>
                            <?php
                            if (isset($csrf_name)) {
                                echo '<input type="hidden" name="'.$csrf_name.'" id="csrf_token_delete_user" />';
                            }
                            ?>
                            <input type="hidden" name="id_user" id="id_delete_user"/>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="delete_name" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">NRK/ NPJLP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nrk" id="delete_nrk" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">NIP</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nip" id="delete_nip" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnDeleteUser" onclick="save()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal APD -->
        <div class="modal fade" id="modalAPD" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center my-4">
                            <img src="" id="apdImage" class="img-fluid" alt="...">
                        </div>
                        

                        <form action="#" id="form_APD" class="needs-validation" novalidate>
                            <?php
                            if (isset($csrf_name)) {
                                echo '<input type="hidden" name="'.$csrf_name.'" id="csrf_token_apd" />';
                            }
                            ?>
                            <input type="hidden" name="id_apd" id="id_apd"/>
                            <input type="hidden" name="petugas_id" id="petugas_id"/>
                            <div class="row mb-3">
                                <label for="jenis_apd" class="col-sm-2 col-form-label">Jenis APD</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="jenis_apd" id="jenis_apd" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="merk" class="col-sm-2 col-form-label">merk/ pengadaan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="merk_apd" id="merk_apd" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="status_apd" id="status_apd" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="keberadaan" class="col-sm-2 col-form-label">Keberadaan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="keberadaan" id="keberadaan" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Kondisi</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="kondisi" id="kondisi" disabled>
                                </div>
                            </div>

                            <div class="row mb-3" id="noSeri">
                            </div>
                            
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Keterangan Petugas</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="keterangan_petugas" id="keterangan_petugas" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Tanggal Input</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="created_at" id="created_at" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Tanggal Edit</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="updated_at" id="updated_at" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeSektor" class="col-sm-2 col-form-label">Pesan Admin</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="admin_message" id="admin_message" required>
                                    <div class="invalid-feedback">
                                        Harus diisi
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="btnResetAPD" onclick="validasi()">Reset Validasi</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>