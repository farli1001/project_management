<?php
include 'template.php';
?>
<?php
require_once 'config.php';


// Mengambil daftar tugas dari database
$query = $conn->prepare('SELECT * FROM tasks');
$query->execute();
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

// Hitung total tugas
$totalTasks = count($tasks);

// Mengambil daftar proyek dari database
$query = $conn->prepare('SELECT COUNT(*) as totalProjects FROM projects');
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);
$totalProjects = $result['totalProjects'];
?>

<!DOCTYPE html>
<html>

<head>
	<title>Website Project Management</title>
	<style>
		.green-button {
			background-color: ##a5d6a7;
			color: white;
			transition: background-color 0.3s;
			border: none;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
			border-radius: 5px;
			padding: 10px 20px;
			cursor: pointer;
		}

		.green-button.active,
		.green-button:hover {
			background-color: #a5d6a7;
		}
	</style>
</head>

<body>
	<div class="jumbotron">
		<div class="container">
			<h1 class="display-4">Hallooo</h1>
			<p class="lead">
				Semoga website ini membantu pengelolaan projek anda.
			</p>
			<hr class="my-4" />
			<p>
				saya harap dengan ini anda tidak menjadi deadliner!!
			</p>
		</div>
	</div>

	<div class="container">
		<div>
			<? //php if (isset($_SESSION['admin'])): 
			?>
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Data</h4>
				</div>
				<div class="card-body">
					<div class="row justify-content-end">
						<div class="col-sm-6 col-md-6">
							<div class="card card-default card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-project-diagram"></i>
											</div>
										</div>
										<div class="col-6 col-stats">
											<div class="numbers">
												<p class="card-category">Total<br>Proyek: <?php echo $totalProjects; ?></p>
												<h4 class="card-title"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-6">
							<div class="card card-default card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-tasks"></i>
											</div>
										</div>
										<div class="col-6 col-stats" onclick="location.href='#'">
											<div class="numbers">
												<p class="card-category">Total<br>Task: <?php echo count($tasks); ?></p>
												<h4 class="card-title"></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<? //php endif; 
				?>

				<div class="modal fade bs-example-modal-sm" id="gantiPassword" tabindex="-1" role="dialog" aria-labelledby="gantiPass">
					<div class="modal-dialog modal-sm" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="gantiPass">Ganti Password</h4>
							</div>
							<form action="#" method="post">
								<div class="modal-body">
									<div class="form-group">
										<label class="control-label">Password Lama</label>
										<input name="pass" type="text" class="form-control" placeholder="Password Lama">
									</div>
									<div class="form-group">
										<label class="control-label">Password Baru</label>
										<input name="pass1" type="text" class="form-control" placeholder="Password Baru">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button name="changePassword" type="submit" class="btn btn-primary">Ganti Password</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="modal fade" id="pengaturanAkun" tabindex="-1" role="dialog" aria-labelledby="akunAtur">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h3 class="modal-title" id="akunAtur"><i class="fas fa-user-cog"></i> Pengaturan Akun</h3>
							</div>
							<form action="" method="post" enctype="multipart/form-data">
								<div class="modal-body">
									<div class="form-group">
										<label>Nama Lengkap</label>
										<input type="text" name="nama" class="form-control" value="<?php //echo $data['nama_lengkap']; 
																									?>">
										<input type="hidden" name="id" value="<?php //echo $data['id_admin']; 
																				?>">
									</div>
									<div class="form-group">
										<label>Email</label>
										<input type="text" name="username" class="form-control" value="<?php //echo $data['username']; 
																										?>">
									</div>
									<div class="form-group">
										<label>Foto Profile</label>
										<p>
											<img src="../assets/img/user/<?php //echo $data['foto']; 
																			?>" class="img-thumbnail" style="height: 50px;width: 50px;">
										</p>
										<input type="file" name="foto">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button name="updateProfile" type="submit" class="btn green-button">Simpan</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <div>
            <div class="card-body">
                <div class="row"> -->
		<!-- <div class="col-md-5">
                        <div class="card">
                            <div class="card-body mb-3">
                                <h2 class="card-title mb-4">Tentang Kami</h2>
                                <p class="card-text">
                                    Kami dari kelompok 3 sebagai pembuat website ini, menyediakan solusi untuk memantau Presensi Asisten dengan mudah dan tersistem.
                                </p>
                                <a class="btn btn-primary btn-sm mt-1" href="<?php //echo url('/about'); 
																				?>" role="button">Pelajari Lebih Lanjut</a>
                            </div>
                        </div>
                    </div> -->
		<!-- <div class="col-md-7">
                        <div class="card">
                            <div class="card-body mb-3">
                                <h2 class="card-title mb-2">Kontak</h2>
                                <address>
                                    <strong>LAB ICT UBL</strong><br />
                                    Jl. Ciledug Raya No.99, RT.1/RW.2,
                                    Petukangan Utara, Kec.<br /> Pesanggrahan,
                                    Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12260<br />
                                </address>
                                <p>
                                    Telepon: 123-456-7890<br />
                                    website resmi: <a href="https://labict.budiluhur.ac.id/">https://labict.budiluhur.ac.id/</a>
                                </p>
                            </div>
                        </div>
                    </div> -->
		<!-- </div>
            </div>
        </div> -->
	</div>


	<?php
	include 'footer.php';
	?>