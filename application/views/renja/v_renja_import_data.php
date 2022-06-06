<div class="row import-data">
    <div class="col-md-12">
		<div class="col-md-8 col-md-offset-2">
			<form method="POST" action="<?= site_url('import/excel') ?>" enctype="multipart/form-data" class="form-inline form-import-data">
				<div class="card-body">
					<div class="row">
						<label> Upload your file here</label>
						<div class="col-md-12">
								<input type="file" class="form-control" name="file" required>
						</div>
					</div>
				</div><br>
				<div class="">
					<div class="form-group col-md-12 text-right">
						<button type="submit" name="import" class="btn btn-primary"><i class="bi bi-upload"></i>Upload</button> 
					</div>
				</div>
				
			</form>
		</div>
    </div>
</div>

