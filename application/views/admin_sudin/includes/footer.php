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
                <script>var list_sudin = '.json_encode($list_sektor).';</script>
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
            <script src="'.base_url().'assets/vendor/datatable/js/kasi_sarana.js"></script>
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
        if(isset($crud_delete_user)){
            echo '
                <script src="'.base_url().'assets/admin_sudin/crud_delete_user.js"></script>
                ';
        }
        if(isset($crud_reset_apd)){
            echo '
                <script src="'.base_url().'assets/admin_sudin/crud_reset_apd.js"></script>
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