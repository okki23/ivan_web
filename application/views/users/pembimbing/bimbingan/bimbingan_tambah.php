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
        <h5 class="panel-title">+ Bimbingan</h5>
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
              <select class="form-control cari_mahasiswa" name="nim"  id="nim" required>
                <option value="" selected>-- Pilih Mahasiswa --</option>
                <?php foreach ($v_mahasiswa->result() as $baris): ?>
                  <option value="<?php echo $baris->nim; ?>"><?php echo "$baris->nama_lengkap [NIM: $baris->nim"; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <label for="nama"><b>Nama Pembimbing</b></label>
              <select class="form-control cari_pemb" name="nip"  required>
                <option value="" selected>-- Pilih Mahasiswa --</option>
                <?php foreach ($v_dosen->result() as $baris): ?>
                  <option value="<?php echo $baris->nip; ?>"><?php echo "$baris->nama_lengkap [NIP: $baris->nip"; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-12 pull-left" style="margin-top: 10px;">
              <hr>
              <a href="javascript:history.back()" class="btn btn-default">Kembali</a>
              <button type="submit" name="btnsimpan" class="btn btn-primary" style="float:right;">Simpan</button>
              <input type="reset" id="reset" class="btn btn-default" style="float:right;margin-right:10px;" value="Reset">
            </div>
        </form>

      </div>
    </div>
    <!-- /basic datatable -->
  </div>
  <!-- /dashboard content -->
<script>
  $('#reset').click(function(){
    $("select").val("").trigger("change");
  });
</script>