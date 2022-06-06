<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-program">
	<div class="col-md-12">
	<ul class="list-tree">
		<li>
			<ul>
				<?php
				$header = '';
				foreach($program as $p)
				{
					$p = settrim($p);
					if($p['KDUNIT'] != $header)
					{
						echo "
							</ul>
						</li>
						<li>
							<i class='toggle fa fa-plus-square-o'></i> <strong>{$p['KDUNIT']} {$p['NMUNIT']}</strong>
							<ul class='list-tree' style='display: none;'>";

						$header = $p['KDUNIT'];
					}
					 echo "<li class='btn-select' data-id='{$p['PGRMRKPDKEY']}' style='cursor: pointer;'>&nbsp; &nbsp; <span>{$p['KDUNIT']}.{$p['NUPRGRM']}</span> <kbd>{$p['NMPRGRM']}</kbd></li>";
				}
				?>
				</ul>
			</li>
		</ul>
	</div>
</div>
<script>
var blockLookupProgram = '#lookup-program ';

$(function() {
	$(document).off('click', blockLookupProgram + 'ul.list-tree li i.toggle');
	$(document).on('click', blockLookupProgram + 'ul.list-tree li i.toggle', function(e) {
		e.preventDefault();
		$i = $(this);
		$ul = $i.parent().children('ul');

		$ul.slideToggle('fast', function() {
			$(this).is(':visible') ? $i.addClass('fa-minus-square-o').removeClass('fa-plus-square-o') : $i.addClass('fa-plus-square-o').removeClass('fa-minus-square-o');
		});
	});

	$(document).off('click', blockLookupProgram + '.btn-select');
	$(document).on('click', blockLookupProgram + '.btn-select', function(e) {
		e.preventDefault();
		var setid = $(this).data('id'),
			setkd = $(this).find('span').text(),
			setnm = $(this).find('kbd').text();

		$('<?php echo $setid; ?>').val(setid.trim()).change();
		$('<?php echo $setkd; ?>').val(setkd.trim()).change();
		$('<?php echo $setnm; ?>').val(setnm.trim()).change();

		modalLookupProgram.close();

		return false;
	});
});
</script>