    <div class="page-content">
      <div class="main-wrapper">
        <? //d($listUser); ?>
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-3"><?= $pageTitle ?></h5>
                <?php echo form_open($controller.'/change_password'); ?>
                  <div class="mb-3 mt-3 row">
                    <label for="oldPassword" class="col-sm-2 col-form-label">Password Lama</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="newPassword1" class="col-sm-2 col-form-label">Password Baru</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="newPassword1" name="newPassword" required>
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label for="newPassword2" class="col-sm-2 col-form-label">Password Baru (ulangi)</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="newPassword2" name="confirmPassword" required>
                    </div>
                  </div>

                  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <div class="btn-group">
                      <a href="<? echo base_url().$controller;?>" class="btn btn-secondary"> Cancel </a>
                      <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  <!-- page-container -->
      
        
       