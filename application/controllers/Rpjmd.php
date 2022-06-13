<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rpjmd extends CI_Controller {
	
	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	private $USERID = NULL;

	function __construct()
	{
		parent::__construct();

		$this->sip->is_logged();
		$this->sip->is_menu('0403');

        $this->load->model(['m_rpjmd','m_set']);

		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
		$this->USERID = $this->session->USERID;
	}

	public function index()
	{
        $data['visi'] = $this->m_rpjmd->getVisi();
		$this->load->view('rpjmd/v_rpjmd.php', $data);
	}
	
	public function jadwal_load($page = 1, $first = FALSE){
		$per_page = 12;	
		$filter = "";
		$total = $this->m_rpjmd->getJadwal($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_rpjmd->getJadwal($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_rpjmd->getJadwal([$per_page, $page])->result_array();
		endwhile;

		$this->load->library('pagination');
		$config = paginationBootstrap();
		$config['base_url'] = site_url('dashboard/');
		$config['per_page'] = $per_page;
		$config['total_rows'] = (int) $total;
		$this->pagination->initialize($config);
	
		if($first){ob_start();}

		$type ='';
		$i = 1;
		foreach($rows as $r):
		?>
			<tr id="tr-jadwal-<?php echo $r['ID']; ?>">
			<td class="text-center"><a href="javascript:void(0)" class="btn-show-visi" style="text-decoration: none; color: black;"><?php echo "{$r['SUBTAHAPAN']} RPJMD ({$r['PERIODE_AWAL']} - {$r['PERIODE_AKHIR']})"; ?></a></td>
			<td class="text-center">
				<?php 
					$jadaw = date("d-m-Y", strtotime($r['JADWAL_AWAL'] )); 
					$jadak = date("d-m-Y", strtotime($r['JADWAL_AKHIR'] )); 
					echo "<b>({$jadaw})</b>&nbsp; s/d &nbsp;<b>({$jadak})</b>"; 
				?>
			</td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-jadwal-form" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['ID']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockJadwal + '.block-pagination').html($(blockJadwal + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockJadwal + '.check-all');
			$(document).on('click', blockJadwal + '.check-all', function(e) {
				var checkboxes = $(blockJadwal + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
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

	public function jadwal_form($act){
		$this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$idjadwal 	= $this->input->post('i-idjadwal');
		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}
			$data = [
				'act'			=> $act,
				'idjadwal'		=>'',
				'periode_awal'	=>'',
				'periode_akhir'	=>'',
				'idtahapan'		=>'',
				'idsubtahapan'	=>'',
				'ket'			=>'',
				'jadwal_awal'	=>'',
				'jadwal_akhir'	=>'',
				'curdShow'		=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{
			$row 		= $this->db->query("SELECT	* FROM tbl_JADWAL WHERE ID = ?",[$idjadwal])->row_array();
			$r 			= settrim($row);
			$jad_aw 	= date("d-m-Y", strtotime($r['JADWAL_AWAL']));
			$jad_ak 	= date("d-m-Y", strtotime($r['JADWAL_AKHIR']));
            $data = [
				'act'			=> $act,
				'idjadwal'		=> $idjadwal,
				'periode_awal'	=> $r['PERIODE_AWAL'],
				'periode_akhir'	=> $r['PERIODE_AKHIR'],
				'idtahapan'		=> $r['ID_TAHAPAN'],
				'idsubtahapan'	=> $r['ID_SUBTAHAPAN'],
				'ket'			=> $r['KET'],
				'jadwal_awal'	=> $jad_aw,
				'jadwal_akhir'	=> $jad_ak,
				'curdShow'		=> $this->sip->curdShow('U')
            ];
		}

		$data['tahapan'] = $this->m_rpjmd->getTahapan()->result_array();
		$data['subtahap'] = $this->m_rpjmd->getSubTahap()->result_array();
		$this->load->view('rpjmd/v_rpjmd_jadwal_form', $data);
	}

	public function jadwal_save($act){
		if($act == 'add'){$this->sip->is_curd('I');}
		elseif($act == 'edit'){$this->sip->is_curd('U');}
		$this->load->library('form_validation');
		try
		{
			$this->form_validation->set_rules('i-periode_awal', 'Periode', 'trim|required');
			$this->form_validation->set_rules('i-periode_akhir', 'Periode', 'trim|required');
			$this->form_validation->set_rules('i-tahapan', 'Tahapan', 'trim|required');
			$this->form_validation->set_rules('i-subtahap', 'Sub Tahapan', 'trim|required');
			$this->form_validation->set_rules('i-jadwal_awal', 'Jadwal', 'trim|required');
			$this->form_validation->set_rules('i-jadwal_akhir', 'Jadwal', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}
			$idjadwal		= $this->input->post('i-idjadwal');
			$periode_awal	= $this->input->post('i-periode_awal');
			$periode_akhir	= $this->input->post('i-periode_akhir');
			$tahapan		= $this->input->post('i-tahapan');
			$subtahap		= $this->input->post('i-subtahap');
			$ket			= $this->input->post('i-ket');
			$jadwal_awal	= $this->input->post('i-jadwal_awal');
			$jadwal_akhir	= $this->input->post('i-jadwal_akhir');
			$jad_aw 		= date("Y-m-d", strtotime($jadwal_awal));
			$jad_ak 		= date("Y-m-d", strtotime($jadwal_akhir));

			if($act == 'add')
			{
				$newjadwalkey	= $this->m_set->getNextKey('JADWAL');
				$set = [
					'ID'			=> $newjadwalkey,
					'PERIODE_AWAL'	=> $periode_awal,
					'PERIODE_AKHIR'	=> $periode_akhir,
					'ID_TAHAPAN'	=> $tahapan,
					'ID_SUBTAHAPAN'	=> $subtahap,
					'KET'			=> $ket,
					'JADWAL_AWAL'	=> $jad_aw,
					'JADWAL_AKHIR'	=> $jad_ak,
				];

				$affected = $this->m_rpjmd->addJadwal($set);
				if($affected !== 1)
				{
					throw new Exception('Jadwal gagal ditambahkan.', 2);
				}
				$this->m_set->updateNextKey('JADWAL', $newjadwalkey);
			}elseif($act == 'edit'){
				$set = [
					'PERIODE_AWAL'	=> $periode_awal,
					'PERIODE_AKHIR'	=> $periode_akhir,
					'ID_TAHAPAN'	=> $tahapan,
					'ID_SUBTAHAPAN'	=> $subtahap,
					'KET'			=> $ket,
					'JADWAL_AWAL'	=> $jad_aw,
					'JADWAL_AKHIR'	=> $jad_ak,
				];

				$where = [
					'ID'			=> $idjadwal,
				];

				$affected = $this->m_rpjmd->updateJadwal($where, $set);
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

	public function jadwal_delete(){
		$this->sip->is_curd('D');
		$this->load->library('form_validation');
		try
		{
			$idjadwal = $this->input->post('i-check[]');
			$this->m_rpjmd->deleteJadwal($idjadwal);
			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Jadwal RPJMD gagal dihapus.', 2);
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

	public function visi_load($page = 1, $first = FALSE){
		$per_page = 12;	
		$idjadwal = $this->input->post('f-idjadwal');		
		$filter = "AND ID_JADWAL = '{$idjadwal}'";
		$total = $this->m_rpjmd->getVisi($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_rpjmd->getVisi($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_rpjmd->getVisi([$per_page, $page])->result_array();
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
		$i = 1;
		foreach($rows as $r):
		?>
			<tr id="tr-visi-<?php echo $r['IDVISI']; ?>">
			<td class="text-center"><a href="javascript:void(0)" class="btn-show-misi"><u><?php echo $r['IDVISI']; ?></u></a></td>
			<td><?php echo $r['NMVISI']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-visi-form" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['IDVISI']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockVisi + '.block-pagination').html($(blockVisi + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockVisi + '.check-all');
			$(document).on('click', blockVisi + '.check-all', function(e) {
				var checkboxes = $(blockVisi + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
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

    public function visi_form($act)
	{
		$this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$idjadwal 	= $this->input->post('f-idjadwal');
		$idvisi 	= $this->input->post('i-idvisi');
		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}
			$data = [
				'act'					=> $act,
				'idvisi'				=> '',
				'idjadwal'				=> $idjadwal,
				'novisi'				=> '',
				'nmvisi'				=> '',
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{
			$row = $this->db->query("SELECT	* FROM VISI WHERE IDVISI = ?",[$idvisi])->row_array();
			$r = settrim($row);
            $data = [
				'act'					=> $act,
				'idvisi'				=> $idvisi,
				'idjadwal'				=> $idjadwal,
				'novisi'				=> $r['NOVISI'],
				'nmvisi'				=> $r['NMVISI'],
				'curdShow'				=> $this->sip->curdShow('U')
            ];
		}
		$this->load->view('rpjmd/v_rpjmd_visi_form', $data);
	}

	public function visi_save($act)
	{
		if($act == 'add'){$this->sip->is_curd('I');}
		elseif($act == 'edit'){$this->sip->is_curd('U');}
		$this->load->library('form_validation');
		try
		{
			$this->form_validation->set_rules('i-novisi', 'No Visi', 'trim|required');
			$this->form_validation->set_rules('i-nmvisi', 'Visi', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$idvisi		= $this->input->post('i-idvisi');
			$novisi		= $this->input->post('i-novisi');
			$nmvisi		= $this->input->post('i-nmvisi');
			$idjadwal	= $this->input->post('i-idjadwal');
			
			if($act == 'add')
			{
				$newvisikey	= $this->m_set->getNextKey('VISI');
				$set = [
					'IDVISI'	=> $newvisikey,
					'ID_JADWAL'	=> $idjadwal,
					'NOVISI'	=> $novisi,
					'NMVISI'	=> $nmvisi,
				];

				$affected = $this->m_rpjmd->addVisi($set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}

				$this->m_set->updateNextKey('VISI', $newvisikey);
			}elseif($act == 'edit'){
				$set = [
					'NOVISI'	=> $novisi,
					'NMVISI'	=> $nmvisi,
				];

				$where = [
					'IDVISI'	=> $idvisi,
					'ID_JADWAL'	=> $idjadwal,
				];

				$affected = $this->m_rpjmd->updateVisi($where, $set);

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

	public function visi_delete()
	{
		$this->sip->is_curd('D');
		$this->load->library('form_validation');
		try
		{
			$idvisi	= $this->input->post('i-check[]');
			$this->db->query("DELETE FROM VISI WHERE IDVISI IN ?",[$idvisi]);
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

	public function misi_load($page = 1, $first = FALSE){
		$per_page = 12;	
		$idvisi = $this->input->post('f-idvisi');
		
		$filter = "AND IDVISI = '{$idvisi}'";
		$total = $this->m_rpjmd->getMisi($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_rpjmd->getMisi($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_rpjmd->getMisi([$per_page, $page])->result_array();
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
		$i = 1;
		foreach($rows as $r):
		?>
			<tr id="tr-misi-<?php echo $r['MISIKEY']; ?>">
			<td class="text-center"><a href="javascript:void(0)" class="btn-show-tujuan"><u><?php echo $r['NOMISI']; ?></u></a></td>
			<td><?php echo $r['URAIMISI']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-misi-form" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['MISIKEY']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockMisi + '.block-pagination').html($(blockMisi + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockMisi + '.check-all');
			$(document).on('click', blockMisi + '.check-all', function(e) {
				var checkboxes = $(blockMisi + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
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

	public function misi_form($act)
	{
		$this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$idvisi 	= $this->input->post('f-idvisi');
		$misikey 	= $this->input->post('i-misikey');
		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}
			var_dump($idvisi);
			$data = [
				'act'					=> $act,
				'misikey'				=>'',
				'idvisi'				=>$idvisi,
				'nomisi'				=>'',
				'uraimisi'				=>'',
				'sasaran'				=>'',
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{
			$row = $this->db->query("SELECT	* FROM MISI WHERE MISIKEY = ?",[$misikey])->row_array();
			$r = settrim($row);
            $data = [
				'act'					=> $act,
				'misikey'				=> $misikey,
				'idvisi'				=> $idvisi,
				'nomisi'				=> $r['NOMISI'],
				'uraimisi'				=> $r['URAIMISI'],
				'sasaran'				=> $r['SASARAN'],
				'curdShow'				=> $this->sip->curdShow('U')
            ];
		}
		$this->load->view('rpjmd/v_rpjmd_misi_form', $data);
	}

	public function misi_save($act)
	{
		if($act == 'add'){$this->sip->is_curd('I');}
		elseif($act == 'edit'){$this->sip->is_curd('U');}
		$this->load->library('form_validation');
		try
		{
			$this->form_validation->set_rules('i-nomisi', 'No Misi', 'trim|required');
			$this->form_validation->set_rules('i-uraimisi', 'Misi', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$misikey		= $this->input->post('i-misikey');
			$idvisi			= $this->input->post('i-idvisi');
			$nomisi			= $this->input->post('i-nomisi');
			$uraimisi		= $this->input->post('i-uraimisi');

			if($act == 'add')
			{
				$newmisikey	= $this->m_set->getNextKey('MISI');
				$set = [
					'MISIKEY'	=> $newmisikey,
					'IDVISI'	=> $idvisi,
					'NOMISI'	=> $nomisi,
					'URAIMISI'	=> $uraimisi,
					'SASARAN'	=> "-",
				];

				$affected = $this->m_rpjmd->addMisi($set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}	
				
				$this->m_set->updateNextKey('MISI', $newmisikey);

			}elseif($act == 'edit'){
				$set = [
					'NOMISI'	=> $nomisi,
					'URAIMISI'	=> $uraimisi,
					'SASARAN'	=> "-",
				];

				$where = [
					'IDVISI'	=> $idvisi,
					'MISIKEY'	=> $misikey,
				];

				$affected = $this->m_rpjmd->updateMisi($where, $set);

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

	public function misi_delete(){
		$this->sip->is_curd('D');
		$this->load->library('form_validation');
		try
		{
			$misikey = $this->input->post('i-check[]');
			$this->m_rpjmd->deleteMisi($misikey);
			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Misi gagal dihapus.', 2);
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

	public function tujuan_load($page = 1, $first = FALSE){
		$per_page = 12;	
		$misikey = $this->input->post('f-misikey');
		$filter = "AND MISIKEY = '{$misikey}'";
		$total = $this->m_rpjmd->getTujuan($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_rpjmd->getTujuan($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_rpjmd->getTujuan([$per_page, $page])->result_array();
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
		$i = 1;
		foreach($rows as $r):
		?>
			<tr id="tr-tujuan-<?php echo $r['TUJUKEY']; ?>">
			<td class="text-center"><a href="javascript:void(0)" class="btn-show-sasaran"><u><?php echo $r['NOTUJU']; ?></u></a></td>
			<td><?php echo $r['URAITUJU']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-tujuan-form" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['TUJUKEY']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockTujuan + '.block-pagination').html($(blockTujuan + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockTujuan + '.check-all');
			$(document).on('click', blockTujuan + '.check-all', function(e) {
				var checkboxes = $(blockTujuan + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
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

	public function tujuan_form($act)
	{
		$this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$misikey 	= $this->input->post('f-misikey');
		$tujukey 	= $this->input->post('i-tujukey');
		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}
			$data = [
				'act'					=> $act,
				'misikey'				=> $misikey,
				'tujukey'				=> '',
				'notuju'				=> '',
				'uraituju'				=> '',
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{
			$row = $this->db->query("SELECT	* FROM TUJUAN WHERE TUJUKEY = ?",[$tujukey])->row_array();
			$r = settrim($row);
            $data = [
				'act'					=> $act,
				'misikey'				=> $misikey,
				'tujukey'				=> $tujukey,
				'notuju'				=> $r['NOTUJU'],
				'uraituju'				=> $r['URAITUJU'],
				'curdShow'				=> $this->sip->curdShow('U')
            ];
		}
		$this->load->view('rpjmd/v_rpjmd_tujuan_form', $data);
	}

	public function tujuan_save($act)
	{
		if($act == 'add'){$this->sip->is_curd('I');}
		elseif($act == 'edit'){$this->sip->is_curd('U');}
		$this->load->library('form_validation');
		try
		{
			$this->form_validation->set_rules('i-notuju', 'No Tujuan', 'trim|required');
			$this->form_validation->set_rules('i-uraituju', 'Tujuan', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$misikey		= $this->input->post('i-misikey');
			$tujukey		= $this->input->post('i-tujukey');
			$notuju			= $this->input->post('i-notuju');
			$uraituju		= $this->input->post('i-uraituju');

			if($act == 'add')
			{
				$newtujukey	= $this->m_set->getNextKey('TUJUAN');
				$set = [
					'TUJUKEY'	=> $newtujukey,
					'MISIKEY'	=> $misikey,
					'NOTUJU'	=> $notuju,
					'URAITUJU'	=> $uraituju,
				];

				$affected = $this->m_rpjmd->addTujuan($set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}	
				$this->m_set->updateNextKey('TUJUAN', $newtujukey);

			}elseif($act == 'edit'){
				$set = [
					'NOTUJU'	=> $notuju,
					'URAITUJU'	=> $uraituju,
				];

				$where = [
					'TUJUKEY'	=> $tujukey,
					'MISIKEY'	=> $misikey,
				];

				$affected = $this->m_rpjmd->updateTujuan($where, $set);

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

	public function tujuan_delete(){
		$this->sip->is_curd('D');
		$this->load->library('form_validation');
		try
		{
			$tujukey = $this->input->post('i-check[]');
			$this->m_rpjmd->deleteTujuan($tujukey);
			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Tujuan gagal dihapus.', 2);
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

	public function sasaran_load($page = 1, $first = FALSE){
		$per_page = 12;	
		$tujukey = $this->input->post('f-tujukey');
		$filter = "AND TUJUKEY = '{$tujukey}'";
		$total = $this->m_rpjmd->getSasaran($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_rpjmd->getSasaran($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_rpjmd->getSasaran([$per_page, $page])->result_array();
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
		$i = 1;
		foreach($rows as $r):
		?>
			<tr id="tr-sasaran-<?php echo $r['ID']; ?>">
			<td class="text-center"><a href="javascript:void(0)" class="btn-show-program"><u><?php echo $r['NOSASARAN']; ?></u></a></td>
			<td><?php echo $r['SASARAN']; ?></td>
			<td><?php echo $r['INDIKATOR']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-sasaran-form" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['ID']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockSasaran + '.block-pagination').html($(blockSasaran + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockSasaran + '.check-all');
			$(document).on('click', blockSasaran + '.check-all', function(e) {
				var checkboxes = $(blockSasaran + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
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

	public function sasaran_form($act)
	{
		$this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$idjadwal 	= $this->input->post('f-idjadwal');
		$idvisi 	= $this->input->post('f-idvisi');
		$misikey 	= $this->input->post('f-misikey');
		$tujukey 	= $this->input->post('f-tujukey');
		$idsasaran 	= $this->input->post('i-idsasaran');

		$row = $this->db->query("
			SELECT J.PERIODE_AWAL, J.PERIODE_AKHIR, (J.PERIODE_AKHIR - J.PERIODE_AWAL) AS TOTAL_ROW FROM tbl_JADWAL J
			LEFT JOIN VISI V ON V.ID_JADWAL = J.ID
			LEFT JOIN MISI M ON M.IDVISI = V.IDVISI
			LEFT JOIN TUJUAN T ON T.MISIKEY = M.MISIKEY
			WHERE J.ID = '{$idjadwal}'
			AND V.IDVISI = '{$idvisi}'
			AND M.MISIKEY = '{$misikey}'
			AND T.TUJUKEY = '{$tujukey}'"
		)->row_array();
		$r = settrim($row);

		for($i=$r['PERIODE_AWAL']; $i<=$r['PERIODE_AKHIR']; $i++){
			$rowsasaran[] = $this->db->query("
				SELECT * FROM tbl_SASARAN S 
				LEFT JOIN tbl_SUBSASARAN SS ON SS.ID_SASARAN = S.ID
				WHERE S.ID = ? AND SS.TAHUN = ?",[$idsasaran,$i])->row_array();
			$rs = settrim($rowsasaran);
		}

		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}
			var_dump($r);
			$data = [
				'act'		=> $act,
				'idsasaran'	=> '',
				'idjadwal'	=> $idjadwal,
				'idvisi'	=> $idvisi,
				'misikey'	=> $misikey,
				'tujukey'	=> $tujukey,
				'nosasaran'	=> '',
				'sasaran'	=> '',
				'indikator'	=> '',
				'length'	=> $r['TOTAL_ROW'],
				'target'	=> $rs,
				'tahun'		=> $r['PERIODE_AWAL'],
				'curdShow'	=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{
            $data = [
				'act'		=> $act,
				'idsasaran'	=> $idsasaran,
				'idjadwal'	=> $idjadwal,
				'idvisi'	=> $idvisi,
				'misikey'	=> $misikey,
				'tujukey'	=> $tujukey,
				'nosasaran'	=> $rs[1]['NOSASARAN'],
				'sasaran'	=> $rs[1]['SASARAN'],
				'indikator'	=> $rs[1]['INDIKATOR'],
				'length'	=> $r['TOTAL_ROW'],
				'target'	=> $rs,
				'tahun'		=> $r['PERIODE_AWAL'],
				'curdShow'	=> $this->sip->curdShow('U')
            ];
		}
		$this->load->view('rpjmd/v_rpjmd_sasaran_form', $data);
	}

	public function sasaran_save($act)
	{
		if($act == 'add'){$this->sip->is_curd('I');}
		elseif($act == 'edit'){$this->sip->is_curd('U');}
		$this->load->library('form_validation');
		try
		{
			$idjadwal 	= $this->input->post('i-idjadwal');
			$idvisi 	= $this->input->post('i-idvisi');
			$misikey 	= $this->input->post('i-misikey');
			$tujukey	= $this->input->post('f-tujukey');
			$idsasaran	= $this->input->post('i-idsasaran');
			$nosasaran	= $this->input->post('i-nosasaran');
			$sasaran	= $this->input->post('i-sasaran');
			$indikator	= $this->input->post('i-indikator');

			$row = $this->db->query("
				SELECT J.PERIODE_AWAL, J.PERIODE_AKHIR, (J.PERIODE_AKHIR - J.PERIODE_AWAL) AS TOTAL_ROW FROM tbl_JADWAL J
				LEFT JOIN VISI V ON V.ID_JADWAL = J.ID
				LEFT JOIN MISI M ON M.IDVISI = V.IDVISI
				LEFT JOIN TUJUAN T ON T.MISIKEY = M.MISIKEY
				WHERE J.ID = '{$idjadwal}'
				AND V.IDVISI = '{$idvisi}'
				AND M.MISIKEY = '{$misikey}'
				AND T.TUJUKEY = '{$tujukey}'"
			)->row_array();
			$r = settrim($row); 
			
			$tahun = $r['PERIODE_AWAL'];
			if($act == 'add')
			{
				$newsasarankey	= $this->m_set->getNextKey('SASARAN');
				$set = [
					'ID'		=> $newsasarankey,
					'TUJUKEY'	=> $tujukey,
					'NOSASARAN'	=> $nosasaran,
					'SASARAN'	=> $sasaran,
					'INDIKATOR'	=> $indikator
				];  

				for($i=0; $i<=$r['TOTAL_ROW']; $i++){
						$subset = [
						'ID_SASARAN'=> $newsasarankey,
						'TAHUN'		=> (($r['PERIODE_AWAL'])+$i),
						'TARGET'	=> $this->input->post("i-target{$i}"),
						'SATUAN'	=> $this->input->post("i-satuan{$i}"),
						];
					$affected = $this->m_rpjmd->addSubSasaran($subset);
				}

				$affected = $this->m_rpjmd->addSasaran($set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}
				$this->m_set->updateNextKey('SASARAN', $newsasarankey);
			}elseif($act == 'edit'){
				$set = [
					'NOSASARAN'	=> $nosasaran,
					'SASARAN'	=> $sasaran,
					'INDIKATOR'	=> $indikator
				];

				$where = [
					'ID'		=> $idsasaran,
					'TUJUKEY'	=> $tujukey,
				];

				$affected = $this->m_rpjmd->updateSasaran($where, $set);

				for($i=0; $i<=$r['TOTAL_ROW']; $i++){
						$subset = [
							'TARGET'	=> $this->input->post("i-target{$i}"),
							'SATUAN'	=> $this->input->post("i-satuan{$i}"),
						];

						$where = [
							'ID_SASARAN'=> $idsasaran,
							'TAHUN'		=> (($r['PERIODE_AWAL'])+$i),	
						];
					$this->m_rpjmd->updateSubsasaran($where, $subset);
				}

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

	public function sasaran_delete(){
		$this->sip->is_curd('D');
		$this->load->library('form_validation');
		try
		{
			$idsasaran = $this->input->post('i-check[]');
			$this->m_rpjmd->deleteSasaran($idsasaran);
			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Tujuan gagal dihapus.', 2);
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

	public function program_load($page = 1, $first = FALSE){
		$per_page = 12;	
		$idsasaran = $this->input->post('f-idsasaran');
		$filter = "AND ID_SASARAN = '{$idsasaran}'";
		$total = $this->m_rpjmd->getProgram($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_rpjmd->getProgram($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_rpjmd->getProgram([$per_page, $page])->result_array();
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
		$i = 1;
		foreach($rows as $r):
		?>
			<tr id="tr-program-<?php echo $r['ID']; ?>">
			<td class="text-left "><a href="javascript:void(0)" class=""><u><?php echo $r['NMPRGRM']; ?></u></a></td>
			<td><?php echo $r['NMUNIT']; ?></td>
			<td><?php echo $r['INDIKATOR']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-sasaran-form" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['ID']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockProgram + '.block-pagination').html($(blockProgram + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockProgram + '.check-all');
			$(document).on('click', blockProgram + '.check-all', function(e) {
				var checkboxes = $(blockProgram + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
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
		$is_admin 	= $this->sip->is_admin();
		$idjadwal 	= $this->input->post('f-idjadwal');
		$idvisi 	= $this->input->post('f-idvisi');
		$misikey 	= $this->input->post('f-misikey');
		$tujukey 	= $this->input->post('f-tujukey');
		$idsasaran 	= $this->input->post('f-idsasaran');
		$unitkey 	= $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');

		$row = $this->db->query("
			SELECT J.PERIODE_AWAL, J.PERIODE_AKHIR, (J.PERIODE_AKHIR - J.PERIODE_AWAL) AS TOTAL_ROW FROM tbl_JADWAL J
			LEFT JOIN VISI V ON V.ID_JADWAL = J.ID
			LEFT JOIN MISI M ON M.IDVISI = V.IDVISI
			LEFT JOIN TUJUAN T ON T.MISIKEY = M.MISIKEY
			LEFT JOIN tbl_SASARAN S ON S.TUJUKEY = T.TUJUKEY
			WHERE J.ID = '{$idjadwal}'
			AND V.IDVISI = '{$idvisi}'
			AND M.MISIKEY = '{$misikey}'
			AND T.TUJUKEY = '{$tujukey}'
			AND S.ID = '{$idsasaran}'"
		)->row_array();
		$r = settrim($row);

		// for($i=$r['PERIODE_AWAL']; $i<=$r['PERIODE_AKHIR']; $i++){
		// 	$rowsasaran[] = $this->db->query("
		// 		SELECT * FROM tbl_SASARAN S 
		// 		LEFT JOIN tbl_SUBSASARAN SS ON SS.ID_SASARAN = S.ID
		// 		WHERE S.ID = ? AND SS.TAHUN = ?",[$idsasaran,$i])->row_array();
		// 	$rs = settrim($rowsasaran);
		// }

		if($act == 'add')
		{
			$unitkey = $this->sip->unitkey($this->input->post('l-unitkey'));
			var_dump($unitkey);
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}
			$data = [
				'act'		=> $act,
				'idjadwal'	=> $idjadwal,
				'idvisi'	=> $idvisi,
				'misikey'	=> $misikey,
				'tujukey'	=> $tujukey,
				'idsasaran'	=> $idsasaran,
				'unitkey'	=> $unitkey,
				'pgrmrkpdkey'	=> '',
				'kdprgrm'	=> '',
				'nmprgrm'	=> '',
				'indikator'	=> '',
				'length'	=> $r['TOTAL_ROW'],
				'tahun'		=> $r['PERIODE_AWAL'],
				'curdShow'	=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{
            $data = [
				'act'					=> $act,
				'idsasaran'				=> $idsasaran,
				'idjadwal'				=> $idjadwal,
				'idvisi'				=> $idvisi,
				'misikey'				=> $misikey,
				'tujukey'				=> $tujukey,
				'nosasaran'				=> $rs[1]['NOSASARAN'],
				'sasaran'				=> $rs[1]['SASARAN'],
				'indikator'				=> $rs[1]['INDIKATOR'],
				'length'				=> $r['TOTAL_ROW'],
				'target'				=> $rs,
				'tahun'					=> $r['PERIODE_AWAL'],
				'curdShow'				=> $this->sip->curdShow('U')
            ];
		}
		$this->load->view('rpjmd/v_rpjmd_program_form', $data);
	}

	public function program_save($act)
	{
		if($act == 'add'){$this->sip->is_curd('I');}
		elseif($act == 'edit'){$this->sip->is_curd('U');}
		$this->load->library('form_validation');
		try
		{
			$idjadwal 		= $this->input->post('i-idjadwal');
			$idvisi 		= $this->input->post('i-idvisi');
			$misikey 		= $this->input->post('i-misikey');
			$tujukey		= $this->input->post('f-tujukey');
			$idsasaran		= $this->input->post('f-idsasaran');
			$idprogram		= $this->input->post('i-idprogram');
			$program		= $this->input->post('i-program');
			$indikator		= $this->input->post('i-indikator');
			$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
			$pgrmrkpdkey	= $this->input->post('i-pgrmrkpdkey');

			$row = $this->db->query("
				SELECT J.PERIODE_AWAL, J.PERIODE_AKHIR, (J.PERIODE_AKHIR - J.PERIODE_AWAL) AS TOTAL_ROW FROM tbl_JADWAL J
				LEFT JOIN VISI V ON V.ID_JADWAL = J.ID
				LEFT JOIN MISI M ON M.IDVISI = V.IDVISI
				LEFT JOIN TUJUAN T ON T.MISIKEY = M.MISIKEY
				LEFT JOIN tbl_SASARAN S ON S.TUJUKEY = T.TUJUKEY
				WHERE J.ID = '{$idjadwal}'
				AND V.IDVISI = '{$idvisi}'
				AND M.MISIKEY = '{$misikey}'
				AND T.TUJUKEY = '{$tujukey}'
				AND S.ID = '{$idsasaran}'"
			)->row_array();
			$r = settrim($row); 
			
			$tahun = $r['PERIODE_AWAL'];
			if($act == 'add')
			{
				$newprogramkey	= $this->m_set->getNextKey('MPGRMRPJM');
				$set = [
					'ID'			=> $newprogramkey,
					'ID_SASARAN'	=> '1',
					'UNITKEY'		=> $unitkey,
					'PGRMRKPDKEY'	=> $pgrmrkpdkey,
					'INDIKATOR'		=> $indikator
				];  

				// for($i=0; $i<=$r['TOTAL_ROW']; $i++){
				// 		$subset = [
				// 		'ID_SASARAN'=> $newsasarankey,
				// 		'TAHUN'		=> (($r['PERIODE_AWAL'])+$i),
				// 		'TARGET'	=> $this->input->post("i-target{$i}"),
				// 		'SATUAN'	=> $this->input->post("i-satuan{$i}"),
				// 		];
				// 	$affected = $this->m_rpjmd->addSubSasaran($subset);
				// }

				$affected = $this->m_rpjmd->addProgram($set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}
				$this->m_set->updateNextKey('SASARAN', $newsasarankey);
			}elseif($act == 'edit'){
				$set = [
					'NOSASARAN'	=> $nosasaran,
					'SASARAN'	=> $sasaran,
					'INDIKATOR'	=> $indikator
				];

				$where = [
					'ID'		=> $idsasaran,
					'TUJUKEY'	=> $tujukey,
				];

				$affected = $this->m_rpjmd->updateSasaran($where, $set);

				for($i=0; $i<=$r['TOTAL_ROW']; $i++){
						$subset = [
							'TARGET'	=> $this->input->post("i-target{$i}"),
							'SATUAN'	=> $this->input->post("i-satuan{$i}"),
						];

						$where = [
							'ID_SASARAN'=> $idsasaran,
							'TAHUN'		=> (($r['PERIODE_AWAL'])+$i),	
						];
					$this->m_rpjmd->updateSubsasaran($where, $subset);
				}

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
}
?>