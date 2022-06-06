<?php
function VD($v)
{
	echo "<div style='padding:10px 10px;'>";
	echo var_dump($v);
	echo "</div>";
}

function money($v, $d = 2)
{
	return number_format(((is_numeric($v)) ? $v : 0), $d,',','.');
}

function moneyAcc($v, $d = 2)
{
	$n = number_format(((is_numeric($v)) ? $v : 0), $d,',','.');
	$n = ($v == 0) ? '-' : $n;
	$n = ($v < 0) ? '('.str_replace('-','',$n).')' : $n;
	
	return $n;
}

function parsing($a, $b, $c)
{
	return ($a === $b) ? $c : $a;
}

function settrim($v)
{
	if(is_array($v)) {
		return array_map('settrim',$v);
	} elseif(is_string($v)) {
		return trim($v);
	} else {
		return $v;
	} 
}

function custom_errors($validation_errors, $class = 'danger')
{
	return "<ul class='list-group'>" . str_replace(["\n", "<p>", "</p>"], ["", "<li class='list-group-item list-group-item-{$class}'>", "</li>"], $validation_errors) . "</ul>";
}

function file_delete($file_nama)
{
	$f = './upload/files/' . $file_nama;
	$t = './upload/files/thumbs/' . $file_nama;
	
	if(file_exists($f))
	{
		unlink($f);
	}
	if(file_exists($t))
	{
		unlink($t);
	}
}

function str_limit($l, $s)
{
	return (strlen($s) > $l) ? substr($s, 0, $l) . '....' : $s;
}

function ifactivetab($a, $b, $pane = FALSE)
{
	return ($a == $b) ? (($pane) ? 'in active' : 'active') : '';
}

function ifempty($a, $b)
{
	return ($a == '' OR $a == NULL) ? $b : $a;
}

function setselected($a, $b)
{
	return ($a == $b) ? 'selected' : '';
}

function setchecked($a, $b)
{
	return ($a == $b) ? 'checked' : '';
}

function setdisabled($a, $b)
{
	return ($a == $b) ? 'disabled' : '';
}

function setreadonly($a, $b)
{
	return ($a == $b) ? 'readonly' : '';
}

function paginationBootstrap()
{
	
	//$config['num_links'] = floor($config['total_rows'] / $config['per_page']);
	$config['use_page_numbers'] = TRUE;
	$config['uri_segment'] = 3;
	$config['num_links'] = 3;
	$config['attributes'] = ['class' => 'btn-page'];
	
	$config['full_tag_open'] = "<ul class='pagination'>";
	$config['full_tag_close'] ="</ul>";
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='javascript:void(0);'>";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li>";
	$config['next_tagl_close'] = "</li>";
	$config['prev_tag_open'] = "<li>";
	$config['prev_tagl_close'] = "</li>";
	$config['first_tag_open'] = "<li>";
	$config['first_tagl_close'] = "</li>";
	$config['last_tag_open'] = "<li>";
	$config['last_tagl_close'] = "</li>";

	$config['first_link']='&laquo;';
	$config['last_link']='&raquo;';
	$config['next_link']='&#8250;';
	$config['prev_link']='&#8249;';
	
	return $config;
}

function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}
	
	
	function tanggal_indo($tanggal)
{
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal);
	return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}
