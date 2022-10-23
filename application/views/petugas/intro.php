
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>eAPD</title>
    <meta name="description" content="eAPD Dinas Penanggulangan Kebakaran dan Penyelamatan">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <meta name="google" content="notranslate" />
    <meta name="author" content="Tim eAPD 2021">
    <link rel="icon" href="<?php echo base_url(); ?>assets/icon/damkar.ico" sizes="32x32" type="ico">

    <!-- Bootrap for the demo page -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <!-- Animate CSS for the css animation support if needed -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
    
    <!-- Demo files -->
    <link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
      
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
</head>
<body>

  <div class="container">

    <!-- SmartWizard html -->
    <div id="smartwizard" class="mt-3">
      <ul class="nav nav-progress">
        <li class="nav-item">
          <a class="nav-link" href="#step-1">
            <div class="num">1</div>
            Intro
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#step-2">
            <span class="num">2</span>
            Seragam Dinas
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#step-3">
            <span class="num">3</span>
            APD
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#step-4">
            <span class="num">4</span>
            Konfirmasi
          </a>
        </li>
      </ul>

      <div class="tab-content">
        <!-- step 1 -->
        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
          <form id="form-1" class="row row-cols-1 needs-validation" novalidate>
            <div class="bg-light rounded-3">
              <div class="container-fluid py-2">
                <h1 class="display-5 fw-bold">Welcome to eAPD</h1>
                <p class="col-md-8 fs-4">Ijin Pimpinan, Bapak dan Ibu yang terhormat. Sebelum lanjut ke aplikasi eAPD, mohon kesediaannya untuk mengisi data ukuran Pakaian Dinas dan APD Dinas Penanggulangan Kebakaran dan Penyelamatan pada form ini.</p>
              </div>
            </div>
          </form>
        </div>

        <!-- step 2 -->
        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
          <form id="form-2" class="row row-cols-1 needs-validation" novalidate>
          <?php
            //d($list_ukuran_huruf);
            for ($i=0; $i < 5 ; $i++) { 
                if ($list_tipe_ukuran[$i] == 2 ) {
                    echo '
                    <div class="col-md-3 mb-4">
                        <label class="label" for="'.$list_apd_temp[$i].'">'.$list_label[$i].'</label>
                        <select class="form-select form-select-lg" id="'.$list_apd_temp[$i].'" name="'.$list_apd_temp[$i].'" required>
                            <option selected disabled value="">Pilih Salah Satu</option>';
                    foreach ($list_ukuran_angka as $angka) {
                        echo '
                        <option value="'.$angka.'">'.$angka.'</option>
                        ';
                    }
                    
                } else if ($list_tipe_ukuran[$i] == 3 ) {
                    echo '
                    <div class="col-md-3 mb-4">
                        <label class="label" for="'.$list_apd_temp[$i].'">'.$list_label[$i].'</label>
                        <select class="form-select form-select-lg" id="'.$list_apd_temp[$i].'" name="'.$list_apd_temp[$i].'" required>
                            <option selected disabled value="">Pilih Salah Satu</option>';
                    foreach ($list_ukuran_huruf as $huruf) {
                        echo '
                        <option value="'.$huruf.'">'.$huruf.'</option>
                        ';
                    }
                } else if ($list_tipe_ukuran[$i] == 4 ) {
                  echo '
                  <div class="col-md-3 mb-4">
                      <label class="label" for="'.$list_apd_temp[$i].'">'.$list_label[$i].'</label>
                      <select class="form-select form-select-lg" id="'.$list_apd_temp[$i].'" name="'.$list_apd_temp[$i].'" required>
                          <option selected disabled value="">Pilih Salah Satu</option>';
                  foreach ($list_ukuran_baret as $baret) {
                      echo '
                      <option value="'.$baret.'">'.$baret.'</option>
                      ';
                  }
              }
                
                echo '
                        </select>
                        <div class="invalid-feedback">
                            Harus Diisi
                        </div>
                    </div>
                    ';
            }
          ?>
          </form>
        </div>

        <!-- step 3 -->
        <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
          <form id="form-3" class="row row-cols-1 needs-validation" novalidate>
          <?php
            //d($list_ukuran_huruf);
            for ($i=5; $i < (count($list_apd)) ; $i++) { 
                if ($list_tipe_ukuran[$i] == 2 ) {
                    echo '
                    <div class="col-md-3 mb-4">
                        <label class="label" for="'.$list_apd_temp[$i].'">'.$list_label[$i].'</label>
                        <select class="form-select form-select-lg" id="'.$list_apd_temp[$i].'" name="'.$list_apd_temp[$i].'" required>
                            <option selected disabled value="">Pilih Salah Satu</option>';
                    foreach ($list_ukuran_angka as $angka) {
                        echo '
                        <option value="'.$angka.'">'.$angka.'</option>
                        ';
                    }
                    
                } else if ($list_tipe_ukuran[$i] == 3 ) {
                    echo '
                    <div class="col-md-3 mb-4">
                        <label class="label" for="'.$list_apd_temp[$i].'">'.$list_label[$i].'</label>
                        <select class="form-select form-select-lg" id="'.$list_apd_temp[$i].'" name="'.$list_apd_temp[$i].'" required>
                            <option selected disabled value="">Pilih Salah Satu</option>';
                    foreach ($list_ukuran_huruf as $huruf) {
                        echo '
                        <option value="'.$huruf.'">'.$huruf.'</option>
                        ';
                    }
                } else if ($list_tipe_ukuran[$i] == 4 ) {
                  echo '
                  <div class="col-md-3 mb-4">
                      <label class="label" for="'.$list_apd_temp[$i].'">'.$list_label[$i].'</label>
                      <select class="form-select form-select-lg" id="'.$list_apd_temp[$i].'" name="'.$list_apd_temp[$i].'" required>
                          <option selected disabled value="">Pilih Salah Satu</option>';
                  foreach ($list_ukuran_baret as $baret) {
                      echo '
                      <option value="'.$baret.'">'.$baret.'</option>
                      ';
                  }
              }
                
                echo '
                        </select>
                        <div class="invalid-feedback">
                            Harus Diisi
                        </div>
                    </div>
                    ';
            }
          ?>
          </form>  
        </div>

        <!-- step 4 -->
        <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">

          <div class="mb-1 text-muted">Apakah Data Berikut Sudah Benar?</div>
          <?php
          $csrf = array(
              'name' => $this->security->get_csrf_token_name(),
              'hash' => $this->security->get_csrf_hash()
          );
          ?>

          <form id="form-4" class="row g-3">
              <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
              <input type="hidden" name="users_id" value="<?=$user_id;?>" />
              <input type="hidden" name="waktu" value="<?=$my_time;?>" />

              <div class="my-apd"></div>

              <?php
              echo '<h4 class="mb-3-">Seragam Dinas</h4>
                    <hr class="my-2">';
              for ($i=0; $i < 5 ; $i++) { 
                echo '
                <div class="mb-3 row">
                  <label for="staticEmail" class="col-10 col-form-label">'.$list_label[$i].'</label>
                  <div class="col-2">
                    <input type="text" readonly class="form-control-plaintext" name="'.$list_apd[$i].'" id="'.$list_apd[$i].'" >
                  </div>
                </div>
                ';
              }

              echo '<h4 class="mb-3-">APD</h4>
                    <hr class="my-2">';
              for ($i=5; $i < count($list_apd) ; $i++) { 
                echo '
                <div class="mb-3 row">
                  <label for="staticEmail" class="col-10 col-form-label">'.$list_label[$i].'</label>
                  <div class="col-2">
                    <input type="text" readonly class="form-control-plaintext" name="'.$list_apd[$i].'" id="'.$list_apd[$i].'" >
                  </div>
                </div>
                ';
              }
              ?>

          </form>

          <small class="text-muted">Data ini dapat sewaktu-waktu dirubah dengan mengakses menu profil</small>

        </div>
      </div>

        <div class="progress">
          <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    </div>

    <!-- Sukses Modal -->
    <div class="modal fade" id="suksesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="suksesModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="suksesModalLabel">Sukses, Data Berhasil Disimpan</h5>
          </div>
          <div class="modal-body">
            <img src="<?php echo base_url(); ?>assets/img/sticker86.png" class="img-fluid" alt="...">
          </div>
          <div class="modal-footer">
            <button id="btnAddSize" type="button" class="btn btn-primary" onclick="closeSuksesModal()">OK</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Gagal Modal -->
    <div class="modal fade" id="gagalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="gagalModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="gagalModalLabel">Gagal</h5>
          </div>
          <div class="modal-body">
            Maaf Gagal, Sepertinya Ada Kesalahan
          </div>
          <div class="modal-footer">
            <button id="btnAddSize" type="button" class="btn btn-primary" onclick="closeGagalModal()">Ulangi</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootrap for the demo page -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- jQuery Slim 3.6  -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script> -->

    <!-- Include SmartWizard JavaScript source -->
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>

    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <script>var controller = '<?php echo $controller; ?>';</script>
    <?php echo '<script>var list_apd = '.json_encode($list_apd).';</script>
                <script>var list_apd_temp = '.json_encode($list_apd_temp).';</script>'; ?>
    
    <script type="text/javascript">

        //const myModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        function onCancel() { 
          // Reset wizard
          $('#smartwizard').smartWizard("reset");

          // Reset form
          document.getElementById("form-1").reset();
          document.getElementById("form-2").reset();
          document.getElementById("form-3").reset();
          document.getElementById("form-4").reset();
        }

        /*function onConfirm() {
          let form = document.getElementById('form-4');
          if (form) {
            if (!form.checkValidity()) {
              form.classList.add('was-validated');
              $('#smartwizard').smartWizard("setState", [3], 'error');
              $("#smartwizard").smartWizard('fixHeight');
              return false;
            }
            
            myModal.show();
          }
        }*/

        function simpanUkuran() {
          let info = 'Sukses Tambah Data';
          let url = base_url+controller+'/simpan_ukuran_ajax' ;
          // ajax adding data to database
          $.ajax({
              url : url,
              type: "POST",
              data: $('#form-4').serialize(),
              //data: {username: 'kus',[csrfName]: csrfHash },
              dataType: "JSON",
              success: function(data)
              {
                  //console.log(data);
                  if(data.status) //if success close modal and reload ajax table
                  {
                      //alert(info);
                      //myModal.hide();
                      $('#suksesModal').modal('show');
                  }
                  else
                  {
                      $('#gagalModal').modal('show');
                  }
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                  alert('Error adding / update data');
                  //$('#btnAddSize').text('Simpan'); //change button text
                  //$('#btnAddSize').attr('disabled',false); //set button enable 

              }
          });
        }

        function closeSuksesModal() {
          // Reset wizard
          $('#smartwizard').smartWizard("reset");

          // Reset form
          document.getElementById("form-1").reset();
          document.getElementById("form-2").reset();
          document.getElementById("form-3").reset();
          document.getElementById("form-4").reset();

          $('#suksesModal').modal('hide');
          window.location.replace(base_url+controller);
        }

        function closeGagalModal() {
          // Reset wizard
          $('#smartwizard').smartWizard("reset");

          // Reset form
          document.getElementById("form-1").reset();
          document.getElementById("form-2").reset();
          document.getElementById("form-3").reset();
          document.getElementById("form-4").reset();

          $('#gagalModal').modal('hide');
        }

        function showConfirm() {
          $("#delete_name").val($('#txt_name').val());
          for (let i = 0; i < list_apd.length; i++) {
            $('#'+list_apd[i]+'').val($('#'+list_apd_temp[i]+'').val());
          }
          //$('#smartwizard').smartWizard("fixHeight");
        }

        $(function() {
            // Leave step event is used for validating the forms
            $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) {
                // Validate only on forward movement  
                if (stepDirection == 'forward') {
                  let form = document.getElementById('form-' + (currentStepIdx + 1));
                  if (form) {
                    if (!form.checkValidity()) {
                      form.classList.add('was-validated');
                      $('#smartwizard').smartWizard("setState", [currentStepIdx], 'error');
                      $("#smartwizard").smartWizard('fixHeight');
                      return false;
                    }
                    $('#smartwizard').smartWizard("unsetState", [currentStepIdx], 'error');
                  }
                }
            });

            // Step show event
            $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                $("#prev-btn").removeClass('disabled').prop('disabled', false);
                $("#next-btn").removeClass('disabled').prop('disabled', false);
                if(stepPosition === 'first') {
                    $("#prev-btn").addClass('disabled').prop('disabled', true);
                } else if(stepPosition === 'last') {
                    $("#next-btn").addClass('disabled').prop('disabled', true);
                } else {
                    $("#prev-btn").removeClass('disabled').prop('disabled', false);
                    $("#next-btn").removeClass('disabled').prop('disabled', false);
                }

                // Get step info from Smart Wizard
                let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
                $("#sw-current-step").text(stepInfo.currentStep + 1);
                $("#sw-total-step").text(stepInfo.totalSteps);

                if (stepPosition == 'last') {
                  showConfirm();
                  $("#btnFinish").prop('disabled', false);
                } else {
                  $("#btnFinish").prop('disabled', true);
                }

                // Focus first name
                if (stepIndex == 2) {
                  setTimeout(() => {
                    $('#uk_kaos_temp').focus();
                  }, 0);
                }
            });

            // Smart Wizard
            $('#smartwizard').smartWizard({
                selected: 0,
                autoAdjustHeight: true,
                theme: 'arrows', // basic, arrows, square, round, dots
                transition: {
                  animation:'none'
                },
                toolbar: {
                  showNextButton: true, // show/hide a Next button
                  showPreviousButton: true, // show/hide a Previous button
                  position: 'bottom', // none/ top/ both bottom
                  extraHtml: `<button class="btn btn-success" id="btnFinish" disabled onclick="simpanUkuran()">Simpan</button>
                              <button class="btn btn-danger" id="btnCancel" onclick="onCancel()">Cancel</button>`
                },
                anchor: {
                    enableNavigation: true, // Enable/Disable anchor navigation 
                    enableNavigationAlways: false, // Activates all anchors clickable always
                    enableDoneState: true, // Add done state on visited steps
                    markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    unDoneOnBackNavigation: true, // While navigate back, done state will be cleared
                    enableDoneStateNavigation: true // Enable/Disable the done state navigation
                },
            });

            $("#state_selector").on("change", function() {
                $('#smartwizard').smartWizard("setState", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
                return true;
            });

            $("#style_selector").on("change", function() {
                $('#smartwizard').smartWizard("setStyle", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
                return true;
            });

        });
    </script>
</body>
</html>
