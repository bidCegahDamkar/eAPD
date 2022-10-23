
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <!-- Datatable -->
    <?php
    if(isset($datatable)){
      echo '
      <script src="'.base_url().'assets/vendor/datatable/js/jquery-3.5.1.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/jquery.dataTables.min.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/dataTables.buttons.min.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/jszip.min.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/pdfmake.min.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/vfs_fonts.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/buttons.html5.min.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/buttons.print.min.js"></script>
      <script src="'.base_url().'assets/vendor/datatable/js/costum.js"></script>
      ';
    }
    ?>

  </body>
</html>
