<script src="assets/js/jquery-ui.js"></script>
<script>
$( function() {
  $( "#datepicker" ).datepicker();
} );
</script>
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">

    <!-- Dashboard content -->

  <div class="row">
            <!-- Basic datatable -->
    <div class="col-md-3"></div>
    <div class="panel panel-flat col-md-6">
      <div class="panel-heading">
        <h5 class="panel-title">Isi Nilai</h5>
        <div class="heading-elements">
          <ul class="icons-list">
            <li><a data-action="collapse"></a></li>
          </ul>
        </div>

        </div>
        <hr style="margin:0px;">
        <div class="panel-body">
          <?php
          echo $this->session->flashdata('msg');
          ?>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="nama"><b>Nama Mahasiswa</b></label>
              <select class="form-control cari_mahasiswa" name="nim" required>
                <option value="">-- Pilih Mahasiswa --</option>
                <?php foreach ($v_mahasiswa->result() as $baris): ?>
                  <option value="<?php echo $baris->nim; ?>"><?php echo "$baris->nama_lengkap [NIM: $baris->nim]"; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="nilai"><b>Nilai</b></label>
              <input type="number" class="form-control" id="nilai" name="nilai" value="" placeholder="Nilai" required>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="keterangan"><b>Grade Nilai</b></label>
              <!-- <textarea class="form-control" id="keterangan" name="keterangan" rows="4" cols="80" placeholder="Keterangan" required></textarea> -->
              <input type="text" class="form-control" id="keterangan" name="keterangan" readonly=true  value="" placeholder="Nilai Huruf" required>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <hr>
              <a href="javascript:history.back()" class="btn btn-default">Kembali</a>
              <button type="submit" name="btnsimpan" class="btn btn-primary" style="float:right;">Simpan</button>
              <button type="reset" class="btn btn-default" style="float:right;margin-right:10px;">Reset</button>
            </div>
        </form>

      </div>
    </div>
    <!-- /basic datatable -->
  </div>
  <script>
    $('#nilai').keyup(function(){
      var nilai=$(this).val();
      $('#keterangan').val('');
      
      var grade='';
      if(nilai>=85 && nilai<=100 ){
        grade='A';
      } else if(nilai>=75 && nilai<=84 ){
        grade='B';
      } else if(nilai>=60 && nilai<=74 ){
        grade='C';
      } else if(nilai>=50 && nilai<=59 ){
        grade='D';
      } else if(nilai<50 ){
        grade='E';
      }
      if(nilai==""){
        grade="";
      }
      $('#keterangan').val(grade);
    });
  </script>
  <!-- /dashboard content -->
