<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class master extends CI_Controller {

	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	private $USERID = NULL;

	function __construct()
	{
		parent::__construct();

		$this->sip->is_logged();
		$this->sip->is_menu('03');

		$this->load->model(['m_set', 'm_program', 'm_kegiatan', 'm_hspk', 'm_master']);

		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
		$this->USERID = $this->session->USERID;
	}

	public function index()
	{


	}
//*CALL MENU*
	public function programKegiatan()
	{
		$this->sip->is_menu('030101');
		$this->load->view('master/v_entry_program');
	}

	public function masterPagu()
	{
		$this->sip->is_menu('0302');
		$this->load->view('master/v_entry_pagu');
	}

	public function Pemda()
	{
		$this->sip->is_menu('0004');
		$this->load->view('master/v_entry_pemda');
	}

	public function rekening_belanja_langsung(){
		$this->sip->is_menu('0006');
		$this->load->view('master/v_entry_rekening_belanja_langsung');
	}

//**
//penambahan matangr

//**

public function MasterRekeningBelanjaLangsung_load($page = 1, $first = FALSE){
	$per_page = 20;

	$search_type = $this->input->post('f-search_type');
	$search_key = $this->input->post('f-search_key');

	$filter = "AND KDTAHUN = '{$this->KDTAHUN}'";

	if($search_key)
	{
		switch($search_type)
		{
			case '1' : $search_type = 'MTGKEY'; break;
			case '2' : $search_type = 'MTGLEVEL'; break;
			case '3' : $search_type = 'KDPER'; break;
			case '4' : $search_type = 'NMPER'; break;
			case '5' : $search_type = 'TYPE'; break;
		}

		$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
	}

	$total = $this->m_master->getRekeningMatangR($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
	$rows = $this->m_master->getRekeningMatangR($filter, [$per_page, $page])->result_array();
	while ($page > 1 AND count($rows) < 1):
	$page--;
	$rows = $this->m_master->getRekeningMatangR($filter, [$per_page, $page])->result_array();
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
	$type ='';
	foreach($rows as $r):
	$r = settrim($r);
	$type = $r['TYPE'];
	?>
	<tr id="tr-belanja-langsung-<?php echo $r['MTGKEY']; ?>">
			<td class ="w1px text-center <?php if($type=="H"){ echo "text-bold"; }  ?>"><?php echo $r['MTGKEY']; ?></td>
		<td class ="w1px <?php if($type=="H"){ echo "text-bold"; }  ?> text-center"><?php echo $r['MTGLEVEL']; ?></td>
		<td class ="<?php if($type=="H"){ echo "text-bold"; }  ?>"><?php echo $r['KDPER']; ?></td>
		<td class ="<?php if($type=="H"){ echo "text-bold"; }  ?>"><?php echo $r['NMPER']; ?></td>
		<td class ="<?php if($type=="H"){ echo "text-bold"; }  ?>"><?php echo $r['TYPE']; ?></td>
		<td class="text-center"><a href="javascript:void(0)" class="btn-rekening-belanja-langsung-tambah" data-act="edit"><u>Edit</u></a></td>
		<td class="text-center">
			<div class="checkbox checkbox-inline">
				<input type="checkbox" name="i-check[]" value="<?php echo $r['MTGKEY']; ?>">
				<label></label>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
	<script>
	$(function() {
		$(blockRekeningBelanjaLangsung + '.block-pagination').html($(blockRekeningBelanjaLangsung + '.pagetemp').html());
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


public function MasterRekeningBelanjaLangsung_form($act)
	{

		$this->load->library('form_validation');
		$is_admin = $this->sip->is_admin();
		$mtgkey = $this->input->post('i-mtgkey');


		if($act == 'add')
		{

			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$newmtgkey		= $this->m_set->getNextKey('matangr');

			$data = [
				'act'							=> $act,
				'mtgkey'					=> $newmtgkey,
				'mtglevel'				=> '',
				'kdper'						=> '',
				'nmper'						=> '',
				'type'						=> '',
				'kdtahun'					=> '',
				'disabled'				=> '',
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{


				$row = $this->db->query("
				SELECT
					*
					FROM MATANGR P

					WHERE
							 KDTAHUN= ?
							AND MTGKEY = ?",
				[

					$this->KDTAHUN,
					$mtgkey

				])->row_array();

				$r = settrim($row);

					$data = [
						'act'					=> $act,
						'mtgkey'				=> $mtgkey,
						'mtglevel'				=> $r['MTGLEVEL'],
						'kdper'					=> $r['KDPER'],
						'nmper'					=> $r['NMPER'],
						'type'					=> $r['TYPE'],
						'kdtahun'				=> $this->KDTAHUN,
						'disabled'				=> 'disabled',
						'curdShow'				=> $this->sip->curdShow('U')
				];
				//print_r($data);


		}

		$this->load->view('master/v_entry_rekening_belanja_langsung_form', $data);
	}

	public function MasterRekeningBelanjaLangsung_save($act)
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

			$this->form_validation->set_rules('i-mtglevel', 'Level', 'trim|required');
			$this->form_validation->set_rules('i-kdper', 'Kode Rekening', 'trim|required');
			$this->form_validation->set_rules('i-nmper', 'Uraian', 'trim|required');
			$this->form_validation->set_rules('i-type', 'Tipe', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$mtgkey			= $this->input->post('i-mtgkey');
			$kdper			= $this->input->post('i-kdper');
			$nmper		= $this->input->post('i-nmper');
			$mtglevel		= $this->input->post('i-mtglevel');
			$type		= $this->input->post('i-type');


			if($act == 'add')
			{

				$set = [
					'MTGKEY'					=> $mtgkey,
					'KDTAHUN'					=> $this->KDTAHUN,
					'KDPER'						=> $kdper,
					'NMPER'						=> $nmper,
					'MTGLEVEL'				=> $mtglevel,
					'TYPE'						=> $type
				];

				$affected = $this->m_master->addMatangR($set);
				if($affected !== 1)
				{
					throw new Exception('Data Rekening Belanja Langsung gagal ditambahkan.', 2);
				}

			$this->m_set->updateNextKey('MATANGR', $mtgkey);

			}

			elseif($act == 'edit')
			{

				$set = [

					'KDPER'						=> $kdper,
					'NMPER'						=> $nmper,
					'MTGLEVEL'				=> $mtglevel,
					'TYPE'						=> $type
				];

				$where = [
					'KDTAHUN'				=> $this->KDTAHUN,
						'MTGKEY'					=> $mtgkey,

				];

				$affected = $this->m_master->UpdateMatangR($where, $set);

				if($affected !== 1)
				{
					throw new Exception('Data Rekening Belanja Langsung gagal Dirubah.', 2);
				}

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

	public function MasterRekeningBelanjaLangsung_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{

			$mtgkey	= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM MATANGR
			WHERE
				KDTAHUN = ?
			AND MTGKEY IN ?",
			[
				$this->KDTAHUN,
				$mtgkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Data Rekening Belanja Langsung gagal dihapus.', 2);
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



//===============================================
//Program
	public function programaster_load($page = 1, $first = FALSE)
	{
		$per_page = 6;

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');
		$kd_unit = $this->input->post('v-kdunit');

		$newunitkey = $this->m_master->loadUnitkeyLevel2($unitkey);

		$filter = "
		AND MP.KDTAHUN = '{$this->KDTAHUN}'
		AND MP.UNITKEY = '{$newunitkey}' ";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'MP.PGRMRKPDKEY'; break;
				case '2' : $search_type = 'MP.NMPRGRM'; break;

			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$total = $this->m_master->getAll($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_master->getAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_master->getAll($filter, [$per_page, $page])->result_array();
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

		foreach($rows as $r):
		$r = settrim($r);
		?>
		<tr id="tr-program-<?php echo $r['PGRMRKPDKEY']; ?>">
			<td><a href="javascript:void(0)" class="btn-program-master-show-kegiatan"><u><?php echo $r['KDUNIT'] . $r['PGRMRKPDKEY']; ?></u></a></td>
		  <td><?php echo $r['NUPRGRM']; ?></td>
			<td><?php echo $r['NMPRGRM']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-program-tambah" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['PGRMRKPDKEY']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockEntryProgram + '.block-pagination').html($(blockEntryProgram + '.pagetemp').html());
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

	public function program_form($act)
	{
		$this->load->library('form_validation');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');

		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}


			$data = [
				'act'						=> $act,
				'unitkey'				=> $unitkey,
				'pgrmrkpdkey'		=> '',
				'kdtahun'				=> '',
				'pgrmrpjmkey'		=> '',
				'nuprgrm'				=> '',
				'nmprgrm'				=> '',
  			'type'					=> '',
				'disabled'			=> '',
				'curdShow'			=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

				$newunitkey = $this->m_master->loadUnitkeyLevel2($unitkey);

			$row = $this->db->query("
			SELECT
				*
			FROM
				MPGRMRKPD MP
			WHERE
				MP.KDTAHUN = ?
			AND MP.UNITKEY = ?
			AND MP.PGRMRKPDKEY = ?",
			[
				$this->KDTAHUN,
				$newunitkey,
				$pgrmrkpdkey
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'						=> $act,
				'unitkey'				=> $newunitkey,
				'kdtahun'				=> $this->KDTAHUN,
				'pgrmrkpdkey'		=> $pgrmrkpdkey,
				'pgrmrpjmkey'		=> '12507_',
				'nuprgrm'				=> $r['NUPRGRM'],
				'nmprgrm'				=> $r['NMPRGRM'],
				'type'					=> 'D',
				'disabled'			=> 'disabled',
				'curdShow'			=> $this->sip->curdShow('U')
			];
		}

		if($this->json['cod'] !== NULL)
		{
			echo $this->json['msg'];
		}
		else
		{
			$this->load->view('master/v_entry_program_form', $data);
		}
	}


	public function program_save($act)
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
			$this->form_validation->set_rules('i-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('i-nuprgrm', 'Nomor Program', 'trim|required');
			$this->form_validation->set_rules('i-nmprgrm', 'Nama Program', 'trim|required');


			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}
			$unitkey				= $this->sip->unitkey($this->input->post('i-unitkey'));
			$newunitkey			= $this->m_master->loadUnitkeyLevel2($unitkey);
			$nuprgrm				= $this->input->post('i-nuprgrm');
			$nmprgrm				= $this->input->post('i-nmprgrm');
			$pgrmrkpdkey	= $this->input->post('i-pgrmrkpdkey');

			if($act == 'add')
			{
					$pgrmrkpdkey		= $this->m_set->getNextKey('mpgrmrkpd');

				$set = [
					'KDTAHUN'					=> $this->KDTAHUN,
					'UNITKEY'					=> $newunitkey,
					'PGRMRKPDKEY'				=> $pgrmrkpdkey,
					'PGRMRPJMKEY'				=> '12507_',
					'NMPRGRM'					=> $nmprgrm,
					'NUPRGRM'					=> $nuprgrm,
					'Type'						=> 'D'

				];

				$affected = $this->m_master->add($set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}
					$this->m_set->updateNextKey('mpgrmrkpd', $pgrmrkpdkey);
			}
			elseif($act == 'edit')
			{
				$set = [
					'NMPRGRM'				=> $nmprgrm,
					'NUPRGRM'				=> $nuprgrm
				];

				$where = [
					'KDTAHUN'				=> $this->KDTAHUN,
					'UNITKEY'				=> $newunitkey,
					'PGRMRKPDKEY'			=> $pgrmrkpdkey
				];

				$affected = $this->m_master->update($where, $set);

				if($affected !== 1)
				{
					throw new Exception('Program gagal Dirubah.', 2);
				}
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

	public function program_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{

			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$pgrmrkpdkey	= $this->input->post('i-check[]');

			$newunitkey = $this->m_master->loadUnitkeyLevel2($unitkey);

			$this->db->query("
			DELETE FROM MPGRMRKPD
			WHERE
				KDTAHUN = ?
			AND UNITKEY = ?
			AND PGRMRKPDKEY IN ?",
			[
				$this->KDTAHUN,
				$newunitkey,
				$pgrmrkpdkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Program gagal dihapus.', 2);
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

//kegiatan
	public function kegiatanmaster_load($page = 1, $first = FALSE)
	{
		$per_page = 6;
			$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
			$search_type = $this->input->post('f-search_type');
			$search_key = $this->input->post('f-search_key');

			$filter = "
					AND MK.KDTAHUN = '{$this->KDTAHUN}'
				 	AND MK.PGRMRKPDKEY = '{$pgrmrkpdkey}'";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'MK.KEGRKPDKEY'; break;
				case '2' : $search_type = 'MK.NMKEG'; break;

			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$total = $this->m_master->getmasterKegiatanAll($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_master->getmasterKegiatanAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_master->getmasterKegiatanAll($filter, [$per_page, $page])->result_array();
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
		$load .= "
			<tr id='tr-kegiatan-{$r['KEGRKPDKEY']}'>
			<td>{$r['KEGRKPDKEY']}</td>
			<td>{$r['NUKEG']}</td>
			<td>{$r['NMKEG']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-entry-kegiatan-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['KEGRKPDKEY']}'>
					<label></label>
				</div>
			</td>
		</tr>";
		endforeach;
		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {

			$(blockEntryKegiatan + '.block-pagination').html($(blockEntryKegiatan + '.pagetemp').html());
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

	public function kegiatan_form($act)
	{
		$this->load->library('form_validation');
		$is_admin = $this->sip->is_admin();

		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');

		if($act == 'add')
		{
			$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'						=> $act,
				'pgrmrkpdkey'		=> $pgrmrkpdkey,
				'kegrkpdkey'		=> '',
				'kdtahun'			=> '',
				'kdperspektif'		=> '',
				'kegrpjmkey'		=> '',
				'nukeg'				=> '',
				'nmkeg'				=> '',
				'type'				=> '',
				'disabled'			=> '',
				'curdShow'			=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{

			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

				$row = $this->db->query("
							SELECT
								*
							FROM
								MKEGRKPD MK
							WHERE
								MK.KDTAHUN = ?
								AND MK.KEGRKPDKEY = ?",
							[
								$this->KDTAHUN,
								$kegrkpdkey
							])->row_array();


			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'pgrmrkpdkey'		=> $pgrmrkpdkey,
				'kegrkpdkey'		=> $kegrkpdkey,
				'kdtahun'				=> $this->KDTAHUN,
				'kdperspektif'	=> '1',
				'kegrpjmkey'		=> '10916_',
				'nukeg'					=> $r['NUKEG'],
				'nmkeg'					=> $r['NMKEG'],
				'type'					=> 'D',
				'disabled'		=> 'disabled',
				'curdShow'		=> $this->sip->curdShow('U')
			];
		}

		if($this->json['cod'] !== NULL)
		{

			echo $this->json['msg'];

		}
		else
		{

			$this->load->view('master/v_entry_kegiatan_form', $data);

		}
	}

	public function kegiatan_save($act)
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

			$this->form_validation->set_rules('i-nukeg', 'Nomor Kegiatan', 'trim|required');
			$this->form_validation->set_rules('i-nmkeg', 'Nama Kegiatan', 'trim|required');


			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$nukeg				= $this->input->post('i-nukeg');
			$nmkeg				= $this->input->post('i-nmkeg');
			$pgrmrkpdkey		= $this->input->post('i-pgrmrkpdkey');
			$kegrkpdkey 		= $this->input->post('i-kegrkpdkey');


			if($act == 'add')
			{
					$newkegrkpdkey		= $this->m_set->getNextKey('MKEGRKPD');

				$set = [
					'pgrmrkpdkey'			=> $pgrmrkpdkey,
					'kegrkpdkey'			=> $newkegrkpdkey,
					'kdtahun'				=> $this->KDTAHUN,
					'kdperspektif'			=> '01',
					'kegrpjmkey'			=> '10916_',
					'nukeg'					=> $nukeg,
					'nmkeg'					=> $nmkeg,
					'type'					=> 'D'


				];

				$affected = $this->m_master->addKegiatan($set);
				if($affected !== 1)
				{
					throw new Exception('Kegiatan gagal ditambahkan.', 2);
				}
					$this->m_set->updateNextKey('MKEGRKPD', $newkegrkpdkey);
			}
			elseif($act == 'edit')
			{
				$set = [
					'NUKEG'				=> $nukeg,
					'NMKEG'				=> $nmkeg
				];

				$where = [
					'KDTAHUN'				=> $this->KDTAHUN,
					'KEGRKPDKEY'			=> $kegrkpdkey,

				];

				$affected = $this->m_master->KegiatanUpdate($where, $set);

				if($affected !== 1)
				{
					throw new Exception('Kegiatan gagal Dirubah.', 2);
				}
					//$this->m_set->updateNextKey('MKEGRKPD', $kegrkpdkey);
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

	public function kegiatan_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{

			$kegrkpdkey	= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM MKEGRKPD
			WHERE
				KDTAHUN = ?
			AND KEGRKPDKEY IN ?",
			[
				$this->KDTAHUN,
				$kegrkpdkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Kegiatan gagal dihapus.', 2);
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

//PAGU
	public function pagumaster_load($page = 1, $first = FALSE)
	{
		$per_page = 15;

		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$filter = "
		AND KDTAHUN = '{$this->KDTAHUN}'
		AND KDTAHAP = '{$this->KDTAHAP}' ";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'KDUNIT'; break;
				case '2' : $search_type = 'NMUNIT'; break;

			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$total = $this->m_master->getPagu($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_master->getPagu($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_master->getPagu($filter, [$per_page, $page])->result_array();
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

		foreach($rows as $r):
		$r = settrim($r);
		?>
		<tr id="tr-pagu-<?php echo $r['UNITKEY']; ?>">
			<td><?php echo $r['KDUNIT']; ?></td>
		  <td><?php echo $r['NMUNIT']; ?></td>
			<td class="text-right">Rp. <?php echo number_format($r['PAGU'], 0, ',', '.') ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-pagu-tambah" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['UNITKEY']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockEntryPagu + '.block-pagination').html($(blockEntryPagu + '.pagetemp').html());
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

	public function pagu_form($act)
	{

		$this->load->library('form_validation');
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));

		if($act == 'add')
		{

			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}


			$data = [
				'act'					=> $act,
				'unitkey'				=> '',
				'kdtahun'				=> '',
				'kdtahap'				=> '',
				'nilai'					=> 0,
				'pagu'					=> '',
				'disabled'				=> '',
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{
				$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
				if($this->form_validation->run() == FALSE)
				{
					$this->json['cod'] = 2;
					$this->json['msg'] = custom_errors(validation_errors());
				}

				$row = $this->db->query("
				SELECT
					(SELECT KDUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS KDUNIT,
					(SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = U.UNITKEY) AS NMUNIT,
					k.UNITKEY
					KDTAHUN,
					KDTAHAP,
					NILAI ,
					PAGU
					FROM PAGUSKPD K
					JOIN DAFTUNIT U ON K.UNITKEY = U.UNITKEY
					WHERE
							 KDTAHAP= ?
							AND KDTAHUN = ?
							AND K.UNITKEY = ? ",
				[
					$this->KDTAHAP,
					$this->KDTAHUN,
					$unitkey

				])->row_array();

				$r = settrim($row);
				$data = [
					'act'						=> $act,
					'unitkey'				=> $unitkey,
					'kdtahun'				=> $this->KDTAHUN,
					'NMUNIT'				=> $r['NMUNIT'],
					'KDUNIT'				=>  $r['KDUNIT'],
					'kdtahap'				=> $this->KDTAHAP,
					'nilai'					=> number_format($r['NILAI'], 0, ',', '.'),
					'pagu'					=> $r['PAGU'],
					'disabled'			=> 'disabled',
					'curdShow'			=> $this->sip->curdShow('U')
				];


		}

		$this->load->view('master/v_entry_pagu_form', $data);
	}

	public function pagu_save($act, $id=null)
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

			$this->form_validation->set_rules('v-pagu', 'Nilai Pagu', 'trim|required');
			$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
			$pagu			= $this->input->post('v-pagu');
			$unit= $id;

			$pagu1= str_replace(".", "", $pagu);

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}


			if($act == 'add')
			{

				$set = [
					'UNITKEY'		=> $unit,
					'KDTAHAP'		=> $this->KDTAHAP,
					'KDTAHUN'		=> $this->KDTAHUN,
					'NILAI'			=> $pagu1,
					'PAGU'			=> NULL
				];

				$affected = $this->m_master->addPagu($set);
				if($affected !== 1)
				{
					throw new Exception('Pagu gagal ditambahkan.', 2);
				}
				$nmTable = 'PAGUSKPD';
				$nilai = 'Insert ' .json_encode($set);
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

			}
			elseif($act == 'edit')
			{
				$angka1= str_replace(".", "", $pagu);
				$set = [
						'NILAI'			=>$angka1
				];

				$where = [
					'KDTAHUN'				=> $this->KDTAHUN,
					'KDTAHAP'				=> $this->KDTAHAP,
					'UNITKEY'				=>$unitkey

				];

				$affected = $this->m_master->UpdatePagu($where, $set);

				if($affected !== 1)
				{
					throw new Exception('Pagu gagal Dirubah.', 2);
				}
				$nmTable = 'PAGUSKPD';
				$nilai = 'Edit ' .json_encode($set);
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

	public function pagu_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{

			$unitkey	= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM PAGUSKPD
			WHERE
				KDTAHUN = ?
			AND UNITKEY IN ?",
			[
				$this->KDTAHUN,
				$unitkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Kegiatan gagal dihapus.', 2);
			}
			$nmTable = 'PAGUSKPD';
			$nilai = 'Delete ' .json_encode($unitkey);
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

//pemda
public function pemdamaster_load($page = 1, $first = FALSE)
{
	$per_page = 15;

	$search_type = $this->input->post('f-search_type');
	$search_key = $this->input->post('f-search_key');

	$filter = "";

	if($search_key)
	{
		switch($search_type)
		{
			case '1' : $search_type = 'CONFIGVAL'; break;


		}

		$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
	}

	$total = $this->m_master->getPemda($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
	$rows = $this->m_master->getPemda($filter, [$per_page, $page])->result_array();
	while ($page > 1 AND count($rows) < 1):
	$page--;
	$rows = $this->m_master->getPemda($filter, [$per_page, $page])->result_array();
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

	foreach($rows as $r):
	$r = settrim($r);
	?>
	<tr id="tr-pagu-<?php echo $r['CONFIGID']; ?>">
		<td><?php echo $r['CONFIGDES']; ?></td>
		<td><?php echo $r['CONFIGVAL']; ?></td>
		<td class="text-center"><a href="javascript:void(0)" class="btn-pemda-tambah" data-act="edit"><u>Edit</u></a></td>
		<td class="text-center" style ="display:none">
			<div class="checkbox checkbox-inline">
				<input type="checkbox" name="i-check[]" value="<?php echo $r['CONFIGID']; ?>">
				<label></label>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
	<script>
	$(function() {
		$(blockPemda + '.block-pagination').html($(blockPemda + '.pagetemp').html());
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

public function pemda_form($act)
{

			$this->load->library('form_validation');
			$configid = $this->input->post('f-configid');

			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				*
				FROM PEMDA
				where
				CONFIGID= ?",
				[
				$configid
				])->row_array();

			$r = settrim($row);
			$data = [
				'act'						=> $act,
				'configid'				=> $configid,
				'configval'				=> $r['CONFIGVAL'],
				'configdes'				=> $r['CONFIGDES'],
				'disabled'			=> 'disabled',
				'curdShow'			=> $this->sip->curdShow('U')
			];

	$this->load->view('master/v_entry_pemda_form', $data);
}

public function pemda_save($act, $id=null)
{
	$this->load->library('form_validation');

	try
	{

		$this->form_validation->set_rules('v-configval', 'Nilai', 'trim|required');
		$this->form_validation->set_rules('v-configdes', 'Uraian', 'trim|required');
		$configval				= $this->input->post('v-configval');
		$configdes				= $this->input->post('v-configdes');
		$configid = $id;


		if($this->form_validation->run() == FALSE)
		{
			throw new Exception(custom_errors(validation_errors()), 2);
		}

			$set = [
					'CONFIGVAL'			=>$configval,
					'CONFIGDES'			=>$configdes
			];

			$where = [

				'CONFIGID'				=>$configid

			];

			$affected = $this->m_master->UpdatePemda($where, $set);

			if($affected !== 1)
			{
				throw new Exception('Data PEMDA gagal Dirubah.', 2);
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

//11-10-18

public function SSH()
	{
		$this->sip->is_menu('0005');
		$this->load->view('master/v_entry_ssh');
	}

public function sshmaster_load($page = 1, $first = FALSE)
{
	$per_page = 20;

	$search_type = $this->input->post('f-search_type');
	$search_key = $this->input->post('f-search_key');

	$filter = "AND KDTAHUN = '{$this->KDTAHUN}'";

	if($search_key)
	{
		switch($search_type)
		{
			case '1' : $search_type = 'KDSSH'; break;
			case '2' : $search_type = 'KDREK'; break;
			case '3' : $search_type = 'SSH_NAMA'; break;
			case '4' : $search_type = 'SSH_SATUAN'; break;
			case '5' : $search_type = 'SSH_HARGA'; break;
			case '6' : $search_type = 'SSH_SPEK'; break;


		}

		$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
	}

	$total = $this->m_master->getSSH1($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
	$rows = $this->m_master->getSSH1($filter, [$per_page, $page])->result_array();
	while ($page > 1 AND count($rows) < 1):
	$page--;
	$rows = $this->m_master->getSSH1($filter, [$per_page, $page])->result_array();
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

	foreach($rows as $r):
	$r = settrim($r);
	?>
	<tr id="tr-ssh-<?php echo $r['KDSSH']; ?>">
		<td><?php echo $r['KDREK']; ?></td>
		<td><?php echo $r['SSH_NAMA']; ?></td>
		<td><?php echo $r['SSH_SPEK']; ?></td>
		<td><?php echo $r['SSH_SATUAN']; ?></td>
		<td class ="text-right"><?php echo number_format($r['SSH_HARGA'], 2, ',', '.') ; ?></td>
		<td class="text-center"><a href="javascript:void(0)" class="btn-ssh-tambah" data-act="edit"><u>Edit</u></a></td>
		<td class="text-center">
			<div class="checkbox checkbox-inline">
				<input type="checkbox" name="i-check[]" value="<?php echo $r['KDSSH']; ?>">
				<label></label>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
	<script>
	$(function() {
		$(blockSSH + '.block-pagination').html($(blockSSH + '.pagetemp').html());
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

public function ssh_form($act)
	{

		$this->load->library('form_validation');
		$is_admin = $this->sip->is_admin();
		$kdssh = $this->input->post('i-kdssh');


		if($act == 'add')
		{

			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$newkdssh		= $this->m_set->getNextKeySSH('PHPSSH');

			$data = [
				'act'					=> $act,
				'kdtahun'				=> '',
				'kdssh'					=> $newkdssh,
				'kdrek'					=> '',
				'nmper'					=> '',
				'ssh_nama'				=> '',
				'ssh_satuan'			=> '',
				'ssh_harga'				=> '',
				'ssh_spek'				=> '',
				'ssh_aktif'				=> '',
				'disabled'				=> '',
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{


				$row = $this->db->query("
				SELECT
					*
					FROM PHPSSH P

					WHERE
							 KDTAHUN= ?
							AND KDSSH = ?",
				[

					$this->KDTAHUN,
					$kdssh

				])->row_array();

				$r = settrim($row);

				$kdrekening1 = $r['KDREK'];
				$cari = $this->db->query(" SELECT * FROM MATANGR WHERE KDPER = ? AND KDTAHUN = {$this->KDTAHUN}", [$kdrekening1])->row_array();
				$c = settrim($cari);

				$data = [
					'act'						=> $act,
					'kdtahun'					=> $this->KDTAHUN,
					'kdssh'						=> $kdssh,
					'kdrek'						=> $r['KDREK'],
					'nmper'						=> $c['NMPER'],
					'ssh_nama'					=> $r['SSH_NAMA'],
					'ssh_satuan'				=> $r['SSH_SATUAN'],
					'ssh_harga'					=> $r['SSH_HARGA'],
					'ssh_spek'					=> $r['SSH_SPEK'],
					'ssh_aktif'					=> $r['SSH_AKTIF'],
					'disabled'					=> 'disabled',
					'curdShow'					=> $this->sip->curdShow('U')
				];


		}

		$this->load->view('master/v_entry_ssh_form', $data);
	}

	public function ssh_save($act)
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

			$this->form_validation->set_rules('i-ssh_nama', 'Nama', 'trim|required');
			$this->form_validation->set_rules('i-ssh_satuan', 'Satuan', 'trim|required');
			$this->form_validation->set_rules('i-ssh_harga', 'Harga', 'trim|required');
			$this->form_validation->set_rules('i-ssh_spek', 'Spesifikasi', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$kdssh			= $this->input->post('i-kdssh');
			$kdrek			= $this->input->post('i-kdrek');
			$ssh_nama		= $this->input->post('i-ssh_nama');
			$ssh_satuan		= $this->input->post('i-ssh_satuan');
			$ssh_harga		= $this->input->post('i-ssh_harga');
			$ssh_spek		= $this->input->post('i-ssh_spek');

			if($act == 'add')
			{

				$set = [
					'KDSSH'					=> $kdssh,
					'KDTAHUN'				=> $this->KDTAHUN,
					'KDREK'					=> $kdrek,
					'SSH_NAMA'				=> $ssh_nama,
					'SSH_SATUAN'			=> $ssh_satuan,
					'SSH_HARGA'				=> $ssh_harga,
					'SSH_SPEK'				=> $ssh_spek,
					'SSH_AKTIF'				=> 1,
				];

				$affected = $this->m_master->addSSH($set);
				if($affected !== 1)
				{
					throw new Exception('SSH gagal ditambahkan.', 2);
				}

			$this->m_set->updateNextKey('PHPSSH', $kdssh);

			}

			elseif($act == 'edit')
			{

				$set = [
					'KDREK'					=> $kdrek,
					'SSH_NAMA'					=> $ssh_nama,
					'SSH_SATUAN'					=> $ssh_satuan,
					'SSH_HARGA'					=> $ssh_harga,
					'SSH_SPEK'					=> $ssh_spek,
					'SSH_AKTIF'					=> 1,
				];

				$where = [
					'KDTAHUN'				=> $this->KDTAHUN,

					'KDSSH'				=>$kdssh

				];

				$affected = $this->m_master->UpdateSSH($where, $set);

				if($affected !== 1)
				{
					throw new Exception('SSH gagal Dirubah.', 2);
				}

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

	public function ssh_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{

			$kdssh	= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM PHPSSH
			WHERE
				KDTAHUN = ?
			AND kdssh IN ?",
			[
				$this->KDTAHUN,
				$kdssh
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('SSH gagal dihapus.', 2);
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

	public function rekeningSSH()
	{
		$data['setid'] = $this->input->post('setid');
		$data['setkd'] = $this->input->post('setkd');
		$data['setnm'] = $this->input->post('setnm');

		$data['sshrekening'] = $this->rekeningSSH_load(1, TRUE);
		//$data['sshrekening'] = $this->rekeningSSH_load(TRUE);

		$this->load->view('master/v_rekening_form', $data);
	}

	public function rekeningSSH_load($page = 1, $first = FALSE)
	{

		$per_page = 15;

		$norek = $this->input->post('l-kdrek');
		$nmrek = $this->input->post('l-nmper');

		$filter = '';
		if($norek)
		{
			$filter .= "AND KDPER LIKE '%{$norek}%'";
		}
		if($nmrek)
		{
			$filter .= "AND NMPER LIKE '%{$nmrek}%'";
		}

		$total = $this->m_master->getRekeningSshAll($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_master->getRekeningSshAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_master->getRekeningSshAll($filter, [$per_page, $page])->result_array();
		endwhile;

		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);

		$load = '';
		foreach($rows as $u):
		$u = settrim($u);
		$load .= "
		<tr data-id='{$u['MTGKEY']}'>
			<td class='w1px'><button type='button' class='btn btn-success btn-xs btn-select'>Pilih</button></td>
			<td>{$u['KDPER']}</td>
			<td>{$u['NMPER']}</td>
		</tr>";
		endforeach;

		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockRekeningSSH + '.block-pagination').html($(blockRekeningSSH + '.pagetemp').html());
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



}
