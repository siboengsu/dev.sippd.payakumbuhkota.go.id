<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Import Data</h1>
		</div>
	</div>
</div>

<div class="row block-import">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold"></div>
			<div class="panel-body">

          <div class="row">
      			<div class="col-md-12">
      				<div class="header-renja">
      				<h2 style='text-decoration: underline;'>	<i class='fa fa-info-circle' style='color:red'></i>
      					Import Data Musrenbang</h2>
      				</div>
                <div class="col-xs-6"><button type="button" class="btn btn-primary btn-import-musrenbang" data-act="add"><i class="fa fa-plus"></i> Import</button></div>
      			</div>

      		</div>

          <div class="row">
            <div class="col-md-12">
              <div class="header-renja">
              <h2 style='text-decoration: underline;'>	<i class='fa fa-info-circle' style='color:red'></i>
                Import Data Epokir</h2>
              </div>
                <div class="col-xs-6"><button type="button" class="btn btn-primary btn-import-pokir" data-act="add"><i class="fa fa-plus"></i> import</button></div>
            </div>

            	</div>
		</div>
	</div>
</div>


<script>

var blockEntryPagu = '.block-import ';

$(function() {
	$(document).off('click', blockEntryPagu + '.btn-pagu-tambah');
	$(document).on('click', blockEntryPagu + '.btn-pagu-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Entry Pagu Baru';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Pagu';
			type = 'type-warning';
			data = { 'f-unitkey'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			}

		modalEntryPaguForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/master/pagu_form/' + act, data)
		});
		modalEntryPaguForm.open();

		return false;
	});

	$(document).off('click', blockEntryPagu + '.btn-page');
	$(document).on('click', blockEntryPagu + '.btn-page', function(e) {
		e.preventDefault();
		$(blockEntryPagu + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterPagu();
		return false;
	});

	$(document).off('submit', blockEntryPagu + '.form-load');
	$(document).on('submit', blockEntryPagu + '.form-load', function(e) {
		e.preventDefault();
		$(blockEntryPagu + '.page').val('1');
		dataLoadMasterPagu


		();
		return false;
	});


		$(document).off('click', blockEntryPagu + '.check-all');
	$(document).on('click', blockEntryPagu + '.check-all', function(e) {
		var checkboxes = $(blockEntryPagu + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});



	$(document).off('click', blockEntryPagu + '.btn-pagu-delete');
	$(document).on('click', blockEntryPagu + '.btn-pagu-delete', function(e) {
		e.preventDefault();
			if($(blockEntryPagu + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar Pagu OPD yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockEntryPagu + '.form-delete').serializeObject()

					);
					$.post('/master/pagu_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadMasterPagu();
						}
					});
				}
			}
		});

		return false;
	});


});
</script>
