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
          <h6 class="panel-title"><i class="icon-book3"></i> Data Bimbingan Mahasiswa <b><?php echo ucwords($user->row()->nama_lengkap); ?></b> <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
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
                      <th>Nama Pembimbing</th>
                      <th class="text-center" width="30">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = 1;
                      foreach ($v_mahasiswa->result() as $baris) {
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
                       $nama_pemb = $this->db->join('tbl_pemb','tbl_bimbingan.nip=tbl_pemb.nip')->get_where('tbl_bimbingan', "nim='$baris->nim'")->row()->nama_lengkap;
                        if ($nama_pemb == '') {
                            $nama_pemb = '-';
                        }
      
                      ?>
                        <tr>
                          <td><?php echo $no.'.'; ?></td>
                          <td><?php echo $baris->nim; ?></td>
                          <td><?php echo $baris->nama_lengkap; ?></td>
                          <td><?php echo $kelas; ?> <?php echo $jurusan; ?></td>
                          <td><?php echo $nama_pemb; ?></td>
                          <td>
                            <a href="users/d_mahasiswa/d/<?php echo $baris->nim; ?>" class="btn btn-info btn-xs"><i class="icon-eye"></i></a>
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

      <!-- /basic datatable -->
    </div>
    <!-- /dashboard content -->
