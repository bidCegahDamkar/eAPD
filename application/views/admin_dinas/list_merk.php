<div class="page-content">
      <div class="main-wrapper">
        <? //d($list_data_pos); ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-2"><?= $pageTitle ?></h5>
                <div class="d-grid gap-2 d-md-flex mb-2 justify-content-md-end">
                  <button class="btn btn-primary me-md-2" onclick="add_merk()"><i class="fas fa-plus loader-animation"></i> Tambah</button>
                </div>
                <div class="table-responsive">
                  <table id="list-merk" class="stripe row-border order-column" style="width:100%">
                    <thead>
                        <tr>
                          <th>Aksi</th>
                          <th>#</th>
                          <th>Merk</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      
</div> <!-- page-container -->
        
       