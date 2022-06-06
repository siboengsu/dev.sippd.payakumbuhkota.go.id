<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-akses">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Hak Akses Group</h1>
		</div>
	</div>
	
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Group</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<input type="hidden" name="f-groupid" id="f-groupid" value="<?php echo $this->session->GROUPID; ?>" required>
					<input type="hidden" value="1" class="page">
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Group</label>
						<div class="col-sm-2">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
								<input type="text" value="" class="form-control text-bold v-nmgroup" placeholder="Nama Group" readonly>
							</div>
						</div>
						
						<div class="form-gap visible-xs-block"></div>
						
						<div class="col-sm-8">
							<?php if($this->sip->is_admin()): ?>
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-lookup-group"><i class="fa fa-folder-open"></i></button>
								</span>
								<input type="text" value="Pilih Group Admin" class="form-control text-bold v-nmgroup" placeholder="Nama Group" readonly>
							</div>
							<?php else: ?>
							<input type="text" value="<?php echo $this->session->NMGROUP; ?>" class="form-control text-bold v-nmgroup" placeholder="Nama Group" readonly>
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
								<option value="1">Id</option>
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
			<div class="panel-heading text-center text-bold">Akses SOPD</div>
			
			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped">
			<tr>
				<th>Id</th>
				<th>Uraian</th>
				<th>Tipe</th>
				<th>Tahap</th>
				<th class="w1px">
					<div class="checkbox checkbox-inline">
						<input type="checkbox" class="check-all">
						<label></label>
					</div>
				</th>
			</tr>
			<tbody class="data-load">
				<?php echo $akses; ?>
			</tbody>
			</table>
			</div>
			</form>
			
			<div class="panel-footer">
				<div class="row">
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-akses-form <?php $this->sip->curdShow('I'); ?>"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-akses-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<link href="<?php echo base_url(); ?>assets/node_modules/jstree/dist/themes/default-dark/style.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/node_modules/jstree/dist/jstree.min.js"></script>

<script>
var blockAkses = '.block-akses ';

function dataLoad() {
	if(isEmpty(getVal('#f-groupid'))) return false;
	$.post('/user/akses_load/', $(blockAkses + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockAkses + '.data-load').html(res);
		}
	});
}

$(function() {
	updateSelect();
	
	$(document).off('change', '#f-groupid');
	$(document).on('change', '#f-groupid', function(e) {
		e.preventDefault();
		dataLoad();
	});
	
	$(document).off('submit', blockAkses + '.form-load');
	$(document).on('submit', blockAkses + '.form-load', function(e) {
		e.preventDefault();
		dataLoad();
		return false;
	});
	
	$(document).off('click', blockAkses + '.btn-lookup-group');
	$(document).on('click', blockAkses + '.btn-lookup-group', function(e) {
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
	
	$(document).off('click', blockAkses + '.check-all');
	$(document).on('click', blockAkses + '.check-all', function(e) {
		var checkboxes = $("input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});
	
	$(document).off('click', blockAkses + '.btn-akses-form');
	$(document).on('click', blockAkses + '.btn-akses-form', function(e) {
		e.preventDefault();
		var data = $('.form-load').serializeArray();
		modalAksesForm = new BootstrapDialog({
			title: 'Tambah Akses',
			type: 'type-success',
			size: 'size-wide',
			message: $('<div></div>').load('/user/akses_form', data),
			onhidden: function() {
				dataLoad();
			}
		});
		modalAksesForm.open();
	});
	
	$(document).off('click', blockAkses + '.btn-akses-delete');
	$(document).on('click', blockAkses + '.btn-akses-delete', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-groupid'))) return false;
		if($(".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar akses yang dipilih ?', 
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$('.form-delete').serializeObject(),
						{'i-groupid' : getVal('#f-groupid')}
					);
					$.post('/user/akses_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoad();
						}
					});
				}
			}
		});
		
		return false;
	});
});
// </script>

