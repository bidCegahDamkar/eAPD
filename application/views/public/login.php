<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	    <!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

	    <title>eAPD</title>

	    <!-- Favicons -->
		<link rel="icon" href="<?php echo base_url(); ?>assets/icon/damkar.ico" sizes="32x32" type="ico">
		<meta name="theme-color" content="#563d7c">

	    
	    <!-- Custom styles for this template -->
	    <link href="<?php echo base_url(); ?>assets/login/login.css" rel="stylesheet">
  	</head>
  	<body>
		  <?php
			$attributes = array('class' => 'form-signin', 'id' => 'myform');
			echo form_open('auth/login', $attributes);
		  ?>
		  <div class="text-center mb-4">
		    <img class="mb-4" src="<?php echo base_url(); ?>assets/login/logo_dki.png" alt="" width="72" height="72">
		    <img class="mb-4" src="<?php echo base_url(); ?>assets/login/logo_damkar_dki.png" alt="" width="72" height="72">
		    <h1 class="h3 mb-3 font-weight-normal">eAPD</h1>
		    <p class="mb-0">Sistem Informasi APD Petugas </p>
		    <p class="mb-0" style="color: darkblue;">Dinas Penanggulangan Kebakaran dan Penyelamatan</p>
		    <p style="color: darkblue;">Provinsi DKI Jakarta</p>
		  </div>

		  <?php 
			if(! is_null($message)){
				echo '<div class="alert alert-warning" role="alert">
				'.$message.'
			  </div>';
			}
		?>

		  <div class="form-label-group">
		    <input type="text" id="identity" name="identity" class="form-control login-form pr-2" placeholder="NRK" required autofocus>
		    <label for="identity" style="text-indent: 10px;">NRK/ NPJLP</label>
		  </div>

		  <div class="form-label-group">
		    <input type="password" id="password" name="password" class="form-control login-form" placeholder="Password" required>
		    <label for="password" style="text-indent: 10px;">Password</label>
		  </div>

		  <div class="checkbox mb-3 text-center">
		    <label>
				<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?> Ingat saya
		    </label>
		  </div>
		  <button class="btn btn-lg btn-primary btn-block" type="submit">Masuk</button>
		  <a href="<?php echo base_url(); ?>" class="btn btn-lg btn-secondary btn-block mt-3">Halaman Depan</a>

		  <p class="mt-5 mb-3 text-muted text-center">&copy; 2021</p>
		</form>

		


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

  </body>
</html>
