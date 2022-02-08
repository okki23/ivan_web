<?php
$sub_menu3 = strtolower($this->uri->segment(3)); 
$v_industri = $v_industri->row(); ?>

<!-- Main content -->
<div class="content-wrapper">
  <!-- Content area -->
  <div class="content">
    <!-- Dashboard content -->
    <div class="row">
      <!-- Basic datatable -->
      <div class="panel panel-flat">
        <div class="panel-heading">
          <h6 class="panel-title"><i class="icon-office"></i>  Detail Industri <?php echo ucwords($user->nama_industri); ?></h6>
          <div class="heading-elements">
            <ul class="icons-list">
                <li> <a class="btn btn-success" href="<?=base_url('users/industri_view')?>"><i class="icon-undo"></i></a></a></li>
   
            </ul>
           </div>
        </div>

        <div class="panel-body">
        <fieldset class="content-group">
            
                <center>
                  <img src="foto/<?php if ($v_industri->foto == '') { echo'default.png'; }else{echo "industri/$v_industri->foto";}?>" alt="<?php echo $user->nama_industri; ?>" class="img-circle" width="176" height="176">
                  <br>
                  <b>
                    Logo Industri <br>
                     <?php echo ucwords($v_industri->nama_industri); ?>
                  </b>
                </center>
                <hr>
                <table width="100%" border=0>
                    <tr>
                      <th width="100"><b>Nama Industri</b></th>
                      <td width="2%"><b>:</b>&nbsp; </td>
                      <td> <b><?php echo $v_industri->nama_industri; ?></b></td>
                    </tr>
                    <tr>
                      <th><b>Bidang Kerja</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->bidang_kerja); ?></td>
                    </tr>
                    <tr>
                      <th><b>Deskripsi</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->deskripsi); ?></td>
                    </tr>
                    <tr>
                      <th><b>Alamat Industri</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->alamat_industri); ?></td>
                    </tr>
                    <tr>
                      <th><b>Wilayah</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->wilayah); ?></td>
                    </tr>
                    <tr>
                      <th><b>Telepon</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->telepon); ?></td>
                    </tr>
                    <tr>
                      <th><b>Website</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->website); ?></td>
                    </tr>
                    <tr>
                      <th><b>Email</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->email); ?></td>
                    </tr>
                    <tr>
                      <th><b>Syarat</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->syarat); ?></td>
                    </tr>
                    <tr>
                      <th><b>Kuota</b></th>
                      <td><b>:</b>&nbsp; </td>
                      <td> <?php echo ucwords($v_industri->kuota); ?></td>
                    </tr>
                </table>

              <hr>

            </fieldset>
          </div>
        </div>
      </div>

      <!-- /basic datatable -->
    </div>
    <!-- /dashboard content -->
