<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-user-form">
	<div class="col-md-12">
		<form class="form-horizontal form-user">
			<input id="i-userid" type="hidden" name="i-userid" value="<?php echo $userid; ?>">
			<input type="hidden" value="1" class="page">

			<div class="form-group">
				<label class="col-sm-2 control-label">Hak Akses</label>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
						<input type="text" value="<?php echo $groupid; ?>" id="f-groupid" name="f-groupid" class="form-control text-bold" placeholder="Nama Group" readonly>
					</div>
				</div>
				<?php 
				if($act == 'edit'){
					$dat = $this->db->query("SELECT NMGROUP FROM WEBGROUP WHERE GROUPID = '$groupid'")->row_array();
					foreach ($dat as $d)
					{
						$nama = $d;
					}
				}
				?>
				<div class="form-gap visible-xs-block"></div>
				
				<div class="col-sm-7">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-group" data-setid="#f-groupid" data-setnm="#v-nmgroup"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmgroup" value="<?php echo $nama; ?>" class="form-control text-bold v-nmgroup" placeholder="Nama Group" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Username</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" 
					<?php 
					if($act == 'add')
					{
						echo 'name="i-userid" id="i-userid"';
					}
					else if($act == 'edit')
					{
						echo 'name="i-userid1" id="i-userid1';
					}
					?> class="form-control" value="<?php echo $userid; ?>">
				</div>
			</div>
			
			<div class="form-group" id="nmpengguna" style="display: none;"> 
				<label class="col-sm-2 control-label">Nama</label>
				<div class="col-sm-9">
					<input type="text" name="i-nama" id="i-nama" class="form-control" >
				</div>
			</div>

			<div class="form-group" id="prktdaerah" style="display: none;">
				<label class="col-sm-2 control-label">Perangkat Daerah</label>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
						<input type="text" value="<?php echo $unitkey; ?>" id="f-unitkey" name="f-unitkey" class="form-control text-bold" placeholder="Kode Unit" readonly>
					</div>
				</div>
				<?php 
				if($act == 'edit'){
					if ($unitkey != "")
					{
						$row = $this->db->query("SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = '$unitkey'")->row_array();
						foreach ($row as $r)
						{
							$nama = $r;
						}	
					}else{
						if ($userid == "bid_ekonomi")
						{
							$nama = "BIDANG EKONOMI DAN PERENCANAAN MAKRO";
						}elseif($userid == "bkeu")
						{
							$nama = "BADAN KEUANGAN DAERAH";
						}elseif($userid == "bid_ipw")
						{
							$nama = "BIDANG INFRASTRUKTUR DAN PENGEMBANGAN WILAYAH";
						}elseif($userid == "bid_litbang")
						{
							$nama = "BIDANG PENELITIAN, PENGEMBANGAN DAN EVALUASI";
						}elseif($userid == "bid_sekre")
						{
							$nama = "BIDANG SEKRETARIAT";
						}elseif($userid == "dev")
						{
							$nama = "DEVELOPER";
						}elseif($userid == "dinkes")
						{
							$nama = "DINAS KESEHATAN";
						}elseif($userid == "dishub")
						{
							$nama = "DINAS PERHUBUNGAN";
						}elseif($userid == "diskopukm")
						{
							$nama = "DINAS KOPERASI USAHA KECIL DAN MENENGAH";
						}elseif($userid == "dislh")
						{
							$nama = "DINAS LINGKUNGAN HIDUP";
						}elseif($userid == "disnakerperin")
						{
							$nama = "DINAS TENAGA KERJA DAN  PERINDUSTRIAN";
						}elseif($userid == "disparpora")
						{
							$nama = "DINAS PARIWISATA PEMUDA DAN OLAHRAGA";
						}elseif($userid == "dispupr")
						{
							$nama = "DINAS PEKERJAAN UMUM DAN PENATAAN RUANG";
						}elseif($userid == "distan")
						{
							$nama = "DINAS PERTANIAN";
						}elseif($userid == "pyk_brt")
						{
							$nama = "KECAMATAN PAYAKUMBUH BARAT";
						}elseif($userid == "pyk_slt")
						{
							$nama = "KECAMATAN PAYAKUMBUH SELATAN";
						}elseif($userid == "pyk_tmr")
						{
							$nama = "KECAMATAN PAYAKUMBUH TIMUR";
						}elseif($userid == "pyk_utr")
						{
							$nama = "KECAMATAN PAYAKUMBUH UTARA";
						}elseif($userid == "bid_sosbud")
						{
							$nama = "BIDANG SOSBUD";
						}elseif($userid == "ari")
						{
							$nama = "DEVELOPER";
						}}
				}
				?>

				<div class="form-gap visible-xs-block"></div>
				
				<div class="col-sm-7">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmunit" value="<?php echo $nama; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Password</label>
				<div class="col-sm-9">
					<input type="password" name="i-phppwd" id="i-phppwd" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Konfirmasi Password</label>
				<div class="col-sm-9">
					<input type="password" name="i-passcon" id="i-passcon" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success "><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
var blockuserform = '.block-user-form ';
function dataLoad() {
	if(isEmpty(getVal('#f-groupid'))) return false;
	$.post('/user/akses_load/', $(blockuserform + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockuserform + '.data-load').html(res);
		}
	});
}

$(function() {
	updateMask(blockuserform);

	$(document).off('submit', blockuserform + '.form-user');
	$(document).on('submit', blockuserform + '.form-user', function(e) {
		e.preventDefault();
		$.post('/user/user_save/<?php echo $act; ?>', $(blockuserform + '.form-user').serializeArray(), function(res, status, xhr) {
			console.log(blockuserform);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modaluserform.close();
				dataLoadUser();
			}
		});
		return false;
	});

	$(document).off('change', '#f-groupid');
	$(document).on('change', '#f-groupid', function(e) {
		e.preventDefault();
		dataLoad();
	});
	
	$(document).off('submit', blockuserform + '.form-load');
	$(document).on('submit', blockuserform + '.form-load', function(e) {
		e.preventDefault();
		dataLoad();
		return false;
	});
	
	$(document).off('click', blockuserform + '.btn-lookup-group');
	$(document).on('click', blockuserform + '.btn-lookup-group', function(e) {
		e.preventDefault();
		var data = {
			'setid'	: '#f-groupid',
			'setnm'	: '.v-nmgroup'
		};
		
		modalLookupGroup = new BootstrapDialog({
			title: 'Lookup Group',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/group/', data)
		});
		modalLookupGroup.open();
		return false;

	});
});
</script>

