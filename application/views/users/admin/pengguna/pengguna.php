<?php
$sub_menu3 = strtolower($this->uri->segment(3)); ?>
<!-- Main content -->
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">
    <!-- Dashboard content -->
    <div class="row">
      <!-- Basic datatable -->
      <div class="panel panel-flat">
        <div class="panel-heading">
          <h6 class="panel-title"><i class="icon-users"></i> Data Pengguna <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
          <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="close"></a></li>
            </ul>
           </div>
        </div>

        <div class="panel-body">
          <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
              <li class="<?php if($sub_menu3 == ''){echo 'active';} ?>"><a href="#tbl_pemb" data-toggle="tab" aria-expanded="true">+ Pembimbing</a></li>
              <li class="<?php if($sub_menu3 == 'tbl_mahasiswa'){echo 'active';} ?>"><a href="#tbl_mahasiswa" data-toggle="tab" aria-expanded="true">+ Mahasiswa</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane <?php if($sub_menu3 == ''){echo 'active';} ?>" id="tbl_pemb">

                <?php
                echo $this->session->flashdata('msg_pemb');
                ?>
                <a href="users/pengguna/t_pemb" class="btn btn-primary"><i class="icon-user-plus"></i> Pembimbing Baru</a>

                <table class="table datatable-basic" width="100%">
                  <thead>
                    <tr>
                      <th width="30px;">No.</th>
                      <th>Username</th>
                      <th>Nama Lengkap</th>
                      <th>NIP</th>
                      <th class="text-center" width="130">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = 1;
                      foreach ($v_pemb->result() as $baris) {
                      ?>
                        <tr>
                          <td><?php echo $no.'.'; ?></td>
                          <td><?php echo $baris->username; ?></td>
                          <td><?php echo $baris->nama_lengkap; ?></td>
                          <td><?php echo $baris->nip; ?></td>
                          <td>
                          <a href="users/pengguna/e_pemb/<?php echo $baris->kdpemb; ?>" class="btn btn-success btn-xs"><i class="icon-pencil7"></i></a>
                            <a href="users/pengguna/d_pemb/<?php echo $baris->kdpemb; ?>" class="btn btn-info btn-xs"><i class="icon-eye"></i></a>
                            <!-- <a href="users/pengguna/e_pemb/<?php echo $baris->kdpemb; ?>" class="btn btn-success btn-xs"><i class="icon-pencil7"></i></a> -->
                            <a href="users/pengguna/h_pemb/<?php echo $baris->kdpemb; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin?')"><i class="icon-trash"></i></a>
                          </td>
                        </tr>
                      <?php
                      $no++;
                      } ?>
                  </tbody>
                </table>

              </div>

              <div class="tab-pane <?php if($sub_menu3 == 'tbl_mahasiswa'){echo 'active';} ?>" id="tbl_mahasiswa">

                <?php
                echo $this->session->flashdata('msg_mahasiswa');
                ?>
                <a href="users/pengguna/t_mahasiswa" class="btn btn-primary"><i class="icon-user-plus"></i> Tambah Mahasiswa</a>

                <table class="table datatable-basic" width="100%">
                  <thead>
                    <tr>
                      <th width="30px;">No.</th>
                      <th>NIM</th>
                      <th>Nama Lengkap</th>
                      <th>Telp</th>
                      <th>Nama Pembimbing</th>
                      <th class="text-center" width="130">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = 1;
                      foreach ($v_mahasiswa->result() as $baris) {
                        $nama_pemb = $this->db->select('tbl_pemb.nama_lengkap')->join('tbl_pemb','tbl_bimbingan.nip=tbl_pemb.nip')->get_where('tbl_bimbingan', ['nim'=>$baris->nim])->row();
                        if($nama_pemb){
                          $nama_pemb=$nama_pemb->nama_lengkap;
                        }else{
                          $nama_pemb='-';
                        }
                      ?>
                        <tr>
                          <td><?php echo $no.'.'; ?></td>
                          <td><?php echo $baris->nim; ?></td>
                          <td><?php echo $baris->nama_lengkap; ?></td>
                          <td><?php echo $baris->telp; ?></td>
                          <td><?php echo $nama_pemb; ?></td>
                          <td>
                          <a href="users/pengguna/e_mahasiswa/<?php echo $baris->nim; ?>" class="btn btn-success btn-xs"><i class="icon-pencil7"></i></a>
                            <a href="users/pengguna/d_mahasiswa/<?php echo $baris->nim; ?>" class="btn btn-info btn-xs"><i class="icon-eye"></i></a>
                            <!-- <a href="users/pengguna/e_mahasiswa/<?php echo $baris->nim; ?>" class="btn btn-success btn-xs"><i class="icon-pencil7"></i></a> -->
                            <a href="users/pengguna/h_mahasiswa/<?php echo $baris->nim; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin?')"><i class="icon-trash"></i></a>
                          </td>
                        </tr>
                      <?php
                      $no++;
                      } ?>
                  </tbody>
                </table>

              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- /basic datatable -->
    </div>
    <!-- /dashboard content -->
