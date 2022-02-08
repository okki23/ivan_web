<?php
$sub_menu3 = strtolower($this->uri->segment(3));
$user = $query; ?>
<!-- Main content -->
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">

    <!-- Dashboard content -->
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="panel panel-flat">

            <div class="panel-body">

              <fieldset class="content-group">
                <legend class="text-bold"><i class="icon-user"></i> Edit <?php if ($sub_menu3 == 'e_pemb') {echo "Pembimbing";}else{echo "Mahasiswa";} ?></legend>
                <?php
               echo $this->session->flashdata('msg');
               if ($sub_menu3 == 'e_pemb') {?>
               <form class="form-horizontal" action="" method="post">
               <div class="form-group">
                    <label class="control-label col-lg-3">NIP</label>
                    <div class="col-lg-9">
                      <input type="text" name="nip" class="form-control" value="<?=$user->nip?>" readonly  id="valuepassword" placeholder="NIP" maxlength="21" required>
                    </div>
                </div>
               <div class="form-group">
                    <label class="control-label col-lg-3">Nama Lengkap</label>
                    <div class="col-lg-9">
                      <input type="text" name="nama_lengkap" class="form-control" value="<?=$user->nama_lengkap?>" placeholder="Nama Lengkap" maxlength="50" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Nama Jurusan</label>
                    <div class="col-lg-9">
                      <select class="form-control cari_jurusan" name="jurusan" required style="width:100%;">
                        <option value="">-- Pilih Jurusan --</option>
                        <?php foreach ($v_jurusan->result() as $baris){ ?>
                          <option value="<?php echo $baris->kdjurusan; ?>" <?=$user->kdjurusan==$baris->kdjurusan ? 'selected':''?>><?php echo $baris->nama; ?></option>
                        <?php }; ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Username</label>
                    <div class="col-lg-9">
                      <input type="text" name="username" class="form-control" value="" placeholder="<?=$user->username?>"  >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-3">Email</label>
                    <div class="col-lg-9">
                      <input type="text" name="email" class="form-control" value="<?=$user->email?>" placeholder="Email"  required>
                    </div>
                  </div>
                 <a href="javascript:history.back()" class="btn btn-default"><< Kembali</a>
                 <button type="submit" name="btnupdate1" class="btn btn-primary" style="float:right;">Update</button>
               </form>
               <?php
               }else{ ?>
                <form class="form-horizontal" action="" method="post">
                <div class="form-group">
                      <label class="control-label col-lg-3">Nama Kelas</label>
                      <div class="col-lg-9">
                        <select class="form-control cari_kelas" name="kelas" required style="width:100%;">
                          <option value="">-- Pilih Kelas --</option>
                          <?php foreach ($v_kelas->result() as $baris){ ?>
                            <option value="<?php echo $baris->kdkelas; ?>" <?=$baris->kdkelas == $user->kdkelas ? 'selected':''?>><?php echo $baris->nama; ?></option>
                          <?php }; ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">NIM </label>
                      <div class="col-lg-9">
                        <input type="text" name="nim" class="form-control" value="<?=$user->nim?>" id="valuepassword" readonly placeholder="Masukkan NIM" maxlength="11" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">Telp</label>
                      <div class="col-lg-9">
                        <input type="text" name="telp" class="form-control" value="<?=$user->telp?>" placeholder="Telp" maxlength="14" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-lg-3">Nama Lengkap</label>
                      <div class="col-lg-9">
                        <input type="text" name="nama_lengkap" class="form-control"value="<?=$user->nama_lengkap?>"placeholder="Nama Lengkap" maxlength="50" required>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-lg-3">Foto</label>
                      <div class="col-lg-9">
                        <input type="file" name="file" class="form-control" value="" placeholder="Foto" >
                        <b style="color:red;font-size:10px;">*Max Size: 5 MB</b>
                      </div>
                    </div>

                  <a href="javascript:history.back()" class="btn btn-default"><< Kembali</a>
                  <button type="submit" name="btnupdate2" class="btn btn-primary" style="float:right;">Update</button>
                </form>
                <?php
               } ?>

                </fieldset>
  
  
              </div>
  
          </div>
        </div>
      </div>
      <!-- /dashboard content -->