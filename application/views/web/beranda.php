<?php
$ceks = $this->session->userdata('prakrin_smk@Proyek-2017'); ?>
<!-- Page content of course! -->
<main class="bs-docs-masthead" id="content" role="main" style="margin-left:-20px;margin-right:-20px;margin-top:-60px;">
 <div class="container">
	 <center>
		 <img src="foto/UNIMAL.jpg" alt="Logo" class="img-circle" height="200" width="200">
	 </center>
	 <p class="lead">
		 Selamat Datang di Sistem Informasi Manajemen Magang Mahasiswa 
		 Pada Prodi Sistem Informasi Universitas Malikussaleh 
	 </p>
	 <p class="lead">
		 <?php
		 if ($ceks) {?>
		 	<!-- <a href="web/logout" class="btn btn-outline-inverse btn-lg">Keluar</a> -->
			<br>
		<?php
		 }else{ ?>
			 <a href="web/login" class="btn btn-outline-inverse btn-lg">Masuk</a>
		<?php
		 } ?>
	 </p>
	 <!-- <?php
	 if (!$ceks) {?>
	 <p class="version">v1.0</p>
	 <?php
 	 } ?> -->
 </div>
</main>
