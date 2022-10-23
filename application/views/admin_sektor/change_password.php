<main class="container">
    <? //d($listUser); ?>
    <div class="bg-primary text-white mt-2 mb-4 px-5 py-3 rounded">
        <h5 class="card-title">Setting</h5>
        <h6 class="card-subtitle mb-2">Rubah Password</h6>
    </div>

    <?php 
        $attributes = array('class' => 'needs-validation', 'novalidate' => 'novalidate');
        echo form_open('admin_sektor/setting');
    ?>
        <div class="mb-3 row">
            <label for="oldPassword" class="col-sm-2 col-form-label">Password Lama</label>
            <div class="col-sm-10">
            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="newPassword" class="col-sm-2 col-form-label">Password Baru</label>
            <div class="col-sm-10">
            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="confirmPassword" class="col-sm-2 col-form-label">Konfirmasi Password</label>
            <div class="col-sm-10">
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>

    </form>



</main>