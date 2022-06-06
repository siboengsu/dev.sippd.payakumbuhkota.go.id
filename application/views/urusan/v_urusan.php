<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-urusan">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Urusan OPD</h1>
		</div>
	</div>
	
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<input type="hidden" name="f-unitkey" id="f-unitkey" value="<?php echo $this->session->UNITKEY; ?>" required>
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
	
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Urusan SOPD</div>
			
			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped">
			<tr>
				<th class="w1px">Kode</th>
				<th>Uraian</th>
				<th class="w1px">
					<div class="checkbox checkbox-inline">
						<input type="checkbox" class="check-all">
						<label></label>
					</div>
				</th>
			</tr>
			<tbody class="data-load">
				<?php echo $urusan; ?>
			</tbody>
			</table>
			</div>
			</form>
			
			<div class="text-center block-pagination"></div>
			
			<div class="panel-footer">
				<div class="row">
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-urusan-form <?php $this->sip->curdShow('I'); ?>"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-urusan-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var blockUrusan = '.block-urusan ';

function dataLoad() {
	$.post('/user/urusan_load/', $('.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockUrusan + '.data-load').html(res);
		}
	});
}
	
$(function() {
	updateSelect();
	
	$(document).off('change', '#f-unitkey');
	$(document).on('change', '#f-unitkey', function(e) {
		e.preventDefault();
		dataLoad();
	});
	
	$(document).off('submit', blockUrusan + '.form-load');
	$(document).on('submit', blockUrusan + '.form-load', function(e) {
		e.preventDefault();
		dataLoad();
		return false;
	});
	
	$(document).off('click', blockUrusan + '.check-all');
	$(document).on('click', blockUrusan + '.check-all', function(e) {
		var checkboxes = $("input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});
	
	$(document).off('click', blockUrusan + '.btn-urusan-form');
	$(document).on('click', blockUrusan + '.btn-urusan-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		var data = $(blockUrusan + '.form-load').serializeArray();
		modalUrusanAdd = new BootstrapDialog({
			title: 'Tambah Urusan OPD',
			type: 'type-success',
			size: 'size-wide',
			message: $('<div></div>').load('/user/urusan_form', data),
			onhidden: function() {
				dataLoad();
			}
		});
		modalUrusanAdd.open();
	});
	
	$(document).off('click', blockUrusan + '.btn-urusan-delete');
	$(document).on('click', blockUrusan + '.btn-urusan-delete', function(e) {
		e.preventDefault();
		var count = $(blockUrusan + "input[name='i-check[]']:checked").length;
		if(count == 0) {
			return false;
		}
		
		goConfirm({
			msg : 'Hapus daftar urusan yang dipilih ?', 
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$('.form-delete').serializeObject(),
						{'f-unitkey' : getVal('#f-unitkey')}
					);
					
					$.post('/user/urusan_save/delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoad();
						}
					});
				}
			}
		});
	});
});
</script>

