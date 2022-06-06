<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prarka extends CI_Controller {

	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];
	
	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	private $USERID = NULL;
	
	function __construct()
	{
		parent::__construct();
		
		$this->sip->is_logged();
		$this->sip->is_menu('040201');
		
		$this->load->model(['m_set', 'm_rka', 'm_program', 'm_kegiatan', 'm_hspk', 'm_master']);
		
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
		$this->USERID = $this->session->USERID;
	}
	
	public function index()
	{
		$this->load->view('prarka/v_prarka');
	}
	
	public function rekening_load($page = 1, $first = FALSE)
	{
		$per_page = 6;
		
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');
		
		$pagu = $this->m_set->getPaguKegiatanSummary($unitkey, $kegrkpdkey);
		
		$filter = "
		AND R.UNITKEY = '{$unitkey}'
		AND R.KDTAHUN = '{$this->KDTAHUN}'
		AND R.KDTAHAP = '{$this->KDTAHAP}'
		AND R.KEGRKPDKEY = '{$kegrkpdkey}'";
		
		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'T.KDPER'; break;
				case '2' : $search_type = 'T.NMPER'; break;
			}
			
			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}
		
		$get_total = $this->m_rka->getRekeningAll($filter, NULL, TRUE)->row_array();
		$grand_total = $get_total['GRAND_TOTAL'];
		$total = $get_total['TOTAL_ROW'];
		$rows = $this->m_rka->getRekeningAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_rka->getRekeningAll($filter, [$per_page, $page])->result_array();
		endwhile;
		
		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);
		
		if($first)
		{
			ob_start();
		}
		
		$sub_total = 0;
		foreach($rows as $r):
		$r = settrim($r);
		?>
		<tr id="tr-rekening-<?php echo $r['MTGKEY']; ?>">
			<td><a href="javascript:void(0)" class="btn-rekening-show-detail"><u><?php echo $r['KDPER']; ?></u></a></td>
			<td><?php echo $r['NMPER']; ?></td>
			<td class="text-right nu2d"><?php echo $r['NILAI']; ?></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['MTGKEY']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php
		
		$sub_total += $r['NILAI'];
		endforeach;
		$subgrand = "";
		
		?>
		<tr>
			<td></td>
			<td class="text-right text-bold">Sub Total<br>Grand Total</td>
			<td class="text-right text-bold">
				<span class="nu2d"><?php echo $sub_total; ?></span><br>
				<span class="nu2d"><?php echo $grand_total; ?></span></td>
			<td></td>
		</tr>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			updatePagu(<?php echo "{$pagu['PAGU']},{$pagu['PAGUUSED']},{$pagu['SELISIH']}";?>);
			
			$(blockRekening + '.block-pagination').html($(blockRekening + '.pagetemp').html());
			$(blockRekening + '.subgrand-total').html("<?php echo $subgrand; ?>");
		});
		</script>
		<?php
		
		if($first)
		{
			$load = ob_get_contents();
			ob_end_clean();
			return $load;
		}
	}
	
	public function rekening_form($act)
	{
		//$this->sip->is_curd('I');
		//$data['unitkey'] = $this->input->post('l-unitkey');
		//$data['kegrkpdkey'] = $this->input->post('l-kegrkpdkey');
		//$data['rekening'] = $this->rekening_form_load(1, TRUE);
		//$this->load->view('prarka/v_prarka_rekening_form', $data);
		
		//20190308 check sumber dana
		$unitkey = $this->input->post('l-unitkey');
		$kegrkpdkey = $this->input->post('l-kegrkpdkey');

		$check = (int) $this->db->query("
			SELECT COUNT(NILAI) AS TOTAL
			FROM KEGRKPDDANA
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
		",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->row_array()['TOTAL'];
		if($check !== 0)
		{
			$this->sip->is_curd('I');

			$data['unitkey'] = $this->input->post('l-unitkey');
			$data['kegrkpdkey'] = $this->input->post('l-kegrkpdkey');

			$data['rekening'] = $this->rekening_form_load(1, TRUE);

			$this->load->view('prarka/v_prarka_rekening_form', $data);
		}
		else {
			?>
				 <script type="text/javascript">
						 alert("Sumber dana belum ada, silahkan masukkan sumber dana pada renja");
						 modalRekeningForm.close();
				 </script>
		 <?php

		}
		//==================================
	
	}
	
	public function rekening_form_load($page = 1, $first = FALSE)
	{
		$this->sip->is_curd('I');
		
		$per_page = 10;
		
		$unitkey	= $this->sip->unitkey($this->input->post('l-unitkey'));
		$kegrkpdkey	= $this->input->post('l-kegrkpdkey');
		$kdper		= $this->input->post('l-kdper');
		$nmper		= $this->input->post('l-nmper');
		
		$filter = '';
		if($kdper)
		{
			$filter .= " AND KDPER LIKE '%{$kdper}%'";
		}
		if($nmper)
		{
			$filter .= " AND NMPER LIKE '%{$nmper}%'";
		}
		
		$total = $this->m_rka->getRekeningForm($unitkey, $kegrkpdkey, $filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_rka->getRekeningForm($unitkey, $kegrkpdkey, $filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_rka->getRekeningForm($unitkey, $kegrkpdkey, $filter, [$per_page, $page])->result_array();
		endwhile;
		
		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);
		
		$load = '';
		foreach($rows as $r):
		$r = settrim($r);
		
		$mtgkey = ($r['TYPE'] == 'D') ? "
		<div class='checkbox checkbox-inline'>
			<input type='checkbox' name='i-check[]' value='{$r['MTGKEY']}'>
			<label></label>
		</div>" : "";
		
		$load .= "
		<tr>
			<td class='text-center w1px'>{$mtgkey}</td>
			<td>{$r['KDPER']}</td>
			<td>{$r['NMPER']}</td>
		</tr>";
		endforeach;
		
		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockRekeningForm + '.block-pagination').html($(blockRekeningForm + '.pagetemp').html());
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
	
	public function rekening_save()
	{
		$this->sip->is_curd('I');
		
		$this->load->library('form_validation');
		
		try
		{
			$this->form_validation->set_rules('i-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('i-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			
			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}
			
			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey		= $this->input->post('i-kegrkpdkey');
			$mtgkey_list	= $this->input->post('i-check[]');
			
			foreach($mtgkey_list AS $mtgkey)
			{
				if($mtgkey == '') continue;
				$this->db->insert('PRARASKR',
				[
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'KEGRKPDKEY'	=> $kegrkpdkey,
					'MTGKEY'		=> $mtgkey,
					'NILAI'			=> '0'
				]);
				
				$nilai = ' KDTAHUN : '. $this->KDTAHUN .	' KDTAHAP : ' . $this->KDTAHAP . ' UNITKEY : '		. $unitkey .  ' KEGRKPDKEY : '. $kegrkpdkey .	'MTGKEY : ' . $mtgkey ;
				$nmTable = 'PRARASKR';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
			}
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			$this->json['cod'] = $e->getCode();
			$this->json['msg'] = $e->getMessage();
		}
		
		if($this->json['cod'] !== NULL)
		{
			$this->output->set_content_type('application/json')->set_output(json_encode($this->json));
		}
	}
	
	public function rekening_delete()
	{
		$this->sip->is_curd('D');
		
		$this->load->library('form_validation');
		
		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$mtgkey		= $this->input->post('i-check[]');
			
			$this->db->query("
			DELETE FROM PRARASKDETR 
			WHERE 
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND MTGKEY IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$mtgkey
			]);
			
			$this->db->query("
			DELETE FROM PRARASKR 
			WHERE 
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND MTGKEY IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$mtgkey
			]);
			
			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Rekening gagal dihapus.', 2);
			}
			
			$data = json_encode($mtgkey);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'PRARASKR dan PRARASKDETR ';
			$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			$this->json['cod'] = $e->getCode();
			$this->json['msg'] = $e->getMessage();
		}
		
		if($this->json['cod'] !== NULL)
		{
			$this->output->set_content_type('application/json')->set_output(json_encode($this->json));
		}
	}
	
	public function detail_load($page = 1, $first = FALSE)
	{
		$per_page = 10;

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');
		$mtgkey = $this->input->post('f-mtgkey');
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$filter = "
		AND UNITKEY = '{$unitkey}'
		AND KDTAHUN = '{$this->KDTAHUN}'
		AND KDTAHAP = '{$this->KDTAHAP}'
		AND KEGRKPDKEY = '{$kegrkpdkey}'
		AND MTGKEY = '{$mtgkey}'
		";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $typesearch = 'KDJABAR'; break;
				case '2' : $typesearch = 'URAIAN'; break;
				case '3' : $typesearch = 'JUMBYEK'; break;
				case '4' : $typesearch = 'SATUAN'; break;
				case '5' : $typesearch = 'TARIF'; break;
				case '6' : $typesearch = 'SUBTOTAL'; break;
				case '7' : $typesearch = 'TYPE'; break;
			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$get_total = $this->m_rka->getDetailAll($filter, NULL, TRUE)->row_array();
		$grand_total = $get_total['GRAND_TOTAL'];
		$total = $get_total['TOTAL_ROW'];

		$rows = $this->m_rka->getDetailAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_rka->getDetailAll($filter, [$per_page, $page])->result_array();
		endwhile;

		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);

		$sum_subtotal = 0;
		$load = '';
		foreach($rows as $r):
		$r = settrim($r);

		if($r['TYPE'] == 'D')
		{
			$header_bold = '';
			$jumyek = $r['JUMBYEK'];
			$satuan = $r['SATUAN'];
			$tarif = $r['TARIF'];
			$num = 'nu2d';
		}
		else
		{
			$header_bold = 'text-bold';
			$jumyek = '';
			$satuan = '';
			$tarif = '';
			$num = '';
		}

		$load .= "
		<tr id='tr-detail-{$r['KDJABAR']}'>
			<td class='{$header_bold}'>{$r['KDJABAR']}</td>
			<td class='{$header_bold}'>{$r['URAIAN']}</td>
			<td class='text-right {$num}'>{$jumyek}</td>
			<td class='text-center'>{$satuan}</td>
			<td class='text-right {$num}'>{$tarif}</td>
			<td class='text-right nu2d {$header_bold}'>{$r['SUBTOTAL']}</td>
			<td class='text-center'>{$r['TYPE']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-detail-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['KDNILAI']}'>
					<label></label>
				</div>
			</td>
			<td class='text-center'>
				".(($r['TYPE'] == 'D') ? '' : "<a href='javascript:void(0)' class='btn-detail-form' data-act='add' data-set='C'><i class='fa fa-arrows-v'></i></a>")."
			</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-detail-form' data-act='add' data-set='S'><i class='fa fa-arrows-h'></i></a></td>
		</tr>";

		$sum_subtotal += ($r['TYPE'] == 'D') ? $r['SUBTOTAL'] : 0;
		endforeach;

		$load .= "
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class='text-right text-bold'>Sub Total<br>Grand Total</td>
			<td class='text-right text-bold'><span class='nu2d'>{$sum_subtotal}</span><br><span class='nu2d'>{$grand_total}</span></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockDetail + '.block-pagination').html($(blockDetail + '.pagetemp').html());
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
	
	public function detail_form($act)
	{
		$this->load->library('form_validation');
		$is_admin = $this->sip->is_admin();

		$unitkey	= $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');
		$mtgkey		= $this->input->post('f-mtgkey');
		$kdper		= $this->input->post('f-kdper');
		$kdnilai	= $this->input->post('f-kdnilai');
		$kddana	= $this->input->post('i-kddana');

		$kode		= $this->input->post('f-kode');
		$set		= $this->input->post('f-set');

		$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
		$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
		$this->form_validation->set_rules('f-mtgkey', 'Kode Rekening', 'trim|required');
		$this->form_validation->set_rules('f-kdper', 'Kode Rekening', 'trim|required');

		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$kdjabar = '';
			$type = 'H';

			if($set == 'S') {
				$kdjabar = $this->db->query("
				SELECT TOP 1 D.KDJABAR
				FROM PRARASKDETR D,
					(SELECT UNITKEY, KDTAHUN, KDTAHAP, KEGRKPDKEY, MTGKEY, KDJABAR FROM PRARASKDETR WHERE KDNILAI = ?) X
				WHERE
					D.KDJABAR IN (SELECT * FROM dbo.PHP_STR_CUT(X.KDJABAR))
					AND D.KDJABAR	!= X.KDJABAR
					AND D.TYPE		= 'H'
					AND D.UNITKEY	= X.UNITKEY
					AND D.KDTAHUN	= X.KDTAHUN
					AND D.KDTAHAP	= X.KDTAHAP
					AND D.KEGRKPDKEY = X.KEGRKPDKEY
					AND D.MTGKEY	= X.MTGKEY
				ORDER BY D.KDJABAR DESC", $kode)->row_array()['KDJABAR'];
			} elseif($set == 'C') {
				$kdjabar = $kode;
				$type = 'D';
			}

			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'mtgkey'		=> $mtgkey,
				'kdper'			=> $kdper,
				'kdnilai'		=> '',
				'kdjabar'		=> $kdjabar,
				'uraian'		=> '',
				'jumbyek'		=> '0',
				'satuan'		=> '',
				'tarif'			=> '0',
				'type'			=> $type,
				'kdssh'			=> '',
				'rossh'			=> '',
				'kddana'			=> '',

				'curdShow'		=> $this->sip->curdShow('I')
			];

			$data['sumberdana'] = $this->m_rka->getSumberDanaDetail($unitkey,$kegrkpdkey);

		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-kdnilai', 'Kode Nilai', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				KDNILAI,
				KDJABAR,
				URAIAN,
				JUMBYEK,
				SATUAN,
				TARIF,
				TYPE,
				KDDANA,
				KDSSH
			FROM PRARASKDETR R
			WHERE
				KDTAHUN		= ?
			AND KDTAHAP		= ?
			AND UNITKEY		= ?
			AND KEGRKPDKEY	= ?
			AND MTGKEY		= ?
			AND KDNILAI		= ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$mtgkey,
				$kdnilai
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'mtgkey'		=> $mtgkey,
				'kdper'			=> $kdper,
				'kdnilai'		=> $kdnilai,
				'kdjabar'		=> $r['KDJABAR'],
				'uraian'		=> $r['URAIAN'],
				'jumbyek'		=> $r['JUMBYEK'],
				'satuan'		=> $r['SATUAN'],
				'tarif'			=> $r['TARIF'],
				'type'			=> ($r['KDSSH'] != '') ? 'D' : $r['TYPE'],
				'kdssh'			=> $r['KDSSH'],
				'kddana'		=> $r['KDDANA'],
				'rossh'			=> ($r['KDSSH'] != '') ? 'readonly' : '',
				'curdShow'		=> $this->sip->curdShow('U')
			];
		}
			$data['sumberdana'] = $this->m_rka->getSumberDanaDetail($unitkey,$kegrkpdkey);

			if($this->json['cod'] !== NULL)
		{
			echo $this->json['msg'];
		}
		else
		{
			$this->load->view('prarka/v_prarka_detail_form', $data);
		}
	}

	public function detail_save($act)
	{
		if($act == 'add')
		{
			$this->sip->is_curd('I');
		}
		elseif($act == 'edit')
		{
			$this->sip->is_curd('U');
		}

		$this->load->library('form_validation');

		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$mtgkey		= $this->input->post('i-mtgkey');
			$kdnilai	= $this->input->post('i-kdnilai');
			$kdjabar	= $this->input->post('i-kdjabar');
			$uraian		= $this->input->post('i-uraian');
			$jumbyek	= $this->input->post('i-jumbyek');
			$satuan		= $this->input->post('i-satuan');
			$tarif		= $this->input->post('i-tarif');
			$type		= $this->input->post('i-type');
			$kdssh		= $this->input->post('i-kdssh');
			$kddana		= $this->input->post('i-kddana');
			$subtotal	= ($jumbyek * $tarif);

			$type		= (!empty($kdssh)) ? 'D' : $type;

			$this->form_validation->set_rules('i-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('i-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('i-mtgkey', 'Kode Rekening', 'trim|required');
			$this->form_validation->set_rules('i-kdjabar', 'Kode', 'trim|required');
			$this->form_validation->set_rules('i-uraian', 'Uraian', 'trim|required');
			$this->form_validation->set_rules('i-type', 'Type', 'trim|required');
			$this->form_validation->set_rules('i-kdssh', 'Kode SSH', 'trim');
			$this->form_validation->set_rules('i-kddana', 'Sumber Dana', 'trim');

			if($act == 'add')
			{
				if($type == 'H')
				{
					if($this->form_validation->run() == FALSE)
					{
						throw new Exception(custom_errors(validation_errors()), 2);
					}

					$jumbyek = 0;
					$satuan = '';
					$tarif = 0;
					$subtotal = 0;
					$kdssh = '';
				}
				elseif($type == 'D')
				{

					$this->form_validation->set_rules('i-jumbyek', 'Volume', 'trim|required|numeric|greater_than_equal_to[0]');
					$this->form_validation->set_rules('i-satuan', 'Satuan', 'trim|required');
					$this->form_validation->set_rules('i-tarif', 'Tarif', 'trim|required|numeric|greater_than_equal_to[0]');

					if($this->form_validation->run() == FALSE)
					{
						throw new Exception(custom_errors(validation_errors()), 2);
					}

					$check = (int) $this->db->query("
						SELECT COUNT(KDJABAR) AS TOTAL
						FROM PRARASKDETR
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND KEGRKPDKEY = ?
						AND MTGKEY = ?
						AND KDJABAR = ?
					",
					[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey, $mtgkey, $kdjabar])->row_array()['TOTAL'];

					if($check !== 0)
					{
						throw new Exception("Kode <strong>{$kdjabar}</strong> telah terdaftar.", 2);
					}

					$check_pagu = $this->db->query("
					SELECT
						PAGUTIF AS PAGU_LIMIT,
						(
							SELECT
								SUM(COALESCE(SUBTOTAL,0)) + $subtotal
							FROM
								PRARASKDETR
							WHERE
								KDTAHUN = K.KDTAHUN
							AND KDTAHAP = K.KDTAHAP
							AND UNITKEY = K.UNITKEY
							AND KEGRKPDKEY = K.KEGRKPDKEY
							AND TYPE = 'D'
						) AS TOTAL_PENJABARAN
					FROM
						KEGRKPD K
					WHERE
						KDTAHUN = ?
					AND KDTAHAP = ?
					AND UNITKEY = ?
					AND KEGRKPDKEY = ?
					",
					[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->row_array();

					if($check_pagu['TOTAL_PENJABARAN'] > $check_pagu['PAGU_LIMIT'])
					{
						throw new Exception(
							"Total Penjabaran = <strong>".money($check_pagu['TOTAL_PENJABARAN'])."</strong><br>".
							"Pagu Kegiatan = <strong>".money($check_pagu['PAGU_LIMIT'])."</strong><br>".
							"Nilai Selisih = <strong>".money($check_pagu['TOTAL_PENJABARAN'] - $check_pagu['PAGU_LIMIT'])."</strong>"
						, 2);
					}
				}

				$kdnilai = $this->m_set->getNextKey('PRARASKDETR');

				$set = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'KEGRKPDKEY'	=> $kegrkpdkey,
					'MTGKEY'		=> $mtgkey,
					'KDNILAI'		=> $kdnilai,
					'KDJABAR'		=> $kdjabar,
					'URAIAN'		=> $uraian,
					'JUMBYEK'		=> $jumbyek,
					'SATUAN'		=> $satuan,
					'TARIF'			=> $tarif,
					'SUBTOTAL'		=> $subtotal,
					'EKSPRESI'		=> $jumbyek,
					'TYPE'			=> $type,
					'KDSSH'			=> $kdssh,
					'KDDANA'			=> $kddana
				];

				$affected = $this->m_rka->addDetail($set);
				if($affected !== 1)
				{
					throw new Exception('Penjabaran gagal ditambahkan.', 2);
				}

				$this->m_set->updateNextKey('PRARASKDETR', $kdnilai);

				$this->_updatePraRka($unitkey, $kegrkpdkey, $mtgkey);
				
				$nilai = 'Insert ' .json_encode($set);
				$nmTable = 'PRARASKDETR';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
				
			}
			elseif($act == 'edit')
			{
				$kdjabar_old = $this->db->query("
					SELECT KDJABAR
					FROM PRARASKDETR
					WHERE
						KDTAHUN = ?
					AND KDTAHAP = ?
					AND UNITKEY = ?
					AND KEGRKPDKEY = ?
					AND MTGKEY = ?
					AND KDNILAI = ?
				",
				[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey, $mtgkey, $kdnilai])->row_array()['KDJABAR'];

				if($kdjabar != $kdjabar_old)
				{
					$check = (int) $this->db->query("
						SELECT COUNT(KDJABAR) AS TOTAL
						FROM PRARASKDETR
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND KEGRKPDKEY = ?
						AND MTGKEY = ?
						AND KDJABAR = ?
					",
					[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey, $mtgkey, $kdjabar])->row_array()['TOTAL'];

					if($check > 0)
					{
						throw new Exception("Kode <strong>{$kdjabar}</strong> telah terdaftar.", 2);
					}
				}

				if($type == 'H')
				{
					if($this->form_validation->run() == FALSE)
					{
						throw new Exception(custom_errors(validation_errors()), 2);
					}

					$jumbyek = 0;
					$satuan = '';
					$tarif = 0;
					$subtotal = 0;
					$kdssh = '';
				}
				elseif($type == 'D')
				{
					$this->form_validation->set_rules('i-kdnilai', 'Kode Nilai', 'trim|required');
					$this->form_validation->set_rules('i-jumbyek', 'Volume', 'trim|required|numeric|greater_than_equal_to[0]');
					$this->form_validation->set_rules('i-satuan', 'Satuan', 'trim|required');
					$this->form_validation->set_rules('i-tarif', 'Tarif', 'trim|required|numeric|greater_than_equal_to[0]');
					$this->form_validation->set_rules('i-kddana', 'Sumber Dana', 'trim|required|numeric|greater_than_equal_to[0]');

					if($this->form_validation->run() == FALSE)
					{
						throw new Exception(custom_errors(validation_errors()), 2);
					}

					$check_pagu = $this->db->query("
					SELECT
						PAGUTIF AS PAGU_LIMIT,
						(
							SELECT
								SUM(COALESCE(SUBTOTAL,0)) + $subtotal
							FROM
								PRARASKDETR
							WHERE
								UNITKEY = K.UNITKEY
							AND KDTAHUN = K.KDTAHUN
							AND KDTAHAP = K.KDTAHAP
							AND KEGRKPDKEY = K.KEGRKPDKEY
							AND TYPE = 'D'
							AND KDNILAI != ?
						) AS TOTAL_PENJABARAN
					FROM
						KEGRKPD K
					WHERE
						KDTAHUN = ?
					AND KDTAHAP = ?
					AND UNITKEY = ?
					AND KEGRKPDKEY = ?
					",
					[$kdnilai, $this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->row_array();

					if($check_pagu['TOTAL_PENJABARAN'] > $check_pagu['PAGU_LIMIT'])
					{
						throw new Exception(
							"Total Penjabaran = <strong>".money($check_pagu['TOTAL_PENJABARAN'])."</strong><br>".
							"Pagu Kegiatan = <strong>".money($check_pagu['PAGU_LIMIT'])."</strong><br>".
							"Nilai Selisih = <strong>".money($check_pagu['TOTAL_PENJABARAN'] - $check_pagu['PAGU_LIMIT'])."</strong>"
						, 2);
					}
				}

				$set = [
					'KDJABAR'	=> $kdjabar,
					'URAIAN'	=> $uraian,
					'JUMBYEK'	=> $jumbyek,
					'SATUAN'	=> $satuan,
					'TARIF'		=> $tarif,
					'SUBTOTAL'	=> $subtotal,
					'EKSPRESI'	=> $jumbyek,
					'TYPE'		=> $type,
					'KDSSH'		=> $kdssh,
					'KDDANA'		=> $kddana
				];

				$where = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'KEGRKPDKEY'	=> $kegrkpdkey,
					'MTGKEY'		=> $mtgkey,
					'KDNILAI'		=> $kdnilai
				];

				$affected = $this->m_rka->updateDetail($where, $set);
				if($affected !== 1)
				{
					throw new Exception('Penjabaran gagal ditambahkan.', 2);
				}

					
				$nilai = 'Edit ' .json_encode($set);
				$nmTable = 'PRARASKDETR';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
				
				$this->_updatePraRka($unitkey, $kegrkpdkey, $mtgkey);
			}
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			$this->json['cod'] = $e->getCode();
			$this->json['msg'] = $e->getMessage();
		}

		if($this->json['cod'] !== NULL)
		{
			$this->output->set_content_type('application/json')->set_output(json_encode($this->json));
		}
		else
		{
			echo $mtgkey;
		}
	}

	public function detail_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$mtgkey		= $this->input->post('i-mtgkey');
			$kdnilai	= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM PRARASKDETR
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND MTGKEY = ?
			AND KDNILAI IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$mtgkey,
				$kdnilai
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Penjabaran gagal dihapus.', 2);
			}
			
			$data = json_encode($kdnilai);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'PRARASKDETR';
			$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
			
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			$this->json['cod'] = $e->getCode();
			$this->json['msg'] = $e->getMessage();
		}

		if($this->json['cod'] !== NULL)
		{
			$this->output->set_content_type('application/json')->set_output(json_encode($this->json));
		}
	}

	
	public function _updatePraRka($unitkey, $kegrkpdkey, $mtgkey)
	{
		$rows = $this->db->query("
			SELECT KDJABAR
			FROM PRARASKDETR
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND MTGKEY = ?
			AND TYPE = 'H'
			ORDER BY KDJABAR DESC
		",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey, $mtgkey])->result_array();
			
		$sql = "";
		$done = [];
		foreach($rows as $r)
		{
			$r = settrim($r);
			
			$len = strlen($r['KDJABAR']);
			for($i = $len; $i>0; $i--)
			{
				$kdj = substr($r['KDJABAR'],0,$i);
				if(!in_array($kdj, $done))
				{
					$this->db->query("
						UPDATE RD
						SET RD.SUBTOTAL = (
							SELECT SUM(COALESCE(SUBTOTAL,0)) AS SUBTOTAL
							FROM PRARASKDETR
							WHERE
								UNITKEY = RD.UNITKEY
							AND KDTAHUN = RD.KDTAHUN
							AND KDTAHAP = RD.KDTAHAP
							AND KEGRKPDKEY = RD.KEGRKPDKEY
							AND MTGKEY = RD.MTGKEY
							AND TYPE = 'D'
							AND KDJABAR LIKE '{$kdj}%'
						)
						FROM PRARASKDETR RD
						WHERE
							RD.UNITKEY = '{$unitkey}'
						AND RD.KDTAHUN = '{$this->KDTAHUN}'
						AND RD.KDTAHAP = '{$this->KDTAHAP}'
						AND RD.KEGRKPDKEY = '{$kegrkpdkey}'
						AND RD.MTGKEY = '{$mtgkey}'
						AND RD.TYPE = 'H'
						AND RD.KDJABAR = '{$kdj}';
						
					");
					
					$done[] = $kdj;
				}
			}
		}
		
		//$this->db->query($sql);
		
		$this->db->query("
		UPDATE R
		SET R.NILAI = (
			SELECT SUM(COALESCE(SUBTOTAL,0)) AS SUBTOTAL
			FROM PRARASKDETR
			WHERE
				UNITKEY = R.UNITKEY
			AND KDTAHUN = R.KDTAHUN
			AND KDTAHAP = R.KDTAHAP
			AND KEGRKPDKEY = R.KEGRKPDKEY
			AND MTGKEY = R.MTGKEY
			AND TYPE = 'D'
		)
		FROM PRARASKR R
		WHERE
			R.KDTAHUN = ?
		AND R.KDTAHAP = ?
		AND R.UNITKEY = ?
		AND R.KEGRKPDKEY = ?
		AND R.MTGKEY = ?
		",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey, $mtgkey]);
	}
}
