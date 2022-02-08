<?php
$sub_menu3 = strtolower($this->uri->segment(3));
$user = $v_bimbingan->row();
$cek_status = $this->db->get_where('tbl_penempatan', "kdpenempatan='$user->kdpenempatan'")->row()->status;
if ($cek_status != 'diterima') {
  redirect('web/error_not_found');
}?>
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
                <legend class="text-bold"><i class="icon-user"></i> Detail Mahasiswa <?php echo ucwords($user->nama_lengkap); ?></legend>
                <?php
                echo $this->session->flashdata('msg');
                ?>
                <center>
                  <img src="foto/<?php if ($user->foto == '') { echo'default.png'; }else{echo "mahasiswa/$user->foto";}?>" alt="<?php echo $user->nama_lengkap; ?>" class="img-circle" width="176" height="176">
                  <br>
                  <b>
                    <?php echo $user->nim; ?> <br>
                    <?php echo ucwords($user->nama_lengkap); ?>
                  </b>
                </center>
                <hr>
                  <table width="100%" border=0>
                      <tr>
                        <th width="30%"><b>NIM</b></th>
                        <td width="2%"><b>:</b>&nbsp;</td>
                        <td> <b><?php echo $user->nim; ?></b></td>
                      </tr>
                      <tr>
                        <th><b>Nama Lengkap</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td> <?php echo ucwords($user->nama_lengkap); ?></td>
                      </tr>
                      <tr>
                        <th><b>Telp</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td> <?php echo $user->telp; ?></td>
                      </tr>
                      <tr>
                        <th><b>Kelas</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td>
                          <?php $kelas = $this->db->get_where('tbl_kelas', "kdkelas='$user->kdkelas'")->row();
                          echo $kelas->nama; ?>
                        </td>
                      </tr>
                      <tr>
                        <th><b>Jurusan</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td>
                          <?php $jurusan = $this->db->get_where('tbl_jurusan', "kdjurusan='$kelas->kdjurusan'")->row();
                          echo $jurusan->nama; ?>
                        </td>
                      </tr>
                      <tr>
                        <th><b>Nama Pembimbing</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td>
                          <b>
                          <?php 
                             $nama_pemb = $this->db->select('tbl_pemb.nama_lengkap')->join('tbl_pemb','tbl_bimbingan.nip=tbl_pemb.nip')->get_where('tbl_bimbingan', ['nim'=>$user->nim])->row();
                             if($nama_pemb){
                               $nama_pemb=$nama_pemb->nama_lengkap;
                             }else{
                               $nama_pemb='-';
                             }
                            ?>
                          <?php 
                          echo $nama_pemb; ?>
                          </b>
                        </td>
                      </tr>
                  </table>

                  <hr>
                  <h3 align="center">Keterangan Magang</h3>
                  <hr>
                  <table width="100%" border=0>
                      <tr>
                        <th><b>Tanggal</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td> <?php echo users::format($user->tanggal); ?></td>
                      </tr>
                      <tr>
                        <th width="30%"><b>Judul</b></th>
                        <td width="2%"><b>:</b>&nbsp;</td>
                        <td> <b><?php echo strtoupper($user->judul); ?></b></td>
                      </tr>
                      <tr>
                        <th><b>Catatan</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td> <?php echo $user->catatan; ?></td>
                      </tr>
                      <tr>
                        <th><b>File</b></th>
                        <td><b>:</b>&nbsp;</td>
                        <td>
                          <?php
                          if ($user->file != '') {?>
                             <a href="<?php echo $user->file; ?>"><?php echo $user->file; ?></a>
                          <?php
                          }else{ ?>
                            -
                          <?php
                          } ?>
                        </td>
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
