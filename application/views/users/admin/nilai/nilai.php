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
          <h6 class="panel-title"><i class="icon-star-full2"></i> Data Nilai Magang <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
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
                  <a href="users/nilai_admin/t" class="btn btn-primary my-2"><i class="icon-plus22"></i> Isi Nilai</a>

                <table class="table datatable-basic" width="100%">
                  <thead>
                    <tr>
                      <th width="30px;">No.</th>
                      <th>NIM</th>
                      <th>Nama Mahasiswa</th>
                      <th>Kelas</th>
                      <th>Peringkat Huruf</th>
                      <th>Nilai</th>
                      <th class="text-center" width="50">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $no = 1;
                      foreach ($v_nilai->result() as $baris) {
                        $cek_kelas = $this->db->get_where('tbl_kelas', "kdkelas='$baris->kdkelas'")->row();
                        if ($cek_kelas->kdkelas == '') {
                            $kelas = '-';
                        }else{
                            $kelas =$cek_kelas->nama;
                        }
                        $cek_jurusan = $this->db->get_where('tbl_jurusan', "kdjurusan='$cek_kelas->kdjurusan'")->row();
                        if ($cek_jurusan->kdjurusan == '') {
                            $jurusan = '-';
                        }else{
                            $jurusan =$cek_jurusan->nama;
                        }
                      ?>
                        <tr>
                          <td><?php echo $no.'.'; ?></td>
                          <td><?php echo $baris->nim; ?></td>
                          <td><?php echo $baris->nama_lengkap; ?></td>
                          <td><?php echo $kelas; ?> <?php echo $jurusan; ?></td>
                          <td><?php echo $baris->keterangan; ?></td>
                          <td><?php echo $baris->nilai; ?></td>
                          <td>
                            <div class="btn-group">
                              <a href="users/nilai_admin/d/<?php echo $baris->kdnilai; ?>" class="btn btn-info btn-sm"><i class="icon-eye"></i></a>
                              <a href="users/nilai_admin/h/<?php echo $baris->kdnilai; ?>" class="btn btn-danger btn-sm"><i class="icon-bin"></i></a>
                            </div>

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
