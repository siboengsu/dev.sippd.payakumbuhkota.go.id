<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    
class Opd extends CI_Controller {
    
	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];
	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	function __construct()
	{
		parent::__construct();
		$this->sip->is_logged();
		$this->load->model(['m_set', 'm_opd']);
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
	}

	// opd
	public function index()
	{
		$this->load->view('opd/v_opd');
	}

	public function opd_load($page = 1, $first = FALSE){
		$per_page = 12;
	
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');
		$filter = "";
		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'NIP'; break;
				case '2' : $search_type = 'NAMA'; break;
				case '3' : $search_type = 'NMUNIT'; break;
			}
			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}
	
		$total = $this->m_opd->getopd($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_opd->getopd($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_opd->getopd($filter, [$per_page, $page])->result_array();
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
		$type 		= '';
		$i 			= 1;
		$unitkey	= null;
		$nmnit 		= null;
		foreach($rows as $r):
		$r	 		= settrim($r);
		$id			= $r['id'];
		$unitkey	= $r['UNITKEY'];
		$nmnit 		= $r['NMUNIT'];
		$value		= $r['VALUE'];
		?>
		<tr id="tr-opd-<?php echo $r['id']; ?>">
			<td><?php echo $r['NMUNIT']; ?></td>
			<td><?php echo $r['NAMA']; ?></td>
			<td><?php echo $r['NIP']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-opd-tambah" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['id'];?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockopd + '.block-pagination').html($(blockopd + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockopd + '.check-all');
			$(document).on('click', blockopd + '.check-all', function(e) {
				var checkboxes = $(blockopd + "input[name='i-check[]']:checkbox");
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

	public function opd_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey = $this->input->post('i-check[]');
			$periode = $this->input->post('i-periode');
			
			$this->db->query("
			DELETE FROM ATASBEND
			WHERE
			id IN ?",
			[
				$unitkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Data Kepala Daerah gagal dihapus.', 2);
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


	public function opd_form($act)
    {
        $this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$id 		= $this->input->post('i-id');
		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'					=> $act,
				'id'				    =>'',
				'unitkey'				=>'',
				'nip'					=>'',
				'opd'					=>'',
				'nama'					=>'', 
				'periode1'				=>'', 
				'periode2'				=>'', 
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{
			$row = $this->db->query("SELECT D.NMUNIT, A.UNITKEY, A.NIP, P.NAMA, A.KDJABATAN, A.PERIODE1, A.PERIODE2, J.JABATAN FROM ATASBEND AS A 
									LEFT JOIN DAFTUNIT AS D ON A.UNITKEY = D.UNITKEY 
									LEFT JOIN JABATAN AS J ON A.KDJABATAN = J.KDJABATAN
									LEFT JOIN PEGAWAI AS P ON A.NIP = P.NIP WHERE A.id = ?", [$id])->row_array();
			$r = settrim($row);
				$data = [
					'act'					=> $act,
					'id'					=> $id,
					'opd'					=> $r['NMUNIT'],
					'unitkey'				=> $r['UNITKEY'],
					'nip'					=> $r['NIP'],
					'nama'					=> $r['NAMA'],
					'kdjabatan'				=> $r['KDJABATAN'],
					'periode1'				=> $r['PERIODE1'],
					'periode2'				=> $r['PERIODE2'],
					'curdShow'				=> $this->sip->curdShow('U')
			];
		}
		$data['jabatan'] = $this->m_opd->getjabatan();
		$this->load->view('opd/v_opd_form', $data);
	}

	public function opd_save($act)
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
			$this->form_validation->set_rules('f-nip', 'Username', 'trim|required');

			$unitkey			= $this->input->post('i-unitkey');
			$funitkey			= $this->input->post('f-unitkey');
			$nip				= $this->input->post('f-nip');
			$jabatan			= $this->input->post('i-jabatan');
			$opd				= $this->input->post('v-nmunit');
			$periode1			= $this->input->post('f-tglawal');
			$periode2			= $this->input->post('f-tglakhir'); 
			if ($funitkey == '83_')
			{
				$opd = "PAYAKUMBUH BARAT";
			}elseif ($funitkey == '84_')
			{
				$opd = "PAYAKUMBUH TIMUR";
			}elseif ($funitkey == '85_')
			{
				$opd = "PAYAKUMBUH UTARA";
			}elseif ($funitkey == '86_')
			{
				$opd = "PAYAKUMBUH SELATAN";
			}elseif ($funitkey == '87_')
			{
				$opd = "LAMPOSI TIGO NAGARI";
			}
			
			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			if($act == 'add')
			{
				$row = $this->db->query("SELECT COUNT(A.UNITKEY) AS TOTAL_ROW FROM ATASBEND AS A WHERE A.UNITKEY = ?", [$funitkey])->row_array();
				$r = settrim($row);
				$value = $r['TOTAL_ROW'];
				$value = $value + 1;

				$this->db->affected_rows();
				$funitkey		= $this->input->post('f-unitkey');
				$set = [
					'UNITKEY'	=>$funitkey,
					'NIP'		=>$nip,
					'PERIODE1'	=>$periode1,
					'PERIODE2'	=>$periode2,
					'VALUE'		=>$value,
				];
				$affected = $this->m_opd->addopd($set);

				$valuee = $value - 1;
				$tgl	= $this->input->post('f-tglawal');
				function manipulasiTanggal($tgl,$jumlah=1,$format='days'){
					$currentDate = $tgl;
					return date('Y-m-d', strtotime($jumlah.' '.$format, strtotime($currentDate)));
				}

				$tgl1 	= manipulasiTanggal($tgl,'-1','days');

				$this->db->query("
				UPDATE ATASBEND SET PERIODE2 = '{$tgl1}' WHERE UNITKEY = '{$funitkey}' AND VALUE = '{$valuee}'");
				$this->db->affected_rows();

			}

			elseif($act == 'edit')
			{
				$id				= $this->input->post('i-id');
				$set = [
					'UNITKEY'	=>$funitkey,
					'NIP'		=>$nip,
					'PERIODE1'	=>$periode1,
					'PERIODE2'	=>$periode2,
					'KDJABATAN'	=>$jabatan,
				];

				$where = [
					'id'	=> $id,
				];

				$affected = $this->m_opd->updateopd($where, $set);
				$this->db->query("
				UPDATE PEGAWAI SET UNITKEY = A.UNITKEY FROM ATASBEND A
								LEFT JOIN PEGAWAI P ON P.NIP = A.NIP
								WHERE P.NIP = '{$nip}'");
				$this->db->affected_rows();
				$this->db->query("
				UPDATE PEGAWAI SET JABATAN = '{$jabatan} {$opd}'
						WHERE NIP = '{$nip}'");
				$this->db->affected_rows();

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


	// pegawai
	public function pegawaiindex()
	{
		$data['setid'] = $this->input->post('setid');
		$data['setnm'] = $this->input->post('setnm');
		
		$this->load->view('opd/v_pegawai', $data);
	}

	public function pegawai_load($page = 1, $first = FALSE){
		$per_page = 5;
	
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');
		$filter = "";
		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'NIP'; break;
				case '2' : $search_type = 'NAMA'; break;
				case '3' : $search_type = 'NMUNIT'; break;
			}
			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}
	
		$total = $this->m_opd->getpegawai($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_opd->getpegawai($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_opd->getpegawai($filter, [$per_page, $page])->result_array();
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
		$type 		= '';
		$i 			= 1;
		$nip 		= null;
		$nmnit 		= null;
		foreach($rows as $r):
		$r	 		= settrim($r);
		$nip 		= $r['NIP'];
		$nmnit 		= $r['NMUNIT'];
		?>
			<tr id="tr-pegawai-<?php echo $r['NIP']; ?>">
			<td class='text-center'><a href='javascript:void(0)' class='btn-select'>Select</a></td>
			<td><?php echo $r['NIP']; ?></td>
			<td><?php echo $r['NAMA']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-pegawai-tambah" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['NIP']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockpegawai + '.block-pagination').html($(blockpegawai + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockpegawai + '.check-all');
			$(document).on('click', blockpegawai + '.check-all', function(e) {
				var checkboxes = $(blockpegawai + "input[name='i-check[]']:checkbox");
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

	public function pegawai_form($act)
    {
        $this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$nip 	= $this->input->post('i-nip');
		if($act == 'add')
		{
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'					=> $act,
				'unitkey'				=>NULL,
				'opd'					=>'',
				'nip'					=>'',
				'kdgol'					=>'',
				'nama'					=>NULL,
				'alamat'				=>NULL,
				'jabatan'				=>NULL,
				'pddk'					=>NULL,
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{
			$row = $this->db->query("SELECT * FROM PEGAWAI WHERE NIP = ?", [$nip])->row_array();
			var_dump($nip);
			$r = settrim($row);
				$data = [
					'act'					=> $act,
					'unitkey'				=>$r['UNITKEY'],
					'nip'					=>$nip,
					'kdgol'					=>$r['KDGOL'],
					'nama'					=>$r['NAMA'],
					'alamat'				=>$r['ALAMAT'],
					'jabatan'				=>$r['JABATAN'],
					'pddk'					=>$r['PDDK'],
					'curdShow'				=> $this->sip->curdShow('U')
			];
		}
		$data['golongan'] = $this->m_opd->getgolongan();
		$this->load->view('opd/v_pegawai_form', $data);
	}

	public function pegawai_save($act)
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
			$this->form_validation->set_rules('i-nip', 'Username', 'trim|required');

			$unitkey			= $this->input->post('i-unitkey');
			$funitkey			= $this->input->post('f-unitkey');
			$nip				= $this->input->post('i-nip');
			$nama 				= $this->input->post('i-nama');
			$kdgol 				= $this->input->post('i-kdgol');
			$alamat				= $this->input->post('i-alamat');
			$jabatan			= $this->input->post('i-jabatan');
			$pddk				= $this->input->post('i-pddk');
			
			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			if($act == 'add')
			{
				$funitkey		= $this->input->post('f-unitkey');
				$set = [
					'NIP'		=>$nip,
					'KDGOL'		=>$kdgol, 
					'UNITKEY'	=>NULL,
					'NAMA'		=>$nama,
					'ALAMAT'	=>$alamat,
					'JABATAN'	=>$jabatan,
					'PDDK'		=>$pddk,
				];

				$affected = $this->m_opd->addpegawai($set);
			}

			elseif($act == 'edit')
			{
				$nip		= $this->input->post('i-nip');
				$set = [
					'UNITKEY'	=>$funitkey,
					'NIP'		=>$nip,
					'KDGOL'		=>$kdgol, 
					'NAMA'		=>$nama,
					'ALAMAT'	=>$alamat,
					'JABATAN'	=>$jabatan,
					'PDDK'		=>$pddk,
				];

				$where = [
					'NIP'	=> $nip,
				];

				$affected = $this->m_opd->updatepegawai($where, $set);

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

	public function pegawai_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$nip = $this->input->post('i-check[]');
			$this->db->query("
			DELETE FROM PEGAWAI
			WHERE
			NIP IN ?",
			[
				$nip
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Data Kepala Daerah gagal dihapus.', 2);
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
