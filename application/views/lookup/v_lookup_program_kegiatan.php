<?php
defined('BASEPATH') OR exit('No direct script access allowed');


?>
<div class="row" id="lookup-programkegiatan">
	<div class="col-md-12">
	<ul class="list-tree">
		<li style='display: none;'>
			<ul>
				<li>
					<ul>
					<?php
					$header = '';
					$program = '';
					foreach($prokeg as $r)
					{
						$r = settrim($r);
						
						if($r['KDUNIT'] != $header)
						{
							echo "
										</ul>
									</li>
								</ul>
							</li>
							<li>
								<i class='toggle fa fa-plus-square-o'></i> <strong>{$r['KDUNIT']} {$r['NMUNIT']}</strong>
								<ul class='list-tree' style='display: none;'>
									<li>
										<ul>";
							
							$header = $r['KDUNIT'];
						}
						
						if($r['NMPRGRM'] != $program)
						{
							echo "
									</ul>
								</li>
								<li>
									<i class='toggle fa fa-plus-square-o'></i> <i>{$r['KDUNIT']}.{$r['NUPRGRM']} {$r['NMPRGRM']}</i>
									<ul class='list-tree' style='display: none;'>";
							
							$program = $r['NMPRGRM'];
						}
						
						echo "<li class='btn-select' data-id='{$r['KEGRKPDKEY']}' style='cursor: pointer;'>&nbsp; &nbsp; <span>{$r['KDUNIT']}.{$r['NUPRGRM']}.{$r['NUKEG']}</span> <kbd>{$r['NMKEG']}</kbd></li>";
					}
					?>	
					</ul>
				</li>
			</ul>
		</li>
	</ul>
	</div>
</div>
<script>
var blockLookupProgramKegiatan = '#lookup-programkegiatan ';

$(function() {
	$(document).off('click', blockLookupProgramKegiatan + 'ul.list-tree li i.toggle');
	$(document).on('click', blockLookupProgramKegiatan + 'ul.list-tree li i.toggle', function(e) {
		e.preventDefault();
		$i = $(this);
		$ul = $i.parent().children('ul');
		
		$ul.slideToggle('fast', function() {
			$(this).is(':visible') ? $i.addClass('fa-minus-square-o').removeClass('fa-plus-square-o') : $i.addClass('fa-plus-square-o').removeClass('fa-minus-square-o');
		});
	});
	
	$(document).off('click', blockLookupProgramKegiatan + '.btn-select');
	$(document).on('click', blockLookupProgramKegiatan + '.btn-select', function(e) {
		e.preventDefault();
		var setid = $(this).data('id'),
			setkd = $(this).find('span').text(),
			setnm = $(this).find('kbd').text();
		
		$('<?php echo $setid; ?>').val(setid.trim()).change();
		$('<?php echo $setkd; ?>').val(setkd.trim()).change();
		$('<?php echo $setnm; ?>').val(setnm.trim()).change();
		
		modalLookupProgramKegiatan.close();
		
		return false;
	});
});
</script>