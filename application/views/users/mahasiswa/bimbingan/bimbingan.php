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
          <h6 class="panel-title"><i class="icon-book3"></i> Data Bimbingan <b><?php echo ucwords($user->row()->nama_lengkap); ?></b> <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
          <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="close"></a></li>
            </ul>
           </div>
        </div>

        <div class="panel-body">
                <?php
                echo $this->session->flashdata('msg');
                ?>

                <table class="table datatable-basic" width="100%">
                  <thead>
                    <tr>
                      <th width="30px;">No.</th>
                      <th>NIM</th>
                      <th>Nama Mahasiswa</th>
                      <th>Kelas</th>
                      <th>Judul</th>
                      <th class="text-center" width="30">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = 1;
                      foreach ($v_bimbingan->result() as $baris) {
                        $cek_status = $this->db->get_where('tbl_penempatan', "kdpenempatan='$baris->kdpenempatan'")->row()->status;
                        if ($cek_status == 'diterima') {
                          $cek_kelas = $this->db->get_where('tbl_kelas', "kdkelas='$baris->kdkelas'")->row();
                          if ($cek_kelas->kdkelas == '') {
                              $kelas = '-';
                          }else{
                              $kelas =$cek_kelas->nama;
                          }
                          $cek_jurusan = $this->db->get_where('tbl_jurusan', "kdjurusan='$cek_kelas->kdkelas'")->row();
                          if ($cek_jurusan->kdjurusan == '') {
                              $jurusan = '-';
                          }else{
                              $jurusan =$cek_jurusan->nama;
                          }
                          $cek_pemb = $this->db->get_where('tbl_pemb', "kdpemb='$baris->kdpemb'")->row();
                          if ($cek_pemb == '') {
                              $nama_pemb = '-';
                          }else{
                              $nama_pemb =$cek_pemb->nama_lengkap;
                          }
                      ?>
                          <tr>
                            <td><?php echo $no.'.'; ?></td>
                            <td><?php echo $baris->nim; ?></td>
                            <td><?php echo $baris->nama_lengkap; ?></td>
                            <td><?php echo $kelas; ?> <?php echo $jurusan; ?></td>
                            <td><?php echo $baris->judul; ?></td>
                            <td>
                              <a href="users/bimbingan_mahasiswa/d/<?php echo $baris->kdbimbingan; ?>" class="btn btn-info btn-xs"><i class="icon-eye"></i></a>
                            </td>
                          </tr>
                      <?php
                      $no++;
                        }
                      } ?>
                  </tbody>
                </table>


          </div>
        </div>
      </div>

      <!-- /basic datatable -->
    </div>
    <!-- /dashboard content -->
