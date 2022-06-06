<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SIPPD</title>

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
	
	<script src="<?php echo base_url(); ?>assets/node_modules/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
	

</head>
<body style="padding-top: 70px;">
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/dashboard">
				<img alt="Brand" src="../favicon-96x96.png" height="20" width="20" style="display:inline-block;">
				SIPPD
			</a>
		</div>
		
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<p class="navbar-text" style="margin-right:-8px;"><strong>Tahun</strong></p>
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<div class="col-sm-10">
						<form style = "margin-top: 8px;">
							<select name="tahun" id="btntahun" class="form-control selectpicker show-menu-arrow" data-width="auto" title="<?php echo "Tahun ".$r['NMTAHUN']; ?>">
								<?php foreach($tahun as $r):
									$r = settrim($r); ?>
									<option id="tahun" data-tahun = "<?php echo $r['KDTAHUN']; ?>" <?php echo setselected($r['KDTAHUN'], $this->session->KDTAHUN); ?>><?php echo $r['NMTAHUN']; ?></option>
								<?php endforeach; ?>
							</select>							
						</form>
					</div>
				</li>
			</ul>
			<p class="navbar-text"><strong><?php echo $this->session->NMTAHAP; ?></strong></p>

			<ul class="nav navbar-nav">
				<?php
				$ary_menu = [];
				foreach($menu as $r)
				{
					$r = settrim($r);

					$r['LVL'] =
					(strlen($r['ID_MENU']) == 2) ? '1' : (
						(strlen($r['ID_MENU']) == 4) ? '2' : (
							(strlen($r['ID_MENU']) == 6) ? '3' : ''
						)
					);

					$ary_menu[] = $r;
				}

				function getMenu($ary, $id_pare)
				{
					$html = '';

					foreach($ary AS $e)
					{
						if($e['ID_PARE'] == $id_pare)
						{
							if($e['TIPE'] == 'H')
							{
								$ischild = getMenu($ary, $e['ID_MENU']);

								if($e['LVL'] == '1' AND $ischild != '')
								{
									$html .= "
									<li class='dropdown'>
										<a href='javascript:void(0)' class='dropdown-toggle' data-toggle='dropdown' role='button' data-id_menu='{$e['ID_MENU']}'>{$e['NMMENU']} <span class='caret'></span></a>
										{$ischild}
									</li>";
								}
								elseif($ischild != '')
								{
									$html .= "
									<li class='dropdown-submenu'>
										<a tabindex='-1' href='javascript:void(0)' data-id_menu='{$e['ID_MENU']}'>{$e['NMMENU']}</a>
										{$ischild}
									</li>";
								}
							}
							elseif($e['TIPE'] == 'D')
							{
								$html .= "<li><a href='javascript:void(0)' class='app-menu' data-id_menu='{$e['ID_MENU']}'>{$e['NMMENU']}</a></li>";
							}
						}
					}

					if($id_pare != '' AND $html != '')
					{
						$html = "<ul class='dropdown-menu'>{$html}</ul>";
					}

					return $html;
				}

				echo getMenu($ary_menu, $ary_menu[0]['ID_PARE']);
				?>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php echo $this->session->NAMA; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0)" id="btn-account-form"><i class="fa fa-key"></i> &nbsp;Ubah Password</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="/home/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
			<p class="navbar-text navbar-right"><strong><?php echo $this->session->NMUNIT; ?></strong></p>
		</div>
	</div>
</nav>

<div id="main" class="container-fluid" style="padding-bottom:150px;"></div>

<script>
$(function() {
	
	$('.app-menu').click(function(e) {
		e.preventDefault();
		var id_menu = $(this).data('id_menu');
		var url = '';
		
		switch(id_menu) {
			case '0101'		: url = '/user/tahap'; break;
			case '0201'		: url = '/user/urusan'; break;
			case '030101'	: url = '/master/programKegiatan'; break;
			case '0302'		: url = '/master/masterPagu'; break;
			case '0303'		: url = '/master/masterImport'; break;
			case '040101'	: url = '/renja'; break;
			case '040201'	: url = '/prarka'; break;
			case '0403'		: url = '/rpjmd'; break;
			case '0001'		: url = '/user/akses'; break;
			case '0002'		: url = '/user/verif_rka'; break;
			case '0003'		: url = '/user/password_reset'; break;
			case '0004'		: url = '/master/Pemda'; break;
			case '0005'		: url = '/master/SSH'; break;
			case '0006'		: url = '/master/rekening_belanja_langsung'; break;
			case '0008'		: url = '/User/userindex'; break;
			case '0009'		: url = '/Opd/index'; break;
			
			case '090101'	: url = '/report/induk/rkpd_matrik'; break;
			case '090102'	: url = '/report/induk/rkpd_rekap_opd'; break;
			case '090103'	: url = '/report/induk/rkpd_rekap_urusan'; break;
			case '090104'	: url = '/report/induk/rkpd_pagu_opd'; break;
			case '090201'	: url = '/report/induk/rka_prarka'; break;
			case '090106'	: url = '/report/induk/matrik51'; break;
			case '090107'	: url = '/report/induk/rkpd_matrik_opd'; break;
			case '090108'	: url = '/report/induk/program_kegiatan'; break;
			case '090305'	: url = '/report/induk/matrik43'; break;
			case '090109'	: url = '/report/induk/pagu_opdnonkelurahan'; break;
			case '090110'	: url = '/report/induk/ssh'; break;
			case '090111'	: url = '/report/induk/matrik51_perubahan'; break;
			case '090112'	: url = '/report/induk/matrik51_perubahan_opd'; break;
			case '090113'	: url = '/report/induk/matrik51_opd_uptd_blud_perubahan'; break;
			case '090115'	: url = '/report/induk/matrik52_perubahan'; break;
			case '090116'	: url = '/report/induk/matrik53_perubahan'; break;
			case '090117'	: url = '/report/induk/matrik51_opd_uptd_blud'; break;
			case '090119'	: url = '/report/induk/matrik51_perubahan_skpd'; break;
				//ppas
			case '090301'	: url = '/report/induk/matrik_renja_perangkat_daerah'; break;
			case '090302'	: url = '/report/induk/per_urusan'; break;
			case '090303'	: url = '/report/induk/pagu_perangkat_daerah'; break;
			case '090304'	: url = '/report/induk/matrik_renja_per_urusan'; break;
			case '090118'	: url = '/report/induk/cetak_lap41'; break;

			case '090105'	: url = '/report/perubahan/rkpd_pagu_opd'; break;
			case '090202'	: url = '/report/perubahan/rka_prarka'; break;

			default: url = '';
		}
		
		$.get(url, function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				$('#main').html(res);
			}
		});
		
		return false;
	});

	$(".app-menu").on("click", function(event){
		var $trigger = $(".app-menu");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".dropdown-menu").trigger('click');
        }
	});
	
	$('#btn-account-form').click(function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').data('id');
		modalAccount = new BootstrapDialog({
			title: 'Informasi Akun',
			type: 'type-primary',
			size: 'size-wide',
			message: $('<div></div>').load('/user/account_form/')
		});
		modalAccount.open();

		return false;
	});
	
	$('#btntahun').on('change', function(e) {
		e.preventDefault();
		var data = {
			setid : $('#btntahun option:selected').data('tahun')
		}
		modalAccount = new BootstrapDialog({
			title: 'Informasi Akun',
			type: 'type-primary',
			size: 'size-wide',
			message: $('<div></div>').load('/dashboard/rubahtahun/', data)
		});
		alert("Tahun berhasil dirubah");
		location.reload();
		return false;			
	});
});
</script>
<?php
//$var = password_hash('aassddff', PASSWORD_DEFAULT, ['cost' => 8]);
//$var = $this->session->userdata();
//VD($var);
?>

<div class="pswp" tabindex="-1" role="dialog">
	<div class="pswp__bg"></div>
	<div class="pswp__scroll-wrap">
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
				<button class="pswp__button pswp__button--share" title="Share"></button>
				<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
				<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>
			<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
			<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
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
</body>
</html>
