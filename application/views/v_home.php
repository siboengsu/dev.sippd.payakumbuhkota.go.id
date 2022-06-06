<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SIPPD | Login</title>

<link href="<?php echo base_url(); ?>assets/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/fine-uploader/jquery.fine-uploader/fine-uploader-new.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/photoswipe/dist/photoswipe.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/photoswipe/dist/default-skin/default-skin.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/node_modules/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/angular.min.js"></script>

<script src="<?php echo base_url(); ?>assets/node_modules/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<style>
html, body {
	height: 100%;
}
</style>
</head>
<body style="background-color:#EEEEEE;">
<div style="height: 100%;display:flex; align-items:center; justify-content:center;">
	<div class="panel panel-primary" style="border:none; max-width:450px;">
		<div class="panel-heading text-center">
			<h2 style="display:inline;">S</h2><h4 style="display:inline;">istem</h4>
			<h2 style="display:inline;">I</h2><h4 style="display:inline;">nformasi</h4>
			<br>
			<h2 style="display:inline;">P</h2><h4 style="display:inline;">erencanaan</h4>
			<h2 style="display:inline;">P</h2><h4 style="display:inline;">embangunan</h4>
			<h2 style="display:inline;">D</h2><h4 style="display:inline;">aerah</h4>
		</div>
		<form method="post" action="login.php" name="form-login" id="form-login">
		<div class="panel-body">
			<div class="text-center">
				<img alt="Logo Kota Payakumbuh" src="assets/img/logo-kota-payakumbuh.png" height="125" style="display:block; margin:0 auto;">
				<h4>Bappeda Kota Payakumbuh</h4>
			</div>
			<br>
			
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
						<input type="text" name="i-userid" id="i-userid" class="form-control" placeholder="User ID">
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock fa-lg"></i></span>
						<input type="password" name="i-phppwd" id="i-phppwd" class="form-control" placeholder="Password">
					</div>
				</div>
				<div class="form-group">
					<select name="i-tahun" id="i-tahun" class="form-control selectpicker show-tick show-menu-arrow">
						<option class="text-center text-bold" value="">-- Tahun Anggaran --</option>
						<?php foreach($tahun as $r):
						$r = settrim($r);?>
						<option class="text-center text-bold" value="<?php echo $r['KDTAHUN']; ?>"><?php echo $r['NMTAHUN']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				</div>
				<button type="submit" class="btn btn-primary btn-block" style="border: none; border-top-left-radius: 0px; border-top-right-radius: 0px; height:50px;">Login <i class="fa fa-sign-in fa-lg"></i></button>
			</form>
		
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/numeral/min/numeral.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/fine-uploader/jquery.fine-uploader/jquery.fine-uploader.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/photoswipe/dist/photoswipe.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/photoswipe/dist/photoswipe-ui-default.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/devbridge-autocomplete/dist/jquery.autocomplete.js"></script>

<script src="<?php echo base_url(); ?>assets/node_modules/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/inputmask/dist/min/inputmask/phone-codes/phone.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/inputmask/dist/min/inputmask/phone-codes/phone-be.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/inputmask/dist/min/inputmask/phone-codes/phone-ru.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/flatpickr/dist/flatpickr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/flatpickr/dist/l10n/id.js"></script>
<script src="<?php echo base_url(); ?>assets/node_modules/list.js/dist/list.min.js"></script>
<script>
$(function() {
	updateSelect();
	
	$('#form-login').submit(function(e) {
		e.preventDefault();
		if($.trim($("input[name='i-userid']").val()) == '') {
			goAlert('Masukkan User Id'); return false;
		} else if($.trim($("input[name='i-phppwd']").val()) == '') {
			goAlert('Masukkan password'); return false;
		} else if($.trim($("select[name='i-tahun']").val()) == '') {
			goAlert('Pilih tahun anggaran'); return false;
		}
		
		$.post('home/login', $(this).serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				goAlert({
					msg : 'Contact Administrator', 
					type: 'warning'
				});
			}
		});
		
		return false;
	});
});
</script>
</body>
</html>