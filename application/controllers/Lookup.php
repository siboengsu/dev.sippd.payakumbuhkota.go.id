<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lookup extends CI_Controller {

	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	function __construct()
	{
		parent::__construct();

		$this->sip->is_logged();

		$this->load->model(['m_set', 'm_user']);

		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}

	public function index()
	{

	}

	public function unit()
	{
		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$KEYY = $this->session->USERID; 
		$tipe = "TYPE";
		
		$kondisi = "";
		if ($KEYY == 'dinkes')
		{	
			$kondisi = "WHERE UNITKEY = '1_' OR UNITKEY = '3_' OR UNITKEY = '54_' OR UNITKEY = '176_' OR UNITKEY = '177_' OR UNITKEY = '178_' OR UNITKEY = '179_' OR UNITKEY = '180_' OR UNITKEY = '181_' OR UNITKEY = '182_' OR UNITKEY = '183_' OR UNITKEY = '184_' OR UNITKEY = '185_' OR UNITKEY = '186_'";
		}elseif ($KEYY == 'dispupr')
		{
			$kondisi = "WHERE UNITKEY = '1_' OR UNITKEY = '4_' OR UNITKEY = '55_' OR UNITKEY = '187_' OR UNITKEY = '188_' OR UNITKEY = '189_'";
		}elseif ($KEYY == 'distan')
		{
			$kondisi = "WHERE UNITKEY = '27_' OR UNITKEY = '30_' OR UNITKEY = '72_' OR UNITKEY = '145_' OR UNITKEY = '146_' OR UNITKEY = '147_' OR UNITKEY = '148_' OR UNITKEY = '149_' OR UNITKEY = '150_'";
		}elseif ($KEYY == 'dishub')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '17_' OR UNITKEY = '66_' OR UNITKEY = '172_' OR UNITKEY = '173_' OR UNITKEY = '174_' OR UNITKEY = '175_'";
		}elseif ($KEYY == 'diskopukm')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '19_' OR UNITKEY = '68_' OR UNITKEY = '160_' OR UNITKEY = '161_'";
		}elseif ($KEYY == 'disparpora')
		{
			$kondisi = "WHERE UNITKEY = '27_' OR UNITKEY = '29_' OR UNITKEY = '70_' OR UNITKEY = '151_' OR UNITKEY = '152_'";
		}elseif ($KEYY == 'bkeu')
		{
			$kondisi = "WHERE UNITKEY = '39_' OR UNITKEY = '41_' OR UNITKEY = '79_' OR UNITKEY = '157_' OR UNITKEY = '158_' OR UNITKEY = '159_'";
		}elseif ($KEYY == 'disnakerperin')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '9_' OR UNITKEY = '60_' OR UNITKEY = '155_' OR UNITKEY = '156_'";
		}elseif ($KEYY == 'dislh')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '13_' OR UNITKEY = '63_' OR UNITKEY = '153_' OR UNITKEY = '154_'";
		}elseif ($KEYY == 'pyk_brt')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '83_' OR UNITKEY = '89_' OR UNITKEY = '90_' OR UNITKEY = '91_' OR UNITKEY = '121_' OR UNITKEY = '92_' OR UNITKEY = '93_' OR UNITKEY = '103_' OR UNITKEY = '95_' OR UNITKEY = '97_' OR UNITKEY = '96_' OR UNITKEY = '98_' OR UNITKEY = '99_' OR UNITKEY = '100_' OR UNITKEY = '101_' OR UNITKEY = '102_' OR UNITKEY = '104_' OR UNITKEY = '105_' OR UNITKEY = '107_'";
		}elseif ($KEYY == 'pyk_tmr')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '84_' OR UNITKEY = '108_' OR UNITKEY = '109_' OR UNITKEY = '110_' OR UNITKEY = '111_' OR UNITKEY = '112_' OR UNITKEY = '113_' OR UNITKEY = '114_' OR UNITKEY = '115_' OR UNITKEY = '116_' OR UNITKEY = '118_'";
		}elseif ($KEYY == 'pyk_utr')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '85_' OR UNITKEY = '119_' OR UNITKEY = '120_' OR UNITKEY = '121_' OR UNITKEY = '122_' OR UNITKEY = '123_' OR UNITKEY = '124_' OR UNITKEY = '94_' OR UNITKEY = '125_' OR UNITKEY = '127_' OR UNITKEY = '129_' OR UNITKEY = '126_'";
		}elseif ($KEYY == 'pyk_slt')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '86_' OR UNITKEY = '130_' OR UNITKEY = '131_' OR UNITKEY = '133_' OR UNITKEY = '134_' OR UNITKEY = '135_' OR UNITKEY = '137_' OR UNITKEY = '132_'";
		}elseif ($KEYY == 'lamposi')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '87_' OR UNITKEY = '138_' OR UNITKEY = '139_' OR UNITKEY = '140_' OR UNITKEY = '141_' OR UNITKEY = '142_' OR UNITKEY = '143_'";
		}
		
		$data['unit'] = $this->db->query("
		SELECT
		UNITKEY,
			KDLEVEL,
			KDUNIT,
			CASE WHEN  LEN(KDUNIT)> 4
			THEN
				CASE WHEN LEN(KDUNIT)=14
				THEN
					(REPLACE(SUBSTRING(KDUNIT,1,4),'-','.0'))
				ELSE
					(REPLACE(SUBSTRING(KDUNIT,1,4),'-','.'))
				END
			ELSE (REPLACE(SUBSTRING(KDUNIT,1,4),'-','.'))
			END AS KDUNIT2,
			NMUNIT,
			TYPE FROM DAFTUNIT echo $kondisi
			ORDER BY
			KDUNIT2, KDUNIT ASC")->result_array();
		
		$this->load->view('lookup/v_lookup_unit', $data);
	}
	
	public function unit_uptd_blud()
	{
		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');
		$KEYY = $this->session->USERID; 
		$kondisi = "";
		if ($KEYY == 'dinkes')
		{	
			$kondisi = "WHERE UNITKEY = '1_' OR UNITKEY = '3_' OR UNITKEY = '54_'";
		}elseif ($KEYY == 'dispupr')
		{
			$kondisi = "WHERE UNITKEY = '1_' OR UNITKEY = '4_' OR UNITKEY = '55_'";
		}elseif ($KEYY == 'distan')
		{
			$kondisi = "WHERE UNITKEY = '27_' OR UNITKEY = '30_' OR UNITKEY = '72_'";
		}elseif ($KEYY == 'dishub')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '17_' OR UNITKEY = '66_'";
		}elseif ($KEYY == 'diskopukm')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '19_' OR UNITKEY = '68_'";
		}elseif ($KEYY == 'disparpora')
		{
			$kondisi = "WHERE UNITKEY = '27_' OR UNITKEY = '29_' OR UNITKEY = '70_'";
		}elseif ($KEYY == 'bkeu')
		{
			$kondisi = "WHERE UNITKEY = '39_' OR UNITKEY = '41_' OR UNITKEY = '79_'";
		}elseif ($KEYY == 'disnakerperin')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '9_' OR UNITKEY = '60_'";
		}elseif ($KEYY == 'dislh')
		{
			$kondisi = "WHERE UNITKEY = '8_' OR UNITKEY = '13_' OR UNITKEY = '63_'";
		}elseif ($KEYY == 'pyk_brt')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '83_'";
		}elseif ($KEYY == 'pyk_tmr')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '84_'";
		}elseif ($KEYY == 'pyk_utr')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '85_'";
		}elseif ($KEYY == 'pyk_slt')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '86_'";
		}elseif ($KEYY == 'lamposi')
		{
			$kondisi = "WHERE UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '87_'";
		}

		if (($KEYY == 'dev') || ($KEYY == 'ari'))
		{	
			$kondisi = "WHERE UNITKEY = '1_' OR UNITKEY = '2_' OR UNITKEY = '53_'
			OR UNITKEY = '1_' OR UNITKEY = '3_' OR UNITKEY = '54_' 
			OR UNITKEY = '1_' OR UNITKEY = '4_' OR UNITKEY = '55_' 
			OR UNITKEY = '1_' OR UNITKEY = '5_' OR UNITKEY = '56_' 
			OR UNITKEY = '1_' OR UNITKEY = '6_' OR UNITKEY = '57_' OR UNITKEY = '58_' 
			OR UNITKEY = '1_' OR UNITKEY = '7_' OR UNITKEY = '59_' 
			OR UNITKEY = '8_' OR UNITKEY = '9_' OR UNITKEY = '60_'
			OR UNITKEY = '8_' OR UNITKEY = '11_' OR UNITKEY = '61_'
			OR UNITKEY = '8_' OR UNITKEY = '13_' OR UNITKEY = '63_'
			OR UNITKEY = '8_' OR UNITKEY = '14_' OR UNITKEY = '64_'
			OR UNITKEY = '8_' OR UNITKEY = '17_' OR UNITKEY = '66_'
			OR UNITKEY = '8_' OR UNITKEY = '18_' OR UNITKEY = '67_'
			OR UNITKEY = '8_' OR UNITKEY = '19_' OR UNITKEY = '68_'
			OR UNITKEY = '8_' OR UNITKEY = '20_' OR UNITKEY = '69_'
			OR UNITKEY = '8_' OR UNITKEY = '25_' OR UNITKEY = '71_'
			OR UNITKEY = '27_' OR UNITKEY = '29_' OR UNITKEY = '151_' 
			OR UNITKEY = '27_' OR UNITKEY = '30_' OR UNITKEY = '72_' 			
			OR UNITKEY = '36_' OR UNITKEY = '37_' OR UNITKEY = '76_' 			
			OR UNITKEY = '36_' OR UNITKEY = '38_' OR UNITKEY = '77_' 			
			OR UNITKEY = '39_' OR UNITKEY = '40_' OR UNITKEY = '78_'
			OR UNITKEY = '39_' OR UNITKEY = '41_' OR UNITKEY = '79_'
			OR UNITKEY = '39_' OR UNITKEY = '42_' OR UNITKEY = '80_'
			OR UNITKEY = '47_' OR UNITKEY = '48_' OR UNITKEY = '82_'
			OR UNITKEY = '47_' OR UNITKEY = '48_' OR UNITKEY = '82_'
			OR UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '83_'
			OR UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '84_'
			OR UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '85_'
			OR UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '86_'
			OR UNITKEY = '49_' OR UNITKEY = '50_' OR UNITKEY = '87_'
			OR UNITKEY = '51_' OR UNITKEY = '52_' OR UNITKEY = '88_'";
		}

		$data['unit'] = $this->db->query("
		SELECT
				UNITKEY,
				KDLEVEL,
				KDUNIT,
				NMUNIT,
				CASE WHEN KDLEVEL = 3 THEN 'D'
				ELSE TYPE
				END AS TYPE
			FROM
				DAFTUNIT
				echo $kondisi
			ORDER BY
				KDUNIT ASC")->result_array();

				//print_r(	$data['unit'] );

		$this->load->view('lookup/v_lookup_unit_uptd_blud', $data);
	}

	public function program($act = '')
	{
		$unitkey = $this->sip->unitkey($this->input->post('l-unitkey'));

		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$where = '';

		if($act != 'all')
		{
			$where = "
			AND MP.PGRMRKPDKEY NOT IN (
				SELECT
					PGRMRKPDKEY
				FROM
					PGRRKPD
				WHERE
					UNITKEY = '{$unitkey}'
				AND KDTAHUN = '{$this->KDTAHUN}'
				AND KDTAHAP = '{$this->KDTAHAP}'
			)";
		}

		$data['program'] = $this->db->query("
		SELECT
			MP.PGRMRKPDKEY,
			ISNULL(D.NMUNIT, 'SEMUA URUSAN') AS NMUNIT,
			ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
			MP.NUPRGRM,
			MP.NMPRGRM
		FROM
			MPGRMRKPD MP
		LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
		WHERE
			MP.KDTAHUN = '{$this->KDTAHUN}'
		AND MP.TYPE = 'D'
		AND (
			MP.UNITKEY IS NULL
			OR MP.UNITKEY IN (
				SELECT
					URUSKEY
				FROM
					URUSANUNIT
				WHERE
					UNITKEY = '{$unitkey}'
			)
		)
		{$where}
		ORDER BY
			KDUNIT ASC,
			NMPRGRM ASC")->result_array();

		$this->load->view('lookup/v_lookup_program', $data);
	}

	public function program2($act = '')
	{
		$unitkey = $this->sip->unitkey($this->input->post('l-unitkey'));

		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$where = '';

		if($act != 'all')
		{
			$where = "
			AND MP.PGRMRKPDKEY NOT IN (
				SELECT
					PGRMRKPDKEY
				FROM
					tbl_PROGRAM
			)";
		}

		$data['program'] = $this->db->query("
		SELECT
			MP.PGRMRKPDKEY,
			ISNULL(D.NMUNIT, 'SEMUA URUSAN') AS NMUNIT,
			ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
			MP.NUPRGRM,
			MP.NMPRGRM
		FROM
			MPGRMRKPD MP
		LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
		WHERE
			MP.KDTAHUN = '{$this->KDTAHUN}'
		AND MP.TYPE = 'D'
		AND (
			MP.UNITKEY IS NULL
			OR MP.UNITKEY IN (
				SELECT
					URUSKEY
				FROM
					URUSANUNIT
				WHERE
					UNITKEY = '{$unitkey}'
			)
		)
		{$where}
		ORDER BY
			KDUNIT ASC,
			NMPRGRM ASC")->result_array();

		$this->load->view('lookup/v_lookup_program', $data);
	}

	public function kegiatan($act = '')
	{
		$data['unitkey'] = $this->sip->unitkey($this->input->post('l-unitkey'));
		$data['pgrmrkpdkey'] = $this->input->post('l-pgrmrkpdkey');

		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$data['kegiatan'] = $this->kegiatan_load($act, TRUE);

		$this->load->view('lookup/v_lookup_kegiatan', $data);
	}

	public function kegiatan_load($act = '', $first = FALSE)
	{
		$unitkey = $this->sip->unitkey($this->input->post('l-unitkey'));
		$pgrmrkpdkey = $this->input->post('l-pgrmrkpdkey');

		$nukeg = $this->input->post('l-nukeg');
		$nmkeg = $this->input->post('l-nmkeg');

		$filter = '';
		if($nukeg)
		{
			$filter .= " AND MK.NUKEG LIKE '%{$nukeg}%'";
		}
		if($nmkeg)
		{
			$filter .= " AND MK.NMKEG LIKE '%{$nmkeg}%'";
		}

		if($act != 'all')
		{
			$filter .= "
			AND MK.KEGRKPDKEY NOT IN (
				SELECT
					K.KEGRKPDKEY
				FROM
					KEGRKPD K
				WHERE
					K.KDTAHUN = '{$this->KDTAHUN}'
				AND K.KDTAHAP = '{$this->KDTAHAP}'
				AND K.UNITKEY = '{$unitkey}'
				AND K.PGRMRKPDKEY = '{$pgrmrkpdkey}'
			)";
		}

		$kegiatan = $this->db->query("
			SELECT
				MK.KEGRKPDKEY,
				MK.NUKEG,
				MK.NMKEG
			FROM
				MKEGRKPD MK
			WHERE
				MK.KDTAHUN = '{$this->KDTAHUN}'
			AND MK.PGRMRKPDKEY = '{$pgrmrkpdkey}'
			AND MK.TYPE = 'D'
				{$filter}
			ORDER BY
				MK.NUKEG
		")->result_array();

		$load = '';
		foreach($kegiatan as $p):
		$p = settrim($p);
		$load .= "
		<tr data-id='{$p['KEGRKPDKEY']}'>
			<td class='text-center'><a href='javascript:void(0)' class='btn-select'>Select</a></td>
			<td class='text-center'>{$p['NUKEG']}</td>
			<td>{$p['NMKEG']}</td>
		</tr>";
		endforeach;

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

	public function prioritas123()
	{
		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$data['prioritas'] = $this->prioritas_load(TRUE);

		$this->load->view('lookup/v_lookup_prioritas', $data);
	}

	public function prioritas_load123($first = FALSE)
	{
		$noprioppas = $this->input->post('l-noprioppas');
		$nmprioppas = $this->input->post('l-nmprioppas');

		$filter = '';
		if($noprioppas)
		{
			$filter .= " AND O.NOPRIOPPAS LIKE '%{$noprioppas}%'";
		}
		if($nmprioppas)
		{
			$filter .= " AND O.NMPRIOPPAS LIKE '%{$nmprioppas}%'";
		}

		$prioritas = $this->db->query("
			SELECT
				O.PRIOPPASKEY,
				O.NOPRIOPPAS,
				O.NMPRIOPPAS
			FROM
				PRIOPPAS O
			WHERE
				O.KDTAHUN = '{$this->KDTAHUN}'
				{$filter}
			ORDER BY O.NOPRIOPPAS ASC
		")->result_array();

		$load = '';
		foreach($prioritas as $p):
		$p = settrim($p);
		$load .= "
		<tr data-id='{$p['PRIOPPASKEY']}'>
			<td class='text-center'><a href='javascript:void(0)' class='btn-select'>Select</a></td>
			<td class='text-center'>{$p['NOPRIOPPAS']}</td>
			<td>{$p['NMPRIOPPAS']}</td>
		</tr>";
		endforeach;

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

	public function sasaran123()
	{
		$data['prioppaskey'] = $this->input->post('l-prioppaskey');
		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$data['sasaran'] = $this->sasaran_load(TRUE);

		$this->load->view('lookup/v_lookup_sasaran', $data);
	}

	public function sasaran_load123($first = FALSE)
	{
		$prioppaskey = $this->input->post('l-prioppaskey');
		$nosas = $this->input->post('l-nosas');
		$nmsas = $this->input->post('l-nmsas');

		$filter = '';
		if($nosas)
		{
			$filter .= " AND S.NOSAS LIKE '%{$nosas}%'";
		}
		if($nmsas)
		{
			$filter .= " AND S.NMSAS LIKE '%{$nmsas}%'";
		}

		$sasaran = $this->db->query("
			SELECT
				S.IDSAS,
				S.NOSAS,
				S.NMSAS
			FROM
				SASARAN S
			JOIN PRIOSAS OS ON S.IDSAS = OS.IDSAS
			WHERE
				OS.PRIOPPASKEY = '{$prioppaskey}'
			AND OS.KDTAHUN = '{$this->KDTAHUN}'
				{$filter}
			ORDER BY
				S.NOSAS ASC
		")->result_array();

		$load = '';
		foreach($sasaran as $s):
		$s = settrim($s);
		$load .= "
		<tr data-id='{$s['IDSAS']}'>
			<td class='text-center'><a href='javascript:void(0)' class='btn-select'>Select</a></td>
			<td class='text-center'>{$s['NOSAS']}</td>
			<td>{$s['NMSAS']}</td>
		</tr>";
		endforeach;

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

	public function hspk2()
	{
		$data = [
			'setkode' => $this->input->post('setkode'),
			'setifno' => $this->input->post('setifno'),
			'setnmpek' => $this->input->post('setnmpek'),
			'setjnpek' => $this->input->post('setjnpek'),
			'setsatuan' => $this->input->post('setsatuan'),
			'setharga' => $this->input->post('setharga')
		];

		$data['hspk2'] = $this->hspk2_load(1, TRUE);

		$this->load->view('lookup/v_lookup_hspk2', $data);
	}

	public function hspk2_load($page = 1, $first = FALSE)
	{
		$per_page = 10;

		$kdhspk2 = $this->input->post('l-kdhspk2');
		$hspk2_nama = $this->input->post('l-hspk2_nama');

		$filter = " AND HSPK2_AKTIF = 1 AND KDTAHUN = '{$this->KDTAHUN}'";
		if($kdhspk2)
		{
			$filter .= " AND KDHSPK2 LIKE '%{$kdhspk2}%'";
		}
		if($hspk2_nama)
		{
			$filter .= " AND HSPK2_NAMA LIKE '%{$hspk2_nama}%'";
		}

		$total = $this->m_set->getHspk2($filter, [], TRUE)->row_array()['TOTAL_ROW'];
		$user = $this->m_set->getHspk2($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($user) < 1):
		$page--;
		$user = $this->m_set->getHspk2($filter, [$per_page, $page])->result_array();
		endwhile;

		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);

		$load = '';
		foreach($user as $u):
		$u = settrim($u);
		$load .= "
		<tr data-id='{$u['KDHSPK2']}'>
			<td class='w1px'><button type='button' class='btn btn-success btn-xs btn-select'>Pilih</button></td>
			<td class='text-nowrap w1px'>{$u['KDHSPK2']}</td>
			<td>{$u['HSPK2_NAMA']}</td>
		</tr>";
		endforeach;

		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockLookupHspk2 + '.block-pagination').html($(blockLookupHspk2 + '.pagetemp').html());
		});
		</script>";

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

	public function hspk3($kdhspk2)
	{
		$data = [
			'kdhspk2' => $kdhspk2,
			'setkode' => $this->input->post('setkode'),
			'setifno' => $this->input->post('setifno'),
			'setnmpek' => $this->input->post('setnmpek'),
			'setjnpek' => $this->input->post('setjnpek'),
			'setsatuan' => $this->input->post('setsatuan'),
			'setharga' => $this->input->post('setharga')
		];

		$data['hspk3'] = $this->hspk3_load($kdhspk2, 1, TRUE);

		$this->load->view('lookup/v_lookup_hspk3', $data);
	}

	public function hspk3_load($kdhspk2, $page = 1, $first = FALSE)
	{
		$per_page = 10;

		$kdhspk3 = $this->input->post('l-kdhspk3');
		$hspk3_nama = $this->input->post('l-hspk3_nama');

		$filter = " AND HSPK3_AKTIF = 1 AND H3.KDTAHUN = '{$this->KDTAHUN}' AND H3.KDHSPK2 = '{$kdhspk2}'";
		if($kdhspk3)
		{
			$filter .= " AND KDHSPK3 LIKE '%{$kdhspk3}%'";
		}
		if($hspk3_nama)
		{
			$filter .= " AND HSPK3_NAMA LIKE '%{$hspk3_nama}%'";
		}

		$total = $this->m_set->getHspk3($filter, [], TRUE)->row_array()['TOTAL_ROW'];
		$user = $this->m_set->getHspk3($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($user) < 1):
		$page--;
		$user = $this->m_set->getHspk2($filter, [$per_page, $page])->result_array();
		endwhile;

		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);

		$load = '';
		foreach($user as $u):
		$u = settrim($u);
		$load .= "
		<tr data-id='{$u['KDHSPK3']}' data-hspk2_nama='{$u['HSPK2_NAMA']}'>
			<td class='w1px'><button type='button' class='btn btn-success btn-xs btn-select'>Pilih</button></td>
			<td class='text-nowrap w1px'>{$u['KDHSPK3']}</td>
			<td>{$u['HSPK3_NAMA']}</td>
			<td class='text-center'>{$u['HSPK3_SATUAN']}</td>
			<td class='text-right nu2d'>{$u['HSPK3_HARGA']}</td>
		</tr>";
		endforeach;

		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockLookupHspk2 + '.block-pagination').html($(blockLookupHspk2 + '.pagetemp').html());
		});
		</script>";

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

	public function program_kegiatan($act = '')
	{
		$unitkey = $this->sip->unitkey($this->input->post('unitkey'));
		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$data['prokeg'] = $this->db->query("
		SELECT
			MK.KEGRKPDKEY,
			ISNULL(D.KDUNIT, '0.00.') AS KDUNIT,
			ISNULL(D.NMUNIT, 'SEMUA URUSAN') AS NMUNIT,
			MP.NUPRGRM,
			MP.NMPRGRM,
			MK.NUKEG,
			MK.NMKEG
		FROM
			MKEGRKPD MK
		JOIN MPGRMRKPD MP ON MK.PGRMRKPDKEY = MP.PGRMRKPDKEY
		AND MK.KDTAHUN = MP.KDTAHUN
		LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
		WHERE
			MK.KDTAHUN = '{$this->KDTAHUN}'
		AND MK.TYPE = 'D'
		AND MP.TYPE = 'D'
		AND (
			MP.UNITKEY IS NULL
			OR MP.UNITKEY IN (
				SELECT
					URUSKEY
				FROM
					URUSANUNIT
				WHERE
					UNITKEY = '{$unitkey}'
			)
		)
		AND MK.KEGRKPDKEY IN (
			SELECT
				KEGRKPDKEY
			FROM
				KEGRKPD
			WHERE
				UNITKEY = '{$unitkey}'
			AND KDTAHUN = '{$this->KDTAHUN}'
			AND KDTAHAP = '{$this->KDTAHAP}'
		)
		ORDER BY
			D.KDUNIT ASC,
			MP.NUPRGRM ASC,
			MK.NUKEG ASC",
		[

		])->result_array();

		$this->load->view('lookup/v_lookup_program_kegiatan', $data);
	}

	public function ssh()
	{
		$data = [
			'kdrek'	=> $this->input->post('l-kdrek'),
			'setkode'	=> $this->input->post('setkode'),
			'setnama'	=> $this->input->post('setnama'),
			'setsatuan'	=> $this->input->post('setsatuan'),
			'setharga'	=> $this->input->post('setharga')
		];

		$data['ssh'] = $this->ssh_load(1, TRUE);

		$this->load->view('lookup/v_lookup_ssh', $data);
	}

	public function ssh_load($page = 1, $first = FALSE)
	{
		$per_page = 10;

		$kdrek = $this->input->post('l-kdrek');
		$kdssh = $this->input->post('l-kdssh');
		$ssh_nama = $this->input->post('l-ssh_nama');
		$ssh_spek = $this->input->post('l-ssh_spek');

		$filter = " AND SSH_AKTIF = 1 AND KDTAHUN = '{$this->KDTAHUN}'";
		if($kdrek)
		{
			$filter .= " AND KDREK = '{$kdrek}'";
		}
		if($kdssh)
		{
			$filter .= " AND KDSSH LIKE '%{$kdssh}%'";
		}
		if($ssh_nama)
		{
			$filter .= " AND SSH_NAMA LIKE '%{$ssh_nama}%'";
		}
		if($ssh_spek)
		{
			$filter .= " AND SSH_SPEK LIKE '%{$ssh_spek}%'";
		}

		$total = $this->m_set->getSsh($filter, [], TRUE)->row_array()['TOTAL_ROW'];
		$user = $this->m_set->getSsh($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($user) < 1):
		$page--;
		$user = $this->m_set->getSsh($filter, [$per_page, $page])->result_array();
		endwhile;

		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);

		$load = '';
		foreach($user as $u):
		$u = settrim($u);
		$load .= "
		<tr data-id='{$u['KDSSH']}'>
			<td class='w1px'><button type='button' class='btn btn-success btn-xs btn-select'>Pilih</button></td>
			<td class='text-nowrap w1px'>{$u['KDSSH']}</td>
			<td>{$u['SSH_NAMA']}</td>
			<td>{$u['SSH_SPEK']}</td>
			<td class='text-center text-nowrap w1px'>{$u['SSH_SATUAN']}</td>
			<td class='text-right nu2d'>{$u['SSH_HARGA']}</td>
		</tr>";
		endforeach;

		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockLookupSsh + '.block-pagination').html($(blockLookupSsh + '.pagetemp').html());
		});
		</script>";

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

	public function user()
	{
		$data['setid'] = $this->input->post('setid');
		$data['setnm'] = $this->input->post('setnm');

		$data['user'] = $this->user_load(1, TRUE);

		$this->load->view('lookup/v_lookup_user', $data);
	}

	public function user_load($page = 1, $first = FALSE)
	{
		$per_page = 10;

		$userid = $this->input->post('l-userid');
		$nip = $this->input->post('l-nip');
		$nama = $this->input->post('l-nama');
		$kdunit = $this->input->post('l-kdunit');

		$filter = '';
		if($userid)
		{
			$filter .= " AND U.USERID LIKE '%{$userid}%'";
		}
		if($nip)
		{
			$filter .= " AND U.NIP LIKE '%{$nip}%'";
		}
		if($nama)
		{
			$filter .= " AND U.NAMA LIKE '%{$nama}%'";
		}
		if($kdunit)
		{
			$filter .= " AND KD.KDUNIT LIKE '%{$kdunit}%'";
		}

		$total = $this->m_user->getAll($filter, [], TRUE)->row_array()['TOTAL_ROW'];
		$user = $this->m_user->getAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($user) < 1):
		$page--;
		$user = $this->m_user->getAll($filter, [$per_page, $page])->result_array();
		endwhile;

		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);

		$load = '';
		foreach($user as $u):
		$u = settrim($u);
		$load .= "
		<tr data-id='{$u['USERID']}'>
			<td class='w1px'><button type='button' class='btn btn-success btn-xs btn-select'>Pilih</button></td>
			<td>{$u['USERID']}</td>
			<td>{$u['NIP']}</td>
			<td>{$u['NAMA']}</td>
			<td class='text-center'>{$u['KDUNIT']}</td>
		</tr>";
		endforeach;

		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockLookupUser + '.block-pagination').html($(blockLookupUser + '.pagetemp').html());
		});
		</script>";

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

	public function group()
	{
		$data['setid'] = $this->input->post('setid');
		$data['setnm'] = $this->input->post('setnm');

		$data['group'] = $this->db->query("
		SELECT
			GROUPID,
			NMGROUP,
			KET
		FROM
			WEBGROUP
		ORDER BY
			NMGROUP
		")->result_array();

		$this->load->view('lookup/v_lookup_group', $data);
	}


	//===============================================PRIORITAS DAN SASARAN DAERAH

	public function prioritas()
	{

		$data['unitkey'] = $this->input->post('f-unitkey');
		$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
		$data['prioritas'] = $this->prioritas_load(TRUE);
		$this->load->view('lookup/v_lookup_prioritas_daerah', $data);
	}

	public function prioritas_load($first = FALSE)
	{
		$noprioppas = $this->input->post('l-noprioppas');
		$nmprioppas = $this->input->post('l-nmprioppas');
		$unitkey = $this->input->post('f-unitkey');
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');

		$filter = '';
		if($noprioppas)
		{
			$filter .= " AND O.NOPRIOPPAS LIKE '%{$noprioppas}%'";
		}
		if($nmprioppas)
		{
			$filter .= " AND O.NMPRIOPPAS LIKE '%{$nmprioppas}%'";
		}

		$prioritas = $this->db->query("
									SELECT
									PRIOPPASKEY,
									NOPRIOPPAS,
									NMPRIOPPAS
									FROM PRIOPPAS P
										WHERE
											KDTAHUN = '{$this->KDTAHUN}'
											AND 	P.PRIOPPASKEY NOT IN (
													SELECT PRIOPPASKEY FROM EPRIOPPAS E WHERE
													KDTAHUN = '{$this->KDTAHUN}'
												AND KDTAHAP = '{$this->KDTAHAP}'
												AND UNITKEY = '{$unitkey}'
												AND PGRMRKPDKEY = '{$pgrmrkpdkey}'
													)
											{$filter}
										ORDER BY NOPRIOPPAS ASC
		")->result_array();

		$load = '';
		foreach($prioritas as $p):
		$p = settrim($p);
		$load .= "
		<tr>
			<td class='text-center'>
			<div class='checkbox checkbox-inline'>
				<input type='checkbox' name='i-check[]' value='{$p['PRIOPPASKEY']}'>
				<label></label>
			</div>
			</td>
			<td class='text-center'>{$p['NOPRIOPPAS']}</td>
			<td>{$p['NMPRIOPPAS']}</td>
		</tr>";
		endforeach;

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}


	public function sasaran()
	{
		$data['unitkey'] = $this->input->post('f-unitkey');
		$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
		$data['prioppaskey'] = $this->input->post('f-prioppaskey');


		$data['sasaran'] = $this->sasaran_load(TRUE);

		$this->load->view('lookup/v_lookup_sasaran', $data);
	}

	public function sasaran_load($first = FALSE)
	{
		$unitkey = $this->input->post('f-unitkey');
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$prioppaskey = $this->input->post('f-prioppaskey');
		$nosas = $this->input->post('l-nosas');
		$nmsas = $this->input->post('l-nmsas');
		$filter = '';
		if($nosas)
		{
			$filter .= " AND S.NOSAS LIKE '%{$nosas}%'";
		}
		if($nmsas)
		{
			$filter .= " AND S.NMSAS LIKE '%{$nmsas}%'";
		}

		$sasaran = $this->db->query("
			SELECT
				S.IDSAS,
				S.NOSAS,
				S.NMSAS
			FROM
				SASARAN S
			JOIN PRIOSAS OS ON S.IDSAS = OS.IDSAS
			WHERE
				OS.PRIOPPASKEY = '{$prioppaskey}'
			AND OS.KDTAHUN = '{$this->KDTAHUN}'

			AND 	S.IDSAS NOT IN (
					SELECT IDSAS FROM ESASARAN E WHERE
					KDTAHUN = '{$this->KDTAHUN}'
				AND KDTAHAP = '{$this->KDTAHAP}'
				AND UNITKEY = '{$unitkey}'
				AND PGRMRKPDKEY = '{$pgrmrkpdkey}'
				AND PRIOPPASKEY = '{$prioppaskey}')
				{$filter}
			ORDER BY
				S.NOSAS ASC
		")->result_array();

		$load = '';
		foreach($sasaran as $s):
		$s = settrim($s);
		$load .= "
		<tr data-id='{$s['IDSAS']}'>
			<td class='text-center'>
			<div class='checkbox checkbox-inline'>
				<input type='checkbox' name='i-check[]' value='{$s['IDSAS']}'>
				<label></label>
			</div>
			</td>
			<td class='text-center'>{$s['NOSAS']}</td>
			<td>{$s['NMSAS']}</td>
		</tr>";

		endforeach;

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

//====================================================PRIORITAS DAN SASARAN PROVINSI


	public function prioritas_provinsi()
	{

		$data['unitkey'] = $this->input->post('f-unitkey');
		$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
		$data['lookup_prioritas_provinsi'] = $this->prioritas_load_provinsi(TRUE);
		$this->load->view('lookup/v_lookup_prioritas_provinsi', $data);
	}

	public function prioritas_load_provinsi($first = FALSE)
	{
		$noprio = $this->input->post('l-noprio');
		$nmprio = $this->input->post('l-nmprio');
		$unitkey = $this->input->post('f-unitkey');
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');


		$filter = '';
		if($noprio)
		{
			$filter .= " AND O.NOPRIO LIKE '%{$noprio}%'";
		}
		if($nmprio)
		{
			$filter .= " AND O.NMPRIO LIKE '%{$nmprio}%'";
		}

		$prioritas_provinsi = $this->db->query("
			SELECT
				O.PRIOPROVKEY,
				O.NOPRIO,
				O.NMPRIO
			FROM
				PRIOPROVINSI O
			WHERE
			 O.KDTAHUN = '{$this->KDTAHUN}'
			AND	O.PRIOPROVKEY NOT IN (
					SELECT PRIOPROVKEY FROM EPRIOPPASPROV E WHERE
					KDTAHUN = '{$this->KDTAHUN}'
				AND KDTAHAP = '{$this->KDTAHAP}'
				AND UNITKEY = '{$unitkey}'
				AND PGRMRKPDKEY = '{$pgrmrkpdkey}'
					)
				{$filter}
			ORDER BY O.NOPRIO ASC
		")->result_array();

		$load = '';
		foreach($prioritas_provinsi as $p):
		$p = settrim($p);
		$load .= "
		<tr>
			<td class='text-center'>
			<div class='checkbox checkbox-inline'>
				<input type='checkbox' name='i-check[]' value='{$p['PRIOPROVKEY']}'>
				<label></label>
			</div>
			</td>
			<td class='text-center'>{$p['NOPRIO']}</td>
			<td>{$p['NMPRIO']}</td>
		</tr>";
		endforeach;

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}


	public function sasaran_provinsi()
	{
		$data['unitkey'] = $this->input->post('f-unitkey');
		$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
		$data['prioprovkey'] = $this->input->post('f-prioprovkey');


		$data['sasaran_provinsi'] = $this->sasaran_provinsi_load(TRUE);

		$this->load->view('lookup/v_lookup_sasaran_provinsi', $data);
	}

	public function sasaran_provinsi_load($first = FALSE)
	{
		$unitkey = $this->input->post('f-unitkey');
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$prioprovkey = $this->input->post('f-prioprovkey');

		$filter = '';

		$sasaranprov = $this->db->query("
			SELECT
				S.IDSASPROV,
				S.NOSAS,
				S.NMSAS
			FROM
				SASARANPROV S
			JOIN PRIOSASPROV OS ON S.IDSASPROV = OS.IDSASPROV
			WHERE
				OS.PRIOPROVKEY = '{$prioprovkey}'
			AND OS.KDTAHUN = '{$this->KDTAHUN}'

			AND 	S.IDSASPROV NOT IN (
					SELECT IDSASPROV FROM ESASARANPROV E WHERE
					KDTAHUN = '{$this->KDTAHUN}'
				AND KDTAHAP = '{$this->KDTAHAP}'
				AND UNITKEY = '{$unitkey}'
				AND PGRMRKPDKEY = '{$pgrmrkpdkey}'
				AND PRIOPROVKEY = '{$prioprovkey}')
				{$filter}
			ORDER BY
				S.NOSAS ASC
		")->result_array();

		$load = '';
		foreach($sasaranprov as $sp):
		$sp = settrim($sp);
		$load .= "
		<tr data-id='{$sp['IDSASPROV']}'>
			<td class='text-center'>
			<div class='checkbox checkbox-inline'>
				<input type='checkbox' name='i-check[]' value='{$sp['IDSASPROV']}'>
				<label></label>
			</div>
			</td>
			<td class='text-center'>{$sp['NOSAS']}</td>
			<td>{$sp['NMSAS']}</td>
		</tr>";

		endforeach;

		if($first)
		{
			return $load;
		}
		else
		{
			echo $load;
		}
	}

//============================================PRIORITAS DAN SASARAN NASIONAL


public function prioritas_nasional()
{

	$data['unitkey'] = $this->input->post('f-unitkey');
	$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
	$data['lookup_prioritas_nasional'] = $this->prioritas_load_nasional(TRUE);
	$this->load->view('lookup/v_lookup_prioritas_nasional', $data);
}

public function prioritas_load_nasional($first = FALSE)
{
	$nuprio = $this->input->post('l-nuprio');
	$nmprio = $this->input->post('l-nmprio');
	$unitkey = $this->input->post('f-unitkey');
	$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');


	$filter = '';
	if($nuprio)
	{
		$filter .= " AND O.NUPRIO LIKE '%{$nuprio}%'";
	}
	if($nmprio)
	{
		$filter .= " AND O.NMPRIO LIKE '%{$nmprio}%'";
	}

	$prioritas_nasional = $this->db->query("
		SELECT
			O.PRIONASKEY,
			O.NUPRIO,
			O.NMPRIO
		FROM
			PRIONAS O
		WHERE
		 O.KDTAHUN = '{$this->KDTAHUN}'
		AND	O.PRIONASKEY NOT IN (
				SELECT PRIONASKEY FROM EPRIOPPASNAS E WHERE
				KDTAHUN = '{$this->KDTAHUN}'
			AND KDTAHAP = '{$this->KDTAHAP}'
			AND UNITKEY = '{$unitkey}'
			AND PGRMRKPDKEY = '{$pgrmrkpdkey}'
				)
			{$filter}
		ORDER BY O.NUPRIO ASC
	")->result_array();

	$load = '';
	foreach($prioritas_nasional as $p):
	$p = settrim($p);
	$load .= "
	<tr>
		<td class='text-center'>
		<div class='checkbox checkbox-inline'>
			<input type='checkbox' name='i-check[]' value='{$p['PRIONASKEY']}'>
			<label></label>
		</div>
		</td>
		<td class='text-center'>{$p['NUPRIO']}</td>
		<td>{$p['NMPRIO']}</td>
	</tr>";
	endforeach;

	if($first)
	{
		return $load;
	}
	else
	{
		echo $load;
	}
}


		public function sasaran_nasional()
		{
			$data['unitkey'] = $this->input->post('f-unitkey');
			$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
			$data['prionaskey'] = $this->input->post('f-prionaskey');


			$data['sasaran_nasional'] = $this->sasaran_nasional_load(TRUE);

			$this->load->view('lookup/v_lookup_sasaran_nasional', $data);
		}

		public function sasaran_nasional_load($first = FALSE)
		{
			$unitkey = $this->input->post('f-unitkey');
			$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
			$prionaskey = $this->input->post('f-prionaskey');

			$filter = '';

			$sasarannas = $this->db->query("
				SELECT
					S.IDSASNAS,
					S.NOSAS,
					S.NMSAS
				FROM
					SASARANNAS S
				JOIN PRIOSASNAS OS ON S.IDSASNAS = OS.IDSASNAS
				WHERE
					OS.PRIONASKEY = '{$prionaskey}'
				AND OS.KDTAHUN = '{$this->KDTAHUN}'

				AND 	S.IDSASNAS NOT IN (
						SELECT IDSASNAS FROM ESASARANNAS E WHERE
						KDTAHUN = '{$this->KDTAHUN}'
					AND KDTAHAP = '{$this->KDTAHAP}'
					AND UNITKEY = '{$unitkey}'
					AND PGRMRKPDKEY = '{$pgrmrkpdkey}'
					AND PRIONASKEY = '{$prionaskey}')
					{$filter}
				ORDER BY
					S.NOSAS ASC
			")->result_array();

			$load = '';
			foreach($sasarannas as $sp):
			$sp = settrim($sp);
			$load .= "
			<tr data-id='{$sp['IDSASNAS']}'>
				<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$sp['IDSASNAS']}'>
					<label></label>
				</div>
				</td>
				<td class='text-center'>{$sp['NOSAS']}</td>
				<td>{$sp['NMSAS']}</td>
			</tr>";

			endforeach;

			if($first)
			{
				return $load;
			}
			else
			{
				echo $load;
			}
		}


//tambahan sub kegiatan

public function subkegiatan($act = '')
{
	$data['unitkey'] = $this->sip->unitkey($this->input->post('l-unitkey'));
	$data['kegrkpdkey'] = $this->input->post('l-kegrkpdkey');

	$data['setid'] = $this->input->post('setid');
	$data['setkd'] = $this->input->post('setkd');
	$data['setnm'] = $this->input->post('setnm');

	$data['subkegiatan'] = $this->subkegiatan_load($act, TRUE);

	$this->load->view('lookup/v_lookup_subkegiatan', $data);
}

public function subkegiatan_load($act = '', $first = FALSE)
{
	$unitkey = $this->sip->unitkey($this->input->post('l-unitkey'));
	$kegrkpdkey = $this->input->post('l-kegrkpdkey');

	$nusubkeg = $this->input->post('l-nusubkeg');
	$nmsubkeg = $this->input->post('l-nmsubkeg');

	$filter = '';
	if($nusubkeg)
	{
		$filter .= " AND MK.NUKEG LIKE '%{$nusubkeg}%'";
	}
	if($nmsubkeg)
	{
		$filter .= " AND MK.NMKEG LIKE '%{$nmsubkeg}%'";
	}

	if($act != 'all')
	{
		$filter .= "
		AND MK.SUBKEGRKPDKEY NOT IN (
			SELECT
				K.SUBKEGRKPDKEY
			FROM
				SUBKEGRKPD K
			WHERE
				K.KDTAHUN = '{$this->KDTAHUN}'
			AND K.KDTAHAP = '{$this->KDTAHAP}'
			AND K.UNITKEY = '{$unitkey}'
			AND K.KEGRKPDKEY = '{$kegrkpdkey}'
		)";
	}

	$subkegiatan = $this->db->query("
		SELECT
			MK.SUBKEGRKPDKEY,
			MK.NUSUBKEG,
			MK.NMSUBKEG
		FROM
			MSUBKEGRKPD MK
		WHERE
			MK.KDTAHUN = '{$this->KDTAHUN}'
		AND MK.KEGRKPDKEY = '{$kegrkpdkey}'
		AND MK.TYPE = 'D'
			{$filter}
		ORDER BY
			MK.NUSUBKEG
	")->result_array();

	$load = '';
	foreach($subkegiatan as $p):
	$p = settrim($p);
	$load .= "
	<tr data-id='{$p['SUBKEGRKPDKEY']}'>
		<td class='text-center'><a href='javascript:void(0)' class='btn-select'>Select</a></td>
		<td class='text-center'>{$p['NUSUBKEG']}</td>
		<td>{$p['NMSUBKEG']}</td>
	</tr>";
	endforeach;

	if($first)
	{
		return $load;
	}
	else
	{
		echo $load;
	}
}










//selesai














}
