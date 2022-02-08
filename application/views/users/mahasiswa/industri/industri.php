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
          <h6 class="panel-title"><i class="icon-office"></i> Data Industri <b><?php echo ucwords($user->row()->nama_lengkap); ?></b> <a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
          <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="close"></a></li>
            </ul>
           </div>
        </div>

        <div class="panel-body">
             
        <table class="table datatable-basic table-striped" width="100%">
              <thead>
                <tr>
                  <th width="30px;">No.</th>
                  <th width="50">Logo</th>
                  <th>Nama Industri</th>
                  <th>Bidang Usaha</th>
                  <th>Wilayah</th>
                  <th>Telpon</th>
                  <th class="text-center" width="70">Aksi</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                  $no = 1;
                  foreach ($v_industri->result() as $baris) {
                  ?>
                    <tr>
                      <td><?php echo $no.'.'; ?></td>
                      <td><img src="foto/industri/<?php echo $baris->foto; ?>" alt="industri" width="50" height="50"></td>
                      <td><?php echo $baris->nama_industri; ?></td>
                      <td><?php echo $baris->bidang_kerja; ?></td>
                      <td><?php echo $baris->wilayah; ?></td>
                      <td><?php echo $baris->telepon; ?></td>
                      <td>
                        <a href="users/industri_view/<?php echo $baris->kdind; ?>" class="btn btn-info btn-xs">Detail</a>
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
