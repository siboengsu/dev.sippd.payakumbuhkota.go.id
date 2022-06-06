<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Belanja Langsung Pra RKA</h1>
		</div>
	</div>
</div>

<div class="row block-rekening">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<input type="hidden" name="f-unitkey" id="f-unitkey" value="<?php echo $this->session->UNITKEY; ?>" required>
					<input type="hidden" name="f-kegrkpdkey" id="f-kegrkpdkey" required>
					<input type="hidden" value="1" class="page">
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Unit Organisasi</label>
						<div class="col-sm-2">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
								<input type="text" value="<?php echo $this->session->KDUNIT; ?>" id="v-kdunit" class="form-control text-bold" placeholder="Kode Unit" readonly>
							</div>
						</div>
						
						<div class="form-gap visible-xs-block"></div>
						
						<div class="col-sm-8">
							<?php if($this->sip->is_admin()): ?>
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
								</span>
								<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
							</div>
							<?php else: ?>
							<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Kegiatan</label>
						<div class="col-sm-2">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
								<input type="text" id="v-nukeg" class="form-control text-bold" placeholder="Kode Kegiatan" readonly>
							</div>
						</div>
						
						<div class="form-gap visible-xs-block"></div>
						
						<div class="col-sm-8">
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-lookup-programkegiatan"><i class="fa fa-folder-open"></i></button>
								</span>
								<input type="text" id="v-nmkeg" class="form-control text-bold" placeholder="Nama Kegiatan" readonly>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Pencarian</label>
						<div class="col-sm-2">
							<input type="text" name="f-search_key" class="form-control">
						</div>
						
						<div class="form-gap visible-xs-block"></div>
						
						<div class="col-sm-8">
							<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
								<option value="1">Kode</option>
								<option value="2">Uraian</option>
							</select>
							<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-md-4 col-md-offset-4">
		<div class="table-responsive block-pagu">
		<table class="table table-condensed table-bordered">
		<tbody>
		<tr>
			<td class="w1px text-bold text-nowrap">Pagu Kegiatan</td>
			<td class="w1px">:</td>
			<td class="text-right text-bold nu2d pagu-total"></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Pagu Digunakan</td>
			<td class="w1px">:</td>
			<td class="text-right nu2d pagu-used"></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Pagu Sisa</td>
			<td class="w1px">:</td>
			<td class="text-right text-bold nu2d pagu-selisih"></td>
		</tr>
		</tbody>
		</table>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Belanja Langsung</div>
			
			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="text-center w1px">Kode</th>
				<th class="text-center text-nowrap">Uraian</th>
				<th class="text-center text-nowrap">Jumlah</th>
				<th class="w1px">
					<div class="checkbox checkbox-inline">
						<input type="checkbox" class="check-all">
						<label></label>
					</div>
				</th>
			</tr>
			<tbody class="data-load"></tbody>
			</table>
			</div>
			</form>
			
			<div class="text-center block-pagination"></div>
			
			<div class="panel-footer">
				<div class="row">
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-rekening-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-rekening-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 block-detail" style="display:none;">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Penjabaran</div>
			<div class="panel-body">
				<form class="form-inline form-load">
					<input type="hidden" name="f-mtgkey" id="f-mtgkey">
					<input type="hidden" name="f-kdper" id="f-kdper">
					<input type="hidden" value="1" class="page">
					
					<div class="form-group">
						<label class="sr-only"></label>
						<input type="text" name="f-search_key" class="form-control">
					</div>
					<div class="form-group">
						<label class="sr-only"></label>
						<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
							<option value="1">Kode</option>
							<option value="2">Urusan</option>
							<option value="3">Volume</option>
							<option value="4">Satuan</option>
							<option value="5">Tarif</option>
							<option value="6">Jumlah</option>
							<option value="7">Type</option>
						</select>
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
					</div>
				</form>
			</div>
			
			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="w1px text-center text-nowrap va-mid">Kode</th>
				<th class="text-center text-nowrap va-mid">Uraian</th>
				<th class="text-center text-nowrap va-mid">Volume</th>
				<th class="text-center text-nowrap va-mid">Satuan</th>
				<th class="text-center text-nowrap va-mid">Tarif</th>
				<th class="text-center text-nowrap va-mid">Jumlah</th>
				<th class="w1px text-center text-nowrap va-mid">Type</th>
				<th class="w1px text-center text-nowrap va-mid">Edit</th>
				<th class="w1px va-mid">
					<div class="checkbox checkbox-inline">
						<input type="checkbox" class="check-all">
						<label></label>
					</div>
				</th>
				<th class="w1px text-center text-nowrap va-mid">Tambah<br>Anak</th>
				<th class="w1px text-center text-nowrap va-mid">Tambah<br>Saudara</th>
			</tr>
			<tbody class="data-load"></tbody>
			</table>
			</div>
			</form>
			
			<div class="text-center block-pagination"></div>
			
			<div class="panel-footer">
				<div class="row">
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-detail-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-detail-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
var blockRekening = '.block-rekening ',
	blockDetail = '.block-detail ';

function updatePagu(a,b,c) {
	$('.block-pagu .pagu-total').html(a);
	$('.block-pagu .pagu-used').html(b);
	$('.block-pagu .pagu-selisih').html(c);
	updateNum('.block-pagu ');
}

function dataLoadRekening(updateFromDetail) {
	var update = ((typeof updateFromDetail !== 'undefined') ? updateFromDetail : false);
	if(update === false) {
		$(blockDetail).hide();
		$(blockDetail + '.page').val('1');
	}
	
	var page = $(blockRekening + '.page').val();
	$.post('/prarka/rekening_load/' + page, $(blockRekening + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockRekening + '.data-load').html(res);
			updateNum(blockRekening);
			if(update) $('#tr-rekening-' + update).find('.btn-rekening-show-detail').addClass('text-bold text-success');
		}
	});
}

function dataLoadDetail() {
	var page = $(blockDetail + '.page').val(),
		data = $.extend({},
			$(blockDetail + '.form-load').serializeObject(),
			{
				'f-unitkey'		: getVal('#f-unitkey'),
				'f-kegrkpdkey'	: getVal('#f-kegrkpdkey')
			}
		);
	$.post('/prarka/detail_load/' + page, data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockDetail + '.data-load').html(res);
			updateNum(blockDetail);
		}
	});
}

$(function() {
	updateSelect();
	
	// Rekening
	$(document).off('click', '.btn-lookup-programkegiatan');
	$(document).on('click', '.btn-lookup-programkegiatan', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		var data = {
			'unitkey'	: getVal('#f-unitkey'),
			'setid'		: '#f-kegrkpdkey',
			'setkd'		: '#v-nukeg',
			'setnm'		: '#v-nmkeg'
		}
		
		modalLookupProgramKegiatan = new BootstrapDialog({
			title: 'Lookup Kegiatan',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/program_kegiatan/', data)
		});
		modalLookupProgramKegiatan.open();
		
		return false;
	});
	
	$(document).off('change', blockRekening + '#f-unitkey');
	$(document).on('change', blockRekening + '#f-unitkey', function(e) {
		e.preventDefault();
		$('#f-kegrkpdkey').val('');
		$('#v-nukeg, #v-nmkeg').val('');
		$(blockRekening + '.data-load').empty();
	});
	
	$(document).off('change', blockRekening + '#f-kegrkpdkey');
	$(document).on('change', blockRekening + '#f-kegrkpdkey', function(e) {
		e.preventDefault();
		$(blockRekening + '.page').val('1');
		dataLoadRekening();
	});
	
	$(document).off('submit', blockRekening + '.form-load');
	$(document).on('submit', blockRekening + '.form-load', function(e) {
		e.preventDefault();
		$(blockRekening + '.page').val('1');
		dataLoadRekening();
		return false;
	});
	
	$(document).off('click', blockRekening + '.btn-page');
	$(document).on('click', blockRekening + '.btn-page', function(e) {
		e.preventDefault();
		$(blockRekening + '.page').val($(this).data('ci-pagination-page'));
		dataLoadRekening();
		return false;
	});
	
	$(document).off('click', blockRekening + '.check-all');
	$(document).on('click', blockRekening + '.check-all', function(e) {
		var checkboxes = $(blockRekening + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});
	
	$(document).off('click', blockRekening + '.btn-rekening-show-detail');
	$(document).on('click', blockRekening + '.btn-rekening-show-detail', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val(),
			kd = $(this).closest('tr').find('td:eq(0)').text().trim();
		
		$(blockRekening + '.btn-rekening-show-detail').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-mtgkey').val(id);
		$('#f-kdper').val(kd);
		$(blockDetail).fadeIn('fast');
		dataLoadDetail();
	});
	
	$(document).off('click', blockRekening + '.btn-rekening-form');
	$(document).on('click', blockRekening + '.btn-rekening-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
		var act = $(this).data('act'),
			data, title, type;
		
		data = {
			'l-unitkey'		: getVal('#f-unitkey'),
			'l-kegrkpdkey'	: getVal('#f-kegrkpdkey')
		};
		
		if(act == 'add') {
			title = 'Tambah Rekening';
			type = 'type-success';
		} else if(act == 'edit') {
			title = 'Ubah Rekening';
			type = 'type-warning';
		}
		
		modalRekeningForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/prarka/rekening_form/' + act, data)
		});
		modalRekeningForm.open();
		
		return false;
	});
	
	$(document).off('click', blockRekening + '.btn-rekening-delete');
	$(document).on('click', blockRekening + '.btn-rekening-delete', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if($(blockRekening + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar rekening yang dipilih ?', 
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockRekening + '.form-delete').serializeObject(),
						{
							'i-unitkey'		: getVal('#f-unitkey'),
							'i-kegrkpdkey'	: getVal('#f-kegrkpdkey')
						}
					);
					$.post('/prarka/rekening_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadRekening();
						}
					});
				}
			}
		});
		
		return false;
	});
	
	// Detail
	$(document).off('submit', blockDetail + '.form-load');
	$(document).on('submit', blockDetail + '.form-load', function(e) {
		e.preventDefault();
		$(blockDetail + '.page').val('1');
		dataLoadDetail();
		return false;
	});
	
	$(document).off('click', blockDetail + '.btn-page');
	$(document).on('click', blockDetail + '.btn-page', function(e) {
		e.preventDefault();
		$(blockDetail + '.page').val($(this).data('ci-pagination-page'));
		dataLoadDetail();
		return false;
	});
	
	$(document).off('click', blockDetail + '.check-all');
	$(document).on('click', blockDetail + '.check-all', function(e) {
		var checkboxes = $(blockDetail + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});
	
	$(document).off('click', blockDetail + '.btn-detail-form');
	$(document).on('click', blockDetail + '.btn-detail-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
		if(isEmpty(getVal('#f-mtgkey'))) return false;
		var act = $(this).data('act'),
			set = $(this).data('set'),
			data, title, type;
		
		data = {
			'f-unitkey'		: getVal('#f-unitkey'),
			'f-kegrkpdkey'	: getVal('#f-kegrkpdkey'),
			'f-mtgkey'		: getVal('#f-mtgkey'),
			'f-kdper'		: getVal('#f-kdper')
		};
		
		if(act == 'add') {
			title = 'Tambah Penjabaran';
			type = 'type-success';
			
			if(set == 'C') {
				var kode = $(this).closest('tr').find('td:eq(0)').text().trim();
			} else if (set == 'S') {
				var kode = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
			}
			
			data = $.extend({},
				data,
				{'f-kode' : kode, 'f-set' : set}
			);
		} else if(act == 'edit') {
			title = 'Ubah Penjabaran';
			type = 'type-warning';
			
			data = $.extend({},
				data,
				{'f-kdnilai' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
			);
		}
		
		modalDetailForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/prarka/detail_form/' + act, data)
		});
		modalDetailForm.open();
		
		return false;
	});
	
	$(document).off('click', blockDetail + '.btn-detail-delete');
	$(document).on('click', blockDetail + '.btn-detail-delete', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
		if(isEmpty(getVal('#f-mtgkey'))) return false;
		if($(blockDetail + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar yang dipilih ?', 
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockDetail + '.form-delete').serializeObject(),
						{
							'i-unitkey'		: getVal('#f-unitkey'),
							'i-kegrkpdkey'	: getVal('#f-kegrkpdkey'),
							'i-mtgkey'		: getVal('#f-mtgkey')
						}
					);
					$.post('/prarka/detail_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadDetail();
						}
					});
				}
			}
		});
		
		return false;
	});
});
</script>

