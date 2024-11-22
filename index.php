<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Halaman Konsultasi</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
	<?php
	require_once 'koneksi.php';
	?>

	<div class="container mt-5">
		<h1 class="mb-4"><center>Sistem Pakar Diagnosa Penyakit</h1></center>
		<form method="post" action="hasil.php">
			<div class="mb-3">
				<h3>Pilih Gejala yang Dialami:</h3>
				<table class="table table-striped">
					<?php
					$sql = "SELECT * FROM gejala WHERE id_gejala < 8";
					$result = mysqli_query($conn, $sql);
					while ($g = mysqli_fetch_assoc($result)) {
					?>
						<tr>
							<td><input class="form-check-input" type="checkbox" name="gejala[]" 
							value="<?php echo $g['id_gejala']; ?>"  
							id="gejala_<?php echo $g['id_gejala']; ?>">
						</td>
						<td>
                            <font color="red">
                                <label class="form-check-label" for="gejala_<?php echo $g['id_gejala']; ?>">
								<?php echo '-'. $g['nama_gejala']; ?>
							</label>
                            </font>
						</td>
						</tr>
					<?php  } ?>
				</table>
			</div>
			<div class="d-grid gap-2">
				<button type="submit" class="btn btn-success">Diagnosa</button>
			</div>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>