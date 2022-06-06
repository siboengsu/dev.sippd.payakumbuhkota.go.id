<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-unit">
	<div class="col-md-12">
		<?php
		$row = [];
		foreach($unit as $u)
		{
			$row[] = settrim($u);
		}

		function setTree($row)
		{
			$data = [];
			foreach($row AS $i => $v)
			{
				$n = 1;
				while($n >= 1)
				{
					if($row[$i]['KDLEVEL'] == "1")
					{
						$data[0][] = $row[$i];
						$n = 0;
					}
					elseif(stripos($row[$i]['KDUNIT'], $row[$i-$n]['KDUNIT']) === 0)
					{
						$data[$row[$i-$n]['KDUNIT']][] = $row[$i];
						$n = 0;
					}
					else
					{
						$n++;
					}
				}
			}
			
			return $data;
		}
		
		function getTree($data, $parent = 0)
		{
			$i = 1;
			$tab = str_repeat(" ", $i);
			if(isset($data[$parent]))
			{
				$hide = ($parent === 0) ? "" : "style='display: none;'";
				$html = "$tab<ul class='list-tree' $hide>";
				$i++;
				foreach($data[$parent] as $v)
				{
					$child = getTree($data, $v['KDUNIT']);
					
					if($child)
					{
						$html .= "$tab<li>";
						$html .= "<i class='toggle fa fa-plus-square-o'></i>";
						$html .= ($parent === 0) ? " <strong>{$v['KDUNIT']} {$v['NMUNIT']} </strong>" : " <i>{$v['KDUNIT']} {$v['NMUNIT']}</i>";
						$i--;
						$html .= $child;
						$html .= "$tab";
						$html .= '</li>';
					}
					else
					{
						$html .= "$tab<li class='btn-select' data-id='{$v['UNITKEY']}' style='cursor: pointer;'>&nbsp; &nbsp; ";
						$html .= ($v['TYPE'] == "H") ? "<i>{$v['KDUNIT']} {$v['NMUNIT']}</i>" : "<span>{$v['KDUNIT']}</span> <kbd>{$v['NMUNIT']}</kbd>";
						$html .= '</li>';
					}
				}
				$html .= "$tab</ul>";
				return $html;
			}
			else
			{
				return false;
			}
		}
			
		$data = setTree($row);
		$tree = getTree($data);
		echo $tree;
		?>
	</div>
</div>
<script>
var blockLookupUnit = '#lookup-unit ';

$(function() {
	$(document).off('click', blockLookupUnit + 'ul.list-tree li i.toggle');
	$(document).on('click', blockLookupUnit + 'ul.list-tree li i.toggle', function(e) {
		e.preventDefault();
		$i = $(this);
		$ul = $i.parent().children('ul');
		
		$ul.slideToggle('fast', function() {
			$(this).is(':visible') ? $i.addClass('fa-minus-square-o').removeClass('fa-plus-square-o') : $i.addClass('fa-plus-square-o').removeClass('fa-minus-square-o');
		});
	});
	
	$(document).off('click', blockLookupUnit + '.btn-select');
	$(document).on('click', blockLookupUnit + '.btn-select', function(e) {
		e.preventDefault();
		var setid = $(this).data('id'),
			setkd = $(this).find('span').text(),
			setnm = $(this).find('kbd').text();
		
		$('<?php echo $setid; ?>').val(setid.trim()).change();
		$('<?php echo $setkd; ?>').val(setkd.trim()).change();
		$('<?php echo $setnm; ?>').val(setnm.trim()).change();
		
		modalLookupUnit.close();
		
		return false;
	});
});
</script>