<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-group">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Aksi</th>
			<th>Nama</th>
			<th>Keterangan</th>
		</tr>
		<tbody class="data-load">
			<?php
			foreach($group as $r) :
			$r = settrim($r);
			echo "
			<tr data-id='{$r['GROUPID']}'>
				<td class='text-center'><a href='javascript:void(0)' class='btn-select'>Select</a></td>
				<td>{$r['NMGROUP']}</td>
				<td>{$r['KET']}</td>		
			</tr>
			";
			endforeach;
			?>
		</tbody>
		</table>
		</div>
		<div class="text-center block-pagination"></div>
	</div>
</div>
<script>
var blockLookupGroup = '#lookup-group ';

$(function() {
	
	$(document).off('click', blockLookupGroup + '.btn-select');
	$(document).on('click', blockLookupGroup + '.btn-select', function(e) {
		e.preventDefault();
		var tr = $(this).closest('tr');
		var setid = tr.data('id'),
			setnm = tr.find('td:eq(1)').text();
		
		$('<?php echo $setid; ?>').val(setid.trim()).change();
		$('<?php echo $setnm; ?>').val(setnm.trim()).change();
		
		modalLookupGroup.close();
		var groupid = document.getElementById("f-groupid").value;
		if ( groupid !== "31_"){
			document.getElementById('nmpengguna').style.display = '';
			document.getElementById('prktdaerah').style.display = 'none';
		}else{document.getElementById('nmpengguna').style.display = 'none';
			  document.getElementById('prktdaerah').style.display = '';
		}
		return false;
	});
});
</script>