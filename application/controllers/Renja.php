<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Renja extends CI_Controller {

	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;
	private $USERID = NULL;

	function __construct()
	{
		parent::__construct();

		$this->sip->is_logged();
		$this->sip->is_menu('040101');

		$this->load->model(['m_set', 'm_program', 'm_kegiatan', 'm_hspk', 'm_master','m_subkegiatan']);
 
		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;
		$this->USERID = $this->session->USERID;
	}

	function COBA(){
		$dataa['WEBUSER'] = $this->m_program->getuser0()->result();
		$this->load->view('renja/v_renja', $dataa);
	}

	public function index()
	{
		$data['program'] = $this->program_load(1, TRUE);
		$this->load->view('renja/v_renja', $data);
	}

	public function program_load($page = 1, $first = FALSE)
	{
		$per_page = 6;

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$pagu = $this->m_set->getPaguOpdSummary($unitkey);

		$filter = "
		AND P.KDTAHUN = '{$this->KDTAHUN}'
		AND P.KDTAHAP = '{$this->KDTAHAP}'
		AND P.UNITKEY = '{$unitkey}' ";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'D.KDUNIT + MP.NUPRGRM'; break;
				case '2' : $search_type = 'MP.NMPRGRM'; break;
				case '3' : $search_type = 'S.NMSAS'; break;
				case '4' : $search_type = 'S.NMSAS'; break;
			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$total = $this->m_program->getAll($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_program->getAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_program->getAll($filter, [$per_page, $page])->result_array();
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
			<td><a href="javascript:void(0)" class="btn-program-show-kegiatan"><u><?php echo $r['KDUNIT'] . $r['NUPRGRM']; ?></u></a></td>
			<td><?php echo $r['NMPRGRM']; ?></td>
			<td><?php echo $r['INDIKATOR']; ?></td>
			<td><?php echo $r['SASARAN']; ?></td>
			<td><?php echo $r['NMPRIOPPAS']; ?></td>
			<td><?php echo $r['NMSAS']; ?></td>
			<td><?php echo $r['TOLOKUR']; ?></td>
			<td><?php echo $r['TARGETSEBELUM']; ?></td>
			<td><?php echo $r['TARGET']; ?></td>
			<td class="text-center"><?php echo $r['TGLVALID']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-program-form" data-act="edit"><u>Edit</u></a></td>
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
			updatePagu(<?php echo "{$pagu['PAGU']},{$pagu['PAGUUSED']},{$pagu['SELISIH']}";?>);

			$(blockProgram + '.block-pagination').html($(blockProgram + '.pagetemp').html());
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
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'pgrmrkpdkey'	=> '',
				'kdprgrm'		=> '',
				'nmprgrm'		=> '',
				'indikator'		=> '',
				'sasaran'		=> '',
				'prioppaskey'	=> '',
				'noprioppas'	=> '',
				'nmprioppas'	=> '',
				'idsas'			=> '',
				'nosas'			=> '',
				'nmsas'			=> '',
				'tglvalid'		=> '',
				'tolokur'		=>'',
				'targetsebelum'	=>'',
				'target'		=>'',
				'disabled'		=> '',
				'curdShow'		=> $this->sip->curdShow('I')
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

			$row = $this->db->query("
			SELECT
				ISNULL(D.KDUNIT, '0.00.') + MP.NUPRGRM AS KDPRGRM,
				MP.NMPRGRM,
				P.INDIKATOR,
				P.SASARAN,
				O.PRIOPPASKEY,
				O.NOPRIOPPAS,
				O.NMPRIOPPAS,
				S.IDSAS,
				S.NOSAS,
				S.NMSAS,
				P.TOLOKUR,
				P.TARGET,
				P.TARGETSEBELUM,
				CASE WHEN P.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),P.TGLVALID,105) END AS TGLVALID
			FROM
				PGRRKPD P
				LEFT JOIN MPGRMRKPD MP ON P.PGRMRKPDKEY = MP.PGRMRKPDKEY
					AND P.KDTAHUN = MP.KDTAHUN
				LEFT JOIN DAFTUNIT D ON MP.UNITKEY = D.UNITKEY
				LEFT JOIN PRIOPPAS O ON P.PRIOPPASKEY = O.PRIOPPASKEY
					AND P.KDTAHUN = O.KDTAHUN
				LEFT JOIN SASARAN S ON P.IDSAS = S.IDSAS
			WHERE
				P.KDTAHUN = ?
			AND P.KDTAHAP = ?
			AND P.UNITKEY = ?
			AND P.PGRMRKPDKEY = ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$pgrmrkpdkey
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'pgrmrkpdkey'	=> $pgrmrkpdkey,
				'kdprgrm'		=> $r['KDPRGRM'],
				'nmprgrm'		=> $r['NMPRGRM'],
				'indikator'		=> $r['INDIKATOR'],
				'sasaran'		=> $r['SASARAN'],
				'prioppaskey'	=> $r['PRIOPPASKEY'],
				'noprioppas'	=> $r['NOPRIOPPAS'],
				'nmprioppas'	=> $r['NMPRIOPPAS'],
				'idsas'			=> $r['IDSAS'],
				'nosas'			=> $r['NOSAS'],
				'nmsas'			=> $r['NMSAS'],
				'tglvalid'		=> $r['TGLVALID'],
				'tolokur'		=>$r['TOLOKUR'],
				'target'		=>$r['TARGET'],
				'targetsebelum'	=>$r['TARGETSEBELUM'],
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
			$this->load->view('renja/v_renja_program_form', $data);
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
			$this->form_validation->set_rules('i-pgrmrkpdkey', 'Program', 'trim|required');
			$this->form_validation->set_rules('i-indikator', 'Capaian Program', 'trim|required');
			$this->form_validation->set_rules('i-sasaran', 'Target', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$pgrmrkpdkey	= $this->input->post('i-pgrmrkpdkey');
			$prioppaskey	= $this->input->post('i-prioppaskey');
			$idsas			= $this->input->post('i-idsas');
			$sasaran		= $this->input->post('i-sasaran');
			$indikator		= $this->input->post('i-indikator');
			$tglvalid		= $this->input->post('i-tglvalid');
			$tolokur		= $this->input->post('i-tolokur');
			$target			= $this->input->post('i-target');
			$targetsebelum			= $this->input->post('i-targetsebelum');

			if($act == 'add')
			{
				$set = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'PGRMRKPDKEY'	=> $pgrmrkpdkey,
					'UNITUSUL'		=> $unitkey,
					'SASARAN'		=> $sasaran,
					'INDIKATOR'		=> $indikator,
					'IDSAS'			=> $idsas,
					'PRIOPPASKEY'	=> $prioppaskey,
					'TOLOKUR' 		=> $tolokur,
					'TARGET'		=> $target,
					'TARGETSEBELUM'	=> $targetsebelum
				];

				$affected = $this->m_program->add($set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}

				$nilai = 'Insert ' .json_encode($set);
				$nmTable = 'PGRRKPD';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

			}
			elseif($act == 'edit')
			{
				if($this->sip->is_admin()):
					if(empty($tglvalid)):
						$tglvalid = 'NULL';
					else:
						if($this->KDTAHAP == '4'):
							// Tahap Induk
							if(intval(substr($tglvalid, 6)) != (intval($this->KDTAHUN) - 1 + 2000)):
								throw new Exception('Tahun induk tidak sesuai', 2);
							endif;
						else:
							// Tahap Berjalan
							if(intval(substr($tglvalid,6)) != (intval($this->KDTAHUN) + 2000)):
								throw new Exception('Tahun berjalan tidak sesuai', 2);
							endif;
						endif;

						$tglvalid = "CONVERT(DATETIME,'{$tglvalid}',105)";
					endif;

					$this->db->set('TGLVALID', $tglvalid, FALSE);
				else:
					$tglvalid_now = $this->db->query("
						SELECT
							CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID
						FROM PGRRKPD
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND PGRMRKPDKEY = ?
					",
					[
						$this->KDTAHUN,
						$this->KDTAHAP,
						$unitkey,
						$pgrmrkpdkey
					])->row_array();

					if($tglvalid_now AND $tglvalid_now['TGLVALID'] != ''):
						throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
					endif;
				endif;

				$set = [
					'SASARAN'		=> $sasaran,
					'INDIKATOR'		=> $indikator,
					'IDSAS'			=> $idsas,
					'PRIOPPASKEY'	=> $prioppaskey,
					'TOLOKUR' 		=> $tolokur,
					'TARGET'		=> $target,
					'TARGETSEBELUM'	=> $targetsebelum
				];

				$where = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'PGRMRKPDKEY'	=> $pgrmrkpdkey
				];
				$affected = $this->m_program->update($where, $set);
				$this->db->query("
						UPDATE KEGRKPD SET TGLVALID = P.TGLVALID FROM PGRRKPD P
						LEFT JOIN KEGRKPD K ON K.UNITKEY = P.UNITKEY
						AND K.PGRMRKPDKEY = P.PGRMRKPDKEY
						AND K.KDTAHUN = P.KDTAHUN
						AND K.KDTAHAP = P.KDTAHAP
						where K.KDTAHUN = '{$this->KDTAHUN}'
						and K.KDTAHAP = '{$this->KDTAHAP}'
						and K.unitkey = '{$unitkey}'
						and P.pgrmrkpdkey = RTRIM('{$pgrmrkpdkey}')");
				$this->db->affected_rows();

				$affected = $this->m_program->update($where, $set);
				$this->db->query("
						UPDATE SUBKEGRKPD SET TGLVALID = P.TGLVALID FROM PGRRKPD P
						LEFT JOIN KEGRKPD K ON K.UNITKEY = P.UNITKEY
						LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = P.UNITKEY
						AND K.PGRMRKPDKEY = P.PGRMRKPDKEY
						AND SK.KEGRKPDKEY = K.KEGRKPDKEY
						AND K.KDTAHUN = P.KDTAHUN
						AND K.KDTAHAP = P.KDTAHAP
						where P.KDTAHUN = '{$this->KDTAHUN}'
						and P.KDTAHAP = '{$this->KDTAHAP}'
						and P.unitkey = '{$unitkey}'
						and K.KDTAHUN = '{$this->KDTAHUN}'
						and K.KDTAHAP = '{$this->KDTAHAP}'
						and K.unitkey = '{$unitkey}'
						and SK.KDTAHUN = '{$this->KDTAHUN}'
						and SK.KDTAHAP = '{$this->KDTAHAP}'
						and SK.unitkey = '{$unitkey}'
						and P.pgrmrkpdkey = RTRIM('$pgrmrkpdkey')");
				$this->db->affected_rows();

				$affected = $this->m_program->update($where, $set);
				if($affected !== 1)
				{
					throw new Exception('Program gagal ditambahkan.', 2);
				}

				$nilai = 'Edit ' .json_encode($set);
				$nmTable = 'PGRRKPD';
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

	public function program_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$pgrmrkpdkey	= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM PGRRKPD
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND PGRMRKPDKEY IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$pgrmrkpdkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Program gagal dihapus.', 2);
			}

			$data = json_encode($pgrmrkpdkey);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'PGRRKPD';
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

	public function kegiatan_load($page = 1, $first = FALSE)
	{
		$per_page = 6;

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$filter = "
		AND K.UNITKEY = '{$unitkey}'
		AND K.PGRMRKPDKEY = '{$pgrmrkpdkey}'
		AND K.KDTAHUN = '{$this->KDTAHUN}'
		AND K.KDTAHAP = '{$this->KDTAHAP}'
		";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'MK.NUKEG'; break;
				case '2' : $search_type = 'MK.NMKEG'; break;
				case '3' : $search_type = 'K.PAGUTIF'; break;
				case '4' : $search_type = 'K.KUANTITATIF'; break;
				case '5' : $search_type = 'K.TARGETSEBELUM'; break;
				//case '5' : $search_type = 'K.SATUAN'; break;
				case '6' : $search_type = 'K.LOKASI'; break;
				case '7' : $search_type = 'K.LOKASI'; break;
				case '8' : $search_type = 'K.LOKASI'; break;
			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$total = $this->m_kegiatan->getAll($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_kegiatan->getAll($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_kegiatan->getAll($filter, [$per_page, $page])->result_array();
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
			$gender = $r['IS_RES_GENDER'];
			if ($gender == '1') {
				$data = "<i class='fa fa-check'></i>";
			}else {
				$data = '';
			}
			$load .= "
			<tr id='tr-kegiatan-{$r['KEGRKPDKEY']}'>
				<td><a href='javascript:void(0)' class='btn-kegiatan-show-rincian'><u>{$r['NUKEG']}</u></a></td>
				<td>{$r['NMKEG']}</td>
				<td class='text-right nu2d'>{$r['PAGUTIF']}</td>
				<td class='text-right'>{$r['TARGETSEBELUM']}</td>
				<td class='text-right'>{$r['KUANTITATIF']}</td>
				<td>{$r['LOKASI']}</td>
				<td class='text-center'> $data</td>
				<td class='text-center'>{$r['TGLVALID']}</td>
				<td class='text-center'><a href='javascript:void(0)' class='btn-kegiatan-form' data-act='edit'><u>Edit</u></a></td>
				<td class='text-center'>
					<div class='checkbox checkbox-inline'>
						<input type='checkbox' name='i-check[]' value='{$r['KEGRKPDKEY']}'>
						<label></label>
					</div>
				</td>
			</tr>";
			endforeach;

			$pagu = $this->m_set->getPaguOpdSummary($unitkey);

			$load .= "
			<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
			<script>
			$(function() {
				updatePagu({$pagu['PAGU']},{$pagu['PAGUUSED']},{$pagu['SELISIH']});

				$(blockKegiatan + '.block-pagination').html($(blockKegiatan + '.pagetemp').html());
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

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');


		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'pgrmrkpdkey'	=> $pgrmrkpdkey,
				'kegrkpdkey'	=> '',
				'nukeg'			=> '',
				'nmkeg'			=> '',
				'kdsifat'		=> '',
				'pagutif'		=> '0',
				'paguplus'		=> '0',
				'kuantitatif'	=> '',
				'targetsebelum'	=> '',
				'pagutifdpa'		=> '0',
				//'satuan'		=> '',
				'lokasi'		=> '',
				'is_res_gender'	 =>'',
				'ket'			=> '',
				'tglvalid'		=> '',
				'disabled'		=> '',
				'curdShow'		=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');
			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				MK.NUKEG,
				MK.NMKEG,
				S.KDSIFAT,
				K.PAGUTIF,
				K.PAGUPLUS,
				K.PAGUTIFDPA,
				K.KUANTITATIF,
				K.TARGETSEBELUM,
				IS_RES_GENDER,
				K.LOKASI,
				K.KET,
				CASE WHEN K.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),K.TGLVALID,105) END AS TGLVALID
			FROM
				KEGRKPD K
			JOIN MKEGRKPD MK ON
					K.KEGRKPDKEY = MK.KEGRKPDKEY
				AND K.PGRMRKPDKEY = MK.PGRMRKPDKEY
				AND K.KDTAHUN = MK.KDTAHUN
			LEFT JOIN SIFATKEG S ON K.KDSIFAT = S.KDSIFAT
			WHERE
				K.KDTAHUN = ?
			AND K.KDTAHAP = ?
			AND K.UNITKEY = ?
			AND K.PGRMRKPDKEY = ?
			AND K.KEGRKPDKEY = ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$pgrmrkpdkey,
				$kegrkpdkey,
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'pgrmrkpdkey'	=> $pgrmrkpdkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'nukeg'			=> $r['NUKEG'],
				'nmkeg'			=> $r['NMKEG'],
				'kdsifat'		=> $r['KDSIFAT'],
				'pagutif'		=> $r['PAGUTIF'],
				'paguplus'		=> $r['PAGUPLUS'],
				'kuantitatif'	=> $r['KUANTITATIF'],
				'targetsebelum'	=> $r['TARGETSEBELUM'],
				'pagutifdpa'	=> $r['PAGUTIFDPA'],
				//'satuan'		=> $r['SATUAN'],
				'lokasi'		=> $r['LOKASI'],
				'ket'			=> $r['KET'],
				'tglvalid'		=> $r['TGLVALID'],
				'disabled'		=> 'disabled',
				'is_res_gender'	 =>  $r['IS_RES_GENDER'],
				'curdShow'		=> $this->sip->curdShow('U')
			];
		}

		$data['list_sifat'] = $this->db->query("SELECT KDSIFAT, NMSIFAT FROM SIFATKEG")->result_array();

		if($this->json['cod'] !== NULL)
		{

			echo $this->json['msg'];

		}
		else
		{
			$this->load->view('renja/v_renja_kegiatan_form', $data);

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
			$this->form_validation->set_rules('i-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('i-pgrmrkpdkey', 'Program', 'trim|required');
			$this->form_validation->set_rules('i-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('i-kdsifat', 'Sifat Kegiatan', 'trim|required');
		//	$this->form_validation->set_rules('i-pagutif', 'Pagu', 'trim|required|numeric|greater_than_equal_to[0]');
		//	$this->form_validation->set_rules('i-paguplus', 'Pagu (n+1)', 'trim|required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('i-kuantitatif', 'Target', 'trim|required');
			$this->form_validation->set_rules('i-lokasi', 'Lokasi', 'trim|required');
			$this->form_validation->set_rules('i-ket', 'Keterangan', 'trim|required');


			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$pgrmrkpdkey	= $this->input->post('i-pgrmrkpdkey');
			$kegrkpdkey		= $this->input->post('i-kegrkpdkey');
			$kdsifat		= $this->input->post('i-kdsifat');
			$pagutif		= $this->input->post('i-pagutif');
			$pagutifdpa		= $this->input->post('i-pagutifdpa');
			$paguplus		= $this->input->post('i-paguplus');
			$kuantitatif	= $this->input->post('i-kuantitatif');
			$targetsebelum	= $this->input->post('i-targetsebelum');
			//$satuan			= $this->input->post('i-satuan');
			$lokasi			= $this->input->post('i-lokasi');
			$ket			= $this->input->post('i-ket');
			$tglvalid		= $this->input->post('i-tglvalid');
			$is_res_gender = $this->input->post('i-isres-gender');

			if($act == 'add')
				{
					 $check_pagu = $this->db->query("
						 SELECT
							 NILAI AS PAGU_LIMIT,
							 (
								 SELECT
									  sum(pagutif)
								 FROM
									 KEGRKPD
								 WHERE
									 KDTAHUN = K.KDTAHUN
								 AND KDTAHAP = K.KDTAHAP
								 AND UNITKEY = K.UNITKEY
							  )AS TOTAL_PENJABARAN
						 FROM
							 PAGUSKPD K
						 WHERE
							 KDTAHUN = ?
						 AND KDTAHAP = ?
						 AND UNITKEY = ?
						 ",
						 [$this->KDTAHUN, $this->KDTAHAP, $unitkey])->row_array();

				 if($check_pagu['TOTAL_PENJABARAN'] +$pagutif <= $check_pagu['PAGU_LIMIT'])
					 {
						 $set = [
								'KDTAHUN'		=> $this->KDTAHUN,
								'KDTAHAP'		=> $this->KDTAHAP,
								'UNITKEY'		=> $unitkey,
								'PGRMRKPDKEY'	=> $pgrmrkpdkey,
								'KEGRKPDKEY'	=> $kegrkpdkey,
								'UNITUSUL'		=> $unitkey,
								'KDSIFAT'		=> $kdsifat,
								'PAGUPLUS'		=> $paguplus,
								'PAGUTIF'		=> $pagutif,
								'PAGUTIFDPA'	=> $pagutifdpa,
								'KUANTITATIF'	=> $kuantitatif,
								'TARGETSEBELUM'	=> $targetsebelum,
								//'SATUAN'		=> $satuan,
								'LOKASI'		=> $lokasi,
								'IS_RES_GENDER'	 =>$is_res_gender,
								'KET'			=> $ket
							];
							
							$affected = $this->m_kegiatan->add($set);
							if($affected !== 1)
								{
									throw new Exception('Kegiatan gagal ditambahkan.', 2);
								}
								$nilai = 'Insert ' .json_encode($set);
								$nmTable = 'KEGRKPD';
								$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
					 }else {

							 throw new Exception(
								 "PAGU OPDD = <strong>".money($check_pagu['PAGU_LIMIT'])."</strong><br>".
								 "PAGU KEGIATAN = <strong>".money($check_pagu['TOTAL_PENJABARAN'])."</strong><br>".
								 "Nilai Selisih = <strong>".money($check_pagu['PAGU_LIMIT'] - $check_pagu['TOTAL_PENJABARAN'])."</strong>"
							 , 2);
						 }
				}


			elseif($act == 'edit')
			{
				if($this->sip->is_admin()):
					if(empty($tglvalid)):
						$tglvalid = 'NULL';
					else:
						if($this->KDTAHAP == '4'):
							// Tahap Induk
							if(intval(substr($tglvalid, 6)) != (intval($this->KDTAHUN) - 1 + 2000)):
								throw new Exception('Tahun induk tidak sesuai', 2);
							endif;
						else:
							// Tahap Berjalan
							if(intval(substr($tglvalid,6)) != (intval($this->KDTAHUN) + 2000)):
								throw new Exception('Tahun berjalan tidak sesuai', 2);
							endif;
						endif;

						$tglvalid = "CONVERT(DATETIME,'{$tglvalid}',105)";
					endif;

					$this->db->set('TGLVALID', $tglvalid, FALSE);
				else:
					$tglvalid_now = $this->db->query("
						SELECT
							CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID
						FROM KEGRKPD
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND PGRMRKPDKEY = ?
						AND KEGRKPDKEY = ?
					",
					[
						$this->KDTAHUN,
						$this->KDTAHAP,
						$unitkey,
						$pgrmrkpdkey,
						$kegrkpdkey
					])->row_array();

					if($tglvalid_now AND $tglvalid_now['TGLVALID'] != ''):
						throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
					endif;
				endif;

				$check_pagu_edit =  $this->db->query("
						SELECT
							MK.NUKEG,
							MK.NMKEG,
							S.KDSIFAT,
							K.PAGUTIF as PAGU_LAMA,
							K.PAGUPLUS,
							K.KUANTITATIF,
							K.TARGETSEBELUM,
							K.PAGUTIFDPA,
							K.LOKASI,
							K.KET,
							CASE WHEN K.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),K.TGLVALID,105) END AS TGLVALID
						FROM
							KEGRKPD K
						JOIN MKEGRKPD MK ON
								K.KEGRKPDKEY = MK.KEGRKPDKEY
							AND K.PGRMRKPDKEY = MK.PGRMRKPDKEY
							AND K.KDTAHUN = MK.KDTAHUN
						LEFT JOIN SIFATKEG S ON K.KDSIFAT = S.KDSIFAT
						WHERE
							K.KDTAHUN = ?
						AND K.KDTAHAP = ?
						AND K.UNITKEY = ?
						AND K.PGRMRKPDKEY = ?
						AND K.KEGRKPDKEY = ?",
						[
							$this->KDTAHUN,
							$this->KDTAHAP,
							$unitkey,
							$pgrmrkpdkey,
							$kegrkpdkey,
						])->row_array();


				 $check_pagu = $this->db->query("
						 SELECT
							 NILAI AS PAGU_LIMIT,
							 (
								 SELECT
									  sum(pagutif)
								 FROM
									 KEGRKPD
								 WHERE
									 KDTAHUN = K.KDTAHUN
								 AND KDTAHAP = K.KDTAHAP
								 AND UNITKEY = K.UNITKEY
							  )AS TOTAL_PENJABARAN
						 FROM
							 PAGUSKPD K
						 WHERE
							 KDTAHUN = ?
						 AND KDTAHAP = ?
						 AND UNITKEY = ?
						 ",
						 [$this->KDTAHUN, $this->KDTAHAP, $unitkey])->row_array();

				if(($check_pagu['TOTAL_PENJABARAN'] - $check_pagu_edit ['PAGU_LAMA'] )+$pagutif <= $check_pagu['PAGU_LIMIT'])
				 {

					$check =$this->m_set->adjustmentkinkegrka($unitkey,$kegrkpdkey);
				 foreach ($check as $check){

								 $praraskr =  substr($check->PRARASKR,0,-3);
						 }

							$newpraraskr	= preg_replace('/[^A-Za-z0-9]/', '', $praraskr);

						 if ($pagutif >= $newpraraskr ){

							$set = [
								'KDSIFAT' =>$kdsifat,
								'PAGUTIF'		=> $pagutif,
								'PAGUPLUS'		=> $paguplus,
								'PAGUTIFDPA'	=> $pagutifdpa,
								'KUANTITATIF'	=> $kuantitatif,
								'TARGETSEBELUM'	=> $targetsebelum,
								//'SATUAN'		=> $satuan,
								'LOKASI'		=> $lokasi,
								'KET'			=> $ket,
								'IS_RES_GENDER'	 =>$is_res_gender
							];

							$where = [
								'KDTAHUN'		=> $this->KDTAHUN,
								'KDTAHAP'		=> $this->KDTAHAP,
								'UNITKEY'		=> $unitkey,
								'PGRMRKPDKEY'	=> $pgrmrkpdkey,
								'KEGRKPDKEY'	=> $kegrkpdkey
							];

							$affected = $this->m_kegiatan->update($where, $set);
							
							$this->db->query("
							UPDATE KINKEGRKPD SET TOLOKUR = K.KET FROM KEGRKPD K
								LEFT JOIN KINKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
								AND SK.KEGRKPDKEY = K.KEGRKPDKEY
								AND SK.KDTAHUN = K.KDTAHUN
								AND SK.KDTAHAP = K.KDTAHAP
								where SK.KDTAHUN = '{$this->KDTAHUN}'
								and SK.KDTAHAP = '{$this->KDTAHAP}'
								and SK.unitkey = '{$unitkey}'
								AND SK.KDJKK = '02'
								and K.kegrkpdkey = RTRIM('{$kegrkpdkey}')");
							$this->db->affected_rows();
							
							$this->db->query("
							UPDATE KINKEGRKPD SET TARGET = K.KUANTITATIF FROM KEGRKPD K
								LEFT JOIN KINKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
								AND SK.KEGRKPDKEY = K.KEGRKPDKEY
								AND SK.KDTAHUN = K.KDTAHUN
								AND SK.KDTAHAP = K.KDTAHAP
								where SK.KDTAHUN = '{$this->KDTAHUN}'
								and SK.KDTAHAP = '{$this->KDTAHAP}'
								and SK.unitkey = '{$unitkey}'
								AND SK.KDJKK = '02'
								and K.kegrkpdkey = RTRIM('{$kegrkpdkey}')");
							$this->db->affected_rows();
							
							if($affected !== 1)
							{
								throw new Exception('Kegiatan gagal ditambahkan.', 2);
							}

							$nilai = 'Edit ' .json_encode($set);
							 $nmTable = 'KEGRKPD';
							 $simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
						}
				 else {
					 throw new Exception(
						 "Total Nilai RKA  = <strong>" .money($newpraraskr). "</strong><br>".
						 "PAGU KEGIATAN = <strong>" .money($pagutif). "</strong><br>"

					 , 2);
				 }
			 }else {

				 throw new Exception(
					 "PAGU OPDD = <strong>".money($check_pagu['PAGU_LIMIT'])."</strong><br>".
					 "PAGU KEGIATAN = <strong>".money($check_pagu['TOTAL_PENJABARAN'])."</strong><br>".
					 "Nilai Selisih = <strong>".money($check_pagu['PAGU_LIMIT'] - $check_pagu['TOTAL_PENJABARAN'])."</strong>"
				 , 2);
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

	public function kegiatan_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$pgrmrkpdkey	= $this->input->post('i-pgrmrkpdkey');
			$kegrkpdkey		= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM KEGRKPD
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND PGRMRKPDKEY = ?
			AND KEGRKPDKEY IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$pgrmrkpdkey,
				$kegrkpdkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Kegiatan gagal dihapus.', 2);
			}

			$data = json_encode($pgrmrkpdkey);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'KEGRKPD';
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

	public function rincian_opd()
	{
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');

		$rows = $this->db->query("
		SELECT
			TIPE,
			ID_KEGHSPK,
			KDHSPK3,
			NMPEKERJAAN,
			JNPEKERJAAN,
			LOKASI,
			KETERANGAN,
			VOLUME,
			SATUAN,
			HARGA,
			TOTAL
		FROM
			PHPKEGHSPK
		WHERE
			TIPE = 1
		AND KDTAHUN = ?
		AND KDTAHAP = ?
		AND UNITKEY = ?
		AND KEGRKPDKEY = ?",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array();

		$sum_total = 0;
		foreach($rows as $r):
		$r = settrim($r);
		echo "
		<tr>
			<td>{$r['LOKASI']}</td>
			<td>{$r['KETERANGAN']}</td>
			<td class='text-center w1px'><button type='button' class='btn btn-xs btn-info btn-hspk' data-id='{$r['KDHSPK3']}'><i class='fa fa-balance-scale'></i> {$r['KDHSPK3']}</button></td>
			<td>{$r['JNPEKERJAAN']}</td>
			<td class='text-center'>{$r['SATUAN']}</td>
			<td class='text-right nu2d'>{$r['VOLUME']}</td>
			<td class='text-right nu2d'>{$r['HARGA']}</td>
			<td class='text-right nu2d'>{$r['TOTAL']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-hspk-form' data-act='edit' data-tipe='{$r['TIPE']}'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['ID_KEGHSPK']}'>
					<label></label>
				</div>
			</td>
		</tr>";

		$sum_total += $r['TOTAL'];
		endforeach;
		echo "
		<tr>
			<td colspan='7' class='text-right text-bold'>Total</td>
			<td class='text-right text-bold nu2d'>{$sum_total}</td>
			<td colspan='2'></td>
		</tr>";
		?>
		<script>
		$(function() {
			$(document).off('click', blockRincianOpd + '.check-all');
			$(document).on('click', blockRincianOpd + '.check-all', function(e) {
				var checkboxes = $(blockRincianOpd + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
		});
		</script>
		<?php
	}

	public function rincian_musrenbang()
	{
		?>
		<script>
			$(function() {
				var url = "http://dev.musrenbang.payakumbuhkota.go.id:8080/index.php/Apimusrenbang/musrenbang"
				$.ajax({
				url: url,
				method: "GET",
				dataType: "jsonp",
				crossDomain: true,
				success: function(data){
				var str = "";          
				for(var i= 0; i < data.length; i++){
					if (data[i].usul_kegrkpdkey == 1 && data[i].usul_subkegrkpdkey == 2){
					str +=
						'<tr>'+
							'<td>'+data[i].id_kel+'</td>'+
							'<td>'+data[i].id_kel+'</td>'+
							'<td>'+data[i].lokasi+'</td>'+
							'<td>'+data[i].keterangan+'</td>'+
							'<td>'+data[i].id_usul+'</td>'+
							'<td>'+data[i].volume+'</td>'+
							'<td>'+data[i].satuan+'</td>'+
							'<td>'+data[i].harga+'</td>'+
							'<td>'+data[i].id_usul+'</td>'+
							'<td>'+data[i].id_usul+'</td>'+
						'<tr>'
					}
				}
				$('#test').html(str);
				}
				});
			});
		</script>
		<?php
		// test1
		// $(function() {
		// 	var url = "http://dev.musrenbang.payakumbuhkota.go.id:8080/index.php/Apimusrenbang/musrenbang"
		// 	$.ajax({
		// 	url: url,
		// 	method: "GET",
		// 	dataType: "jsonp",
		// 	crossDomain: true,
		// 	success: function(data){
		// 	var str = "";          
		// 	for(var i= 0; i < data.length; i++){
		// 		if (data[i].usul_kegrkpdkey == 1 && data[i].usul_subkegrkpdkey == 2){
		// 		str +=
		// 			'<tr>'+
		// 				'<td>'+data[i].id_kel+'</td>'+
		// 				'<td>'+data[i].id_kel+'</td>'+
		// 				'<td>'+data[i].lokasi+'</td>'+
		// 				'<td>'+data[i].keterangan+'</td>'+
		// 				'<td>'+data[i].id_usul+'</td>'+
		// 				'<td>'+data[i].volume+'</td>'+
		// 				'<td>'+data[i].satuan+'</td>'+
		// 				'<td>'+data[i].harga+'</td>'+
		// 				'<td>'+data[i].id_usul+'</td>'+
		// 				'<td>'+data[i].id_usul+'</td>'+
		// 			'<tr>'
		// 		}
		// 	}
		// 	$('#test').html(str);
		// 	}
		// 	});
		// });
		// test1

		// $unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		// $kegrkpdkey = $this->input->post('f-kegrkpdkey');

		// $rows = $this->db->query("
		// SELECT
		// 	TIPE,
		// 	ID_KEGHSPK,
		// 	KDHSPK3,
		// 	KECAMATAN,
		// 	KELURAHAN,
		// 	NMPEKERJAAN,
		// 	JNPEKERJAAN,
		// 	LOKASI,
		// 	KETERANGAN,
		// 	VOLUME,
		// 	SATUAN,
		// 	HARGA,
		// 	TOTAL
		// FROM
		// 	PHPKEGHSPK
		// WHERE
		// 	TIPE = 2
		// AND KDTAHUN = ?
		// AND KDTAHAP = ?
		// AND UNITKEY = ?
		// AND KEGRKPDKEY = ?",
		// [$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array();

		// $sum_total = 0;
		// foreach($rows as $r):
		// $r = settrim($r);
		// echo "
		// <tr>
		// 	<td>{$r['KECAMATAN']}</td>
		// 	<td>{$r['KELURAHAN']}</td>
		// 	<td>{$r['LOKASI']}</td>
		// 	<td>{$r['KETERANGAN']}</td>
		// 	<td class='text-center w1px'><button type='button' class='btn btn-xs btn-info btn-hspk' data-id='{$r['KDHSPK3']}'><i class='fa fa-balance-scale'></i> {$r['KDHSPK3']}</button></td>
		// 	<td>{$r['JNPEKERJAAN']}</td>
		// 	<td class='text-center'>{$r['SATUAN']}</td>
		// 	<td class='text-right nu2d'>{$r['VOLUME']}</td>
		// 	<td class='text-right nu2d'>{$r['HARGA']}</td>
		// 	<td class='text-right nu2d'>{$r['TOTAL']}</td>
		// 	<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-hspk-form' data-act='edit' data-tipe='{$r['TIPE']}'><u>Edit</u></a></td>
		// 	<td class='text-center'>
		// 		<div class='checkbox checkbox-inline'>
		// 			<input type='checkbox' name='i-check[]' value='{$r['ID_KEGHSPK']}'>
		// 			<label></label>
		// 		</div>
		// 	</td>
		// </tr>";

		// $sum_total += $r['TOTAL'];
		// endforeach;
		// echo "
		// <tr>
		// 	<td colspan='9' class='text-right text-bold'>Total</td>
		// 	<td class='text-right text-bold nu2d'>{$sum_total}</td>
		// 	<td colspan='2'></td>
		// </tr>";
		?>
		<script>
		// $(function() {
		// 	$(document).off('click', blockRincianMusrenbang + '.check-all');
		// 	$(document).on('click', blockRincianMusrenbang + '.check-all', function(e) {
		// 		var checkboxes = $(blockRincianMusrenbang + "input[name='i-check[]']:checkbox");
		// 		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
		// 	});
		// });
		</script>
		<?php
	}

	public function rincian_pokir()
	{
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');

		$rows = $this->db->query("
		SELECT
			TIPE,
			ID_KEGHSPK,
			KDHSPK3,
			KECAMATAN,
			KELURAHAN,
			NMPEKERJAAN,
			JNPEKERJAAN,
			LOKASI,
			KETERANGAN,
			VOLUME,
			SATUAN,
			HARGA,
			TOTAL
		FROM
			PHPKEGHSPK
		WHERE
			TIPE = 3
		AND KDTAHUN = ?
		AND KDTAHAP = ?
		AND UNITKEY = ?
		AND KEGRKPDKEY = ?",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array();

		$sum_total = 0;
		foreach($rows as $r):
		$r = settrim($r);
		echo "
		<tr>
			<td>{$r['KECAMATAN']}</td>
			<td>{$r['KELURAHAN']}</td>
			<td>{$r['LOKASI']}</td>
			<td>{$r['KETERANGAN']}</td>
			<td class='text-center w1px'><button type='button' class='btn btn-xs btn-info btn-hspk' data-id='{$r['KDHSPK3']}'><i class='fa fa-balance-scale'></i> {$r['KDHSPK3']}</button></td>
			<td>{$r['JNPEKERJAAN']}</td>
			<td class='text-center'>{$r['SATUAN']}</td>
			<td class='text-right nu2d'>{$r['VOLUME']}</td>
			<td class='text-right nu2d'>{$r['HARGA']}</td>
			<td class='text-right nu2d'>{$r['TOTAL']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-hspk-form' data-act='edit' data-tipe='{$r['TIPE']}'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['ID_KEGHSPK']}'>
					<label></label>
				</div>
			</td>
		</tr>";

		$sum_total += $r['TOTAL'];
		endforeach;
		echo "
		<tr>
			<td colspan='9' class='text-right text-bold'>Total</td>
			<td class='text-right text-bold nu2d'>{$sum_total}</td>
			<td colspan='2'></td>
		</tr>";
		?>
		<script>
		$(function() {
			$(document).off('click', blockRincianPokir + '.check-all');
			$(document).on('click', blockRincianPokir + '.check-all', function(e) {
				var checkboxes = $(blockRincianPokir + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});
		});
		</script>
		<?php
	}

	public function rincian_kinerja()
	{
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');

		$rows = $this->db->query("
		SELECT
			JK.KDJKK,
			JK.URJKK,
			K.TOLOKUR,
			K.TARGETMIN1,
			K.TARGET,
			K.TARGET1
		FROM
			KINKEGRKPD K
		LEFT JOIN JKINKEG JK ON K.KDJKK = JK.KDJKK
		WHERE
			K.KDTAHUN = ?
		AND K.KDTAHAP = ?
		AND K.UNITKEY = ?
		AND K.KEGRKPDKEY = ? ",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array();

		foreach($rows as $r):
		$r = settrim($r);
		$cr=  $r['KDJKK'];
		if($cr == "02")  
		{
			$r['URJKK'] = 'Hasil';
		}
		if($cr == "03")  
		{
			$r['URJKK'] = 'Keluaran';
		}
		echo "
		<tr>
			<td>{$r['URJKK']}</td>
			<td>{$r['TOLOKUR']}</td>
			<td>{$r['TARGETMIN1']}</td>
			<td>{$r['TARGET']}</td>
			<td>{$r['TARGET1']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-kinerja-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['KDJKK']}'>
					<label></label>
				</div>
			</td>
		</tr>";
		endforeach;
		?>
		<script>
		$(function() {
			$(document).off('click', blockRincianKinerja + '.check-all');
			$(document).on('click', blockRincianKinerja + '.check-all', function(e) {
				var checkboxes = $(blockRincianKinerja + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});

			$(document).off('click', blockRincianKinerja + '.btn-rincian-kinerja-form');
			$(document).on('click', blockRincianKinerja + '.btn-rincian-kinerja-form', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
				var act = $(this).data('act'),
					data, title, type;

				data = {
					'f-unitkey'		: getVal('#f-unitkey'),
					'f-kegrkpdkey'	: getVal('#f-kegrkpdkey')
				};

				if(act == 'add') {
					title = 'Tambah Kinerja Kegiatan';
					type = 'type-success';
				} else if(act == 'edit') {
					title = 'Ubah Kinerja Kegiatan';
					type = 'type-warning';
					data = $.extend({},
						data,
						{'f-kdjkk' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
					);
				}

				modalRincianKinerjaForm = new BootstrapDialog({
					title: title,
					type: type,
					size: 'size-wide',
					message: $('<div></div>').load('/renja/rincian_kinerja_form/' + act, data)
				});
				modalRincianKinerjaForm.open();

				return false;
			});

			$(document).off('click', blockRincianKinerja + '.btn-rincian-kinerja-delete');
			$(document).on('click', blockRincianKinerja + '.btn-rincian-kinerja-delete', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
				if($(blockRincianKinerja + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
					return false;
				}
				var id = $(this).closest('tr').data('id');
				goConfirm({
					msg : 'Hapus daftar kinerja yang dipilih ?',
					type: 'danger',
					callback : function(ok) {
						if(ok) {
							var data = $.extend({},
								$(blockRincianKinerja + '.form-delete').serializeObject(),
								{
									'i-unitkey'		: getVal('#f-unitkey'),
									'i-kegrkpdkey'	: getVal('#f-kegrkpdkey')
								}
							);
							$.post('/renja/rincian_kinerja_delete/', data, function(res, status, xhr) {
								if(contype(xhr) == 'json') {
									respond(res);
								} else {
									dataLoadRincian('kinerja');
								}
							});
						}
					}
				});

				return false;
			});
		});
		</script>
		<?php
	}

	public function rincian_kinerja_form($act)
	{
		$this->load->library('form_validation');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');
		$kdjkk = $this->input->post('f-kdjkk');

		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'kdjkk'			=> '',
				'tolokur'		=> '',
				'targetmin1'		=> '',
				'target'		=> '',
				'target1'		=> '',
				'curdShow'		=> $this->sip->curdShow('I'),
				'list_jkinkeg'	=> $this->db->query("
					SELECT KDJKK, URJKK
					FROM JKINKEG
					WHERE
					KDJKK NOT IN (
						SELECT KDJKK FROM KINKEGRKPD
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND KEGRKPDKEY = ? )
					",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array(),
					'capaian_program'	=> $this->db->query("
					SELECT INDIKATOR, SASARAN, TOLOKUR, PR.TARGET FROM KEGRKPD K
							LEFT JOIN PGRRKPD PR
							ON PR.KDTAHUN = K.KDTAHUN
							AND PR.KDTAHAP = K.KDTAHAP
							AND PR.UNITKEY = K.UNITKEY
							AND PR.PGRMRKPDKEY = K.PGRMRKPDKEY
							WHERE K.KDTAHUN = ?
							AND K.KDTAHAP = ?
							AND K.UNITKEY = ?
							AND K.KEGRKPDKEY = ?
						",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array(),
						'target_kegiatan'	=> $this->db->query("
						SELECT KUANTITATIF, KET FROM KEGRKPD K
								WHERE K.KDTAHUN = ?
								AND K.KDTAHAP = ?
								AND K.UNITKEY = ?
								AND K.KEGRKPDKEY = ?
							",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array(),
							'masukan_target_kegiatan'	=>$this->db->query("
							 SELECT ISNULL(SUM(CAST(K.TARGET AS BIGINT)),0) AS TARGET,
							 ISNULL(SUM(CAST(K.TARGET1 AS BIGINT)),0) AS TARGET1,
							 ISNULL(SUM(CAST(K.TARGETMIN1 AS BIGINT)),0)AS TARGETMIN1
							 FROM SUBKINKEGRKPD K
						   LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
							 AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
							 AND SK.KDTAHUN = K.KDTAHUN
							 AND SK.KDTAHAP = K.KDTAHAP
								WHERE K.KDTAHUN = ?
								AND K.KDTAHAP = ?
								AND K.UNITKEY = ?
								AND SK.KEGRKPDKEY = ?
								AND K.KDJKK = '01'
								",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array()

			];
		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('f-kdjkk', 'kode', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				KDJKK,
				TOLOKUR,
				TARGETMIN1,
				TARGET,
				TARGET1
			FROM
				KINKEGRKPD
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND KDJKK = ? ",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$kdjkk
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'kdjkk'			=> $kdjkk,
				'tolokur'		=> $r['TOLOKUR'],
				'targetmin1'		=> $r['TARGETMIN1'],
				'target'		=> $r['TARGET'],
				'target1'		=> $r['TARGET1'],
				'curdShow'		=> $this->sip->curdShow('U'),
				'list_jkinkeg'	=> $this->db->query("
					SELECT KDJKK, URJKK
					FROM JKINKEG WHERE KDJKK = ?", $kdjkk)->result_array(),
				'capaian_program'	=> $this->db->query("
					SELECT INDIKATOR, SASARAN, TOLOKUR, PR.TARGET TARGET FROM KEGRKPD K
							LEFT JOIN PGRRKPD PR
							ON PR.KDTAHUN = K.KDTAHUN
							AND PR.KDTAHAP = K.KDTAHAP
							AND PR.UNITKEY = K.UNITKEY
							AND PR.PGRMRKPDKEY = K.PGRMRKPDKEY
							WHERE K.KDTAHUN = ?
							AND K.KDTAHAP = ?
							AND K.UNITKEY = ?
							AND K.KEGRKPDKEY = ?
						",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array(),
						'target_kegiatan'	=> $this->db->query("
						SELECT KUANTITATIF, KET FROM KEGRKPD K
								WHERE K.KDTAHUN = ?
								AND K.KDTAHAP = ?
								AND K.UNITKEY = ?
								AND K.KEGRKPDKEY = ?
							",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array(),
							'masukan_target_kegiatan'	=>$this->db->query("
							 SELECT ISNULL(SUM(CAST(K.TARGET AS BIGINT)),0) AS TARGET,
							 ISNULL(SUM(CAST(K.TARGET1 AS BIGINT)),0) AS TARGET1,
							 ISNULL(SUM(CAST(K.TARGETMIN1 AS BIGINT)),0)AS TARGETMIN1
							 FROM SUBKINKEGRKPD K
							 LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
							 AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
							 AND SK.KDTAHUN = K.KDTAHUN
							 AND SK.KDTAHAP = K.KDTAHAP
								WHERE K.KDTAHUN = ?
								AND K.KDTAHAP = ?
								AND K.UNITKEY = ?
								AND SK.KEGRKPDKEY = ?
								AND K.KDJKK = '01'
								",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array()
			];
		}

		if($this->json['cod'] !== NULL)
		{
			echo $this->json['msg'];
		}
		else
		{
			$this->load->view('renja/v_renja_rincian_kinerja_form', $data);
		}
	}

	public function rincian_kinerja_save($act)
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
			$this->form_validation->set_rules('i-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('i-kdjkk', 'Indikator', 'trim|required');
			$this->form_validation->set_rules('i-tolokur', 'Tolak Ukur', 'trim|required');
			$this->form_validation->set_rules('i-targetmin1', 'Target Kinerja (n-1)', 'trim|required');
			$this->form_validation->set_rules('i-target', 'Target Kinerja n', 'trim|required');
			$this->form_validation->set_rules('i-target1', 'Target Kinerja (n+1)', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$kdjkk		= $this->input->post('i-kdjkk');
			$tolokur	= $this->input->post('i-tolokur');
			$targetmin1	= $this->input->post('i-targetmin1');
			$target		= $this->input->post('i-target');
			$target1	= $this->input->post('i-target1');

			$tglvalid = $this->db->query("SELECT CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID FROM KEGRKPD WHERE KDTAHUN = ? AND KDTAHAP = ? AND UNITKEY = ? AND KEGRKPDKEY = ?",
			[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->row_array();
			if($tglvalid AND $tglvalid['TGLVALID'] != ''):
				throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
			endif;

			if($act == 'add')
			{
				$set = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'KEGRKPDKEY'	=> $kegrkpdkey,
					'KDJKK'			=> $kdjkk,
					'TOLOKUR'		=> $tolokur,
					'TARGETMIN1'		=> $targetmin1,
					'TARGET'		=> $target,
					'TARGET1'		=> $target1
				];

				$this->db->insert('KINKEGRKPD', $set);
				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Kinerja Kegiatan gagal ditambahkan.', 2);
				}

				$nilai = 'Insert ' .json_encode($set);
				$nmTable = 'KINKEGRKPD';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
			}
			elseif($act == 'edit')
			{
				$this->db->query("
				UPDATE KINKEGRKPD
				SET
					TOLOKUR = ?,
					TARGETMIN1	= ?,
					TARGET	= ?,
					TARGET1 = ?
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND KEGRKPDKEY = ?
				AND KDJKK	= ?",
				[
					$tolokur,
					$targetmin1,
					$target,
					$target1,

					$this->KDTAHUN,
					$this->KDTAHAP,
					$unitkey,
					$kegrkpdkey,
					$kdjkk
				]);

				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Kinerja Kegiatan gagal ditambahkan.', 2);
				}

				$nilai = 'Edit TOLOKUR : '. $tolokur . ' TARGETMIN1	: ' . $targetmin1 . ' TARGET	: ' . $target . ' TARGET1 : ' . $target1 ;
				$nmTable = 'KINKEGRKPD';
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

	public function rincian_kinerja_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$kdjkk		= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM KINKEGRKPD
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND KDJKK IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$kdjkk
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Daftar kinerja gagal dihapus.', 2);
			}

			$data = json_encode($kdjkk);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'KINKEGRKPD';
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

	public function rincian_penjabaran()
	{
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');

		$rows = $this->db->query("
		SELECT
			P.KDNILAI,
			P.KDJABAR,
			P.URAIAN,
			P.EKSPRESI,
			P.JUMBYEK,
			P.SATUAN,
			P.TARIF,
			P.SUBTOTAL,
			JD.NMDANA,
			P.TYPE
		FROM
			KEGRKPDDETR P
		LEFT JOIN JDANA JD ON P.KDDANA = JD.KDDANA
		WHERE
			P.KDTAHUN = ?
		AND P.KDTAHAP = ?
		AND P.UNITKEY = ?
		AND P.KEGRKPDKEY = ?",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array();

		foreach($rows as $r):
		$r = settrim($r);
		echo "
		<tr>
			<td>{$r['KDJABAR']}</td>
			<td>{$r['URAIAN']}</td>
			<td class='text-right'>{$r['EKSPRESI']}</td>
			<td class='text-right nu2d'>{$r['JUMBYEK']}</td>
			<td>{$r['SATUAN']}</td>
			<td class='text-right nu2d'>{$r['TARIF']}</td>
			<td class='text-right nu2d'>{$r['SUBTOTAL']}</td>
			<td class='text-center'>{$r['NMDANA']}</td>
			<td class='text-center'>{$r['TYPE']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-penjabaran-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['KDNILAI']}'>
					<label></label>
				</div>
			</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-penjabaran-add-child'><i class='fa fa-long-arrow-down'></i></a></td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-penjabaran-add-sibling'><i class='fa fa-long-arrow-right'></i></a></td>
		</tr>";
		endforeach;
		?>
		<script>
		$(function() {
			$(document).off('click', blockRincianPenjabaran + '.check-all');
			$(document).on('click', blockRincianPenjabaran + '.check-all', function(e) {
				var checkboxes = $(blockRincianPenjabaran + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});

			$(document).off('click', blockRincianPenjabaran + '.btn-rincian-penjabaran-form');
			$(document).on('click', blockRincianPenjabaran + '.btn-rincian-penjabaran-form', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
				var act = $(this).data('act'),
					data, title, type;

				data = {
					'f-unitkey'		: getVal('#f-unitkey'),
					'f-kegrkpdkey'	: getVal('#f-kegrkpdkey')
				};

				if(act == 'add') {
					title = 'Tambah Penjabaran Kegiatan';
					type = 'type-success';
				} else if(act == 'edit') {
					title = 'Ubah Penjabaran Kegiatan';
					type = 'type-warning';
					data = $.extend({},
						data,
						{'f-kdnilai' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
					);
				}

				modalRincianPenjabaranForm = new BootstrapDialog({
					title: title,
					type: type,
					size: 'size-wide',
					message: $('<div></div>').load('/renja/rincian_penjabaran_form/' + act, data)
				});
				modalRincianPenjabaranForm.open();

				return false;
			});

			$(document).off('click', blockRincianPenjabaran + '.btn-rincian-penjabaran-delete');
			$(document).on('click', blockRincianPenjabaran + '.btn-rincian-penjabaran-delete', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
				if($(blockRincianPenjabaran + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
					return false;
				}
				var id = $(this).closest('tr').data('id');
				goConfirm({
					msg : 'Hapus daftar penjabaran yang dipilih ?',
					type: 'danger',
					callback : function(ok) {
						if(ok) {
							var data = $.extend({},
								$(blockRincianPenjabaran + '.form-delete').serializeObject(),
								{
									'i-unitkey'		: getVal('#f-unitkey'),
									'i-kegrkpdkey'	: getVal('#f-kegrkpdkey')
								}
							);
							$.post('/renja/rincian_penjabaran_delete/', data, function(res, status, xhr) {
								if(contype(xhr) == 'json') {
									respond(res);
								} else {
									dataLoadRincian('penjabaran');
								}
							});
						}
					}
				});

				return false;
			});
		});
		</script>
		<?php
	}

	public function rincian_penjabaran_form($act)
	{
		$this->load->library('form_validation');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');
		$kdnilai = $this->input->post('f-kdnilai');

		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'kdnilai'		=> '',
				'kdjabar'		=> '',
				'uraian'		=> '',
				'ekspresi'		=> '',
				'satuan'		=> '',
				'tarif'			=> '',
				'kddana'		=> '',
				'type'			=> '',
				'curdShow'		=> $this->sip->curdShow('I'),
				'list_jdana'	=> $this->db->query("
					SELECT KDDANA, NMDANA
					FROM JDANA
					WHERE TYPE = 'D'")->result_array()
			];
		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('f-kdnilai', 'Kode Nilai', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				P.KDNILAI,
				P.KDJABAR,
				P.URAIAN,
				P.EKSPRESI,
				P.JUMBYEK,
				P.SATUAN,
				P.TARIF,
				P.SUBTOTAL,
				JD.KDDANA,
				P.TYPE
			FROM
				KEGRKPDDETR P
			LEFT JOIN JDANA JD ON P.KDDANA = JD.KDDANA
			WHERE
				P.KDTAHUN = ?
			AND P.KDTAHAP = ?
			AND P.UNITKEY = ?
			AND P.KEGRKPDKEY = ?
			AND P.KDNILAI = ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$kdnilai
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'kdnilai'		=> $kdnilai,
				'kdjabar'		=> $r['KDJABAR'],
				'uraian'		=> $r['URAIAN'],
				'ekspresi'		=> $r['EKSPRESI'],
				'satuan'		=> $r['SATUAN'],
				'tarif'			=> $r['TARIF'],
				'kddana'		=> $r['KDDANA'],
				'type'			=> $r['TYPE'],
				'curdShow'		=> $this->sip->curdShow('U'),
				'list_jdana'	=> $this->db->query("
					SELECT KDDANA, NMDANA
					FROM JDANA
					WHERE TYPE = 'D'")->result_array()
			];
		}

		if($this->json['cod'] !== NULL)
		{
			echo $this->json['msg'];
		}
		else
		{
			$this->load->view('renja/v_renja_rincian_penjabaran_form', $data);
		}
	}

	public function rincian_penjabaran_save($act)
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
			$this->form_validation->set_rules('i-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('i-kdjabar', 'Kode', 'trim|required');
			$this->form_validation->set_rules('i-uraian', 'Uraian', 'trim|required');
			$this->form_validation->set_rules('i-ekspresi', 'Ekspresi', 'trim|required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('i-satuan', 'Satuan', 'trim|required');
			$this->form_validation->set_rules('i-tarif', 'Tarif', 'trim|required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('i-kddana', 'Sumber Dana', 'trim|required');
			$this->form_validation->set_rules('i-type', 'Tipe', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$kdnilai	= $this->input->post('i-kdnilai');
			$kdjabar	= $this->input->post('i-kdjabar');
			$uraian		= $this->input->post('i-uraian');
			$ekspresi	= $this->input->post('i-ekspresi');
			$satuan		= $this->input->post('i-satuan');
			$tarif		= $this->input->post('i-tarif');
			$kddana		= $this->input->post('i-kddana');
			$type		= $this->input->post('i-type');

			$tglvalid = $this->db->query("SELECT CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID FROM KEGRKPD WHERE KDTAHUN = ? AND KDTAHAP = ? AND UNITKEY = ? AND KEGRKPDKEY = ?",
			[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->row_array();
			if($tglvalid AND $tglvalid['TGLVALID'] != ''):
				throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
			endif;

			if($act == 'add')
			{
				$kdnilai = $this->m_set->getNextKey('KEGRKPDDETR');

				$set = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'KEGRKPDKEY'	=> $kegrkpdkey,
					'KDNILAI'		=> $kdnilai,
					'KDJABAR'		=> $kdjabar,
					'URAIAN'		=> $uraian,
					'EKSPRESI'		=> $ekspresi,
					'SATUAN'		=> $satuan,
					'TARIF'			=> $tarif,
					'KDDANA'		=> $kddana,
					'TYPE'			=> $type,

					'JUMBYEK'		=> $ekspresi,
					'SUBTOTAL'		=> ($ekspresi * $tarif)
				];

				$this->db->insert('KEGRKPDDETR', $set);
				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Kinerja Kegiatan gagal ditambahkan.', 2);
				}

				$this->m_set->updateNextKey('KEGRKPDDETR', $kdnilai);
			}
			elseif($act == 'edit')
			{
				$this->db->query("
				UPDATE KEGRKPDDETR
				SET
					KDJABAR	= ?,
					URAIAN	= ?,
					EKSPRESI = ?,
					SATUAN	= ?,
					TARIF	= ?,
					KDDANA	= ?,
					TYPE	= ?,

					JUMBYEK = ?,
					SUBTOTAL = ?
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND KEGRKPDKEY = ?
				AND KDNILAI	= ?",
				[
					$kdjabar,
					$uraian,
					$ekspresi,
					$satuan,
					$tarif,
					$kddana,
					$type,

					$ekspresi,
					($ekspresi * $tarif),

					$this->KDTAHUN,
					$this->KDTAHAP,
					$unitkey,
					$kegrkpdkey,
					$kdnilai
				]);

				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Kinerja Kegiatan gagal ditambahkan.', 2);
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

	public function rincian_penjabaran_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$kdnilai	= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM KEGRKPDDETR
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND KDNILAI IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$kdnilai
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Daftar Penjabaran gagal dihapus.', 2);
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

	public function rincian_sumberdana()
	{
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');

		$rows = $this->db->query("
		SELECT
			D.KDDANA,
			JD.NMDANA,
			D.NILAI
		FROM
			KEGRKPDDANA D
		LEFT JOIN JDANA JD ON D.KDDANA = JD.KDDANA
		WHERE
			D.KDTAHUN = ?
		AND D.KDTAHAP = ?
		AND D.UNITKEY = ?
		AND D.KEGRKPDKEY = ?",
		[
			$this->KDTAHUN,
			$this->KDTAHAP,
			$unitkey,
			$kegrkpdkey
		])->result_array();

		foreach($rows as $r):
		$r = settrim($r);
		echo "
		<tr>
			<td>{$r['KDDANA']}</td>
			<td>{$r['NMDANA']}</td>
			<td class='text-right nu2d'>{$r['NILAI']}</td>
			<td></td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-rincian-sumberdana-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['KDDANA']}'>
					<label></label>
				</div>
			</td>
		</tr>";
		endforeach;
		?>
		<script>
		$(function() {
			$(document).off('click', blockRincianSumberdana + '.check-all');
			$(document).on('click', blockRincianSumberdana + '.check-all', function(e) {
				var checkboxes = $(blockRincianSumberdana + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});

			$(document).off('click', blockRincianSumberdana + '.btn-rincian-sumberdana-form');
			$(document).on('click', blockRincianSumberdana + '.btn-rincian-sumberdana-form', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
				var act = $(this).data('act'),
					data, title, type;

				data = {
					'f-unitkey'		: getVal('#f-unitkey'),
					'f-kegrkpdkey'	: getVal('#f-kegrkpdkey')
				};

				if(act == 'add') {
					title = 'Tambah Sumberdana Kegiatan';
					type = 'type-success';
				} else if(act == 'edit') {
					title = 'Ubah Sumberdana Kegiatan';
					type = 'type-warning';
					data = $.extend({},
						data,
						{'f-kddana' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
					);
				}

				modalRincianSumberdanaForm = new BootstrapDialog({
					title: title,
					type: type,
					size: 'size-wide',
					message: $('<div></div>').load('/renja/rincian_sumberdana_form/' + act, data)
				});
				modalRincianSumberdanaForm.open();

				return false;
			});

			$(document).off('click', blockRincianSumberdana + '.btn-rincian-sumberdana-delete');
			$(document).on('click', blockRincianSumberdana + '.btn-rincian-sumberdana-delete', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-kegrkpdkey'))) return false;
				if($(blockRincianSumberdana + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
					return false;
				}
				var id = $(this).closest('tr').data('id');
				goConfirm({
					msg : 'Hapus daftar sumber dana yang dipilih ?',
					type: 'danger',
					callback : function(ok) {
						if(ok) {
							var data = $.extend({},
								$(blockRincianSumberdana + '.form-delete').serializeObject(),
								{
									'i-unitkey'		: getVal('#f-unitkey'),
									'i-kegrkpdkey'	: getVal('#f-kegrkpdkey')
								}
							);
							$.post('/renja/rincian_sumberdana_delete/', data, function(res, status, xhr) {
								if(contype(xhr) == 'json') {
									respond(res);
								} else {
									dataLoadRincian('sumberdana');
								}
							});
						}
					}
				});

				return false;
			});
		});
		</script>
		<?php
	}

	public function rincian_sumberdana_form($act)
	{
		$this->load->library('form_validation');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');
		$kddana = $this->input->post('f-kddana');

		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'kddana'		=> '',
				'nilai'			=> '',
				'nilai1'		=> '',
				'curdShow'		=> $this->sip->curdShow('I'),
				'list_jdana'	=> $this->db->query("
					SELECT KDDANA, NMDANA
					FROM JDANA
					WHERE TYPE = 'D'
					AND KDDANA NOT IN (
						SELECT KDDANA
						FROM KEGRKPDDANA
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND KEGRKPDKEY = ?
					)",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->result_array()
			];
		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('f-kddana', 'Kode Nilai', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				KDDANA,
				NILAI,
				NILAI1
			FROM
				KEGRKPDDANA
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND KDDANA = ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$kddana
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'kegrkpdkey'	=> $kegrkpdkey,
				'kddana'		=> $kddana,
				'nilai'			=> $r['NILAI'],
				'nilai1'		=> $r['NILAI1'],
				'curdShow'		=> $this->sip->curdShow('U'),
				'list_jdana'	=> $this->db->query("
					SELECT KDDANA, NMDANA
					FROM JDANA
					WHERE TYPE = 'D' AND KDDANA = ?", $kddana)->result_array()
			];
		}

		if($this->json['cod'] !== NULL)
		{
			echo $this->json['msg'];
		}
		else
		{
			$this->load->view('renja/v_renja_rincian_sumberdana_form', $data);
		}
	}

	public function rincian_sumberdana_save($act)
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
			$this->form_validation->set_rules('i-kddana', 'Sumber Dana', 'trim|required');
			$this->form_validation->set_rules('i-nilai', 'Nilai n', 'trim|required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('i-nilai1', 'Nilai n+1', 'trim|required|numeric|greater_than_equal_to[0]');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$kddana		= $this->input->post('i-kddana');
			$nilai		= $this->input->post('i-nilai');
			$nilai1		= $this->input->post('i-nilai1');

			$tglvalid = $this->db->query("SELECT CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID FROM KEGRKPD WHERE KDTAHUN = ? AND KDTAHAP = ? AND UNITKEY = ? AND KEGRKPDKEY = ?",
			[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $kegrkpdkey])->row_array();
			if($tglvalid AND $tglvalid['TGLVALID'] != ''):
				throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
			endif;

			if($act == 'add')
			{
				$set = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'KEGRKPDKEY'	=> $kegrkpdkey,
					'KDDANA'		=> $kddana,
					'NILAI'			=> $nilai,
					'NILAI1'		=> $nilai1
				];

				$this->db->insert('KEGRKPDDANA', $set);
				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Sumber dana gagal ditambahkan.', 2);
				}

				$nilai = 'Insert ' .json_encode($set);
				$nmTable = 'KEGRKPDDANA';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
			}
			elseif($act == 'edit')
			{
				$this->db->query("
				UPDATE KEGRKPDDANA
				SET
					NILAI	= ?,
					NILAI1	= ?
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND KEGRKPDKEY = ?
				AND KDDANA	= ?",
				[
					$nilai,
					$nilai1,

					$this->KDTAHUN,
					$this->KDTAHAP,
					$unitkey,
					$kegrkpdkey,
					$kddana
				]);

				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Sumber dana gagal ditambahkan.', 2);
				}

				$nilai = ' Edit NILAI : '. $nilai . ' NILAI1	: ' . $nilai1 ;
				$nmTable = 'KEGRKPDDANA';
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

	public function rincian_sumberdana_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$kegrkpdkey	= $this->input->post('i-kegrkpdkey');
			$kddana		= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM KEGRKPDDANA
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND KDDANA IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$kddana
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Daftar Sumber Dana gagal dihapus.', 2);
			}

			$data = json_encode($kddana);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'KEGRKPDDANA';
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

	//Prioritas  dna sasaran kota / Daerah
	public function prioritas_form()
	{

		$data['unitkey'] = $this->input->post('f-unitkey');
		$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
		$data['prioritas'] = $this->prioritas_form_load(1, TRUE);
//		print_r($data['prioritas']);
		$this->load->view('renja/v_renja_prioritas', $data);


	}

	public function prioritas_form_load($page = 1, $first = FALSE)
	{

		$per_page = 6;

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$filter = "
		AND E.KDTAHUN = '{$this->KDTAHUN}'
		AND E.KDTAHAP = '{$this->KDTAHAP}'
		AND E.UNITKEY = '{$unitkey}'
		AND E.PGRMRKPDKEY = '{$pgrmrkpdkey}'

		";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'NOPRIOPPAS'; break;
				case '2' : $search_type = 'NMPRIOPPAS'; break;

			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$total = $this->m_program->prioritas_getall($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_program->prioritas_getall($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_program->prioritas_getall($filter, [$per_page, $page])->result_array();
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


		$load = '';
		foreach($rows as $r):
		$r = settrim($r);
			$load .= "
		<tr id='tr-prioritas-{$r['PRIOPPASKEY']}'>
			<td><a href='javascript:void(0)' class='btn-prioritas-show-sasaran'><u>{$r['NOPRIOPPAS']}</u></a></td>
			<td>{$r['NMPRIOPPAS']}</td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['PRIOPPASKEY']}'>
					<label></label>
				</div>
			</td>
		</tr> ";
	endforeach;
		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {

			$(blockLookupPrioritas + '.block-pagination').html($(blockLookupPrioritas + '.pagetemp').html());
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

	public function prioritas_save(){
		$this->sip->is_curd('I');

		$this->load->library('form_validation');

		try
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
			$pgrmrkpdkey		= $this->input->post('f-pgrmrkpdkey');
			$prioritas_list	= $this->input->post('i-check[]');

			foreach($prioritas_list AS $prioritas)
			{


				if($prioritas == '') continue;
				$this->db->insert('EPRIOPPAS',
				[
					'PRIOPPASKEY' => $prioritas,
					'KDTAHAP'		=> $this->KDTAHAP,
					'KDTAHUN'		=> $this->KDTAHUN,
					'PGRMRKPDKEY'	=> $pgrmrkpdkey,
					'UNITKEY'		=> $unitkey
				]);



		//		$nilai ='PRIOPPASKEY: ' . $prioritas . ' KDTAHAP : ' . $this->KDTAHAP . ' KDTAHUN : '. $this->KDTAHUN . ' PGRMRKPDKEY : '. $pgrmrkpdkey .	 ' UNITKEY : '		. $unitkey  ;
		//		$nmTable = 'PRARASKR';
		//		$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
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


	public function prioritas_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$pgrmrkpdkey		= $this->input->post('i-pgrmrkpdkey');
			$prioppaskey	= $this->input->post('i-check[]');

			$check = $this->db->query("
				SELECT
					*
				FROM
					ESASARAN E
				WHERE
				KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND PGRMRKPDKEY = ?
				AND PRIOPPASKEY IN ?
				",
				[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $pgrmrkpdkey, $prioppaskey ])->row_array();

				if ($check >= 1 ){
						throw new Exception('Prioritas gagal Dihapus, Silakan periksa kembali data sasaran jika ingin menghapus prioritas.', 2);
				}else {
					$this->db->query("
					DELETE FROM EPRIOPPAS
					WHERE
						KDTAHUN = ?
					AND KDTAHAP = ?
					AND UNITKEY = ?
					AND PGRMRKPDKEY = ?
					AND PRIOPPASKEY IN ?",
					[
						$this->KDTAHUN,
						$this->KDTAHAP,
						$unitkey,
						$pgrmrkpdkey,
						$prioppaskey
					]);

					$affected = $this->db->affected_rows();
					if($affected < 1)
					{
						throw new Exception('Prioritas gagal dihapus.', 2);
					}

				}
			$data = json_encode($pgrmrkpdkey);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'EPRIOPPAS';
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


	public function sasaran_form_load($page = 1, $first = FALSE)
	{

		$per_page = 6;

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
		$prioppaskey = $this->input->post('f-prioppaskey');
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');


		$filter = "
		AND E.KDTAHUN = '{$this->KDTAHUN}'
		AND E.KDTAHAP = '{$this->KDTAHAP}'
		AND E.UNITKEY = '{$unitkey}'
		AND E.PGRMRKPDKEY = '{$pgrmrkpdkey}'
		AND E.PRIOPPASKEY = '{$prioppaskey}'
		 ";



		$total = $this->m_program->sasaran_getall($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_program->sasaran_getall($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_program->sasaran_getall($filter, [$per_page, $page])->result_array();
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


		$load = '';
		foreach($rows as $r):
		$r = settrim($r);
			$load .= "
		<tr id='tr-sasaran-{$r['IDSAS']}'>
			<td>{$r['NOSAS']}</td>
			<td>{$r['NMSAS']}</td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['IDSAS']}'>
					<label></label>
				</div>
			</td>
		</tr> ";
	endforeach;
		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {

			$(blockSasaran + '.block-pagination').html($(blockSasaran + '.pagetemp').html());
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

	public function sasaran_save(){
		$this->sip->is_curd('I');

		$this->load->library('form_validation');

		try
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
			$pgrmrkpdkey		= $this->input->post('f-pgrmrkpdkey');
			$prioppaskey		= $this->input->post('f-prioppaskey');
			$sasaran_list	= $this->input->post('i-check[]');
			foreach($sasaran_list AS $sasaran)
			{


				if($sasaran == '') continue;
				$this->db->insert('ESASARAN',
				[
					'PRIOPPASKEY' => $prioppaskey,
					'KDTAHAP'		=> $this->KDTAHAP,
					'KDTAHUN'		=> $this->KDTAHUN,
					'PGRMRKPDKEY'	=> $pgrmrkpdkey,
					'UNITKEY'		=> $unitkey,
					'IDSAS'			=>$sasaran
				]);


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
			$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
			$pgrmrkpdkey		= $this->input->post('i-pgrmrkpdkey');
			$prioppaskey		= $this->input->post('f-prioppaskey');
			$idsas		= $this->input->post('i-check[]');


					$this->db->query("
					DELETE FROM ESASARAN
					WHERE
						KDTAHUN = ?
					AND KDTAHAP = ?
					AND UNITKEY = ?
					AND PGRMRKPDKEY = ?
					AND PRIOPPASKEY = ?
					AND IDSAS IN ?",
					[
						$this->KDTAHUN,
						$this->KDTAHAP,
						$unitkey,
						$pgrmrkpdkey,
						$prioppaskey,
						$idsas
					]);

					$affected = $this->db->affected_rows();

					if($affected < 1)
					{
						throw new Exception('Sasaran gagal dihapus.', 2);
					}


			$data = json_encode($pgrmrkpdkey);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'ESASARAN';
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

	//================================ end prioritas dan sasaran daerah


	//Prioritas  dna sasaran provinsi
		public function prioritas_form_provinsi()
		{

			$data['unitkey'] = $this->input->post('f-unitkey');
			$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
			$data['prioritas_provinsi'] = $this->prioritas_form_provinsi_load(1, TRUE);
	//		print_r($data['prioritas']);
			$this->load->view('renja/v_renja_prioritas_provinsi', $data);


		}

		public function prioritas_form_provinsi_load($page = 1, $first = FALSE)
		{

			$per_page = 6;

			$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
			$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
			$search_type = $this->input->post('f-search_type');
			$search_key = $this->input->post('f-search_key');

			$filter = "
			AND E.KDTAHUN = '{$this->KDTAHUN}'
			AND E.KDTAHAP = '{$this->KDTAHAP}'
			AND E.UNITKEY = '{$unitkey}'
			AND E.PGRMRKPDKEY = '{$pgrmrkpdkey}'

			 ";

			if($search_key)
			{
				switch($search_type)
				{
					case '1' : $search_type = 'NOPRIOPPAS'; break;
					case '2' : $search_type = 'NMPRIOPPAS'; break;

				}

				$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
			}

			$total = $this->m_program->prioritas_provinsi_getall($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
			$rows = $this->m_program->prioritas_provinsi_getall($filter, [$per_page, $page])->result_array();
			while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_program->prioritas_provinsi_getall($filter, [$per_page, $page])->result_array();
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


			$load = '';
			foreach($rows as $r):
			$r = settrim($r);
				$load .= "
			<tr id='tr-prioritas-provinsi-{$r['PRIOPROVKEY']}'>
				<td><a href='javascript:void(0)' class='btn-prioritas-show-sasaran'><u>{$r['NOPRIO']}</u></a></td>
				<td>{$r['NMPRIO']}</td>
				<td class='text-center'>
					<div class='checkbox checkbox-inline'>
						<input type='checkbox' name='i-check[]' value='{$r['PRIOPROVKEY']}'>
						<label></label>
					</div>
				</td>
			</tr> ";
		endforeach;
			$load .= "
			<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
			<script>
			$(function() {

				$(blockLookupPrioritasProvinsi  + '.block-pagination').html($(blockLookupPrioritasProvinsi + '.pagetemp').html());
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

		public function prioritas_save_provinsi(){
			$this->sip->is_curd('I');

			$this->load->library('form_validation');

			try
			{
				$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
				$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');

				if($this->form_validation->run() == FALSE)
				{
					throw new Exception(custom_errors(validation_errors()), 2);
				}

				$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
				$pgrmrkpdkey		= $this->input->post('f-pgrmrkpdkey');
				$prioritas_list	= $this->input->post('i-check[]');

				foreach($prioritas_list AS $prioritas)
				{


					if($prioritas == '') continue;
					$this->db->insert('EPRIOPPASPROV',
					[
						'PRIOPROVKEY' => $prioritas,
						'KDTAHAP'		=> $this->KDTAHAP,
						'KDTAHUN'		=> $this->KDTAHUN,
						'PGRMRKPDKEY'	=> $pgrmrkpdkey,
						'UNITKEY'		=> $unitkey
					]);
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


		public function prioritas_delete_provinsi()
		{
			$this->sip->is_curd('D');

			$this->load->library('form_validation');

			try
			{
				$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
				$pgrmrkpdkey		= $this->input->post('i-pgrmrkpdkey');
				$prioprovkey	= $this->input->post('i-check[]');

				$check = $this->db->query("
					SELECT
						*
					FROM
						ESASARANPROV E
					WHERE
					KDTAHUN = ?
					AND KDTAHAP = ?
					AND UNITKEY = ?
					AND PGRMRKPDKEY = ?
					AND PRIOPROVKEY IN ?
					",
					[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $pgrmrkpdkey, $prioprovkey ])->row_array();

					if ($check >= 1 ){
							throw new Exception('Prioritas Provinsi gagal Dihapus, Silakan periksa kembali data sasaran provinsi jika ingin menghapus prioritas.', 2);
					}else {
						$this->db->query("
						DELETE FROM EPRIOPPASPROV
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND PGRMRKPDKEY = ?
						AND PRIOPROVKEY IN ?",
						[
							$this->KDTAHUN,
							$this->KDTAHAP,
							$unitkey,
							$pgrmrkpdkey,
							$prioprovkey
						]);

						$affected = $this->db->affected_rows();
						if($affected < 1)
						{
							throw new Exception('Prioritas gagal dihapus.', 2);
						}

					}
				$data = json_encode($pgrmrkpdkey);
				$nilai = 'Delete ' . $unitkey . ' ' . $data ;
				$nmTable = 'EPRIOPPASPROV';
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


		public function sasaran_form_provinsi_load($page = 1, $first = FALSE)
		{

			$per_page = 6;

			$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
			$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
			$prioprovkey = $this->input->post('f-prioprovkey');
			$search_type = $this->input->post('f-search_type');
			$search_key = $this->input->post('f-search_key');


			$filter = "
			AND E.KDTAHUN = '{$this->KDTAHUN}'
			AND E.KDTAHAP = '{$this->KDTAHAP}'
			AND E.UNITKEY = '{$unitkey}'
			AND E.PGRMRKPDKEY = '{$pgrmrkpdkey}'
			AND E.PRIOPROVKEY = '{$prioprovkey}'
			 ";



			$total = $this->m_program->sasaran_prov_getall($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
			$rows = $this->m_program->sasaran_prov_getall($filter, [$per_page, $page])->result_array();
			while ($page > 1 AND count($rows) < 1):
			$page--;
			$rows = $this->m_program->sasaran_prov_getall($filter, [$per_page, $page])->result_array();
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


			$load = '';
			foreach($rows as $r):
			$r = settrim($r);
				$load .= "
			<tr id='tr-sasaran-{$r['IDSASPROV']}'>
				<td>{$r['NOSAS']}</td>
				<td>{$r['NMSAS']}</td>
				<td class='text-center'>
					<div class='checkbox checkbox-inline'>
						<input type='checkbox' name='i-check[]' value='{$r['IDSASPROV']}'>
						<label></label>
					</div>
				</td>
			</tr> ";
		endforeach;
			$load .= "
			<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
			<script>
			$(function() {

				$(blockSasaranProvinsi + '.block-pagination').html($(blockSasaranProvinsi + '.pagetemp').html());
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

		public function sasaran_save_provinsi(){
			$this->sip->is_curd('I');

			$this->load->library('form_validation');

			try
			{
				$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
				$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');

				if($this->form_validation->run() == FALSE)
				{
					throw new Exception(custom_errors(validation_errors()), 2);
				}

				$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
				$pgrmrkpdkey		= $this->input->post('f-pgrmrkpdkey');
				$prioprovkey		= $this->input->post('f-prioprovkey');
				$sasaran_list	= $this->input->post('i-check[]');
				foreach($sasaran_list AS $sasaranprov)
				{


					if($sasaranprov == '') continue;
					$this->db->insert('ESASARANPROV',
					[
						'PRIOPROVKEY' => $prioprovkey,
						'KDTAHAP'		=> $this->KDTAHAP,
						'KDTAHUN'		=> $this->KDTAHUN,
						'PGRMRKPDKEY'	=> $pgrmrkpdkey,
						'UNITKEY'		=> $unitkey,
						'IDSASPROV'			=>$sasaranprov

					]);


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

		public function sasaran_delete_provinsi(){

			$this->sip->is_curd('D');

			$this->load->library('form_validation');

			try
			{
				$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
				$pgrmrkpdkey		= $this->input->post('i-pgrmrkpdkey');
				$prioprovkey		= $this->input->post('f-prioprovkey');
				$idsasprov		= $this->input->post('i-check[]');


						$this->db->query("
						DELETE FROM ESASARANPROV
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND PGRMRKPDKEY = ?
						AND PRIOPROVKEY = ?
						AND IDSASPROV IN ?",
						[
							$this->KDTAHUN,
							$this->KDTAHAP,
							$unitkey,
							$pgrmrkpdkey,
							$prioprovkey,
							$idsasprov
						]);

						$affected = $this->db->affected_rows();

						if($affected < 1)
						{
							throw new Exception('Sasaran gagal dihapus.', 2);
						}


				$data = json_encode($pgrmrkpdkey);
				$nilai = 'Delete ' . $unitkey . ' ' . $data ;
				$nmTable = 'ESASARANPROV';
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

		//================================ end prioritas dan sasaran Provinsi


		//Prioritas  dna sasaran Nasional
			public function prioritas_form_nasional()
			{

				$data['unitkey'] = $this->input->post('f-unitkey');
				$data['pgrmrkpdkey'] = $this->input->post('f-pgrmrkpdkey');
				$data['prioritas_nasional'] = $this->prioritas_form_nasional_load(1, TRUE);
		//		print_r($data['prioritas']);
				$this->load->view('renja/v_renja_prioritas_nasional', $data);


			}

			public function prioritas_form_nasional_load($page = 1, $first = FALSE)
			{

				$per_page = 6;

				$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
				$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
				$search_type = $this->input->post('f-search_type');
				$search_key = $this->input->post('f-search_key');

				$filter = "
				AND E.KDTAHUN = '{$this->KDTAHUN}'
				AND E.KDTAHAP = '{$this->KDTAHAP}'
				AND E.UNITKEY = '{$unitkey}'
				AND E.PGRMRKPDKEY = '{$pgrmrkpdkey}'

				 ";

				if($search_key)
				{
					switch($search_type)
					{
						case '1' : $search_type = 'NUPRIO'; break;
						case '2' : $search_type = 'NMPRIO'; break;

					}

					$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
				}

				$total = $this->m_program->prioritas_nasional_getall($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
				$rows = $this->m_program->prioritas_nasional_getall($filter, [$per_page, $page])->result_array();
				while ($page > 1 AND count($rows) < 1):
				$page--;
				$rows = $this->m_program->prioritas_nasional_getall($filter, [$per_page, $page])->result_array();
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


				$load = '';
				foreach($rows as $r):
				$r = settrim($r);
					$load .= "
				<tr id='tr-prioritas-provinsi-{$r['PRIONASKEY']}'>
					<td><a href='javascript:void(0)' class='btn-prioritas-show-sasaran'><u>{$r['NUPRIO']}</u></a></td>
					<td>{$r['NMPRIO']}</td>
					<td class='text-center'>
						<div class='checkbox checkbox-inline'>
							<input type='checkbox' name='i-check[]' value='{$r['PRIONASKEY']}'>
							<label></label>
						</div>
					</td>
				</tr> ";
			endforeach;
				$load .= "
				<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
				<script>
				$(function() {

					$(blockLookupPrioritasNasional  + '.block-pagination').html($(blockLookupPrioritasNasional + '.pagetemp').html());
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

public function prioritas_save_nasional(){
	$this->sip->is_curd('I');

	$this->load->library('form_validation');

	try
	{
		$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
		$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');

		if($this->form_validation->run() == FALSE)
		{
			throw new Exception(custom_errors(validation_errors()), 2);
		}

		$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey		= $this->input->post('f-pgrmrkpdkey');
		$prioritas_list_nasional	= $this->input->post('i-check[]');

		foreach($prioritas_list_nasional AS $prioritas_nasional)
		{


			if($prioritas_nasional == '') continue;
			$this->db->insert('EPRIOPPASNAS',
			[
				'PRIONASKEY' => $prioritas_nasional,
				'KDTAHAP'		=> $this->KDTAHAP,
				'KDTAHUN'		=> $this->KDTAHUN,
				'PGRMRKPDKEY'	=> $pgrmrkpdkey,
				'UNITKEY'		=> $unitkey
			]);
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


public function prioritas_delete_nasional()
{
	$this->sip->is_curd('D');

	$this->load->library('form_validation');

	try
	{
		$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
		$pgrmrkpdkey		= $this->input->post('i-pgrmrkpdkey');
		$prionaskey	= $this->input->post('i-check[]');

		$check = $this->db->query("
			SELECT
				*
			FROM
				ESASARANNAS E
			WHERE
			KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND PGRMRKPDKEY = ?
			AND PRIONASKEY IN ?
			",
			[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $pgrmrkpdkey, $prionaskey ])->row_array();

			if ($check >= 1 ){
					throw new Exception('Prioritas Nasional gagal Dihapus, Silakan periksa kembali data sasaran Nasional jika ingin menghapus prioritas.', 2);
			}else {
				$this->db->query("
				DELETE FROM EPRIOPPASNAS
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND PGRMRKPDKEY = ?
				AND PRIONASKEY IN ?",
				[
					$this->KDTAHUN,
					$this->KDTAHAP,
					$unitkey,
					$pgrmrkpdkey,
					$prionaskey
				]);

				$affected = $this->db->affected_rows();
				if($affected < 1)
				{
					throw new Exception('Prioritas gagal dihapus.', 2);
				}

			}
		$data = json_encode($pgrmrkpdkey);
		$nilai = 'Delete ' . $unitkey . ' ' . $data ;
		$nmTable = 'EPRIOPPASNAS';
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


public function sasaran_form_nasional_load($page = 1, $first = FALSE)
{

	$per_page = 6;

	$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
	$pgrmrkpdkey = $this->input->post('f-pgrmrkpdkey');
	$prionaskey = $this->input->post('f-prionaskey');
	$search_type = $this->input->post('f-search_type');
	$search_key = $this->input->post('f-search_key');


	$filter = "
	AND E.KDTAHUN = '{$this->KDTAHUN}'
	AND E.KDTAHAP = '{$this->KDTAHAP}'
	AND E.UNITKEY = '{$unitkey}'
	AND E.PGRMRKPDKEY = '{$pgrmrkpdkey}'
	AND E.PRIONASKEY = '{$prionaskey}'
	 ";

	$total = $this->m_program->sasaran_nas_getall($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
	$rows = $this->m_program->sasaran_nas_getall($filter, [$per_page, $page])->result_array();
	while ($page > 1 AND count($rows) < 1):
	$page--;
	$rows = $this->m_program->sasaran_nas_getall($filter, [$per_page, $page])->result_array();
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


	$load = '';
	foreach($rows as $r):
	$r = settrim($r);
		$load .= "
	<tr id='tr-sasaran-{$r['IDSASNAS']}'>
		<td>{$r['NOSAS']}</td>
		<td>{$r['NMSAS']}</td>
		<td class='text-center'>
			<div class='checkbox checkbox-inline'>
				<input type='checkbox' name='i-check[]' value='{$r['IDSASNAS']}'>
				<label></label>
			</div>
		</td>
	</tr> ";
endforeach;
	$load .= "
	<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
	<script>
	$(function() {

		$(blockSasaranNasional + '.block-pagination').html($(blockSasaranNasional + '.pagetemp').html());
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

public function sasaran_save_nasional(){
	$this->sip->is_curd('I');

	$this->load->library('form_validation');

	try
	{
		$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
		$this->form_validation->set_rules('f-pgrmrkpdkey', 'Kode Program', 'trim|required');

		if($this->form_validation->run() == FALSE)
		{
			throw new Exception(custom_errors(validation_errors()), 2);
		}

		$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
		$pgrmrkpdkey		= $this->input->post('f-pgrmrkpdkey');
		$prionaskey		= $this->input->post('f-prionaskey');
		$sasaran_list	= $this->input->post('i-check[]');
		foreach($sasaran_list AS $sasarannas)
		{


			if($sasarannas == '') continue;
			$this->db->insert('ESASARANNAS',
			[
				'PRIONASKEY' => $prionaskey,
				'KDTAHAP'		=> $this->KDTAHAP,
				'KDTAHUN'		=> $this->KDTAHUN,
				'PGRMRKPDKEY'	=> $pgrmrkpdkey,
				'UNITKEY'		=> $unitkey,
				'IDSASNAS'			=>$sasarannas

			]);


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

public function sasaran_delete_nasional(){

	$this->sip->is_curd('D');

	$this->load->library('form_validation');

	try
	{
		$unitkey		= $this->sip->unitkey($this->input->post('i-unitkey'));
		$pgrmrkpdkey		= $this->input->post('i-pgrmrkpdkey');
		$prionaskey		= $this->input->post('f-prionaskey');
		$idsasnas		= $this->input->post('i-check[]');


				$this->db->query("
				DELETE FROM ESASARANNAS
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND PGRMRKPDKEY = ?
				AND PRIONASKEY = ?
				AND IDSASNAS IN ?",
				[
					$this->KDTAHUN,
					$this->KDTAHAP,
					$unitkey,
					$pgrmrkpdkey,
					$prionaskey,
					$idsasnas
				]);

				$affected = $this->db->affected_rows();

				if($affected < 1)
				{
					throw new Exception('Sasaran gagal dihapus.', 2);
				}


		$data = json_encode($pgrmrkpdkey);
		$nilai = 'Delete ' . $unitkey . ' ' . $data ;
		$nmTable = 'ESASARANNAS';
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

//================================ end prioritas dan sasaran nasional



// Penambahan Sub KEGIATAN

public function subkegiatan_load($page = 1, $first = FALSE)
{
	$per_page = 6;

	$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
	$kegrkpdkey = $this->input->post('s-kegrkpdkey');
	$search_type = $this->input->post('f-search_type');
	$search_key = $this->input->post('f-search_key');

	$filter = "
	AND K.UNITKEY = '{$unitkey}'
	AND K.KEGRKPDKEY = '{$kegrkpdkey}'
	AND K.KDTAHUN = '{$this->KDTAHUN}'
	AND K.KDTAHAP = '{$this->KDTAHAP}'
	";

	if($search_key)
	{
		switch($search_type)
		{
			case '1' : $search_type = 'MK.NUSUBKEG'; break;
			case '2' : $search_type = 'MK.NMSUBKEG'; break;
			// case '' : $search_type = 'K.PAGUTIF'; break;
			case '3' : $search_type = 'K.TARGET'; break;
			case '4' : $search_type = 'K.TARGETSEBELUM'; break;

		}

		$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
	}

	$total = $this->m_subkegiatan->getAll($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
	$rows = $this->m_subkegiatan->getAll($filter, [$per_page, $page])->result_array();
	while ($page > 1 AND count($rows) < 1):
	$page--;
	$rows = $this->m_subkegiatan->getAll($filter, [$per_page, $page])->result_array();
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
		$gender = $r['IS_RES_GENDER'];
		$spm = $r['IS_SPM'];
		$pkd = $r['IS_PKD'];
		if ($gender == '1') {
			$data = "<i class='fa fa-check'></i>";
		}else {
			$data = '';
		}

		if ($spm == '1') {
			$dataspm = "<i class='fa fa-check'></i>";
		}else {
			$dataspm = '';
		}

		if ($pkd == '1') {
			$datapkd = "<i class='fa fa-check'></i>";
		}else {
			$datapkd = '';
		}

		$load .= "
		<tr id='tr-subkegiatan-{$r['SUBKEGRKPDKEY']}'>
			<td><a href='javascript:void(0)' class='btn-subkegiatan-show-rincian'><u>{$r['NUSUBKEG']}</u></a></td>
			<td>{$r['NMSUBKEG']}</td>
			<td class='text-right nu2d'>{$r['PAGUTIF']}</td>
			<td class=''>{$r['TARGETSEBELUM']}</td>
			<td class=''>{$r['TARGET']}</td>
			<td>{$r['LOKASI']}</td>
			<td class='text-center'> $data</td>
			<td class='text-center'> $dataspm</td>
			<td class='text-center'> $datapkd</td>
			<td class='text-center'>{$r['TGLVALID']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-subkegiatan-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['SUBKEGRKPDKEY']}'>
					<label></label>
				</div>
			</td>
		</tr>";
		endforeach;

		$pagu = $this->m_set->getPaguOpdSummary($unitkey);

		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockSubKegiatan + '.block-pagination').html($(blockSubKegiatan + '.pagetemp').html());
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

	public function subkegiatan_form($act)
	{
		$this->load->library('form_validation');
		$is_admin = $this->sip->is_admin();

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('s-kegrkpdkey');
		$subkegrkpdkey = $this->input->post('f-subkegrkpdkey');


		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('s-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'				=> $act,
				'unitkey'			=> $unitkey,
				'kegrkpdkey'		=> $kegrkpdkey,
				'subkegrkpdkey'		=> '',
				'nusubkeg'			=> '',
				'nmsubkeg'			=> '',
				'kdsifat'			=> '',
				'pagutif'			=> '0',
				'paguplus'			=> '0',
				'targetsebelum'		=> '',
				'target'			=> '',
				'pagutifdpa'		=> '0',
				'lokasi'			=> '',
				'is_res_gender'		=>'',
				'is_spm'			=>'',
				'is_pkd'			=>'',
				'ket'				=> '',
				'tglvalid'			=> '',
				'disabled'			=> '',
				'curdShow'			=> $this->sip->curdShow('I')
			];
		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('s-kegrkpdkey', 'Kode Kegitan', 'trim|required');
			$this->form_validation->set_rules('f-subkegrkpdkey', 'Kode Sub Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				MK.NUSUBKEG,
				MK.NMSUBKEG,
				S.KDSIFAT,
				K.PAGUTIF,
				K.PAGUPLUS,
				K.PAGUTIFDPA,
				K.TARGETSEBELUM,
				K.TARGET,
				IS_RES_GENDER,
				IS_SPM,
				IS_PKD,
				K.LOKASI,
				K.KET,
				CASE WHEN K.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),K.TGLVALID,105) END AS TGLVALID
			FROM
				SUBKEGRKPD K
			JOIN MSUBKEGRKPD MK ON
					K.SUBKEGRKPDKEY = MK.SUBKEGRKPDKEY
				AND K.KEGRKPDKEY = MK.KEGRKPDKEY
				AND K.KDTAHUN = MK.KDTAHUN
			LEFT JOIN SIFATKEG S ON K.KDSIFAT = S.KDSIFAT
			WHERE
				K.KDTAHUN = ?
			AND K.KDTAHAP = ?
			AND K.UNITKEY = ?
			AND K.SUBKEGRKPDKEY = ?
			AND K.KEGRKPDKEY = ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$subkegrkpdkey,
				$kegrkpdkey,
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			 => $act,
				'unitkey'		 => $unitkey,
				'kegrkpdkey'	 => $kegrkpdkey,
				'subkegrkpdkey'	 => $subkegrkpdkey,
				'nusubkeg'		 => $r['NUSUBKEG'],
				'nmsubkeg'		 => $r['NMSUBKEG'],
				'kdsifat'		 => $r['KDSIFAT'],
				'pagutif'		 => $r['PAGUTIF'],
				'paguplus'	  	 => $r['PAGUPLUS'],
				'targetsebelum'	 => $r['TARGETSEBELUM'],
				'target'		 => $r['TARGET'],
				'pagutifdpa'	 => $r['PAGUTIFDPA'],
				//'satuan'		 => $r['SATUAN'],
				'lokasi'		 => $r['LOKASI'],
				'ket'			 => $r['KET'],
				'tglvalid'		 => $r['TGLVALID'],
				'disabled'		 => 'disabled',
				'is_res_gender'	 =>  $r['IS_RES_GENDER'],
				'is_spm'		 =>  $r['IS_SPM'],
				'is_pkd'		 =>  $r['IS_PKD'],
				'curdShow'		 => $this->sip->curdShow('U')
			];
		}

		$data['list_sifat_subkegiatan'] = $this->db->query("SELECT KDSIFAT, NMSIFAT FROM SIFATKEG")->result_array();

		if($this->json['cod'] !== NULL)
		{

			echo $this->json['msg'];

		}
		else
		{
			$this->load->view('renja/v_renja_subkegiatan_form', $data);

		}
	}

	public function subkegiatan_save($act)
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
			$this->form_validation->set_rules('v-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('v-kegrkpdkey', 'Kode Kegiatan', 'trim|required');
			$this->form_validation->set_rules('v-subkegrkpdkey', 'Kode Sub Kegiatan', 'trim|required');
			$this->form_validation->set_rules('v-kdsifat', 'Sifat Sub Kegiatan', 'trim|required');
			$this->form_validation->set_rules('v-pagutif', 'Pagu SUb Kegiatan', 'trim|required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('v-paguplus', 'Pagu Sub Kegiatan (n+1)', 'trim|required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('v-target', 'Target', 'trim|required');
			$this->form_validation->set_rules('v-lokasi', 'Lokasi', 'trim|required');
			$this->form_validation->set_rules('v-ket', 'Keterangan', 'trim|required');


			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey		= $this->sip->unitkey($this->input->post('v-unitkey'));
			$kegrkpdkey		= $this->input->post('v-kegrkpdkey');
			$subkegrkpdkey	= $this->input->post('v-subkegrkpdkey');
			$kdsifat		= $this->input->post('v-kdsifat');
			$pagutif		= $this->input->post('v-pagutif');
			$pagutifdpa		= $this->input->post('v-pagutifdpa');
			$paguplus		= $this->input->post('v-paguplus');
			$targetsebelum	= $this->input->post('v-targetsebelum');
			$target			= $this->input->post('v-target');
			//$satuan			= $this->input->post('i-satuan');
			$lokasi			= $this->input->post('v-lokasi');
			$ket			= $this->input->post('v-ket');
			$tglvalid		= $this->input->post('v-tglvalid');
			$is_res_gender 	= $this->input->post('v-isres-gender');
			$is_spm 		= $this->input->post('v-isspm');
			$is_pkd 		= $this->input->post('v-ispkd');

			if($act == 'add')
				{

					 $check_pagu_subkegiatan = $this->db->query("
					 SELECT
						NILAI AS PAGU_LIMIT,
						(
							SELECT
								 sum(pagutif)
							FROM
								SUBKEGRKPD
							WHERE
								KDTAHUN = K.KDTAHUN
							AND KDTAHAP = K.KDTAHAP
							AND UNITKEY = K.UNITKEY

						 )AS TOTAL_PENJABARAN
					FROM
						PAGUSKPD K
					WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?

							 ",
							 [$this->KDTAHUN, $this->KDTAHAP, $unitkey])->row_array();
				 if($check_pagu_subkegiatan['TOTAL_PENJABARAN'] +$pagutif <= $check_pagu_subkegiatan['PAGU_LIMIT'])
					 {

						 $set = [
								'KDTAHUN'		=> $this->KDTAHUN,
								'KDTAHAP'		=> $this->KDTAHAP,
								'UNITKEY'		=> $unitkey,
								'KEGRKPDKEY'	=> $kegrkpdkey,
								'SUBKEGRKPDKEY'	=> $subkegrkpdkey,
								'KDSIFAT'		=> $kdsifat,
								'PAGUPLUS'		=> $paguplus,
								'PAGUTIF'		=> $pagutif,
								'PAGUTIFDPA'	=> $pagutifdpa,
								'TARGETSEBELUM'	=> $targetsebelum,
								'TARGET'		=> $target,
								//'SATUAN'		=> $satuan,
								'LOKASI'		=> $lokasi,
								'IS_RES_GENDER'	=> $is_res_gender,
								'IS_SPM'		=> $is_spm,
								'IS_PKD'		=> $is_pkd,
								'KET'			=> $ket
							];

							$affected = $this->m_subkegiatan->add($set);
							if($affected !== 1)
								{
									throw new Exception('Sub Kegiatan gagal ditambahkan.', 2);
								}
								$nilai = 'Insert ' .json_encode($set);
								$nmTable = 'SUBKEGRKPD';
								$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

								$editpagu = $this->db->query( "select sum(pagutif) as pagutif, sum(paguplus) as  paguplus, sum(pagutifdpa) as pagutifdpa from SUBKEGRKPD
											where KDTAHUN 	= '{$this->KDTAHUN}'
											and KDTAHAP 	= '{$this->KDTAHAP}'
											and unitkey		= '{$unitkey}'
											and kegrkpdkey 	= '{$kegrkpdkey}'")->row_array();

											$set_keg =[
												'PAGUTIF' 		=>$editpagu['pagutif'],
												'PAGUPLUS' 		=>$editpagu	['paguplus'],
												'PAGUTIFDPA'	=>$editpagu	['pagutifdpa']
											];

											$where = [
											 'KDTAHUN'		=> $this->KDTAHUN,
					 						 'KDTAHAP'		=> $this->KDTAHAP,
					 						 'UNITKEY'		=> $unitkey,
					 						 'KEGRKPDKEY'	=> $kegrkpdkey
											];

						$update_pagu_kegitan = $this->m_kegiatan->update($where,$set_keg);

					 }else {

							 throw new Exception(
								 "PAGU OPDD = <strong>".money($check_pagu_subkegiatan['PAGU_LIMIT'])."</strong><br>".
								 "PAGU SUBKEGIATAN = <strong>".money($check_pagu_subkegiatan['TOTAL_PENJABARAN'])."</strong><br>".
								 "Nilai Selisih = <strong>".money($check_pagu_subkegiatan['PAGU_LIMIT'] - $check_pagu_subkegiatan['TOTAL_PENJABARAN'])."</strong>"
							 , 2);
						 }
				}


			elseif($act == 'edit')
			{
				if($this->sip->is_admin()):
					//$unitkey		= $this->sip->unitkey($this->input->post('f-unitkey'));
					if(empty($tglvalid)):
						$tglvalid = 'NULL';
					else:
						if($this->KDTAHAP == '4'):
							// Tahap Induk
							if(intval(substr($tglvalid, 6)) != (intval($this->KDTAHUN) - 1 + 2000)):
								throw new Exception('Tahun induk tidak sesuai', 2);
							endif;
						else:
							// Tahap Berjalan
							if(intval(substr($tglvalid,6)) != (intval($this->KDTAHUN) + 2000)):
								throw new Exception('Tahun berjalan tidak sesuai', 2);
							endif;
						endif;

						$tglvalid = "CONVERT(DATETIME,'{$tglvalid}',105)";
					endif;

					$this->db->set('TGLVALID', $tglvalid, FALSE);
				else:
					$tglvalid_now = $this->db->query("
						SELECT
							CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID
						FROM SUBKEGRKPD
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND KEGRKPDKEY = ?
						AND SUBKEGRKPDKEY = ?
					",
					[
						$this->KDTAHUN,
						$this->KDTAHAP,
						$unitkey,
						$kegrkpdkey,
						$subkegrkpdkey
					])->row_array();

					if($tglvalid_now AND $tglvalid_now['TGLVALID'] != ''):
						throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
					endif;
				endif;

				$check_pagu_edit =  $this->db->query("
						SELECT
							MK.NUSUBKEG,
							MK.NMSUBKEG,
							S.KDSIFAT,
							K.PAGUTIF as PAGU_LAMA,
							K.PAGUPLUS,
							K.TARGETSEBELUM,
							K.TARGET,
							K.PAGUTIFDPA,
							K.LOKASI,
							K.KET,
							CASE WHEN K.TGLVALID IS NULL THEN '' ELSE CONVERT(VARCHAR(10),K.TGLVALID,105) END AS TGLVALID
						FROM
							SUBKEGRKPD K
						JOIN MSUBKEGRKPD MK ON
								K.SUBKEGRKPDKEY = MK.SUBKEGRKPDKEY
							AND K.KEGRKPDKEY = MK.KEGRKPDKEY
							AND K.KDTAHUN = MK.KDTAHUN
						LEFT JOIN SIFATKEG S ON K.KDSIFAT = S.KDSIFAT
						WHERE
							K.KDTAHUN = ?
						AND K.KDTAHAP = ?
						AND K.UNITKEY = ?
						AND K.KEGRKPDKEY = ?
						AND K.SUBKEGRKPDKEY = ?
						",
						[
							$this->KDTAHUN,
							$this->KDTAHAP,
							$unitkey,
							$kegrkpdkey,
							$subkegrkpdkey

						])->row_array();


				 $check_pagu = $this->db->query("
				 SELECT
					NILAI AS PAGU_LIMIT,
					(
						SELECT
							 sum(pagutif)
						FROM
							SUBKEGRKPD
						WHERE
							KDTAHUN = K.KDTAHUN
						AND KDTAHAP = K.KDTAHAP
						AND UNITKEY = K.UNITKEY

					 )AS TOTAL_PENJABARAN
				FROM
					PAGUSKPD K
				WHERE
						KDTAHUN = ?
					AND KDTAHAP = ?
					AND UNITKEY = ?

						 ",
						 [$this->KDTAHUN, $this->KDTAHAP, $unitkey])->row_array();

					if(($check_pagu['TOTAL_PENJABARAN'] - $check_pagu_edit['PAGU_LAMA']) + $pagutif  <= $check_pagu['PAGU_LIMIT']  )
				 {
					 $set = [
						 'KDSIFAT' 	 		 =>$kdsifat,
						 'PAGUTIF'			 => $pagutif,
						 'PAGUPLUS'			 => $paguplus,
						 'PAGUTIFDPA'		 => $pagutifdpa,
						 'TARGET'			 => $target,
						 'TARGETSEBELUM'	 => $targetsebelum,
						 //'SATUAN'			 => $satuan,
						 'LOKASI'			 => $lokasi,
						 'KET'				 => $ket,
						 'IS_RES_GENDER'	 =>$is_res_gender,
						 'IS_SPM'			 =>$is_spm,
						 'IS_PKD'			 =>$is_pkd,
					 ];

					 $where = [
						 'KDTAHUN'			=> $this->KDTAHUN,
						 'KDTAHAP'			=> $this->KDTAHAP,
						 'UNITKEY'			=> $unitkey,
						 'KEGRKPDKEY'		=> $kegrkpdkey,
						 'SUBKEGRKPDKEY'	=> $subkegrkpdkey
					 ];
							$affected = $this->m_subkegiatan->update($where, $set);
							if($affected !== 1)
							{
								throw new Exception('Sub Kegiatan gagal ditambahkan.', 2);
							}


							 $nilai = 'Edit ' .json_encode($set);
							 $nmTable = 'SUBKEGRKPD';
							 $simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

							 $editpagu = $this->db->query( "select sum(pagutif) as pagutif, sum(paguplus) as  paguplus, sum(pagutifdpa) as pagutifdpa from SUBKEGRKPD
						 				where KDTAHUN = '{$this->KDTAHUN}'
						 				and KDTAHAP = '{$this->KDTAHAP}'
						 				and unitkey = '{$unitkey}'
						 				and kegrkpdkey = '{$kegrkpdkey}'")->row_array();

						 				$set_keg =[
						 					'PAGUTIF' 		=>$editpagu ['pagutif'],
						 					'PAGUPLUS' 		=>$editpagu	['paguplus'],
						 					'PAGUTIFDPA'	=>$editpagu	['pagutifdpa']
						 				];

						 				$where1 = [
						 				 'KDTAHUN'		=> $this->KDTAHUN,
						 				 'KDTAHAP'		=> $this->KDTAHAP,
						 				 'UNITKEY'		=> $unitkey,
						 				 'KEGRKPDKEY'	=> $kegrkpdkey
						 				];

							 $update_pagu_kegitan = $this->m_kegiatan->update($where1,$set_keg);

							$this->db->query("
							UPDATE SUBKINKEGRKPD SET TARGET = (SELECT ISNULL(SUM(CAST(K.PAGUTIF AS BIGINT)),0)
																						FROM SUBKEGRKPD K
																						LEFT JOIN SUBKINKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																						AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																						AND SK.KDTAHUN = K.KDTAHUN
																						AND SK.KDTAHAP = K.KDTAHAP
																						where SK.KDTAHUN = '{$this->KDTAHUN}'
																						and SK.KDTAHAP = '{$this->KDTAHAP}'
																						and SK.unitkey = '{$unitkey}'
																						AND SK.KDJKK = '01'
																						and K.subkegrkpdkey = RTRIM('{$subkegrkpdkey}') )
							WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = '{$unitkey}' AND SUBKEGRKPDKEY	= '{$subkegrkpdkey}' AND KDJKK ='01'");
							$this->db->affected_rows();
							
							$this->db->query("
							UPDATE SUBKINKEGRKPD SET TARGET = K.TARGET FROM SUBKEGRKPD K
																LEFT JOIN SUBKINKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																AND SK.KDTAHUN = K.KDTAHUN
																AND SK.KDTAHAP = K.KDTAHAP
																where SK.KDTAHUN = '{$this->KDTAHUN}'
																and SK.KDTAHAP = '{$this->KDTAHAP}'
																and SK.unitkey = '{$unitkey}'
																AND SK.KDJKK = '02'
																and K.subkegrkpdkey = RTRIM('{$subkegrkpdkey}')");
							$this->db->affected_rows();
							
														$this->db->query("
							UPDATE SUBKINKEGRKPD SET TOLOKUR = K.KET FROM SUBKEGRKPD K
																LEFT JOIN SUBKINKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																AND SK.KDTAHUN = K.KDTAHUN
																AND SK.KDTAHAP = K.KDTAHAP
																where SK.KDTAHUN = '{$this->KDTAHUN}'
																and SK.KDTAHAP = '{$this->KDTAHAP}'
																and SK.unitkey = '{$unitkey}'
																AND SK.KDJKK = '02'
																and K.subkegrkpdkey = RTRIM('{$subkegrkpdkey}')");
							$this->db->affected_rows();

							$this->db->query("
							UPDATE KINKEGRKPD SET TARGET = (SELECT ISNULL(SUM(CAST(K.PAGUTIF AS BIGINT)),0)
																						FROM SUBKEGRKPD K
																						LEFT JOIN KINKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																						AND SK.KEGRKPDKEY = K.KEGRKPDKEY
																						AND SK.KDTAHUN = K.KDTAHUN
																						AND SK.KDTAHAP = K.KDTAHAP
																						where SK.KDTAHUN = '{$this->KDTAHUN}'
																						and SK.KDTAHAP = '{$this->KDTAHAP}'
																						and SK.unitkey = '{$unitkey}'
																						AND SK.KDJKK = '01'
																						and K.kegrkpdkey = RTRIM('{$kegrkpdkey}') )
							WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = '{$unitkey}' AND KEGRKPDKEY	= '{$kegrkpdkey}' AND KDJKK ='01'");
							$this->db->affected_rows();
			 }else {

				 throw new Exception(
					 "PAGU OPD = <strong>".money($check_pagu['PAGU_LIMIT'])."</strong><br>".
					 "PAGU SUB KEGIATAN = <strong>".money($check_pagu['TOTAL_PENJABARAN'])."</strong><br>".
					 "Nilai Selisih = <strong>".money($check_pagu['PAGU_LIMIT'] - $check_pagu['TOTAL_PENJABARAN'])."</strong>"
				 , 2);
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

	public function subkegiatan_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey		= $this->sip->unitkey($this->input->post('v-unitkey'));
			$kegrkpdkey	= $this->input->post('v-kegrkpdkey');
			$subkegrkpdkey		= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM SUBKEGRKPD
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND KEGRKPDKEY = ?
			AND SUBKEGRKPDKEY IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$kegrkpdkey,
				$subkegrkpdkey
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Sub Kegiatan gagal dihapus.', 2);
			}

			$data = json_encode($kegrkpdkey);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'SUBKEGRKPD';
			$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

			$editpagu = $this->db->query( "select sum(pagutif) as pagutif, sum(paguplus) as  paguplus, sum(pagutifdpa) as pagutifdpa from SUBKEGRKPD
					 where KDTAHUN = '{$this->KDTAHUN}'
					 and KDTAHAP = '{$this->KDTAHAP}'
					 and unitkey = '{$unitkey}'
					 and kegrkpdkey = '{$kegrkpdkey}'")->row_array();

					 $set_keg =[
						 'PAGUTIF' 		=>$editpagu['pagutif'],
						 'PAGUPLUS' 		=>$editpagu	['paguplus'],
						 'PAGUTIFDPA'	=>$editpagu	['pagutifdpa']
					 ];

					 $where1 = [
						'KDTAHUN'		=> $this->KDTAHUN,
						'KDTAHAP'		=> $this->KDTAHAP,
						'UNITKEY'		=> $unitkey,
						'KEGRKPDKEY'	=> $kegrkpdkey
					 ];

		$update_pagu_kegitan = $this->m_kegiatan->update($where1,$set_keg);


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


	public function subrincian_kinerja()
	{
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$subkegrkpdkey = $this->input->post('f-subkegrkpdkey');

		$rows = $this->db->query("
		SELECT
			JK.KDJKK,
			JK.URJKK,
			K.TOLOKUR,
			K.TARGETMIN1,
			K.TARGET,
			K.TARGET1
		FROM
			SUBKINKEGRKPD K
		LEFT JOIN JKINKEG JK ON K.KDJKK = JK.KDJKK
		WHERE
			K.KDTAHUN = ?
		AND K.KDTAHAP = ?
		AND K.UNITKEY = ?
		AND K.SUBKEGRKPDKEY = ? ",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->result_array();

		foreach($rows as $r):
		$r = settrim($r);
		echo "
		<tr>
			<td>{$r['URJKK']}</td>
			<td>{$r['TOLOKUR']}</td>
			<td>{$r['TARGETMIN1']}</td>
			<td>{$r['TARGET']}</td>
			<td>{$r['TARGET1']}</td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-sub-rincian-kinerja-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['KDJKK']}'>
					<label></label>
				</div>
			</td>
		</tr>";
		endforeach;
		?>
		<script>
		$(function() {
			$(document).off('click', blockRincianKinerjaSubkegiatan + '.check-all');
			$(document).on('click', blockRincianKinerjaSubkegiatan + '.check-all', function(e) {
				var checkboxes = $(blockRincianKinerjaSubkegiatan + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});

			$(document).off('click', blockRincianKinerjaSubkegiatan + '.btn-sub-rincian-kinerja-form');
			$(document).on('click', blockRincianKinerjaSubkegiatan + '.btn-sub-rincian-kinerja-form', function(e) {
				e.preventDefault();

				if(isEmpty(getVal('#f-unitkey'))) return false;

				if(isEmpty(getVal('#f-subkegrkpdkey'))) return false;

				var act = $(this).data('act'),
					data, title, type;

				data = {
					'f-unitkey'		: getVal('#f-unitkey'),
					'f-subkegrkpdkey'	: getVal('#f-subkegrkpdkey')
				};
				console.log(data);

				if(act == 'add') {
					title = 'Tambah Sub Kinerja Kegiatan';
					type = 'type-success';
				} else if(act == 'edit') {
					title = 'Ubah Sub Kinerja Kegiatan';
					type = 'type-warning';
					data = $.extend({},
						data,
						{'f-kdjkk' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
					);
				}

				modalSubRincianKinerjaForm = new BootstrapDialog({
					title: title,
					type: type,
					size: 'size-wide',
					message: $('<div></div>').load('/renja/subrincian_kinerja_form/' + act, data)
				});
				modalSubRincianKinerjaForm.open();

				return false;
			});

			$(document).off('click', blockRincianKinerjaSubkegiatan + '.btn-sub-rincian-kinerja-delete');
			$(document).on('click', blockRincianKinerjaSubkegiatan + '.btn-sub-rincian-kinerja-delete', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-subkegrkpdkey'))) return false;
				if($(blockRincianKinerjaSubkegiatan + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
					return false;
				}
				var id = $(this).closest('tr').data('id');
				goConfirm({
					msg : 'Hapus daftar Sub kinerja yang dipilih ?',
					type: 'danger',
					callback : function(ok) {
						if(ok) {
							var data = $.extend({},
								$(blockRincianKinerjaSubkegiatan + '.form-delete').serializeObject(),
								{
									'i-unitkey'		: getVal('#f-unitkey'),
									'i-subkegrkpdkey'	: getVal('#f-subkegrkpdkey')
								}
							);
							$.post('/renja/subrincian_kinerja_delete/', data, function(res, status, xhr) {
								if(contype(xhr) == 'json') {
									respond(res);
								} else {
									dataLoadRincian('kinerja');
									dataLoadRincian('kinerjasub');
								}
							});
						}
					}
				});

				return false;
			});
		});
		</script>
		<?php
	}

	public function subrincian_kinerja_form($act)
	{
		$this->load->library('form_validation');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$subkegrkpdkey = $this->input->post('f-subkegrkpdkey');
		$kdjkk = $this->input->post('f-kdjkk');

		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-subkegrkpdkey', 'Kode Sub Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'subkegrkpdkey'	=> $subkegrkpdkey,
				'kdjkk'			=> '',
				'tolokur'		=> '',
				'targetmin1'		=> '',
				'target'		=> '',
				'target1'		=> '',
				'curdShow'		=> $this->sip->curdShow('I'),
				'list_jkinkeg'	=> $this->db->query("
					SELECT KDJKK, URJKK
					FROM JKINKEG
					WHERE
					KDJKK NOT IN (
						SELECT KDJKK FROM SUBKINKEGRKPD
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND SUBKEGRKPDKEY = ?
					)",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->result_array(),
					'capaian_program'	=> $this->db->query("
					SELECT INDIKATOR, SASARAN, TOLOKUR, PR.TARGET FROM SUBKEGRKPD SK
							LEFT JOIN KEGRKPD K
							ON K.KDTAHUN = SK.KDTAHUN
							AND K.KDTAHAP = SK.KDTAHAP
							AND K.UNITKEY = SK.UNITKEY
							AND K.KEGRKPDKEY = SK.KEGRKPDKEY
							LEFT JOIN PGRRKPD PR
							ON PR.KDTAHUN = K.KDTAHUN
							AND PR.KDTAHAP = K.KDTAHAP
							AND PR.UNITKEY = K.UNITKEY
							AND PR.PGRMRKPDKEY = K.PGRMRKPDKEY
							WHERE K.KDTAHUN = ?
							AND K.KDTAHAP = ?
							AND K.UNITKEY = ?
							AND SK.SUBKEGRKPDKEY = ?
						",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->result_array(),
						'target_subkegiatan'	=> $this->db->query("
						SELECT TARGET, PAGUTIF, KET FROM SUBKEGRKPD K
								WHERE K.KDTAHUN = ?
								AND K.KDTAHAP = ?
								AND K.UNITKEY = ?
								AND K.SUBKEGRKPDKEY = ?
							",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->result_array()

			];
		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-subkegrkpdkey', 'Kode Sub Kegiatan', 'trim|required');
			$this->form_validation->set_rules('f-kdjkk', 'kode', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				KDJKK,
				TOLOKUR,
				TARGETMIN1,
				TARGET,
				TARGET1
			FROM
				SUBKINKEGRKPD
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND SUBKEGRKPDKEY = ?
			AND KDJKK = ? ",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$subkegrkpdkey,
				$kdjkk
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'subkegrkpdkey'	=> $subkegrkpdkey,
				'kdjkk'			=> $kdjkk,
				'tolokur'		=> $r['TOLOKUR'],
				'targetmin1'		=> $r['TARGETMIN1'],
				'target'		=> $r['TARGET'],
				'target1'		=> $r['TARGET1'],
				'curdShow'		=> $this->sip->curdShow('U'),
				'list_jkinkeg'	=> $this->db->query("
					SELECT KDJKK, URJKK
					FROM JKINKEG WHERE KDJKK = ?", $kdjkk)->result_array(),
				'capaian_program'	=> $this->db->query("
				SELECT INDIKATOR, SASARAN, TOLOKUR, PR.TARGET FROM SUBKEGRKPD SK
						LEFT JOIN KEGRKPD K
						ON K.KDTAHUN = SK.KDTAHUN
						AND K.KDTAHAP = SK.KDTAHAP
						AND K.UNITKEY = SK.UNITKEY
						AND K.KEGRKPDKEY = SK.KEGRKPDKEY
						LEFT JOIN PGRRKPD PR
						ON PR.KDTAHUN = K.KDTAHUN
						AND PR.KDTAHAP = K.KDTAHAP
						AND PR.UNITKEY = K.UNITKEY
						AND PR.PGRMRKPDKEY = K.PGRMRKPDKEY
						WHERE K.KDTAHUN = ?
						AND K.KDTAHAP = ?
						AND K.UNITKEY = ?
						AND SK.SUBKEGRKPDKEY = ?
						",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->result_array(),
						'target_subkegiatan'	=> $this->db->query("
						SELECT TARGET, PAGUTIF, KET FROM SUBKEGRKPD K
								WHERE K.KDTAHUN = ?
								AND K.KDTAHAP = ?
								AND K.UNITKEY = ?
								AND K.SUBKEGRKPDKEY = ?
							",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->result_array()
			];
		}

		if($this->json['cod'] !== NULL)
		{
			echo $this->json['msg'];
		}
		else
		{
			$this->load->view('renja/v_renja_sub_rincian_kinerja_form', $data);
		}
	}

	public function subrincian_kinerja_save($act)
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
			$kdjkk		= $this->input->post('i-kdjkk');
			if ($kdjkk == "01") 
			{
				$this->form_validation->set_rules('i-targetmin1', 'Target Kinerja (n-1)', 'trim|required|numeric');
				$this->form_validation->set_rules('i-target', 'Target Kinerja n', 'trim|required|numeric');
				$this->form_validation->set_rules('i-target1', 'Target Kinerja (n+1)', 'trim|required|numeric');
			}else
			{
				$this->form_validation->set_rules('i-targetmin1', 'Target Kinerja (n-1)', 'trim|required');
				$this->form_validation->set_rules('i-target', 'Target Kinerja n', 'trim|required');
				$this->form_validation->set_rules('i-target1', 'Target Kinerja (n+1)', 'trim|required');
			}
			$this->form_validation->set_rules('i-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('i-subkegrkpdkey', 'Kode Sub Kegiatan', 'trim|required');
			$this->form_validation->set_rules('i-kdjkk', 'Indikator', 'trim|required');
			$this->form_validation->set_rules('i-tolokur', 'Tolak Ukur', 'trim|required');
			

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$subkegrkpdkey	= $this->input->post('i-subkegrkpdkey');
			
			$tolokur	= $this->input->post('i-tolokur');
			$targetmin1	= $this->input->post('i-targetmin1');
			$target		= $this->input->post('i-target');
			$target1	= $this->input->post('i-target1');

			$tglvalid = $this->db->query("SELECT CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID FROM SUBKEGRKPD WHERE KDTAHUN = ? AND KDTAHAP = ? AND UNITKEY = ? AND SUBKEGRKPDKEY = ?",
			[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->row_array();
			if($tglvalid AND $tglvalid['TGLVALID'] != ''):
				throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
			endif;

			if($act == 'add')
			{
				$set = [
					'KDTAHUN'		=> $this->KDTAHUN,
					'KDTAHAP'		=> $this->KDTAHAP,
					'UNITKEY'		=> $unitkey,
					'SUBKEGRKPDKEY'	=> $subkegrkpdkey,
					'KDJKK'			=> $kdjkk,
					'TOLOKUR'		=> $tolokur,
					'TARGETMIN1'	=> $targetmin1,
					'TARGET'		=> $target,
					'TARGET1'		=> $target1
				];

				$this->db->insert('SUBKINKEGRKPD', $set);
				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Sub Kinerja Kegiatan gagal ditambahkan.', 2);
				}

				$nilai = 'Insert ' .json_encode($set);
				$nmTable = 'SUBKINKEGRKPD';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
				if($kdjkk = "01"){

					$kegrkpdkey = $this->db->query("select * from subkegrkpd where KDTAHUN = '{$this->KDTAHUN}'	and KDTAHAP = '{$this->KDTAHAP}' and unitkey = '{$unitkey}' and subkegrkpdkey ='{$subkegrkpdkey}'")->row_array()['KEGRKPDKEY'];

					$this->db->query("
					UPDATE KINKEGRKPD SET TARGET = (SELECT ISNULL(SUM(CAST(K.TARGET AS BIGINT)),0)
																					FROM SUBKINKEGRKPD K
																				  LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																					AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																					AND SK.KDTAHUN = K.KDTAHUN
																					AND SK.KDTAHAP = K.KDTAHAP
																					where K.KDTAHUN = '{$this->KDTAHUN}'
														 							and K.KDTAHAP = '{$this->KDTAHAP}'
														 							and K.unitkey = '{$unitkey}'
														 							AND K.KDJKK = '01'
														 							and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}') ),
																					TARGET1 = (SELECT ISNULL(SUM(CAST(K.TARGET1 AS BIGINT)),0)
																					FROM SUBKINKEGRKPD K
																				  LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																					AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																					AND SK.KDTAHUN = K.KDTAHUN
																					AND SK.KDTAHAP = K.KDTAHAP
																					where K.KDTAHUN = '{$this->KDTAHUN}'
														 							and K.KDTAHAP = '{$this->KDTAHAP}'
														 							and K.unitkey = '{$unitkey}'
														 							AND K.KDJKK = '01'
														 							and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}')),
																					TARGETMIN1 = (SELECT ISNULL(SUM(CAST(K.TARGETMIN1 AS BIGINT)),0)
																					FROM SUBKINKEGRKPD K
																				  LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																					AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																					AND SK.KDTAHUN = K.KDTAHUN
																					AND SK.KDTAHAP = K.KDTAHAP
																					where K.KDTAHUN = '{$this->KDTAHUN}'
														 							and K.KDTAHAP = '{$this->KDTAHAP}'
														 							and K.unitkey = '{$unitkey}'
														 							AND K.KDJKK = '01'
														 							and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}'))
					WHERE	KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = '{$unitkey}' AND KEGRKPDKEY	= '{$kegrkpdkey}' AND KDJKK ='01'");
					$this->db->affected_rows();
					}


			}
			elseif($act == 'edit')
			{
				$this->db->query("
				UPDATE SUBKINKEGRKPD
				SET
					TOLOKUR = ?,
					TARGETMIN1	= ?,
					TARGET	= ?,
					TARGET1 = ?
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND SUBKEGRKPDKEY = ?
				AND KDJKK	= ?",
				[
					$tolokur,
					$targetmin1,
					$target,
					$target1,

					$this->KDTAHUN,
					$this->KDTAHAP,
					$unitkey,
					$subkegrkpdkey,
					$kdjkk
				]);

				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Sub Kinerja Kegiatan gagal Diganti.', 2);
				}

				$nilai = 'Edit TOLOKUR : '. $tolokur . ' TARGETMIN1	: ' . $targetmin1 . ' TARGET	: ' . $target . ' TARGET1 : ' . $target1 ;
				$nmTable = 'SUBKINKEGRKPD';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

				if($kdjkk = "01"){

					$kegrkpdkey = $this->db->query("select * from subkegrkpd where KDTAHUN = '{$this->KDTAHUN}'	and KDTAHAP = '{$this->KDTAHAP}' and unitkey = '{$unitkey}' and subkegrkpdkey ='{$subkegrkpdkey}'")->row_array()['KEGRKPDKEY'];

					$this->db->query("
					UPDATE KINKEGRKPD SET TARGET = (SELECT ISNULL(SUM(CAST(K.TARGET AS BIGINT)),0)
																					FROM SUBKINKEGRKPD K
																				  LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																					AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																					AND SK.KDTAHUN = K.KDTAHUN
																					AND SK.KDTAHAP = K.KDTAHAP
																					where K.KDTAHUN = '{$this->KDTAHUN}'
														 							and K.KDTAHAP = '{$this->KDTAHAP}'
														 							and K.unitkey = '{$unitkey}'
														 							AND K.KDJKK = '01'
														 							and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}') ),
																					TARGET1 = (SELECT ISNULL(SUM(CAST(K.TARGET1 AS BIGINT)),0)
																					FROM SUBKINKEGRKPD K
																				  LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																					AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																					AND SK.KDTAHUN = K.KDTAHUN
																					AND SK.KDTAHAP = K.KDTAHAP
																					where K.KDTAHUN = '{$this->KDTAHUN}'
														 							and K.KDTAHAP = '{$this->KDTAHAP}'
														 							and K.unitkey = '{$unitkey}'
														 							AND K.KDJKK = '01'
														 							and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}')),
																					TARGETMIN1 = (SELECT ISNULL(SUM(CAST(K.TARGETMIN1 AS BIGINT)),0)
																					FROM SUBKINKEGRKPD K
																				  LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																					AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																					AND SK.KDTAHUN = K.KDTAHUN
																					AND SK.KDTAHAP = K.KDTAHAP
																					where K.KDTAHUN = '{$this->KDTAHUN}'
														 							and K.KDTAHAP = '{$this->KDTAHAP}'
														 							and K.unitkey = '{$unitkey}'
														 							AND K.KDJKK = '01'
														 							and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}'))
					WHERE	KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = '{$unitkey}' AND KEGRKPDKEY	= '{$kegrkpdkey}' AND KDJKK ='01'");
					$this->db->affected_rows();
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

	public function subrincian_kinerja_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$subkegrkpdkey	= $this->input->post('i-subkegrkpdkey');
			$kdjkk		= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM SUBKINKEGRKPD
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND SUBKEGRKPDKEY = ?
			AND KDJKK IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$subkegrkpdkey,
				$kdjkk
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Daftar Sub kinerja gagal dihapus.', 2);
			}

			$data = json_encode($kdjkk);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'SUBKINKEGRKPD';
			$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);

			if($kdjkk = "01"){

				$kegrkpdkey = $this->db->query("select * from subkegrkpd where KDTAHUN = '{$this->KDTAHUN}'	and KDTAHAP = '{$this->KDTAHAP}' and unitkey = '{$unitkey}' and subkegrkpdkey ='{$subkegrkpdkey}'")->row_array()['KEGRKPDKEY'];

				$this->db->query("
				UPDATE KINKEGRKPD SET TARGET = (SELECT ISNULL(SUM(CAST(K.TARGET AS BIGINT)),0)
																				FROM SUBKINKEGRKPD K
																				LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																				AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																				AND SK.KDTAHUN = K.KDTAHUN
																				AND SK.KDTAHAP = K.KDTAHAP
																				where K.KDTAHUN = '{$this->KDTAHUN}'
																				and K.KDTAHAP = '{$this->KDTAHAP}'
																				and K.unitkey = '{$unitkey}'
																				AND K.KDJKK = '01'
																				and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}') ),
																				TARGET1 = (SELECT ISNULL(SUM(CAST(K.TARGET1 AS BIGINT)),0)
																				FROM SUBKINKEGRKPD K
																				LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																				AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																				AND SK.KDTAHUN = K.KDTAHUN
																				AND SK.KDTAHAP = K.KDTAHAP
																				where K.KDTAHUN = '{$this->KDTAHUN}'
																				and K.KDTAHAP = '{$this->KDTAHAP}'
																				and K.unitkey = '{$unitkey}'
																				AND K.KDJKK = '01'
																				and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}')),
																				TARGETMIN1 = (SELECT ISNULL(SUM(CAST(K.TARGETMIN1 AS BIGINT)),0)
																				FROM SUBKINKEGRKPD K
																				LEFT JOIN SUBKEGRKPD SK ON SK.UNITKEY = K.UNITKEY
																				AND SK.SUBKEGRKPDKEY = K.SUBKEGRKPDKEY
																				AND SK.KDTAHUN = K.KDTAHUN
																				AND SK.KDTAHAP = K.KDTAHAP
																				where K.KDTAHUN = '{$this->KDTAHUN}'
																				and K.KDTAHAP = '{$this->KDTAHAP}'
																				and K.unitkey = '{$unitkey}'
																				AND K.KDJKK = '01'
																				and SK.kegrkpdkey = RTRIM('{$kegrkpdkey}'))
				WHERE	KDTAHUN = '{$this->KDTAHUN}' AND KDTAHAP = '{$this->KDTAHAP}' AND UNITKEY = '{$unitkey}' AND KEGRKPDKEY	= '{$kegrkpdkey}' AND KDJKK ='01'");
				$this->db->affected_rows();
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

	public function subrincian_sumberdana()
	{
		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$kegrkpdkey = $this->input->post('f-kegrkpdkey');
		$subkegrkpdkey = $this->input->post('f-subkegrkpdkey');

		$rows = $this->db->query("
		SELECT
			D.KDDANA,
			JD.NMDANA,
			D.NILAI
		FROM
			SUBKEGRKPDDANA D
		LEFT JOIN JDANA JD ON D.KDDANA = JD.KDDANA
		WHERE
			D.KDTAHUN = ?
		AND D.KDTAHAP = ?
		AND D.UNITKEY = ?
		AND D.SUBKEGRKPDKEY = ?",
		[
			$this->KDTAHUN,
			$this->KDTAHAP,
			$unitkey,
			$subkegrkpdkey
		])->result_array();

		foreach($rows as $r):
		$r = settrim($r);
		echo "
		<tr>
			<td>{$r['KDDANA']}</td>
			<td>{$r['NMDANA']}</td>
			<td class='text-right nu2d'>{$r['NILAI']}</td>
			<td></td>
			<td class='text-center'><a href='javascript:void(0)' class='btn-sub-rincian-sumberdana-form' data-act='edit'><u>Edit</u></a></td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['KDDANA']}'>
					<label></label>
				</div>
			</td>
		</tr>";
		endforeach;
		?>
		<script>
		$(function() {
			$(document).off('click', blockRincianSumberdanaSubkegiatan + '.check-all');
			$(document).on('click', blockRincianSumberdanaSubkegiatan + '.check-all', function(e) {
				var checkboxes = $(blockRincianSumberdanaSubkegiatan + "input[name='i-check[]']:checkbox");
				checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
			});

			$(document).off('click', blockRincianSumberdanaSubkegiatan + '.btn-sub-rincian-sumberdana-form');
			$(document).on('click', blockRincianSumberdanaSubkegiatan + '.btn-sub-rincian-sumberdana-form', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-subkegrkpdkey'))) return false;
				var act = $(this).data('act'),
					data, title, type;

				data = {
					'f-unitkey'		: getVal('#f-unitkey'),
					'f-subkegrkpdkey'	: getVal('#f-subkegrkpdkey')
				};

				if(act == 'add') {
					title = 'Tambah Sumberdana Sub Kegiatan';
					type = 'type-success';
				} else if(act == 'edit') {
					title = 'Ubah Sumberdana Sub Kegiatan';
					type = 'type-warning';
					data = $.extend({},
						data,
						{'f-kddana' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()}
					);
				}

				modalSubRincianSumberdanaForm = new BootstrapDialog({
					title: title,
					type: type,
					size: 'size-wide',
					message: $('<div></div>').load('/renja/subrincian_sumberdana_form/' + act, data)
				});
				modalSubRincianSumberdanaForm.open();

				return false;
			});

			$(document).off('click', blockRincianSumberdanaSubkegiatan + '.btn-sub-rincian-sumberdana-delete');
			$(document).on('click', blockRincianSumberdanaSubkegiatan + '.btn-sub-rincian-sumberdana-delete', function(e) {
				e.preventDefault();
				if(isEmpty(getVal('#f-unitkey'))) return false;
				if(isEmpty(getVal('#f-subkegrkpdkey'))) return false;
				if($(blockRincianSumberdanaSubkegiatan + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
					return false;
				}
				var id = $(this).closest('tr').data('id');
				goConfirm({
					msg : 'Hapus daftar sumber dana yang dipilih ?',
					type: 'danger',
					callback : function(ok) {
						if(ok) {
							var data = $.extend({},
								$(blockRincianSumberdanaSubkegiatan + '.form-delete').serializeObject(),
								{
									'i-unitkey'		: getVal('#f-unitkey'),
									'i-subkegrkpdkey'	: getVal('#f-subkegrkpdkey')
								}
							);
							$.post('/renja/subrincian_sumberdana_delete/', data, function(res, status, xhr) {
								if(contype(xhr) == 'json') {
									respond(res);
								} else {
									dataLoadRincian('sumberdanasub');
								}
							});
						}
					}
				});

				return false;
			});
		});
		</script>
		<?php
	}

	public function subrincian_sumberdana_form($act)
	{
		$this->load->library('form_validation');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$subkegrkpdkey = $this->input->post('f-subkegrkpdkey');
		$kddana = $this->input->post('f-kddana');

		if($act == 'add')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-subkegrkpdkey', 'Kode Sub Kegiatan', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'subkegrkpdkey'	=> $subkegrkpdkey,
				'kddana'		=> '',
				'nilai'			=> '',
				'nilai1'		=> '',
				'curdShow'		=> $this->sip->curdShow('I'),
				'list_jdana_sub'	=> $this->db->query("
					SELECT KDDANA, NMDANA
					FROM JDANA
					WHERE TYPE = 'D'
					AND KDDANA NOT IN (
						SELECT KDDANA
						FROM SUBKEGRKPDDANA
						WHERE
							KDTAHUN = ?
						AND KDTAHAP = ?
						AND UNITKEY = ?
						AND SUBKEGRKPDKEY = ?
					)",[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->result_array()
			];
		}
		elseif($act == 'edit')
		{
			$this->form_validation->set_rules('f-unitkey', 'Kode Unit', 'trim|required');
			$this->form_validation->set_rules('f-subkegrkpdkey', 'Kode Sub Kegiatan', 'trim|required');
			$this->form_validation->set_rules('f-kddana', 'Kode Nilai', 'trim|required');
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$row = $this->db->query("
			SELECT
				KDDANA,
				NILAI,
				NILAI1
			FROM
				SUBKEGRKPDDANA
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND SUBKEGRKPDKEY = ?
			AND KDDANA = ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$subkegrkpdkey,
				$kddana
			])->row_array();

			$r = settrim($row);
			$data = [
				'act'			=> $act,
				'unitkey'		=> $unitkey,
				'subkegrkpdkey'	=> $subkegrkpdkey,
				'kddana'		=> $kddana,
				'nilai'			=> $r['NILAI'],
				'nilai1'		=> $r['NILAI1'],
				'curdShow'		=> $this->sip->curdShow('U'),
				'list_jdana_sub'	=> $this->db->query("
					SELECT KDDANA, NMDANA
					FROM JDANA
					WHERE TYPE = 'D' AND KDDANA = ?", $kddana)->result_array()
			];
		}

		if($this->json['cod'] !== NULL)
		{
			echo $this->json['msg'];
		}
		else
		{
			$this->load->view('renja/v_renja_sub_rincian_sumberdana_form', $data);
		}
	}

	public function subrincian_sumberdana_save($act)
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
			$this->form_validation->set_rules('i-kddana', 'Sumber Dana', 'trim|required');
			$this->form_validation->set_rules('i-nilai', 'Nilai n', 'trim|required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('i-nilai1', 'Nilai n+1', 'trim|required|numeric|greater_than_equal_to[0]');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$subkegrkpdkey	= $this->input->post('i-subkegrkpdkey');
			$kddana		= $this->input->post('i-kddana');
			$nilai		= $this->input->post('i-nilai');
			$nilai1		= $this->input->post('i-nilai1');

			$tglvalid = $this->db->query("SELECT CASE WHEN TGLVALID IS NULL THEN '' ELSE 'ADA' END AS TGLVALID FROM SUBKEGRKPD WHERE KDTAHUN = ? AND KDTAHAP = ? AND UNITKEY = ? AND SUBKEGRKPDKEY = ?",
			[$this->KDTAHUN, $this->KDTAHAP, $unitkey, $subkegrkpdkey])->row_array();
			if($tglvalid AND $tglvalid['TGLVALID'] != ''):
				throw new Exception('Proses gagal, tanggal valid telah di-set.', 2);
			endif;

			if($act == 'add')
			{
				$set = [
					'KDTAHUN'				=> $this->KDTAHUN,
					'KDTAHAP'				=> $this->KDTAHAP,
					'UNITKEY'				=> $unitkey,
					'SUBKEGRKPDKEY'	=> $subkegrkpdkey,
					'KDDANA'				=> $kddana,
					'NILAI'					=> $nilai,
					'NILAI1'				=> $nilai1
				];

				$this->db->insert('SUBKEGRKPDDANA', $set);
				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Sumber dana gagal ditambahkan.', 2);
				}

				$nilai = 'Insert ' .json_encode($set);
				$nmTable = 'SUBKEGRKPDDANA';
				$simpanHistory = $this->m_master->cHistory($nilai, $nmTable, $unitkey);
			}
			elseif($act == 'edit')
			{
				$this->db->query("
				UPDATE SUBKEGRKPDDANA
				SET
					NILAI	= ?,
					NILAI1	= ?
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND UNITKEY = ?
				AND SUBKEGRKPDKEY = ?
				AND KDDANA	= ?",
				[
					$nilai,
					$nilai1,

					$this->KDTAHUN,
					$this->KDTAHAP,
					$unitkey,
					$subkegrkpdkey,
					$kddana
				]);

				$affected = $this->db->affected_rows();
				if($affected !== 1)
				{
					throw new Exception('Sumber dana gagal ditambahkan.', 2);
				}

				$nilai = ' Edit NILAI : '. $nilai . ' NILAI1	: ' . $nilai1 ;
				$nmTable = 'SUBKEGRKPDDANA';
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

	public function subrincian_sumberdana_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$unitkey	= $this->sip->unitkey($this->input->post('i-unitkey'));
			$subkegrkpdkey	= $this->input->post('i-subkegrkpdkey');
			$kddana		= $this->input->post('i-check[]');

			$this->db->query("
			DELETE FROM SUBKEGRKPDDANA
			WHERE
				KDTAHUN = ?
			AND KDTAHAP = ?
			AND UNITKEY = ?
			AND SUBKEGRKPDKEY = ?
			AND KDDANA IN ?",
			[
				$this->KDTAHUN,
				$this->KDTAHAP,
				$unitkey,
				$subkegrkpdkey,
				$kddana
			]);

			$affected = $this->db->affected_rows();
			if($affected < 1)
			{
				throw new Exception('Daftar Sumber Dana gagal dihapus.', 2);
			}

			$data = json_encode($kddana);
			$nilai = 'Delete ' . $unitkey . ' ' . $data ;
			$nmTable = 'SUBKEGRKPDDANA';
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












}
