
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Pengajuan Renja</h1>
		</div>
	</div>
</div>

<div class="row block-program">
	<div class="col-md-12">

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
								<option value="2">Program</option>
								<option value="3">Sasaran</option> 
								<option value="4">Keterangan</option>
							</select>
							<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
							<?php $onlyadmin = $this->session->USERID; if($onlyadmin == "dev" or $onlyadmin == "ari" or $onlyadmin == "ima") : ?>
							<button type="button" class="btn btn-primary btn-import-data"><i class="fa fa-upload"> Import Data</i> </button>
							<?php endif; ?>
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
			<td class="w1px text-bold text-nowrap">Pagu OPD</td>
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
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>	<i class='fa fa-info-circle' style='color:red'></i>
					Program</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
		
			<div class="panel-heading text-center text-bold">Draft Program RKPD
			</div>

			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="text-center w1px">Kode</th>
				<th class="text-center text-nowrap">Program</th>
				<th class="text-center text-nowrap">IKU (OPD)</th>
				<th class="text-center text-nowrap">Target</th>
				<th class="text-center text-nowrap">Prioritas Daerah</th>
				<th class="text-center text-nowrap">Sasaran Daerah</th>
				<th class="text-center text-nowrap">Indikator Program</th>
				<th class="text-center text-nowrap">Target Sebelum</th>
				<th class="text-center text-nowrap">Target Sesudah</th>
				<th class="text-center text-nowrap">Tanggal Valid</th>
				<th class="text-center text-nowrap">Edit</th>
				<th class="w1px">
					<div class="checkbox checkbox-inline">
						<input type="checkbox" class="check-all">
						<label></label>
					</div>
				</th>
			</tr>
			<tbody class="data-load"><?php echo $program; ?></tbody>
			</table>
			</div>
			</form>

			<div class="text-center block-pagination"></div>

			<div class="panel-footer">
				<div class="row">
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-program-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-program-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 block-kegiatan" style="display:none;">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>	<i class='fa fa-info-circle' style='color:red'></i>
					Kegiatan</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Kegiatan RKPD</div>
			<div class="panel-body">
				<form class="form-inline form-load">
					<input type="hidden" name="f-pgrmrkpdkey" id="f-pgrmrkpdkey">
					<input type="hidden" value="1" class="page">

					<div class="form-group">
						<label class="sr-only"></label>
						<input type="text" name="f-search_key" class="form-control">
					</div>
					<div class="form-group">
						<label class="sr-only"></label>
						<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
							<option value="1">Kode</option>
							<option value="2">Program</option>
							<option value="5">Target Sebelum</option>
							<option value="4">Target Sesudah</option>
						</select>
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
					</div>
				</form>
			</div>

			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="text-center w1px">Kode</th>
				<th class="text-center text-nowrap">Kegiatan</th>
				<th class="text-center text-nowrap">Pagu</th>
				<th class="text-center text-nowrap">Target Sebelum</th>
				<th class="text-center text-nowrap">Target Sesudah</th>
				<th class="text-center text-nowrap">Lokasi</th>
				<th class="text-center w1px">Responsive Gender</th>
				<th class="text-center text-nowrap">Tanggal Valid</th>
				<th class="text-center text-nowrap">Edit</th>
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-kegiatan-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-kegiatan-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12 block-rincian" style="display:none;">
		<!-- <form class="form-load">
			<input type="hidden" name="f-kegrkpdkey" id="f-kegrkpdkey">
		</form> -->
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>	<i class='fa fa-info-circle' style='color:red'></i>
					Rincian Kegiatan</h2>
				</div>
			</div>
		</div>
		<form class="form-load">
			<input type="hidden" name="f-kegrkpdkey" id="f-kegrkpdkey">
		</form>

		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Rincian Kegiatan</div>
			<div style="margin:10px 5px 0 5px;">
				<ul class="nav nav-pills nav-justified" id="rincian-tabs">
					<li class="active"><a class="text-bold" href="#block-rincian-kinerja" data-toggle="tab" data-go="kinerja">Kinerja Kegiatan</a></li>
					<li><a class="text-bold" href="#block-rincian-penjabaran" data-toggle="tab" data-go="penjabaran">Penjabaran Kegiatan</a></li>
					<li><a class="text-bold" href="#block-rincian-sumberdana" data-toggle="tab" data-go="sumberdana">Sumber Dana</a></li>
				</ul>
			</div>
			<div class="panel-body">
				<div class="tab-content">

					<div class="tab-pane fade in active" id="block-rincian-kinerja">
						<form class="form-delete">
						<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped va-mid f12">
						<thead>
						<tr>
							<th class="text-center text-nowrap">Indikator</th>
							<th class="text-center text-nowrap">Tolak Ukur</th>
							<th class="text-center text-nowrap">Target Kinerja (n-1)</th>
							<th class="text-center text-nowrap">Target Kinerja n</th>
							<th class="text-center text-nowrap">Target Kinerja (n+1)</th>
							<th class="text-center text-nowrap">Edit</th>
							<th class="w1px">
								<div class="checkbox checkbox-inline">
									<input type="checkbox" class="check-all">
									<label></label>
								</div>
							</th>
						</tr>
						</thead>
						<tbody class="data-load"></tbody>
						</table>
						</div>
						</form>

						<div class="row">
							<div class="col-xs-6"><button type="button" class="btn btn-primary btn-rincian-kinerja-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
							<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-rincian-kinerja-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
						</div>
					</div>

					<div class="tab-pane fade" id="block-rincian-penjabaran">
						<form class="form-delete">
						<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped f12">
						<thead>
						<tr>
							<th class="text-center w1px">Kode</th>
							<th class="text-center text-nowrap">Uraian</th>
							<th class="text-center text-nowrap">Ekspresi</th>
							<th class="text-center text-nowrap">Volume</th>
							<th class="text-center text-nowrap">Satuan</th>
							<th class="text-center text-nowrap">Tarif</th>
							<th class="text-center text-nowrap">Jumlah</th>
							<th class="text-center text-nowrap">Dana</th>
							<th class="text-center text-nowrap">Type</th>
							<th class="text-center text-nowrap">Edit</th>
							<th class="w1px">
								<div class="checkbox checkbox-inline">
									<input type="checkbox" class="check-all">
									<label></label>
								</div>
							</th>
							<th class="text-center text-nowrap">Tambah Anak</th>
							<th class="text-center text-nowrap">Tambah Saudara</th>
						</tr>
						</thead>
						<tbody class="data-load"></tbody>
						</table>
						</div>
						</form>

						<div class="row">
							<div class="col-xs-6"><button type="button" style="display:none;" class="btn btn-primary btn-rincian-penjabaran-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
							<div class="col-xs-6 text-right"><button type="button" style="display:none;" class="btn btn-danger btn-rincian-penjabaran-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
						</div>
					</div>

					<div class="tab-pane fade" id="block-rincian-sumberdana">
						<form class="form-delete">
						<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped f12">
						<thead>
						<tr>
							<th class="text-center text-nowrap w1px">Kode</th>
							<th class="text-center text-nowrap">Sumber Dana</th>
							<th class="text-center text-nowrap">Nilai</th>
							<th class="text-center text-nowrap">Keterangan</th>
							<th class="text-center text-nowrap">Edit</th>
							<th class="w1px">
								<div class="checkbox checkbox-inline">
									<input type="checkbox" class="check-all">
									<label></label>
								</div>
							</th>
						</tr>
						</thead>
						<tbody class="data-load"></tbody>
						</table>
						</div>
						</form>

						<div class="row">
							<div class="col-xs-6"><button type="button" class="btn btn-primary btn-rincian-sumberdana-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
							<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-rincian-sumberdana-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
						</div>
					</div>
				</div>
			</div>


		</div>
	</div>


	<div class="col-md-12 block-subkegiatan" style="display:none;">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>	<i class='fa fa-info-circle' style='color:red'></i>
					Sub Kegiatan</h2>
				</div>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Draft Sub Kegiatan RKPD</div>
			<div class="panel-body">
				<form class="form-inline form-load">
					<input type="hidden" name="s-kegrkpdkey" id="s-kegrkpdkey">
					<input type="hidden" value="1" class="page">

					<div class="form-group">
						<label class="sr-only"></label>
						<input type="text" name="f-search_key" class="form-control">
					</div>
					<div class="form-group">
						<label class="sr-only"></label>
						<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
							<option value="1">Kode</option>
							<option value="2">Sub Kegiatan</option>
							<option value="4">Target Sebelum</option>
							<option value="3">Target Sesudah</option>
						</select>
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
					</div>
				</form>
			</div>

			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="text-center w1px">Kode</th>
				<th class="text-center text-nowrap">Sub Kegiatan</th>
				<th class="text-center text-nowrap">Pagu</th>
				<th class="text-center text-nowrap">Target Sebelum</th>
				<th class="text-center text-nowrap">Target Sesudah</th>
				<th class="text-center text-nowrap">Lokasi</th>
				<th class="text-center w1px">RG</th>
				<th class="text-center w1px">SPM</th>
				<th class="text-center w1px">PKD</th>
				<th class="text-center text-nowrap">Tanggal Valid</th>
				<th class="text-center text-nowrap">Edit</th>
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-subkegiatan-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-subkegiatan-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-md-12 block-rincian-subkegiatan" style="display:none;">
		<div class="row">
			<div class="col-md-12">
				<div class="header-renja">
				<h2 style='text-decoration: underline;'>	<i class='fa fa-info-circle' style='color:red'></i>
					Sub Kegiatan</h2>
				</div>
			</div>
		</div>
		<form class="form-load">
			<input type="hidden" name="f-subkegrkpdkey" id="f-subkegrkpdkey">
		</form>

		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Rincian Sub Kegiatan</div>
			<div style="margin:10px 5px 0 5px;">
				<ul class="nav nav-pills nav-justified" id="rincian-tabs-sub">
					<li class="active"><a class="text-bold" href="#block-rincian-musrenbang" data-toggle="tab" data-go="musrenbang">Musrenbang</a></li>
					<li><a class="text-bold" href="#block-rincian-pokir" data-toggle="tab" data-go="pokir">Pokir</a></li>
					<li><a class="text-bold" href="#block-rincian-kinerja-subkegiatan" data-toggle="tab" data-go="kinerjasub">Kinerja Sub Kegiatan</a></li>
					<li><a class="text-bold" href="#block-rincian-sumberdana-subkegiatan" data-toggle="tab" data-go="sumberdanasub">Sumber Dana Sub Kegiatan</a></li>
				</ul>
			</div>
			<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane fade in active" id="block-rincian-musrenbang">
						<form class="form-delete">
						<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped va-mid f12">
						<thead>
						<tr>
							<th class="text-center text-nowrap">Kecamatan</th>
							<th class="text-center text-nowrap">Kelurahan</th>
							<th class="text-center text-nowrap">Lokasi</th>
							<th class="text-center text-nowrap">Keterangan</th>
							<th class="text-center text-nowrap">Jenis Pekerjaan</th>
							<th class="text-center text-nowrap">Volume</th>
							<th class="text-center text-nowrap">Satuan</th>
							<th class="text-center text-nowrap">Harga Satuan</th>
							<th class="text-center text-nowrap">Total</th>
							<th class="text-center text-nowrap">Edit</th>
							<th class="w1px">
								<div class="checkbox checkbox-inline">
									<input type="checkbox" class="check-all">
									<label></label>
								</div>
							</th>
						</tr>
						</thead>
						<tbody class="data-load" id="test"></tbody>
						</table>
						</div>
						</form>
					</div>

					
					<div class="tab-pane fade" id="block-rincian-pokir">
						<form class="form-delete">
						<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped va-mid f12">
						<thead>
						<tr>
							<th class="text-center text-nowrap">Kecamatan</th>
							<th class="text-center text-nowrap">Kelurahan</th>
							<th class="text-center text-nowrap">Lokasi</th>
							<th class="text-center text-nowrap">Keterangan</th>
							<th class="text-center text-nowrap w1px">Kode HSPK</th>
							<th class="text-center text-nowrap">Jenis Pekerjaan</th>
							<th class="text-center text-nowrap">Satuan</th>
							<th class="text-center text-nowrap">Volume</th>
							<th class="text-center text-nowrap">Harga Satuan</th>
							<th class="text-center text-nowrap">Total</th>
							<th class="text-center text-nowrap">Edit</th>
							<th class="w1px">
								<div class="checkbox checkbox-inline">
									<input type="checkbox" class="check-all">
									<label></label>
								</div>
							</th>
						</tr>
						</thead>
						<tbody class="data-load"></tbody>
						</table>
						</div>
						</form>

					</div>

					<div class="tab-pane fade " id="block-rincian-kinerja-subkegiatan">
						<form class="form-delete">
						<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped va-mid f12">
						<thead>
						<tr>
							<th class="text-center text-nowrap">Indikator</th>
							<th class="text-center text-nowrap">Tolak Ukur</th>
							<th class="text-center text-nowrap">Target Kinerja Sub Kegiatan(n-1)</th>
							<th class="text-center text-nowrap">Target Kinerja Sub Kegiatan n</th>
							<th class="text-center text-nowrap">Target Kinerja Sub Kegiatan (n+1)</th>
							<th class="text-center text-nowrap">Edit</th>
							<th class="w1px">
								<div class="checkbox checkbox-inline">
									<input type="checkbox" class="check-all">
									<label></label>
								</div>
							</th>
						</tr>
						</thead>
						<tbody class="data-load"></tbody>
						</table>
						</div>
						</form>

						<div class="row">
							<div class="col-xs-6"><button type="button" class="btn btn-primary btn-sub-rincian-kinerja-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
							<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-sub-rincian-kinerja-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
						</div>
					</div>

					<div class="tab-pane fade" id="block-rincian-sumberdana-subkegiatan">
						<form class="form-delete">
						<div class="table-responsive">
						<table class="table table-condensed table-bordered table-striped f12">
						<thead>
						<tr>
							<th class="text-center text-nowrap w1px">Kode</th>
							<th class="text-center text-nowrap">Sumber Dana SUb Kegiatan</th>
							<th class="text-center text-nowrap">Nilai</th>
							<th class="text-center text-nowrap">Keterangan</th>
							<th class="text-center text-nowrap">Edit</th>
							<th class="w1px">
								<div class="checkbox checkbox-inline">
									<input type="checkbox" class="check-all">
									<label></label>
								</div>
							</th>
						</tr>
						</thead>
						<tbody class="data-load"></tbody>
						</table>
						</div>
						</form>

						<div class="row">
							<div class="col-xs-6"><button type="button" class="btn btn-primary btn-sub-rincian-sumberdana-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
							<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-sub-rincian-sumberdana-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
						</div>
					</div>
				</div>
			</div> 
		</div>
	</div>
</div>

<script>
var blockProgram = '.block-program ',
	blockKegiatan = '.block-kegiatan ',
	blockRincian = '.block-rincian ',
	blockRincianOpd = '#block-rincian-opd ',
	blockRincianMusrenbang = '#block-rincian-musrenbang ',
	blockRincianPokir = '#block-rincian-pokir ',
	blockRincianKinerja = '#block-rincian-kinerja ',
	blockRincianPenjabaran = '#block-rincian-penjabaran ',
	blockRincianSumberdana = '#block-rincian-sumberdana ',
	//baru subkegiatan
	blockSubKegiatan = '.block-subkegiatan ',
	blockRincianKinerjaSubkegiatan = '#block-rincian-kinerja-subkegiatan ',
	blockRincianSumberdanaSubkegiatan = '#block-rincian-sumberdana-subkegiatan ',
	blockRincianSubkegiatan = '.block-rincian-subkegiatan';
	blockimportdata = '.block-inport-data';
	//selesai

function updatePagu(a,b,c) {
	$('.block-pagu .pagu-total').html(a);
	$('.block-pagu .pagu-used').html(b);
	$('.block-pagu .pagu-selisih').html(c);
	updateNum('.block-pagu ');
}

function dataLoadProgram() {
	$(blockRincian).hide();
	$(blockKegiatan).hide();
	$(blockSubKegiatan).hide();
	$(blockRincianSubkegiatan).hide();
	$(blockKegiatan + '.page').val('1');
	var page = $(blockProgram + '.page').val();
	$.post('/renja/program_load/' + page, $(blockProgram + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockProgram + '.data-load').html(res);
		}
	});
}

function dataLoadKegiatan(updateFromDetail) {
	$(blockRincian).hide();
	$(blockSubKegiatan).hide();
	$(blockRincianSubkegiatan).hide();
	$(blockSubKegiatan + '.page').val('1');
	var page = $(blockKegiatan + '.page').val(),
		data = $.extend({},
			$(blockKegiatan + '.form-load').serializeObject(),
			{'f-unitkey' : getVal('#f-unitkey')}
		);
	$.post('/renja/kegiatan_load/' + page, data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockKegiatan + '.data-load').html(res);
			updateNum(blockKegiatan);
			// if(update) $('#tr-kegiatan-' + update).find('.btn-kegiatan-show-rincian').addClass('text-bold text-success');
		}
	});
}

// when add sub kegiatan
function dataLoadKegiatann(updateFromDetail) {
	// $(blockRincian).hide();
	// $(blockSubKegiatan).hide();
	$(blockRincianSubkegiatan).hide();
	$(blockSubKegiatan + '.page').val('1');
	var page = $(blockKegiatan + '.page').val(),
		data = $.extend({},
			$(blockKegiatan + '.form-load').serializeObject(),
			{'f-unitkey' : getVal('#f-unitkey')}
		);
	$.post('/renja/kegiatan_load/' + page, data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockKegiatan + '.data-load').html(res);
			updateNum(blockKegiatan);
			// if(update) $('#tr-kegiatan-' + update).find('.btn-kegiatan-show-rincian').addClass('text-bold text-success');
		}
	});
}
// end

//====================================== barU subkegiatan

function dataLoadSubKegiatan(updateFromDetail) {
	// $(blockRincian).hide();
	// $(blockSubKegiatan).hide();
	$(blockRincianSubkegiatan).hide();
	var page = $(blockSubKegiatan + '.page').val(),
		data = $.extend({},
			$(blockSubKegiatan + '.form-load').serializeObject(),
			{'f-unitkey' : getVal('#f-unitkey')}
		);
		console.log(page);
	$.post('/renja/subkegiatan_load/' + page, data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockSubKegiatan + '.data-load').html(res);
			updateNum(blockSubKegiatan);
		}
	});
}

function dataLoadRincian(go) {
	var url = '',
		blockRincianDetail = '';

	switch(go) {
		case 'opd'					: blockRincianDetail = blockRincianKinerja;			url		= '/renja/rincian_kinerja'; break;
		case 'musrenbang'			: blockRincianDetail = blockRincianMusrenbang;	url 		= '/renja/rincian_musrenbang'; break;
		case 'pokir'				: blockRincianDetail = blockRincianPokir;		url 		= '/renja/rincian_pokir'; break;
		case 'kinerja'				: blockRincianDetail = blockRincianKinerja;		url 		= '/renja/rincian_kinerja'; break;
		case 'penjabaran'			: blockRincianDetail = blockRincianPenjabaran;	url 		= '/renja/rincian_penjabaran'; break;
		case 'sumberdana'			: blockRincianDetail = blockRincianSumberdana;	url 		= '/renja/rincian_sumberdana'; break;
		case 'kinerjasub'			: blockRincianDetail = blockRincianKinerjaSubkegiatan; url 	= '/renja/subrincian_kinerja'; break;
		case 'sumberdanasub'		: blockRincianDetail = blockRincianSumberdanaSubkegiatan;url = '/renja/subrincian_sumberdana'; break;

	}


	//selesai

	var data = $.extend({},
		$(blockRincian + '.form-load').serializeObject(),
		$(blockRincianSubkegiatan + '.form-load').serializeObject(),
		{
			'f-unitkey'		: getVal('#f-unitkey'),
			'f-pgrmrkpdkey'	: getVal('#f-pgrmrkpdkey'),
			'f-kegrkpdkey' : getVal('#f-kegrkpdkey'),
			'f-subkegrkpdkey'	: getVal('#f-subkegrkpdkey')
		}
	);

	$.post(url, data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockRincianDetail + '.data-load').html(res);
			updateNum(blockRincianDetail);
		}
	});
}

$(function() {
	updateSelect();

	// Program
	$(document).off('change', blockProgram + '#f-unitkey');
	$(document).on('change', blockProgram + '#f-unitkey', function(e) {
		e.preventDefault();
		$(blockProgram + '.page').val('1');
		dataLoadProgram();
	});

	$(document).off('submit', blockProgram + '.form-load');
	$(document).on('submit', blockProgram + '.form-load', function(e) {
		e.preventDefault();
		$(blockProgram + '.page').val('1');
		dataLoadProgram();
		return false;
	});

	$(document).off('click', blockProgram + '.btn-page');
	$(document).on('click', blockProgram + '.btn-page', function(e) {
		e.preventDefault();
		$(blockProgram + '.page').val($(this).data('ci-pagination-page'));
		dataLoadProgram();
		return false;
	});

	$(document).off('click', blockProgram + '.check-all');
	$(document).on('click', blockProgram + '.check-all', function(e) {
		var checkboxes = $(blockProgram + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});

	$(document).off('click', blockProgram + '.btn-program-show-kegiatan');
	$(document).on('click', blockProgram + '.btn-program-show-kegiatan', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockProgram + '.btn-program-show-kegiatan').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-pgrmrkpdkey').val(id);
		var tt = $('#f-pgrmrkpdkey').val(id);
		console.log(id);
		$(blockKegiatan).fadeIn('fast');
		dataLoadKegiatan();
	});

	$(document).off('click', blockProgram + '.btn-program-form');
	$(document).on('click', blockProgram + '.btn-program-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Tambah Program';
			type = 'type-success';
			data = {'f-unitkey'	: getVal('#f-unitkey')};
		} else if(act == 'edit') {
			title = 'Ubah Program';
			type = 'type-warning';
			data = {
				'f-unitkey'		: getVal('#f-unitkey'),
				'f-pgrmrkpdkey'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()
			};
		}

		modalProgramForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/renja/program_form/' + act, data)
		});
		modalProgramForm.open();

		return false;
	});

	// add
	$(document).off('click', blockProgram + '.btn-import-data');
	$(document).on('click', blockProgram + '.btn-import-data', function(e) {
		e.preventDefault();
			var act = $(this).data('act'),
			data, title, type;

		// if(act == 'add') {
		// 	title = 'Tambah Sub Kegiatan';
		// 	type = 'type-success';
		// 	data = {'f-kegrkpdkey'	: getVal('#f-kegrkpdkey')};
		// } else if(act == 'edit') {
		// 	title = 'Ubah Sub Kegiatan';
		// 	type = 'type-warning';
		// 	data = 	{'f-subkegrkpdkey' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
		// }

		modalMasterSubKegiatanForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/import/coba/' + act, data)
		});
		modalMasterSubKegiatanForm.open();

		return false;
	});
	// end sub kegiatan

	$(document).off('click', blockProgram + '.btn-program-delete');
	$(document).on('click', blockProgram + '.btn-program-delete', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if($(blockProgram + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar program yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockProgram + '.form-delete').serializeObject(),
						{'i-unitkey' : getVal('#f-unitkey')}
					);
					$.post('/renja/program_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadProgram();
						}
					});
				}
			}
		});

		return false;
	});

	// Kegiatan
	$(document).off('submit', blockKegiatan + '.form-load');
	$(document).on('submit', blockKegiatan + '.form-load', function(e) {
		e.preventDefault();
		$(blockKegiatan + '.page').val('1');
		dataLoadKegiatan();
		return false;
	});

	$(document).off('click', blockKegiatan + '.btn-page');
	$(document).on('click', blockKegiatan + '.btn-page', function(e) {
		e.preventDefault();
		$(blockKegiatan + '.page').val($(this).data('ci-pagination-page'));
		dataLoadKegiatan();
		return false;
	});

	$(document).off('click', blockKegiatan + '.check-all');
	$(document).on('click', blockKegiatan + '.check-all', function(e) {
		var checkboxes = $(blockKegiatan + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});

	$(document).off('click', blockKegiatan + '.btn-kegiatan-show-rincian');
	$(document).on('click', blockKegiatan + '.btn-kegiatan-show-rincian', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockKegiatan + '.btn-kegiatan-show-rincian').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-kegrkpdkey').val(id);
		$('#s-kegrkpdkey').val(id);
		$(blockRincian).fadeIn('fast');
		dataLoadRincian('opd');
		$(blockSubKegiatan).fadeIn('fast');
		dataLoadSubKegiatan('kinerjasub');
		$('#rincian-tabs a[href="#block-rincian-opd"]').tab('show');
	});

	$('#rincian-tabs a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		e.target;
		e.relatedTarget;
		dataLoadRincian($(this).data('go'));
	});

	$(document).off('click', blockKegiatan + '.btn-kegiatan-form');
	$(document).on('click', blockKegiatan + '.btn-kegiatan-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if(isEmpty(getVal('#f-pgrmrkpdkey'))) return false;
		var act = $(this).data('act'),
		
			data, title, type;

		data = {
			'f-unitkey'		: getVal('#f-unitkey'),
			'f-pgrmrkpdkey'	: getVal('#f-pgrmrkpdkey')
		};

		if(act == 'add') {
			title = 'Tambah Kegiatan';
			type = 'type-success';
		} else if(act == 'edit') {
			title = 'Ubah Kegiatan';
			type = 'type-warning';
			data = $.extend({},
				data,
				{'f-kegrkpdkey' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
			);
		}

		modalKegiatanForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/renja/kegiatan_form/' + act, data)
		});
		modalKegiatanForm.open();

		return false;
	});

	$(document).off('click', blockKegiatan + '.btn-kegiatan-delete');
	$(document).on('click', blockKegiatan + '.btn-kegiatan-delete', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if(isEmpty(getVal('#f-pgrmrkpdkey'))) return false;
		if($(blockKegiatan + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar kegiatan yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockKegiatan + '.form-delete').serializeObject(),
						{
							'i-unitkey'		: getVal('#f-unitkey'),
							'i-pgrmrkpdkey'	: getVal('#f-pgrmrkpdkey')
						}
					);
					$.post('/renja/kegiatan_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadKegiatan();
						}
					});
				}
			}
		});

		return false;
	});

	// Rincian
	$('#rincian-tabs a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		e.target;
		e.relatedTarget;
		dataLoadRincian($(this).data('go'));
	});

	$(document).off('click', blockRincian + '.btn-subkegiatan-show');
	$(document).on('click', blockRincian + '.btn-subkegiatan-show', function(e) {
		e.preventDefault();
		var id = getVal('#f-kegrkpdkey');
		$(blockRincian + '.btn-subkegiatan-show').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#s-kegrkpdkey').val(id);
		$(blockSubKegiatan).fadeIn('fast');
		dataLoadSubKegiatan();
	});

	$(document).off('click', blockSubKegiatan + '.btn-subkegiatan-form');
	$(document).on('click', blockSubKegiatan + '.btn-subkegiatan-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if(isEmpty(getVal('#s-kegrkpdkey'))) return false;
		var act = $(this).data('act'),
			data, title, type;

		data = {
			'f-unitkey'		: getVal('#f-unitkey'),
			's-kegrkpdkey'	: getVal('#s-kegrkpdkey')
		};

		if(act == 'add') {
			title = 'Tambah Sub Kegiatan';
			type = 'type-success';
		} else if(act == 'edit') {
			title = 'Ubah Sub Kegiatan';
			type = 'type-warning';
			data = $.extend({},
				data,
				{'v-unitkey' : getVal('#f-unitkey'),'f-subkegrkpdkey' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
			);


		}

		modalSubKegiatanForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/renja/subkegiatan_form/' + act, data)
		});
		modalSubKegiatanForm.open();

		return false;
	});

	$(document).off('click', blockSubKegiatan + '.btn-subkegiatan-delete');
	$(document).on('click', blockSubKegiatan + '.btn-subkegiatan-delete', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if(isEmpty(getVal('#s-kegrkpdkey'))) return false;
		if($(blockSubKegiatan + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar sub kegiatan yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockSubKegiatan + '.form-delete').serializeObject(),
						{
							'v-unitkey'		: getVal('#f-unitkey'),
							'v-kegrkpdkey'	: getVal('#s-kegrkpdkey')

						}
					);
					$.post('/renja/subkegiatan_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadSubKegiatan();
						}
					});
				}
			}
		});

		return false;
	});

	$(document).off('click', blockSubKegiatan + '.btn-subkegiatan-show-rincian');
	$(document).on('click', blockSubKegiatan + '.btn-subkegiatan-show-rincian', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockSubKegiatan + '.btn-subkegiatan-show-rincian').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-subkegrkpdkey').val(id);
		$(blockRincianSubkegiatan).fadeIn('fast');
		dataLoadRincian('kinerjasub');
		$('#rincian-tabs-sub a[href="#block-rincian-kinerja-subkegiatan"]').tab('show');
	});

	$('#rincian-tabs-sub a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		e.target;
		e.relatedTarget;
		dataLoadRincian($(this).data('go'));
	});

	$(document).off('submit', blockSubKegiatan + '.form-load');
	$(document).on('submit', blockSubKegiatan + '.form-load', function(e) {
		e.preventDefault();
		$(blockSubKegiatan + '.page').val('1');
		dataLoadSubKegiatan();
		return false;
	});

	$(document).off('click', blockSubKegiatan + '.btn-page');
	$(document).on('click', blockSubKegiatan + '.btn-page', function(e) {
		e.preventDefault();
		$(blockSubKegiatan + '.page').val($(this).data('ci-pagination-page'));
		dataLoadSubKegiatan();
		return false;
	});

	$(document).off('click', blockSubKegiatan + '.check-all');
	$(document).on('click', blockSubKegiatan + '.check-all', function(e) {
		var checkboxes = $(blockSubKegiatan + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});

//selesai
});
</script>
