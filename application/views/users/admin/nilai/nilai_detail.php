<?php
error_reporting(0);
$sub_menu3 = strtolower($this->uri->segment(3));
$user = $v_nilai->row();


$cek_industri = $this->db->get_where('tbl_industri', "kdind='$user->kdind'")->row();
if ($cek_industri->kdind == '') {
    $nama_industri = '-';
    $bidang_kerja  = '-';
}else{
    $nama_industri = $cek_industri->nama_industri;
    $bidang_kerja  = $cek_industri->bidang_kerja;
}
?>
 <?php 
                             $nama_pemb = $this->db->select('tbl_pemb.nama_lengkap,tbl_pemb.nip')->join('tbl_pemb','tbl_bimbingan.nip=tbl_pemb.nip')->get_where('tbl_bimbingan', ['nim'=>$user->nim])->row();
                             if($nama_pemb){
                               $nip=$nama_pemb->nip;
                               $nama_pemb=$nama_pemb->nama_lengkap;
                             }else{
                               $nama_pemb='-';
                             }
                            ?>
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
                <legend class="text-bold"><i class="icon-user"></i> Detail Nilai <?php echo $user->nama_lengkap; ?></legend>
                <?php
                echo $this->session->flashdata('msg');
                ?>
                <center>
                  <img src="foto/<?php if ($user->foto == '') { echo'default.png'; }else{echo "mahasiswa/$user->foto";}?>" alt="<?php echo $user->nama_lengkap; ?>" class="img-circle" width="176" height="176">
                  <br>
                  <b>
                    <?php echo $user->nis; ?> <br>
                    <?php echo $user->nama_lengkap; ?>
                  </b>
                </center>
                <hr>

                  <table width="100%" border=0>
                      <tr>
                        <th width="30%"><b>Telp</b></th>
                        <td width="2%"><b>:</b></td>
                        <td>&nbsp; <?php echo $user->telp; ?></td>
                      </tr>
                      <tr>
                        <th><b>Kelas</b></th>
                        <td><b>:</b></td>
                        <td>&nbsp;
                          <?php $kelas = $this->db->get_where('tbl_kelas', "kdkelas='$user->kdkelas'")->row();
                          echo $kelas->nama; ?>
                        </td>
                      </tr>
                      <tr>
                        <th><b>Jurusan</b></th>
                        <td><b>:</b></td>
                        <td>&nbsp;
                          <?php $jurusan = $this->db->get_where('tbl_jurusan', "kdjurusan='$kelas->kdjurusan'")->row();
                          echo $jurusan->nama; ?>
                        </td>
                      </tr>
                      <tr>
                        <th><b>NIP Pembimbing</b></th>
                        <td><b>:</b></td>
                        <td>&nbsp; <b><?php echo $nip; ?></b></td>
                      </tr>
                      <tr>
                        <th><b>Nama Pembimbing</b></th>
                        <td><b>:</b></td>
                        <td>&nbsp; <b><?php echo ucwords($nama_pemb); ?></b></td>
                      </tr>
                  </table>

                <hr>

                  <h3 align="center">Penempatan Prakerin</h3>
                  <hr>
                  <table width="100%" border=0>
                      <tr>
                        <th width="30%"><b>Nama Industri</b></th>
                        <td width="2%"><b>:</b>&nbsp; </td>
                        <td> <b><?php echo $nama_industri; ?></b></td>
                      </tr>
                      <tr>
                        <th><b>Bidang Kerja</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo ucwords($bidang_kerja); ?></td>
                      </tr>
                  </table>

                <hr>

                  <h3 align="center">Penilai Prakerin</h3>
                  <hr>
                  <table width="100%" border=0>
                      <tr>
                        <th width="30%"><b>Keterangan</b></th>
                        <td width="2%"><b>:</b>&nbsp; </td>
                        <td> <b><?php echo ucwords($user->keterangan); ?></b></td>
                      </tr>
                      <tr>
                        <th><b>Nilai</b></th>
                        <td><b>:</b>&nbsp; </td>
                        <td> <?php echo $user->nilai; ?></td>
                      </tr>
                  </table>

                <hr>
                  <a href="javascript:history.back()" class="btn btn-default"><< Kembali</a>

              </fieldset>


            </div>

        </div>
      </div>
    </div>
    <!-- /dashboard content -->
