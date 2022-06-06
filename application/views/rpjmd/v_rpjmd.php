<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Penyusunan RPJMD</h1>
		</div>
	</div>
</div>

<div class="row blockJadwal">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>
				<i class='fa fa-info-circle' style='color:red'></i>
					Jadwal</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Jadwal</div>
			<form class="form-delete">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped f12">
                        <tr>
                            <th class="col-md-4 text-center ">RPJMD</th>
                            <th class="col-md-7 text-center text-nowrap">Jadwal</th>
                            <th class="col-md-1 text-center text-nowrap">Action</th>
                            <th class="w1px text-center">
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-jadwal-form <?php $this->sip->curdShow('I' ); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-jadwal-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row blockVisi">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>
				<i class='fa fa-info-circle' style='color:red'></i>
					Visi</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Visi</div>
			<form class="form-inline form-load">
				<input type="hidden" name="f-idjadwal" id="f-idjadwal">
				<input type="hidden" value="1" class="page">
			</form>
			<form class="form-delete">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped f12">
                        <tr>
                            <th class="col-md-1 text-center w1px">Kode</th>
                            <th class="col-md-10 text-center text-nowrap">Visi</th>
                            <th class="col-md-1 text-center text-nowrap">Action</th>
                            <th class="w1px text-center">
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-visi-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-visi-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row blockMisi">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>
					<i class='fa fa-info-circle' style='color:red'></i>
					Misi</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Misi</div>
			<form class="form-inline form-load">
				<input type="hidden" name="f-idvisi" id="f-idvisi">
				<input type="hidden" value="1" class="page">
			</form>
			<form class="form-delete">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped f12">
                        <tr>
                            <th class="col-md-1 text-center w1px">No Misi</th>
                            <th class="col-md-10 text-center text-nowrap">Misi</th>
                            <th class="col-md-1 text-center text-nowrap">Action</th>
                            <th class="w1px text-center">
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-misi-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-misi-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row blockTujuan">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>
					<i class='fa fa-info-circle' style='color:red'></i>
					Tujuan</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Tujuan</div>
			<form class="form-inline form-load">
				<input type="hidden" name="f-misikey" id="f-misikey">
				<input type="hidden" value="1" class="page">
			</form>
			<form class="form-delete">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped f12">
                        <tr>
                            <th class="col-md-1 text-center w1px">No. Tujuan</th>
                            <th class="col-md-10 text-center text-nowrap">Tujuan</th>
                            <th class="col-md-1 text-center text-nowrap">Action</th>
                            <th class="w1px text-center">
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-misi-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-misi-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row blockSasaran">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>
					<i class='fa fa-info-circle' style='color:red'></i>
					Sasaran</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Sasaran</div>
			<form class="form-inline form-load">
				<input type="hidden" name="f-tujukey" id="f-tujukey">
				<input type="hidden" value="1" class="page">
			</form>
			<form class="form-delete">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped f12">
                        <tr>
                            <th class="text-center w1px">No. Sasaran</th>
                            <th class="text-center text-nowrap">Sasaran</th>
							<th class="text-center text-nowrap">Indikator Sasaran</th>
                            <th class="text-center text-nowrap">Action</th>
                            <th class="w1px text-center">
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-sasaran-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-sasaran-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row blockProgram">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>
					<i class='fa fa-info-circle' style='color:red'></i>
					Program</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Program</div>
			<form class="form-inline form-load">
				<input type="hidden" name="f-idsasaran" id="f-idsasaran">
				<input type="hidden" value="1" class="page">
			</form>
			<form class="form-delete">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped f12">
                        <tr>
                            <th class="text-center text-nowrap">No. Progrma</th>
                            <th class="text-center text-nowrap">Sasaran</th>
							<th class="text-center text-nowrap">Indikator Sasaran</th>
                            <th class="text-center text-nowrap">Action</th>
                            <th class="w1px text-center">
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-sasaran-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-sasaran-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

var blockJadwal	= '.blockJadwal ',
blockVisi   	= '.blockVisi ',
blockMisi       = '.blockMisi ',
blockTujuan     = '.blockTujuan ',
blockSasaran    = '.blockSasaran ',
blockProgram    = '.blockProgram '

function dataLoadJadwal() {
	$(blockVisi).hide();
	updateSelect(blockJadwal);
	var page = $(blockJadwal + '.page').val();
	$.post('/rpjmd/jadwal_load/' + page, $(blockJadwal + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockJadwal + '.data-load').html(res);
		}
	});
}
window.onload=dataLoadJadwal();

function dataLoadVisi() {
	$(blockMisi).hide();
	updateSelect(blockVisi);
	var page = $(blockVisi + '.page').val();
	$.post('/rpjmd/visi_load/' + page, $(blockVisi + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockVisi + '.data-load').html(res);
		}
	});
}
window.onload=dataLoadVisi();

function dataLoadMisi() {
	$(blockTujuan).hide();
	updateMask(blockMisi);
	var page = $(blockMisi + '.page').val();
	$.post('/rpjmd/misi_load/' + page, $(blockMisi + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockMisi + '.data-load').html(res);
		}
	});
}
window.onload=dataLoadMisi();

function dataLoadTujuan() {
	$(blockSasaran).hide();
	updateMask(blockTujuan);
	var page = $(blockTujuan + '.page').val();
	$.post('/rpjmd/tujuan_load/' + page, $(blockTujuan + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockTujuan + '.data-load').html(res);
		}
	});
}
window.onload=dataLoadTujuan();

function dataLoadSasaran() {
	$(blockProgram).hide();
	updateMask(blockSasaran);
	var page = $(blockSasaran + '.page').val();
	$.post('/rpjmd/sasaran_load/' + page, $(blockSasaran + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockSasaran + '.data-load').html(res);
		}
	});
}
window.onload=dataLoadSasaran();

function dataLoadProgram() {
	updateMask(blockProgram);
	var page = $(blockProgram + '.page').val();
	$.post('/rpjmd/program_load/' + page, $(blockProgram + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockProgram + '.data-load').html(res);
		}
	});
}
window.onload=dataLoadProgram();

$(function() {
	$(document).off('click', blockJadwal + '.btn-jadwal-form');
    $(document).on('click', blockJadwal + '.btn-jadwal-form', function(e) {
        e.preventDefault();
        var act = $(this).data('act'),
            data, title, type;
        if(act == 'add') {
            title = 'Tambah jadwal';
            type = 'type-success';

        } else if(act == 'edit') {
            title = 'Ubah jadwal';
            type = 'type-warning';
            data = {
                'i-idjadwal' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()
            };
        }
        modalJadwalForm = new BootstrapDialog({
            title: title,
            type: type,
            size: 'size-wide',
            message: $('<div></div>').load('/rpjmd/jadwal_form/' + act, data)
        });
        modalJadwalForm.open();
        return false;
    });

	$(document).off('click', blockJadwal + '.btn-jadwal-delete');
	$(document).on('click', blockJadwal + '.btn-jadwal-delete', function(e) {
		e.preventDefault();
		if($(blockJadwal + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus jadwal yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockJadwal + '.form-delete').serializeObject()
					);
					console.log(data);
					$.post('/rpjmd/jadwal_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadJadwal();
						}
					});
				}
			}
		});

		return false;
	});

	$(document).off('click', blockJadwal + '.btn-show-visi');
	$(document).on('click', blockJadwal + '.btn-show-visi', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockJadwal + '.btn-show-visi').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-idjadwal').val(id);
		$(blockVisi).fadeIn('fast');
		dataLoadVisi();
	});

    $(document).off('click', blockVisi + '.btn-visi-form');
    $(document).on('click', blockVisi + '.btn-visi-form', function(e) {
        e.preventDefault();
        var act = $(this).data('act'),
            data, title, type;
        if(act == 'add') {
            title = 'Tambah Visi';
            type = 'type-success';
			data = {'f-idjadwal'	: getVal('#f-idjadwal')};
        } else if(act == 'edit') {
            title = 'Ubah Visi';
            type = 'type-warning';
            data = {
				'f-idjadwal'	: getVal('#f-idjadwal'),
                'i-idvisi' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()
            };
        }
        modalVisiForm = new BootstrapDialog({
            title: title,
            type: type,
            size: 'size-wide',
            message: $('<div></div>').load('/rpjmd/visi_form/' + act, data)
        });
        modalVisiForm.open();
        return false;
    });

	$(document).off('click', blockVisi + '.btn-visi-delete');
	$(document).on('click', blockVisi + '.btn-visi-delete', function(e) {
		e.preventDefault();
		if($(blockVisi + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus visi yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockVisi + '.form-delete').serializeObject()
					);
					$.post('/rpjmd/visi_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadVisi();
						}
					});
				}
			}
		});

		return false;
	});

    $(document).off('click', blockVisi + '.btn-show-misi');
	$(document).on('click', blockVisi + '.btn-show-misi', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockVisi + '.btn-show-misi').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-idvisi').val(id);
		$(blockMisi).fadeIn('fast');
		dataLoadMisi();
	});

	$(document).off('click', blockMisi + '.btn-misi-form');
    $(document).on('click', blockMisi + '.btn-misi-form', function(e) {
        e.preventDefault();
        var act = $(this).data('act'),
            data, title, type;
        if(act == 'add') {
            title = 'Tambah Misi';
            type = 'type-success';
			data = {'f-idvisi'	: getVal('#f-idvisi')};
        } else if(act == 'edit') {
            title = 'Ubah Misi';
            type = 'type-warning';
            data = {
                'i-misikey' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()
            };
        }
        modalMisiForm = new BootstrapDialog({
            title: title,
            type: type,
            size: 'size-wide',
            message: $('<div></div>').load('/rpjmd/misi_form/' + act, data)
        });
        modalMisiForm.open();
        return false;
    });

	$(document).off('click', blockMisi + '.btn-show-tujuan');
	$(document).on('click', blockMisi + '.btn-show-tujuan', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockMisi + '.btn-show-tujuan').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-misikey').val(id);
		$(blockTujuan).fadeIn('fast');
		dataLoadTujuan();
	});

	$(document).off('click', blockTujuan + '.btn-show-sasaran');
	$(document).on('click', blockTujuan + '.btn-show-sasaran', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockTujuan + '.btn-show-sasaran').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-tujukey').val(id);
		$(blockSasaran).fadeIn('fast');
		dataLoadSasaran();
	});

	$(document).off('click', blockSasaran + '.btn-sasaran-form');
    $(document).on('click', blockSasaran + '.btn-sasaran-form', function(e) {
        e.preventDefault();
        var act = $(this).data('act'),
            data, title, type;
        if(act == 'add') {
            title = 'Tambah Sasaran';
            type = 'type-success';
			data = {'f-tujukey'	: getVal('#f-tujukey')};
        } else if(act == 'edit') {
            title = 'Ubah Sasaran';
            type = 'type-warning';
            data = {
                'i-id' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()
            };
        }
        modalSasaranForm = new BootstrapDialog({
            title: title,
            type: type,
            size: 'size-wide',
            message: $('<div></div>').load('/rpjmd/sasaran_form/' + act, data)
        });
        modalSasaranForm.open();
        return false;
    });

	$(document).off('click', blockSasaran + '.btn-show-program');
	$(document).on('click', blockSasaran + '.btn-show-program', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockSasaran + '.btn-show-program').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-idsasaran').val(id);
		$(blockProgram).fadeIn('fast');
		dataLoadProgram();
	});
});
</script>
