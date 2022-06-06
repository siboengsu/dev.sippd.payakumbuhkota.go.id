<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-urusan-form">
	<div class="col-md-12">
		<form class="form-horizontal form-load">
		<input type="hidden" value="1" class="page">
		
		<div class="form-group">
			<label for="f-kdunit" class="col-sm-2 control-label">Pencarian</label>
			<div class="col-sm-4">
				<input type="text" name="f-search_key" class="form-control">
			</div>
			
			<div class="form-gap visible-xs-blockUrusanForm"></div>
			
			<div class="col-sm-4">
				<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
					<option value="1">Kode</option>
					<option value="2">Uraian</option>
				</select>
				<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
			</div>
		</div>
		</form>
		
		<form class="form-urusan">
		<div class="table-responsive">
		<table class="table table-condensed table-bordered table-striped">
		<tr>
			<th class="w1px"></th>
			<th class="w1px">Kode</th>
			<th>Uraian</th>
		</tr>
		<tbody class="data-load">
			<?php echo $urusan; ?>
		</tbody>
		</table>
		</div>
		<button type="submit" class="btn btn-success <?php $this->sip->curdShow('I'); ?>"><i class="fa fa-download"></i> Simpan</button>
		</form>
		
		<div class="text-center block-pagination"></div>
	</div>
</div>
<script>
var blockUrusanForm = '.block-urusan-form ';

function dataLoadUrusan() {
	var page = $(blockUrusanForm + '.page').val();
	$.post('/user/urusan_form_load/'+page, $(blockUrusanForm + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockUrusanForm + '.data-load').html(res);
		}
	});
}
	
$(function() {
	updateSelect();
	
	$(document).off('submit', blockUrusanForm + '.form-load');
	$(document).on('submit', blockUrusanForm + '.form-load', function(e) {
		e.preventDefault();
		$(blockUrusanForm + '.page').val('1');
		dataLoadUrusan();
		return false;
	})

	$(document).off('click', blockUrusanForm + '.btn-page');
	$(document).on('click', blockUrusanForm + '.btn-page', function(e) {
		e.preventDefault();
		$(blockUrusanForm + '.page').val($(this).data('ci-pagination-page'));
		dataLoadUrusan();
		return false;
	});
	
	$(document).off('submit', blockUrusanForm + '.form-urusan');
	$(document).on('submit', blockUrusanForm + '.form-urusan', function(e) {
		e.preventDefault();
		
		var count = $(blockUrusanForm + ".form-urusan input[name='i-check[]']:checked").length;
		if(count == 0) {
			return false;
		}
		
		var data = $.extend({},
			$('.form-urusan').serializeObject(),
			{'f-unitkey' : getVal('#f-unitkey')}
		);
		
		$.post('/user/urusan_save/add/', data, function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoad();
				modalUrusanAdd.close();
			}
		});
		
		return false;
	});
});
</script>

