<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Users extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// load library Excell_Reader
		// $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
	}



	public function index()
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
	
	
		if (!isset($ceks)) {
			redirect('web/login');
		} else {
			if ($level == 'admin' || $level =='kaprodi') {
				redirect('users/profile');
			} elseif ($level == 'pembimbing') {
				redirect('users/profile');
			} else {
				redirect('users/status_prakerin');
			}
		}
	}

	public static function format($date)
	{
		$str = explode('-', $date);
		$bulan = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);
		return $str['2'] . " " . $bulan[$str[1]] . " " . $str[0];
	}

	public function profile()
	{
		$ceks    = $this->session->userdata('prakrin_smk@Proyek-2017');
		$level   = $this->session->userdata('level@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			$this->db->order_by('nama', 'ASC');
			$data['v_kelas'] 		= $this->db->get('tbl_kelas');
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan']  = $this->db->get('tbl_jurusan');
			if ($level == 'admin' || $level=='kaprodi') {
				$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
				$data['email']		 = '';
				$data['level']		 = $level;
			} elseif ($level == 'pembimbing') {
				$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
				$data['email']		 = '';
				$data['level']		 = 'Pembimbing';
			} else {
				$data['user']   	 = $this->db->get_where('tbl_mahasiswa', "nim='$ceks'");
				$data['email']		 = '';
				$data['level']		 = 'Mahasiswa';
			}
			// $data['level_users']  = $this->Mcrud->get_level_users();
			$data['judul_web'] 		= "Profil | Aplikasi SIM MAGANG";

			$this->load->view('users/header', $data);
			$this->load->view('users/profile', $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnupdate'])) {

				if ($level != 'pembimbing') {

					if ($level == 'mahasiswa') {
						$lokasi = 'foto/mahasiswa';
					} else {
						$lokasi = 'foto';
					}

					$file_size = 1024 * 3; // 3 MB
					$this->upload->initialize(array(
						"file_type"     => "image/jpeg",
						"upload_path"   => "./$lokasi",
						"allowed_types" => "jpg|jpeg|png|gif|bmp",
						"max_size" => "$file_size"
					));

					if (!$this->upload->do_upload('avatar-1')) {
						$foto = $data['user']->row()->foto;
					} else {
						if ($data['user']->row()->foto != 'default.png') {
							unlink("$lokasi/" . $data['user']->row()->foto);
						}
						$gbr = $this->upload->data();

						$filename = $gbr['file_name'];
						$foto = preg_replace('/ /', '_', $filename);
					}
				}

				if ($level != 'mahasiswa') {
					$username	    	= htmlentities(strip_tags($this->input->post('username')));
				} else {
					$username				= $ceks;
				}
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				if ($level == 'admin' || $level=='kaprodi') {
					$identitas		= htmlentities(strip_tags($this->input->post('identitas')));
					$status	 		  = htmlentities(strip_tags($this->input->post('status')));
				} elseif ($level == 'pembimbing') {
					$kdjurusan		= htmlentities(strip_tags($this->input->post('jurusan')));
					$nip	 		  	= htmlentities(strip_tags($this->input->post('nip')));
					$wilayah	 		= htmlentities(strip_tags($this->input->post('wilayah')));

					if ($id_user != $nip) {
						$cek_nip   = $this->db->get_where("tbl_pemb", array('nip' => "$nip"));
						if ($cek_nip->num_rows() != 0) {
							$query  = 'gagal';
							$pesan  = "NIP '$nip'";
						}
					}
				} else {
					$kdkelas		  = htmlentities(strip_tags($this->input->post('kelas')));
					$telp	 		    = htmlentities(strip_tags($this->input->post('telp')));
				}

				if ($level == 'admin'||$level=='kaprodi') {
					$data = array(
						'username'	    => $username,
						'nama_lengkap'	=> $nama_lengkap,
						'identitas'			=> $level,
						'status'				=> $level,
						'foto'				  => $foto
					);
					$this->Mcrud->update_user(array('id_user' => $id_user), $data);
				} elseif ($level == 'pembimbing') {
					$data = array(
						'username'	    => $username,
						'nama_lengkap'	=> $nama_lengkap,
						'kdjurusan'			=> $kdjurusan,
						'nip'						=> $nip,
						'wilayah'				=> $wilayah
					);
					$this->db->update('tbl_pemb', $data, array('nip' => $id_user));
				} else {
					$data = array(
						'nama_lengkap'	=> $nama_lengkap,
						'kdkelas'				=> $kdkelas,
						'telp'				  => $telp,
						'foto'				  => $foto
					);
					$this->db->update('tbl_mahasiswa', $data, array('nim' => $ceks));
				}




				if ($level == 'pembimbing') {
					$id_user = $nip;
				} elseif ($level == 'mahasiswa') {
					$id_user = $username;
				}
				$this->session->has_userdata('id_user@Proyek-2017');
				$this->session->set_userdata('id_user@Proyek-2017', "$id_user");

				$this->session->has_userdata('level@Proyek-2017');
				$this->session->set_userdata('level@Proyek-2017', "$level");

				$this->session->set_flashdata(
					'msg',
					'
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Sukses!</strong> Profile berhasil diperbarui.
										</div>'
				);
				redirect('users/profile');
			}


			if (isset($_POST['btnupdate2'])) {
				$password 	= htmlentities(strip_tags($this->input->post('password')));
				$password2 	= htmlentities(strip_tags($this->input->post('password2')));

				if ($password != $password2) {
					$this->session->set_flashdata(
						'msg2',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Password tidak cocok.
									</div>'
					);
				} else {
					$data = array(
						'password'	=> md5($password)
					);
					if ($level == 'admin' || $level=='kaprodi') {
						$this->Mcrud->update_user(array('username' => $ceks), $data);
					} elseif ($level == 'pembimbing') {
						$this->db->update('tbl_pemb', $data, array('username' => $ceks));
					} else {
						$this->db->update('tbl_mahasiswa', $data, array('nim' => $ceks));
					}

					$this->session->set_flashdata(
						'msg2',
						'
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Sukses!</strong> Password berhasil diperbarui.
										</div>'
					);
				}
				redirect('users/profile');
			}
		}
	}


	public function data_info($id_label = '')
	{
		$this->db->join('tbl_label', 'tbl_label.kdlabel=tbl_info.kdlabel');
		if ($id_label != 0) {
			$this->db->where('tbl_info.kdlabel', $id_label);
		}
		$this->db->order_by('kdinfo', 'DESC');
		$data = $this->db->get('tbl_info');
?>
		<!-- <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script> -->

		<!-- <script type="text/javascript" src="assets/js/core/app.js"></script> -->
		<script type="text/javascript" src="assets/js/pages/datatables_basic.js"></script>

		<table class="table datatable-basic" width="100%">
			<thead>
				<tr>
					<th width="30px;">No.</th>
					<th>Judul</th>
					<th>Tanggal</th>
					<th>Label</th>
					<th class="text-center" width="180">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				foreach ($data->result() as $row) { ?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $row->judul; ?></td>
						<td><?php echo $this->format($row->tanggal); ?></td>
						<td><?php echo $row->nama_label; ?></td>
						<td>
							<a href="web/info/<?php echo $row->kdinfo; ?>" class="btn btn-info" target="_blank"><i class="icon-eye"></i></a>
							<a href="users/info/e/<?php echo $row->kdinfo; ?>" class="btn btn-success"><i class="icon-pencil5"></i></a>
							<a href="users/info/h/<?php echo $row->kdinfo; ?>" class="btn btn-danger" onclick="return confirm('Anda Yakin??')"><i class="icon-trash"></i></a>
						</td>
					</tr>
				<?php
					$no++;
				} ?>

			</tbody>
		</table>
	<?php
	}

	public function data_file()
	{
		$this->db->order_by('kdfile', 'DESC');
		$v_file = $this->db->get('tbl_file');
	?>

		<script type="text/javascript" src="assets/js/pages/datatables_basic.js"></script>
		<table class="table datatable-basic" width="100%">
			<thead>
				<tr>
					<th width="30px;">No.</th>
					<th>Judul</th>
					<th>Tanggal</th>
					<th>Nama File</th>
					<th class="text-center" width="180">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				foreach ($v_file->result() as $row) { ?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $row->judul; ?></td>
						<td><?php echo $this->format($row->tanggal); ?></td>
						<td><?php echo $row->nama; ?></td>
						<td>
							<!-- <a href="#" class="btn btn-info"><i class="icon-eye"></i></a> -->
							<a href="users/info/h_file/<?php echo $row->kdfile; ?>" class="btn btn-danger" onclick="return confirm('Anda Yakin??')"><i class="icon-trash"></i></a>
						</td>
					</tr>
				<?php
					$no++;
				} ?>

			</tbody>
		</table>
<?php
	}

	public function info($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {
			
			if(in_array($level,['pembimbing','mahasiswa'])) {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('kdlabel', 'ASC');
			$data['v_label'] 	 = $this->db->get('tbl_label');
			$data['email']		 = '';
			$data['level']		 = $level;

			if ($aksi == 't') {

				$p = 'info/info_tambah';
				$data['judul_web'] = "Tambah Informasi | SIM MAGANG";
			} elseif ($aksi == 'e') {

				$p = 'info/info_edit';
				$data['judul_web'] = "Edit Informasi | SIM MAGANG ";
				$data['v_info']		 = $this->db->get_where('tbl_info', "kdinfo='$id'")->row();

				if ($data['v_info']->kdinfo == '') {
					redirect('web/error_not_found');
				}
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_info', "kdinfo='$id'")->row();
				if ($cek_data->kdinfo == '') {
					redirect('web/error_not_found');
				}

				unlink("$cek_data->gambar");
				$this->db->delete('tbl_info', "kdinfo='$id'");

				$this->session->set_flashdata(
					'msg',
					'
					 <div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times; &nbsp;</span>
							</button>
							<strong>Sukses!</strong> Informasi berhasil dihapus.
					 </div>'
				);
				redirect('users/info');
			} elseif ($aksi == 'h_file') {

				$cek_data = $this->db->get_where('tbl_file', "kdfile='$id'")->row();
				if ($cek_data->kdfile == '') {
					redirect('web/error_not_found');
				}

				unlink("lampiran/file/$cek_data->nama");
				$this->db->delete('tbl_file', "kdfile='$id'");

				$this->session->set_flashdata(
					'msg_file',
					'
					 <div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times; &nbsp;</span>
							</button>
							<strong>Sukses!</strong> File berhasil dihapus.
					 </div>'
				);
				redirect('users/info/u_f');
			} else {

				$p = 'info/info';
				$data['judul_web'] = "Kelola Informasi | SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view('users/admin/' . $p, $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnsimpan'])) {
				$label 			= htmlentities(strip_tags($this->input->post('label')));
				$tanggal 		= htmlentities(strip_tags($this->input->post('tanggal')));
				$judul 			= htmlentities(strip_tags($this->input->post('judul')));
				$deskripsi 	= $this->input->post('deskripsi');

				$file_size = 5500; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./foto/info/",
					"allowed_types" => "jpg|jpeg|png|gif|bmp",
					"max_size" => "$file_size"
				));

				if ($aksi == 't') {
					if (!$this->upload->do_upload('gambar')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$this->session->set_flashdata(
							'msg',
							'
										 <div class="alert alert-warning alert-dismissible" role="alert">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times; &nbsp;</span>
												</button>
												<strong>Gagal!</strong> ' . $error . '.
										 </div>'
						);

						redirect('users/info/t');
					} else {
						$gbr = $this->upload->data();
						$filename = $gbr['file_name'];
						$filename = "foto/info/" . $filename;
						$foto 		= preg_replace('/ /', '_', $filename);

						$data = array(
							'kdlabel'		  => $label,
							'tanggal'			=> date('Y-m-d', strtotime($tanggal)),
							'judul'				=> $judul,
							'deskripsi'	  => $deskripsi,
							'gambar'			=> $foto,
							'penulis'			=> $data['user']->row()->nama_lengkap
						);
						$this->db->insert('tbl_info', $data);

						$this->session->set_flashdata(
							'msg_t',
							'
										 <div class="alert alert-success alert-dismissible" role="alert">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times; &nbsp;</span>
												</button>
												<strong>Sukses!</strong> Informasi berhasil ditambahkan.
										 </div>'
						);
					}
				} elseif ($aksi == 'e') {
					$cek_foto = $data['v_info']->gambar;
					if ($_FILES['gambar']['error'] <> 4) {
						if (!$this->upload->do_upload('gambar')) {
							$error = $this->upload->display_errors('<p>', '</p>');
							$update = "";
						} else {
							
							unlink("$cek_foto");
							$gbr = $this->upload->data();
							$filename = $gbr['file_name'];
							$filename = "foto/info/" . $filename;
							$foto 		= preg_replace('/ /', '_', $filename);

							$update = "yes";
						}
					} else {
						$foto   = $cek_foto;
						$update = "yes";
					}

					if ($update = "yes") {
						$data = array(
							'kdlabel'		  => $label,
							'tanggal'			=> date('Y-m-d', strtotime($tanggal)),
							'judul'				=> $judul,
							'deskripsi'	  => $deskripsi,
							'gambar'			=> $foto
						);
						$this->db->update('tbl_info', $data, array('kdinfo' => $id));

						$this->session->set_flashdata(
							'msg_t',
							'
													 <div class="alert alert-success alert-dismissible" role="alert">
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times; &nbsp;</span>
															</button>
															<strong>Sukses!</strong> Informasi berhasil diperbarui.
													 </div>'
						);
					} else {
						$this->session->set_flashdata(
							'msg',
							'
													 <div class="alert alert-warning alert-dismissible" role="alert">
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times; &nbsp;</span>
															</button>
															<strong>Gagal!</strong> ' . $error . '.
													 </div>'
						);
						redirect('users/info/e');
					}
				}
				redirect('users/info');
			}



			if (isset($_POST['btnupfile'])) {
				$tanggal 		= htmlentities(strip_tags($this->input->post('tanggal')));
				$judul 			= htmlentities(strip_tags($this->input->post('judul')));
				$keterangan = htmlentities(strip_tags($this->input->post('keterangan')));

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./lampiran/file/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				if (!$this->upload->do_upload('file')) {
					$error = $this->upload->display_errors('<p>', '</p>');
					$this->session->set_flashdata(
						'msg_file',
						'
										 <div class="alert alert-warning alert-dismissible" role="alert">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times; &nbsp;</span>
												</button>
												<strong>Gagal!</strong> ' . $error . '.
										 </div>'
					);
				} else {
					$file = $this->upload->data();
					$filename = $file['file_name'];
					$file 		= preg_replace('/ /', '_', $filename);

					$data = array(
						'tanggal'		 => date('Y-m-d', strtotime($tanggal)),
						'judul'			 => $judul,
						'keterangan' => $keterangan,
						'nama'			 => $file,
						'share'			 => ''
					);
					$this->db->insert('tbl_file', $data);

					$this->session->set_flashdata(
						'msg_file',
						'
										 <div class="alert alert-success alert-dismissible" role="alert">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times; &nbsp;</span>
												</button>
												<strong>Sukses!</strong> File berhasil ditambahkan.
										 </div>'
					);
				}

				redirect('users/info/u_f');
			}
		}
	}


	public function j_k($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if(in_array($level,['pembimbing','mahasiswa'])) {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan'] 	 = $this->db->get('tbl_jurusan');
			$this->db->order_by('nama', 'ASC');
			$data['v_kelas'] 	 = $this->db->get('tbl_kelas');
			$data['email']		 = '';
			$data['level']		 = $level;

			if ($aksi == 'e_kelas') {
				$p = "j_k/j_k_edit";

				$data['query'] = $this->db->get_where("tbl_kelas", "kdkelas = '$id'")->row();
				$data['judul_web'] 	  = "Edit Kelas | SIM MAGANG";
			} elseif ($aksi == 'e_jurusan') {
				$p = "j_k/j_k_edit";

				$data['query'] = $this->db->get_where("tbl_jurusan", "kdjurusan = '$id'")->row();
				$data['judul_web'] 	  = "Edit Jurusan | SIM MAGANG";
			} elseif ($aksi == 'h_kelas') {
				$this->Mcrud->delete_kelas_by_id($id);
				$this->session->set_flashdata(
						'msg_kelas',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> jurusan berhasil dihapus.
							</div>'
					);
				redirect('users/j_k/');
			}elseif ($aksi == 'h_jurusan') {
				$this->Mcrud->delete_jurusan_by_id($id);
				$this->session->set_flashdata(
						'msg_jurusan',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Jurusan berhasil dihapus.
							</div>'
					);
				redirect('users/j_k/');
			}
			 else {
				$p = "j_k/j_k";
				$data['judul_web'] 	  = "Jurusan & Fakultas | SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {

				$jurusan   	 	= htmlentities(strip_tags($this->input->post('jurusan')));
				if (!empty($_POST['kelas'])) {
					$kelas   	 	= htmlentities(strip_tags($this->input->post('kelas')));
					$cek_data = $this->db->get_where("tbl_kelas", "nama = '$kelas'")->num_rows();
					if ($cek_data != 0) {
						$this->session->set_flashdata(
							'msg_kelas',
							'
										<div class="alert alert-warning alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Gagal!</strong> Nama Jurusan "' . $kelas . '" Sudah ada.
										</div>'
						);
					} else {
						$data = array(
							'nama'	 	  => $kelas,
							'kdjurusan' => $jurusan
						);
						$this->db->insert('tbl_kelas', $data);

						$this->session->set_flashdata(
							'msg_kelas',
							'
												<div class="alert alert-success alert-dismissible" role="alert">
													 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
													 </button>
													 <strong>Sukses!</strong> Jurusan berhasil ditambah.
												</div>'
						);
					}
					redirect('users/j_k');
				} else {
					$cek_data = $this->db->get_where("tbl_jurusan", "nama = '$jurusan'")->num_rows();
					if ($cek_data != 0) {
						$this->session->set_flashdata(
							'msg_jurusan',
							'
										<div class="alert alert-warning alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
											 </button>
											 <strong>Gagal!</strong> Nama Fakultas "' . $jurusan . '" Sudah ada.
										</div>'
						);
					} else {
						$data = array(
							'nama'	 	 => $jurusan
						);
						$this->db->insert('tbl_jurusan', $data);

						$this->session->set_flashdata(
							'msg_jurusan',
							'
												<div class="alert alert-success alert-dismissible" role="alert">
													 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
													 </button>
													 <strong>Sukses!</strong> Fakultas berhasil ditambah.
												</div>'
						);
					}
					redirect('users/j_k/tbl_jurusan');
				}
			}

			if (isset($_POST['btnupdate'])) {
				$jurusan   	 	= htmlentities(strip_tags($this->input->post('jurusan')));
				if (!empty($_POST['kelas'])) {
					$kelas   	 	= htmlentities(strip_tags($this->input->post('kelas')));
					$data = array(
						'nama'	 	  => $kelas,
						'kdjurusan' => $jurusan
					);
					$this->db->update('tbl_kelas', $data, "kdkelas='$id'");
					$this->session->set_flashdata(
						'msg_kelas',
						'
									<div class="alert alert-success alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Sukses!</strong> Kelas berhasil ditambah.
									</div>'
					);

					redirect('users/j_k');
				} else {
					$data = array(
						'nama'	 	 => $jurusan
					);
					$this->db->update('tbl_jurusan', $data, "kdjurusan='$id'");
					$this->session->set_flashdata(
						'msg_jurusan',
						'
									<div class="alert alert-success alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Sukses!</strong> Jurusan berhasil ditambah.
									</div>'
					);

					redirect('users/j_k/tbl_jurusan');
				}
			}
		}
	}



	public function pengguna($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if(in_array($level,['pembimbing','mahasiswa'])) {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan'] 	 = $this->db->get('tbl_jurusan');
			$this->db->order_by('nama', 'ASC');
			$data['v_kelas'] 	 = $this->db->get('tbl_kelas');
			$this->db->order_by('nama_lengkap', 'ASC');
			$data['v_pemb'] 	 = $this->db->get('tbl_pemb');
			$data['email']		 = '';
			$data['level']		 = $level;

			if ($aksi == 't_pemb') {
				$p = "pengguna/pengguna_tambah";

				$data['judul_web'] 	  = "Tambah Pembimbing | SIM MAGANG";
			} elseif ($aksi == 't_mahasiswa') {
				$p = "pengguna/pengguna_tambah";

				$data['judul_web'] 	  = "Tambah Mahasiswa |SIM MAGANG";
			} elseif ($aksi == 'd_pemb') {
				$p = "pengguna/pengguna_detail";

				$data['query'] = $this->db->get_where("tbl_pemb", "kdpemb = '$id'")->row();
				$data['judul_web'] 	  = "Detail Pembimbing | SIM MAGANG";
			}elseif ($aksi == 'e_pemb') {
				$p = "pengguna/pengguna_edit";
				$data['query'] = $this->db->get_where("tbl_pemb", "kdpemb = '$id'")->row();
				$data['judul_web'] 	  = "Detail Pembimbing | SIM MAGANG";
			}elseif ($aksi == 'e_mahasiswa') {
				$p = "pengguna/pengguna_edit";
				$data['query'] = $this->db->get_where("tbl_mahasiswa", "nim = '$id'")->row();
				$data['judul_web'] 	  = "Detail Pembimbing | SIM MAGANG";
			}elseif ($aksi == 'd_mahasiswa') {
				$p = "pengguna/pengguna_detail";

				$data['query'] = $this->db->get_where("tbl_mahasiswa", "nim = '$id'")->row();
				$data['judul_web'] 	  = "Detail Mahasiswa | SIM MAGANG";
			} elseif ($aksi == 'h_pemb') {

				$data['query'] = $this->db->get_where("tbl_pemb", "kdpemb = '$id'")->row();

				if ($data['query']->username != '') {
					$this->db->delete('tbl_pemb', "kdpemb='$id'");
					$this->session->set_flashdata(
						'msg_pemb',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Pengguna Pembimbing berhasil dihapus.
							</div>'
					);
				}
				redirect('users/pengguna');
			} elseif ($aksi == 'h_mahasiswa') {

				$data['query'] = $this->db->get_where("tbl_mahasiswa", "nim = '$id'")->row();

				if ($data['query']->nim != '') {
					unlink("foto/mahasiswa/" . $data['query']->foto);
					$this->db->delete('tbl_mahasiswa', "nim='$id'");
					$this->session->set_flashdata(
						'msg_mahasiswa',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Pengguna Mahasiswa berhasil dihapus.
							</div>'
					);
				}
				redirect('users/pengguna/tbl_mahasiswa');
			} else {
				$p = "pengguna/pengguna";

				$data['judul_web'] 	  = "Pengguna | SIM MAGANG";

				$this->db->order_by('kdpemb', 'DESC');
				$data['v_pemb']  = $this->db->get("tbl_pemb");

				$this->db->order_by('nim', 'DESC');
				$data['v_mahasiswa']  = $this->db->get("tbl_mahasiswa");
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {
				$jurusan   		 	= htmlentities(strip_tags($this->input->post('jurusan')));
				$username	 		  = htmlentities(strip_tags($this->input->post('username')));
				$nip	 			= htmlentities(strip_tags($this->input->post('nip')));
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				$wilayah	 			= htmlentities(strip_tags($this->input->post('wilayah')));
				$email	 			= htmlentities(strip_tags($this->input->post('email')));


				$cek_user  = $this->db->get_where("tbl_user", "username = '$username'")->num_rows();
				$cek_pemb  = $this->db->get_where("tbl_pemb", "username = '$username'")->num_rows();
				$cek_nip   = $this->db->get_where("tbl_pemb", "nip = '$nip'")->num_rows();
				$cek_mahasiswa = $this->db->get_where("tbl_mahasiswa", "nim = '$username'")->num_rows();
				if ($cek_user != 0) {
					$this->session->set_flashdata(
						'msg',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Username "' . $username . '" Sudah ada.
									</div>'
					);
				} else {
					if ($cek_pemb != 0) {
						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Gagal!</strong> Username "' . $username . '" Sudah ada.
											</div>'
						);
					} else {
						if ($cek_nip != 0) {
							$this->session->set_flashdata(
								'msg',
								'
												<div class="alert alert-warning alert-dismissible" role="alert">
													 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
													 </button>
													 <strong>Gagal!</strong> NIP "' . $nip . '" Sudah ada.
												</div>'
							);
							redirect('users/pengguna/t_pemb');
						}

						$data = array(
							'username'	 	 => $username,
							'kdjurusan'    => $jurusan,
							'password'	 	 => md5($nip),
							'nip'		 			 => $nip,
							'email'		 			 => $email,
							
							'nama_lengkap' => $nama_lengkap,
							'wilayah' 		 => $wilayah
						);
						$this->db->insert('tbl_pemb', $data);

						$this->session->set_flashdata(
							'msg_pemb',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pengguna Pembimbing berhasil ditambahkan.
											</div>'
						);
						redirect('users/pengguna');
					}
				}
				redirect('users/pengguna/t_pemb');
			}


			if (isset($_POST['btnsimpan2'])) {
				$kelas   		 		= htmlentities(strip_tags($this->input->post('kelas')));
				$nim	 				  = htmlentities(strip_tags($this->input->post('nim')));
				$telp	 					= htmlentities(strip_tags($this->input->post('telp')));
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				$kdpemb	 				= htmlentities(strip_tags($this->input->post('kdpemb')));

				$cek_user  = $this->db->get_where("tbl_user", "username = '$nim'")->num_rows();
				$cek_pemb  = $this->db->get_where("tbl_pemb", "username = '$nim'")->num_rows();
				$cek_mahasiswa = $this->db->get_where("tbl_mahasiswa", "nim = '$nim'")->num_rows();
				if ($cek_user != 0) {
					$this->session->set_flashdata(
						'msg',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Username "' . $nim . '" Sudah ada.
									</div>'
					);
				} else {
					if ($cek_pemb != 0) {
						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Gagal!</strong> Username "' . $nim . '" Sudah ada.
											</div>'
						);
					} else {

						$file_size = 1024 * 5; //5 MB
						$this->upload->initialize(array(
							"upload_path"   => "./foto/mahasiswa/",
							"allowed_types" => "*",
							"max_size" => "$file_size"
						));

						if (!$this->upload->do_upload('file')) {
							$error = $this->upload->display_errors('<p>', '</p>');
							$this->session->set_flashdata(
								'msg',
								'
														 <div class="alert alert-warning alert-dismissible" role="alert">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																	<span aria-hidden="true">&times; &nbsp;</span>
																</button>
																<strong>Gagal!</strong> ' . $error . '.
														 </div>'
							);
							redirect('users/pengguna/t_mahasiswa');
						} else {
							$file = $this->upload->data();
							$filename = $file['file_name'];
							$file 		= preg_replace('/ /', '_', $filename);
						}

						$data = array(
							'nim'	 			  => $nim,
							'kdkelas'    	 => $kelas,
							'password'	 	 => md5($nim),
							'nama_lengkap' => $nama_lengkap,
							'telp'				 => $telp,
							'foto'		 		 => $file,
							'kdpemb'		 	 => $kdpemb
						);
						$this->db->insert('tbl_mahasiswa', $data);

						$this->session->set_flashdata(
							'msg_mahasiswa',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pengguna Mahasiswa berhasil ditambahkan.
											</div>'
						);
						redirect('users/pengguna/tbl_mahasiswa');
					}
				}
				redirect('users/pengguna/t_mahasiswa');
			}
			
			if (isset($_POST['btnupdate1'])) {
				$jurusan   		 	= htmlentities(strip_tags($this->input->post('jurusan')));
				$username	 		  = htmlentities(strip_tags($this->input->post('username')));
				$nip	 			= htmlentities(strip_tags($this->input->post('nip')));
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				$wilayah	 			= htmlentities(strip_tags($this->input->post('wilayah')));
				$email	 			= htmlentities(strip_tags($this->input->post('email')));

				$cek_user  = $this->db->get_where("tbl_user", "username = '$username'")->num_rows();
				$cek_pemb  = $this->db->get_where("tbl_pemb", "username = '$username'")->num_rows();
				$cek_nip   = $this->db->get_where("tbl_pemb", "nip = '$nip'")->num_rows();
				$cek_mahasiswa = $this->db->get_where("tbl_mahasiswa", "nim = '$username'")->num_rows();
				if ($cek_user != 0) {
					$this->session->set_flashdata(
						'msg',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Username "' . $username . '" Sudah ada.
									</div>'
					);
				} else {
					if ($cek_pemb != 0) {
						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Gagal!</strong> Username "' . $username . '" Sudah ada.
											</div>'
						);
					} else {
						if($username==''){
							$data = array(
								'kdjurusan'    => $jurusan,
								'password'	 	 => md5($nip),
								'nip'		 			 => $nip,
								'nama_lengkap' => $nama_lengkap,
								'wilayah' 		 => $wilayah,
								'email'=>$email
							);
						}else{
							$data = array(
								'username'	 	 => $username,
								'kdjurusan'    => $jurusan,
								'password'	 	 => md5($nip),
								'nip'		 			 => $nip,
								'nama_lengkap' => $nama_lengkap,
								'wilayah' 		 => $wilayah,
								'email'=>$email
							);
						}

						
						$this->db->update('tbl_pemb',$data,['kdpemb'=>$id]);
						$this->session->set_flashdata(
							'msg_pemb',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pengguna Pembimbing berhasil diupdate.
											</div>'
						);
						redirect('users/pengguna');
					}
				}
				redirect('users/pengguna/e_pemb/'.$id);
			}


			if (isset($_POST['btnupdate2'])) {
				$kelas   		 		= htmlentities(strip_tags($this->input->post('kelas')));
				$nim	 				  = htmlentities(strip_tags($this->input->post('nim')));
				$telp	 					= htmlentities(strip_tags($this->input->post('telp')));
				$nama_lengkap	 	= htmlentities(strip_tags($this->input->post('nama_lengkap')));
				$kdpemb	 				= htmlentities(strip_tags($this->input->post('kdpemb')));

				$cek_user  = $this->db->get_where("tbl_user", "username = '$nim'")->num_rows();
				$cek_pemb  = $this->db->get_where("tbl_pemb", "username = '$nim'")->num_rows();
				$cek_mahasiswa = $this->db->get_where("tbl_mahasiswa", "nim = '$nim'")->num_rows();
				if ($cek_user != 0) {
					$this->session->set_flashdata(
						'msg',
						'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Username "' . $nim . '" Sudah ada.
									</div>'
					);
				} else {
					if ($cek_pemb != 0) {
						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-warning alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Gagal!</strong> Username "' . $nim . '" Sudah ada.
											</div>'
						);
					} else {

						if(empty($_FILES['file'])){
							$data = array(
								'kdkelas'    	 => $kelas,
								'password'	 	 => md5($nim),
								'nama_lengkap' => $nama_lengkap,
								'telp'				 => $telp,
							);
							$this->db->update('tbl_mahasiswa', $data,['nim'=>$nim]);
						}else{
							
							$file_size = 1024 * 5; //5 MB
							$this->upload->initialize(array(
								"upload_path"   => "./foto/mahasiswa/",
								"allowed_types" => "*",
								"max_size" => "$file_size"
							));
							if (!$this->upload->do_upload('file')) {
								$error = $this->upload->display_errors('<p>', '</p>');
								$this->session->set_flashdata(
									'msg',
									'
															 <div class="alert alert-warning alert-dismissible" role="alert">
																	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																		<span aria-hidden="true">&times; &nbsp;</span>
																	</button>
																	<strong>Gagal!</strong> ' . $error . '.
															 </div>'
								);
								redirect('users/pengguna/e_mahasiswa/'.$id);
							} else {
								$file = $this->upload->data();
								$filename = $file['file_name'];
								$file 		= preg_replace('/ /', '_', $filename);
							}
	
							$data = array(
								'kdkelas'    	 => $kelas,
								'nama_lengkap' => $nama_lengkap,
								'telp'				 => $telp,
								'foto'		 		 => $file,
							);
							$this->db->update('tbl_mahasiswa', $data,['nim'=>$id]);
						}
						

						$this->session->set_flashdata(
							'msg_mahasiswa',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pengguna Mahasiswa berhasil diupdate.
											</div>'
						);
						redirect('users/pengguna/');
					}
				}
				redirect('users/pengguna/e_mahasiswa/'.$id);
			}
		}
	}



	public function industri($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if(in_array($level,['pembimbing','mahasiswa'])) {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama_industri', 'ASC');
			$data['v_industri'] 	 = $this->db->get('tbl_industri');
			$data['email']		 = '';
			$data['level']		 = $level;

			if ($aksi == 't') {
				$p = "industri/industri_tambah";

				$data['judul_web'] 	  = "Tambah Industri | SIM MAGANG";
			} elseif ($aksi == 'd') {
				$p = "industri/industri_detail";

				$data['query'] = $this->db->get_where("tbl_industri", "kdind = '$id'")->row();
				$data['judul_web'] 	  = "Detail Industri |  SIM MAGANG";
			} elseif ($aksi == 'e') {
				$p = "industri/industri_edit";

				$data['query'] = $this->db->get_where("tbl_industri", "kdind = '$id'")->row();
				$data['judul_web'] 	  = "Edit Industri |  SIM MAGANG";
			} elseif ($aksi == 'h') {

				$data['query'] = $this->db->get_where("tbl_industri", "kdind = '$id'")->row();

				if ($data['query']->kdind != '') {
					unlink("foto/industri/" . $data['query']->foto);
					$this->db->delete('tbl_industri', "kdind='$id'");
					$this->session->set_flashdata(
						'msg',
						'
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
								 </button>
								 <strong>Sukses!</strong> Industri berhasil dihapus.
							</div>'
					);
				}
				redirect('users/industri');
			} else {
				$p = "industri/industri";

				$data['judul_web'] 	  = "Industri |  SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('d-m-Y H:i:s');

			if (isset($_POST['btnsimpan'])) {
				$nama_industri   	= htmlentities(strip_tags($this->input->post('nama_industri')));
				$bidang_kerja	 		= htmlentities(strip_tags($this->input->post('bidang_kerja')));
				$deskripsi	 			= htmlentities(strip_tags($this->input->post('deskripsi')));
				$alamat_industri	= htmlentities(strip_tags($this->input->post('alamat_industri')));
				$wilayah   		 		= htmlentities(strip_tags($this->input->post('wilayah')));
				$telepon	 				= htmlentities(strip_tags($this->input->post('telepon')));
				$website	 				= htmlentities(strip_tags($this->input->post('website')));
				$email	 					= htmlentities(strip_tags($this->input->post('email')));
				$syarat   		 		= $this->input->post('syarat');
				$kuota	 				  = htmlentities(strip_tags($this->input->post('kuota')));

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./foto/industri/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				if (!$this->upload->do_upload('file')) {
					$error = $this->upload->display_errors('<p>', '</p>');
					$this->session->set_flashdata(
						'msg',
						'
														 <div class="alert alert-warning alert-dismissible" role="alert">
																<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																	<span aria-hidden="true">&times; &nbsp;</span>
																</button>
																<strong>Gagal!</strong> ' . $error . '.
														 </div>'
					);
					redirect('users/industri/t');
				} else {
					$file = $this->upload->data();
					$filename = $file['file_name'];
					$file 		= preg_replace('/ /', '_', $filename);
				}

				$data = array(
					'nama_industri'	 	=> $nama_industri,
					'bidang_kerja'    => $bidang_kerja,
					'deskripsi'				=> $deskripsi,
					'alamat_industri' => $alamat_industri,
					'wilayah'		 		  => $wilayah,
					'telepon'	 			  => $telepon,
					'website'    	    => $website,
					'email'					  => $email,
					'syarat'				  => $syarat,
					'kuota'		 		    => $kuota,
					'foto'	 			 	  => $file
				);
				$this->db->insert('tbl_industri', $data);

				$this->session->set_flashdata(
					'msg',
					'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Industri berhasil ditambahkan.
											</div>'
				);
				redirect('users/industri');
			}



			if (isset($_POST['btnupdate'])) {
				$nama_industri   	= htmlentities(strip_tags($this->input->post('nama_industri')));
				$bidang_kerja	 		= htmlentities(strip_tags($this->input->post('bidang_kerja')));
				$deskripsi	 			= htmlentities(strip_tags($this->input->post('deskripsi')));
				$alamat_industri	= htmlentities(strip_tags($this->input->post('alamat_industri')));
				$wilayah   		 		= htmlentities(strip_tags($this->input->post('wilayah')));
				$telepon	 				= htmlentities(strip_tags($this->input->post('telepon')));
				$website	 				= htmlentities(strip_tags($this->input->post('website')));
				$email	 					= htmlentities(strip_tags($this->input->post('email')));
				$syarat   		 		= $this->input->post('syarat');
				$kuota	 				  = htmlentities(strip_tags($this->input->post('kuota')));

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./foto/industri/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				$cek_foto = $data['query']->foto;
				if ($_FILES['file']['error'] <> 4) {
					if (!$this->upload->do_upload('file')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$update = "";
					} else {
						unlink("foto/industri/$cek_foto");
						$gbr = $this->upload->data();
						$filename = $gbr['file_name'];
						$file 		= preg_replace('/ /', '_', $filename);

						$update = "yes";
					}
				} else {
					$file   = $cek_foto;
					$update = "yes";
				}

				if ($update == 'yes') {

					$data = array(
						'nama_industri'	 	=> $nama_industri,
						'bidang_kerja'    => $bidang_kerja,
						'deskripsi'				=> $deskripsi,
						'alamat_industri' => $alamat_industri,
						'wilayah'		 		  => $wilayah,
						'telepon'	 			  => $telepon,
						'website'    	    => $website,
						'email'					  => $email,
						'syarat'				  => $syarat,
						'kuota'		 		    => $kuota,
						'foto'	 			 	  => $file
					);
					$this->db->update('tbl_industri', $data, "kdind='$id'");

					$this->session->set_flashdata(
						'msg',
						'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Industri berhasil diperbarui.
											</div>'
					);
					redirect('users/industri');
				} else {
					$this->session->set_flashdata(
						'msg',
						'
											 <div class="alert alert-warning alert-dismissible" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times; &nbsp;</span>
													</button>
													<strong>Gagal!</strong> ' . $error . '.
											 </div>'
					);
					redirect('users/industri/e/' . $id);
				}
			}
		}
	}

	

	public function penempatan($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if(in_array($level,['pembimbing','mahasiswa'])) {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('kdpenempatan', 'DESC');
			$this->db->order_by('tahun', 'DESC');
			$data['v_penempatan'] 	 = $this->db->get('tbl_penempatan');
			$data['email']		 = '';
			$data['level']		 = $level;

			if ($aksi == 'd') {
				$p = "penempatan/penempatan_detail";

				$data['query'] = $this->db->get_where("tbl_penempatan", "kdpenempatan = '$id'")->row();
				$data['judul_web'] 	  = "Detail Penempatan |  SIM MAGANG";
			} elseif ($aksi == 'h') {
				$cek_data_tolak = $this->db->get_where('tbl_tolak_penempatan', "kdpenempatan='$id'")->num_rows();
				if ($cek_data_tolak != 0) {
					$this->db->delete('tbl_tolak_penempatan', "kdpenempatan='$id'");
				}
				$cek_data = $this->db->get_where('tbl_penempatan', "kdpenempatan='$id'")->row();
				unlink("lampiran/surat/$cek_data->surat");
				$this->db->delete('tbl_penempatan', "kdpenempatan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Penempatan berhasil dihapus.
						 </div>'
				);
				redirect('users/penempatan');
			} elseif ($aksi == 'tolak') {
				$cek_status = $this->db->get_where('tbl_penempatan', "kdpenempatan='$id'")->row()->status;
				if ($cek_status == 'ditolak') {
					$status = 'proses';
				} else {
					$status = 'ditolak';
				}
				$data = array(
					'status'	 	=> $status
				);
				$this->db->update('tbl_penempatan', $data, "kdpenempatan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						<div class="alert alert-success alert-dismissible" role="alert">
							 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
								 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
							 </button>
							 <strong>Sukses!</strong> Penempatan berhasil diperbarui.
						</div>'
				);
				redirect('users/penempatan');
			} elseif ($aksi == 'setujui') {
				$cek_status = $this->db->get_where('tbl_penempatan', "kdpenempatan='$id'")->row()->status;
				if ($cek_status == 'diterima') {
					$status = 'proses';
				} else {
					$status = 'diterima';
				}
				$data = array(
					'status'	 	=> $status
				);
				$this->db->update('tbl_penempatan', $data, "kdpenempatan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						<div class="alert alert-success alert-dismissible" role="alert">
							 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
								 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
							 </button>
							 <strong>Sukses!</strong> Penempatan berhasil diperbarui.
						</div>'
				);
				redirect('users/penempatan');
			} else {
				$p = "penempatan/penempatan";

				$data['judul_web'] 	  = "Penempatan |  SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			date_default_timezone_set('Asia/Jakarta');
			$tgl = date('Y-m-d');

			for ($i = 1; $i <= $data['v_penempatan']->num_rows(); $i++) {
				if (isset($_POST['btntolak_' . $i])) {
					$kdpenempatan = $_POST['kdpenempatan_' . $i];
					$data = array(
						'kdpenempatan'	 	=> $kdpenempatan,
						'tanggal'	 				=> $tgl,
						'alasan'	 				=> $_POST['pesan_' . $i],
					);
					$this->db->insert('tbl_tolak_penempatan', $data);

					$data = array(
						'status'	 	=> 'ditolak'
					);
					$this->db->update('tbl_penempatan', $data, "kdpenempatan='$kdpenempatan'");

					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-success alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Sukses!</strong> Penolakan tempat berhasil dikirim.
								</div>'
					);
					redirect('users/penempatan');
				}
			}
		}
	}


	public function nilai_praktik($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if(in_array($level,['pembimbing','mahasiswa'])) {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->join('tbl_penempatan', 'tbl_penempatan.nim=tbl_mahasiswa.nim');
			$this->db->join('tbl_nilai', 'tbl_nilai.kdpenempatan=tbl_penempatan.kdpenempatan');
			if ($aksi == 'd') {
				$this->db->where('tbl_nilai.kdnilai', $id);
			}
			$this->db->order_by('tbl_mahasiswa.nama_lengkap', 'ASC');
			$this->db->order_by('tbl_penempatan.tahun', 'DESC');
			$data['v_nilai'] 	 = $this->db->get('tbl_mahasiswa');
			$data['email']		 = '';
			$data['level']		 = $level;


			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/nilai/nilai.php", $data);
			$this->load->view('users/footer');
		}
	}

	//------------------- Pembimbing --------------------//
	public function d_mahasiswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'pembimbing') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
			if ($aksi == 'd') {
				$this->db->where('tbl_mahasiswa.nim', $id);
			}
			$this->db->where('tbl_bimbingan.nip', $id_user);
			$this->db->order_by('nim', 'DESC');
			$data['v_mahasiswa'] 	 = $this->db->select('tbl_mahasiswa.*')->join('tbl_mahasiswa','tbl_bimbingan.nim=tbl_mahasiswa.nim')->get('tbl_bimbingan');
			$data['email']		 = '';
			$data['level']		 = 'Pembimbing';

			if ($aksi == 'd') {
				$p = "daftar_mahasiswa/mahasiswa_detail";

				$data['judul_web'] 	  = "Detail Mahasiswa | SIM MAGANG";
			} else {
				$p = "daftar_mahasiswa/mahasiswa";

				$data['judul_web'] 	  = "Data Mahasiswa | SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/$p", $data);
			$this->load->view('users/footer');
		}
	}


	public function bimbingan($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'kaprodi') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->join('tbl_mahasiswa', 'tbl_mahasiswa.nim=tbl_bimbingan.nim');
			if ($aksi == 'd') {
				$this->db->where('kdbimbingan', $id);
			}
			$this->db->order_by('kdbimbingan', 'DESC');
			$data['v_bimbingan'] 	 = $this->db->get('tbl_bimbingan');
			$data['email']		 = '';
			$data['level']		 = 'kaprodi';

			if ($aksi == 't') {
				$p = "bimbingan/bimbingan_tambah";

				$data['judul_web'] 	  = "Tambah Bimbingan |  SIM MAGANG";
				$this->db->order_by('nim', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_mahasiswa']		= $this->db->get('tbl_mahasiswa');
				$this->db->order_by('nip', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_dosen']		 	= $this->db->get('tbl_pemb');
			} elseif ($aksi == 'd') {
				$p = "bimbingan/bimbingan_detail";

				$data['judul_web'] 	  = "Detail Bimbingan |  SIM MAGANG";
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_bimbingan', "kdbimbingan='$id'")->row();
				unlink("$cek_data->file");
				$this->db->delete('tbl_bimbingan', "kdbimbingan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Bimbingan berhasil dihapus.
						 </div>'
				);
				redirect('users/bimbingan');
			} else {
				$p = "bimbingan/bimbingan";

				$data['judul_web'] 	  = "Data Bimbingan | SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/$p", $data);
			$this->load->view('users/footer');


			if (isset($_POST['btnsimpan'])) {
				$nim	 					= htmlentities(strip_tags($this->input->post('nim')));
				

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');

				$cek_penempatan = $this->db->get_where('tbl_penempatan', "nim='$nim'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Mahasiswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai/t');
				} else {

					

						$ceknim=$this->db->get_where('tbl_bimbingan',['nim'=>$nim])->row();
						if($ceknim){
							$this->session->set_flashdata(
								'msg',
								'
												<div class="alert alert-danger alert-dismissible" role="alert">
													 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
													 </button>
													 <strong>Gagal!</strong> Data telah ada.
												</div>'
							);
							redirect('users/bimbingan');
						// }
						}
						$data = array(
							'kdpenempatan' => $cek_penempatan->row()->kdpenempatan,
							'nip'				   => $this->input->post('nip'),
							'nim'				   => $nim,
							'tanggal'			 => $tgl,
	                  
						);
						$this->db->insert('tbl_bimbingan', $data);

						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Bimbingan berhasil dikirim.
											</div>'
						);
						redirect('users/bimbingan');
					// }
				}
			}
		}
	}


	public function nilai($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'pembimbing') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
			$this->db->join('tbl_penempatan', 'tbl_penempatan.nim=tbl_mahasiswa.nim');
			$this->db->join('tbl_nilai', 'tbl_nilai.kdpenempatan=tbl_penempatan.kdpenempatan');
			if ($aksi == 'd') {
				$this->db->where('tbl_nilai.kdnilai', $id);
			}
			$this->db->order_by('tbl_mahasiswa.nama_lengkap', 'ASC');
			$this->db->order_by('tbl_penempatan.tahun', 'DESC');
			$data['v_nilai'] 	 = $this->db->get('tbl_mahasiswa');
			$data['email']		 = '';
			$data['level']		 = 'Pembimbing';

			if ($aksi == 't') {
				$p = "nilai/nilai_tambah";

				$data['judul_web'] 	  = "Nilai |  SIM MAGANG";
				$this->db->order_by('nim', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_mahasiswa'] 	    = $this->db->get('tbl_mahasiswa');
			} elseif ($aksi == 'd') {
				$p = "nilai/nilai_detail";

				$data['judul_web'] 	  = "Detail Nilai |  SIM MAGANG";
			} elseif ($aksi == 'h') {
				$this->db->delete('tbl_nilai', "kdnilai='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						<div class="alert alert-success alert-dismissible" role="alert">
							 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
								 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
							 </button>
							 <strong>Sukses!</strong> Nilai berhasil dihapus.
						</div>'
				);
				redirect('users/nilai');
			} else {
				$p = "nilai/nilai";

				$data['judul_web'] 	  = "Data Nilai |  SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/$p", $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnsimpan'])) {
				$nim	 					= htmlentities(strip_tags($this->input->post('nim')));
				$nilai	 				= htmlentities(strip_tags($this->input->post('nilai')));
				$keterangan	 		= htmlentities(strip_tags($this->input->post('keterangan')));

				$cek_penempatan = $this->db->get_where('tbl_penempatan', "nim='$nim'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Mahasiswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai/t');
				} else {
					$data = array(
						'kdpenempatan' => $cek_penempatan->row()->kdpenempatan,
						'keterangan'   => $keterangan,
						'nilai'				 => $nilai
					);
					$this->db->insert('tbl_nilai', $data);

					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-success alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Sukses!</strong> Nilai berhasil diisi.
								</div>'
					);
					redirect('users/nilai');
				}
			}
		}
	}




	//---------------------------- Mahasiswa ----------------------------//
	public function status_prakerin($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'mahasiswa') {
				redirect('web/error_not_found');
			}

			// $cek_penempatan = $this->db->get_where('tbl_penempatan', array('nim' => "$id_user", 'status !=' => "ditolak"));
			$this->db->order_by('kdpenempatan', 'DESC');
			$cek_penempatan = $this->db->get_where('tbl_penempatan', array('nim' => "$id_user"));

			$data['cek_penempatan'] = $cek_penempatan;
			$data['user']   	 = $this->Mcrud->get_mahasiswa_by_nim($ceks);
			$data['email']		 = '';
			$data['level']		 = 'Mahasiswa';

			if ($aksi == 't') {
				if ($this->db->get_where('tbl_penempatan', array('nim' => "$id_user", 'status !=' => "ditolak"))->num_rows() != 0) {
					redirect('web/error_not_found');
				}

				$p = "status/status_tambah";

				$data['judul_web'] 	  = "Daftar Penempatan Prakerin |  SIM MAGANG";

				$this->db->order_by('nama_industri', 'ASC');
				$data['v_industri']   = $this->db->get('tbl_industri');
			} else {
				$p = "status/status";

				$data['judul_web'] 	  = "Status Prakerin |  SIM MAGANG";

				$kdpenempatan  = $cek_penempatan->row()->kdpenempatan;
				$this->db->order_by('kdpenempatan', 'DESC');
				$data['query'] = $this->db->get_where("tbl_penempatan", "kdpenempatan = '$kdpenempatan'")->row();
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/mahasiswa/$p", $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnsimpan'])) {
				$kdind	 					= htmlentities(strip_tags($this->input->post('kdind')));
				$wilayah	 				= htmlentities(strip_tags($this->input->post('wilayah')));
				$kdpemb						= $this->db->get_where('tbl_mahasiswa', "nim='$id_user'")->row()->kdpemb;

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');
				$tahun = date('Y');

				$file_size = 1024 * 5; //5 MB
				$this->upload->initialize(array(
					"upload_path"   => "./lampiran/surat/",
					"allowed_types" => "*",
					"max_size" => "$file_size"
				));

				if (!$this->upload->do_upload('file')) {
					$error = $this->upload->display_errors('<p>', '</p>');
					$this->session->set_flashdata(
						'msg_file',
						'
												 <div class="alert alert-warning alert-dismissible" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times; &nbsp;</span>
														</button>
														<strong>Gagal!</strong> ' . $error . '.
												 </div>'
					);

					redirect('users/status_prakerin/t');
				} else {
					$file = $this->upload->data();
					$filename = $file['file_name'];
					$file 		= preg_replace('/ /', '_', $filename);

					$data = array(
						'nim'				   => $id_user,
						'kdpemb'		   => $kdpemb,
						'kdind'			   => $kdind,
						'tanggal'			 => $tgl,
						'wilayah'			 => $wilayah,
						'tahun'			   => $tahun,
						'status'			 => 'proses',
						'surat'			   => $file
					);
					$this->db->insert('tbl_penempatan', $data);

					$this->session->set_flashdata(
						'msg',
						'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Pendaftaran berhasil dikirim.
											</div>'
					);
					redirect('users/status_prakerin');
				}
			}
		}
	}

	public function bimbingan_mahasiswa($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'mahasiswa') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_mahasiswa_by_nim($ceks);
			$this->db->join('tbl_mahasiswa', 'tbl_mahasiswa.nim=tbl_bimbingan.nim');
			$this->db->where('tbl_mahasiswa.nim', $id_user);
			if ($aksi == 'd') {
				$this->db->where('kdbimbingan', $id);
			}
			$this->db->order_by('kdbimbingan', 'DESC');
			$data['v_bimbingan'] 	 = $this->db->get('tbl_bimbingan');
			$data['email']		 = '';
			$data['level']		 = 'Mahasiswa';

			if ($aksi == 'd') {
				$p = "bimbingan/bimbingan_detail";

				$data['judul_web'] 	  = "Detail Bimbingan |  SIM MAGANG";
			} else {
				$p = "bimbingan/bimbingan";

				$data['judul_web'] 	  = "Data Bimbingan | SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/mahasiswa/$p", $data);
			$this->load->view('users/footer');
		}
	}

	public function nilai_prakerin($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'mahasiswa') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_mahasiswa_by_nim($ceks);
			$this->db->join('tbl_penempatan', 'tbl_penempatan.nim=tbl_mahasiswa.nim');
			$this->db->join('tbl_nilai', 'tbl_nilai.kdpenempatan=tbl_penempatan.kdpenempatan');
			if ($aksi == 'd') {
				$this->db->where('tbl_nilai.kdnilai', $id);
			}
			$this->db->where('tbl_mahasiswa.nim', $id_user);
			$this->db->order_by('tbl_mahasiswa.nama_lengkap', 'ASC');
			$this->db->order_by('tbl_penempatan.tahun', 'DESC');
			$data['v_nilai'] 	 = $this->db->get('tbl_mahasiswa');
			$data['email']		 = '';
			$data['level']		 = 'Mahasiswa';

			if ($aksi == 'd') {
				$p = "nilai/nilai_detail";

				$data['judul_web'] 	  = "Detail Nilai |  SIM MAGANG";
			} else {
				$p = "nilai/nilai";

				$data['judul_web'] 	  = "Nilai |  SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/mahasiswa/$p", $data);
			$this->load->view('users/footer');
		}
	}



	public function monitoring()
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if(in_array($level,['pembimbing','mahasiswa'])) {
				redirect('404_override');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->order_by('nama', 'ASC');
			$data['v_jurusan'] = $this->db->get('tbl_jurusan');
			$data['email']		 = '';
			$data['level']		 = $level;


			$p = "monitoring/monitoring";

			$data['judul_web'] 	  = "Monitoring | SIM MAGANG";

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');
		}
	}

	public function industri_view($id='')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		$data['user']   	 = $this->Mcrud->get_mahasiswa_by_nim($ceks);
		if ($id != '') {
			$this->db->where('kdind', $id);
		}
		$this->db->order_by('kdind','DESC');
		$data['v_industri']		 = $this->db->get('tbl_industri');
		$data['level']		 = 'Mahasiswa';
		if ($id == '') {
			$data['judul_web'] = 'Industri';
			
			$p = 'industri';
		}else {
			$data['judul_web'] = $data['v_industri']->row()->nama_industri;
			
			$p = 'industri_detail';		
		}
		

		$this->load->view('users/header', $data);
		$this->load->view("users/mahasiswa/industri/$p", $data);
		$this->load->view('users/footer');

	}

	function nilai_admin($aksi='',$id=''){
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'admin') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_users_by_un($ceks);
			$this->db->join('tbl_penempatan', 'tbl_penempatan.nim=tbl_mahasiswa.nim');
			$this->db->join('tbl_nilai', 'tbl_nilai.kdpenempatan=tbl_penempatan.kdpenempatan');
			if ($aksi == 'd') {
				$this->db->where('tbl_nilai.kdnilai', $id);
			}
			$this->db->order_by('tbl_mahasiswa.nama_lengkap', 'ASC');
			$this->db->order_by('tbl_penempatan.tahun', 'DESC');
			$data['v_nilai'] 	 = $this->db->get('tbl_mahasiswa');
			$data['email']		 = '';
			$data['level']		 = 'admin';

			if ($aksi == 't') {
				$p = "nilai/nilai_tambah";

				$data['judul_web'] 	  = "Nilai |  SIM MAGANG";
				$this->db->order_by('nim', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$data['v_mahasiswa'] 	    = $this->db->get('tbl_mahasiswa');
			} elseif ($aksi == 'd') {
				$p = "nilai/nilai_detail";

				$data['judul_web'] 	  = "Detail Nilai |  SIM MAGANG";
			} elseif ($aksi == 'h') {
				$this->db->delete('tbl_nilai', "kdnilai='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						<div class="alert alert-success alert-dismissible" role="alert">
							 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
								 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
							 </button>
							 <strong>Sukses!</strong> Nilai berhasil dihapus.
						</div>'
				);
				redirect('users/nilai_admin');
			} else {
				$p = "nilai/nilai";

				$data['judul_web'] 	  = "Data Nilai |  SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/admin/$p", $data);
			$this->load->view('users/footer');

			if (isset($_POST['btnsimpan'])) {
				$nim	 					= htmlentities(strip_tags($this->input->post('nim')));
				$nilai	 				= htmlentities(strip_tags($this->input->post('nilai')));
				$keterangan	 		= htmlentities(strip_tags($this->input->post('keterangan')));

				$cek_penempatan = $this->db->get_where('tbl_penempatan', "nim='$nim'");
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Mahasiswa belum menentukan tempat.
								</div>'
					);
					redirect('users/nilai_admin/t');
				} else {
					$data = array(
						'kdpenempatan' => $cek_penempatan->row()->kdpenempatan,
						'keterangan'   => $keterangan,
						'nilai'				 => $nilai
					);
					$where=['kdpenempatan'=>$cek_penempatan->row()->kdpenempatan];
					$cekdata=$this->db->get_where('tbl_nilai',$where)->row();
					if($cekdata){
						$this->session->set_flashdata(
							'msg',
							'
									<div class="alert alert-warning alert-dismissible" role="alert">
										 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
										 </button>
										 <strong>Gagal!</strong> Nilai sudah ditentunkan untuk data.'.$cek_penempatan->row()->nim.'
									</div>'
						);
						redirect('users/nilai_admin/t');
					}
					$this->db->insert('tbl_nilai', $data);
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-success alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Sukses!</strong> Nilai berhasil diisi.
								</div>'
					);
					// redirect('users/nilai_Admin');
				}
			}
		}
	}

	private function update_bimbingan($nim){
		$judul	 				= htmlentities(strip_tags($this->input->post('judul')));
		$catatan  	 		= htmlentities(strip_tags($this->input->post('catatan')));
		$file_size = 1024 * 5; //5 MB
		$this->upload->initialize(array(
						"upload_path"   => "./lampiran/bimbingan/",
						"allowed_types" => "*",
						"max_size" => "$file_size"
					));

					if (!$this->upload->do_upload('file')) {
						$error = $this->upload->display_errors('<p>', '</p>');
						$this->session->set_flashdata(
							'msg_file',
							'
												 <div class="alert alert-warning alert-dismissible" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">&times; &nbsp;</span>
														</button>
														<strong>Gagal!</strong> ' . $error . '.
												 </div>'
						);

						redirect('users/bimbingan/t');
					} else {
						$file = $this->upload->data();
						$filename = "lampiran/bimbingan/" . $file['file_name'];
						$file 		= preg_replace('/ /', '_', $filename);
					    $update['catatan']=$catatan;
						$update['file']=$file;
						$update['judul']=$judul;

						$cek=$this->Mcrud->update_bim(['nim'=>$nim],$update);
						echo $cek;
						exit();
					}


	}

	public function bimbingan_p($aksi = '', $id = '')
	{
		$ceks = $this->session->userdata('prakrin_smk@Proyek-2017');
		$id_user = $this->session->userdata('id_user@Proyek-2017');
		$level = $this->session->userdata('level@Proyek-2017');
		if (!isset($ceks)) {
			redirect('web/login');
		} else {

			if ($level != 'pembimbing') {
				redirect('web/error_not_found');
			}

			$data['user']   	 = $this->Mcrud->get_pemb_by_un($ceks);
			$this->db->join('tbl_mahasiswa', 'tbl_mahasiswa.nim=tbl_bimbingan.nim');
			$this->db->where('nip', $id_user);
			if ($aksi == 'd') {
				$this->db->where('kdbimbingan', $id);
			}
			$this->db->order_by('kdbimbingan', 'DESC');
			$data['v_bimbingan'] 	 = $this->db->get('tbl_bimbingan');
			$data['email']		 = '';
			$data['level']		 = 'Pembimbing';

			if ($aksi == 't') {
				$p = "bimbingan_p/bimbingan_tambah";

				$data['judul_web'] 	  = "Tambah Bimbingan |  SIM MAGANG";
				$this->db->order_by('nim', 'DESC');
				$this->db->order_by('nama_lengkap', 'ASC');
				$this->db->select('tbl_mahasiswa.*')->join('tbl_mahasiswa', 'tbl_mahasiswa.nim=tbl_bimbingan.nim');
				$this->db->where('nip', $id_user);
				$data['v_mahasiswa']		 	 = $this->db->get('tbl_bimbingan');
			} elseif ($aksi == 'd') {
				$p = "bimbingan_p/bimbingan_detail";

				$data['judul_web'] 	  = "Detail Bimbingan |  SIM MAGANG";
			} elseif ($aksi == 'h') {

				$cek_data = $this->db->get_where('tbl_bimbingan', "kdbimbingan='$id'")->row();
				unlink("$cek_data->file");
				$this->db->delete('tbl_bimbingan', "kdbimbingan='$id'");

				$this->session->set_flashdata(
					'msg',
					'
						 <div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times; &nbsp;</span>
								</button>
								<strong>Sukses!</strong> Bimbingan berhasil dihapus.
						 </div>'
				);
				redirect('users/bimbingan_p');
			} else {
				$p = "bimbingan_p/bimbingan";

				$data['judul_web'] 	  = "Data Bimbingan | SIM MAGANG";
			}

			$this->load->view('users/header', $data);
			$this->load->view("users/pembimbing/$p", $data);
			$this->load->view('users/footer');


			if (isset($_POST['btnsimpan'])) {
				$nim	 					= htmlentities(strip_tags($this->input->post('nim')));
				$judul	 				= htmlentities(strip_tags($this->input->post('judul')));
				$catatan  	 		= htmlentities(strip_tags($this->input->post('catatan')));

				date_default_timezone_set('Asia/Jakarta');
				$tgl = date('Y-m-d');

				$cek_penempatan = $this->db->get_where('tbl_penempatan', "nim='$nim'");
				$cek_pembimbing = $this->db->get_where('tbl_bimbingan',"nim='$nim'");
				if ($cek_pembimbing->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong>  belum ditentunkan pembimbing.
								</div>'
					);
					redirect('users/bimbingan_p/t');
				} 
				if ($cek_penempatan->num_rows() == 0) {
					$this->session->set_flashdata(
						'msg',
						'
								<div class="alert alert-warning alert-dismissible" role="alert">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
										 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
									 </button>
									 <strong>Gagal!</strong> Mahasiswa belum menentukan tempat.
								</div>'
					);
					redirect('users/bimbingan_p/t');
				} else {

						$this->update_bimbingan($nim);

						$this->session->set_flashdata(
							'msg',
							'
											<div class="alert alert-success alert-dismissible" role="alert">
												 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
													 <span aria-hidden="true">&times;&nbsp; &nbsp;</span>
												 </button>
												 <strong>Sukses!</strong> Bimbingan berhasil dikirim.
											</div>'
						);
						redirect('users/bimbingan_p');
				}
				
			}
		}
	}

	

	
}
