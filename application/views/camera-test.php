<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


	    <title>eAPD</title>

	    <!-- Favicons -->
		<link rel="icon" href="<?php echo base_url(); ?>assets/icon/damkar.ico" sizes="32x32" type="ico">
		<meta name="theme-color" content="#563d7c">

	    
  	</head>
  	<body>
		
	  	<video id="video" width="320" height="320" autoplay></video>
		<button id="snap" class="sexyButton">Snap Photo</button>
		<canvas id="canvas" width="320" height="320"></canvas>
		
<!--
		<div class="select">
			<label for="videoSource">Video source: </label><select id="videoSource"></select>
		</div>

		<video autoplay muted playsinline></video>
	-->	


	  <script type="module" src="<?php echo base_url(); ?>assets/html5-camera/costum.js"></script>
  </body>
</html>
