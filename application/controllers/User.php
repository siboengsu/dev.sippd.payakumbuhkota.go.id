<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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

	public function account_form()
	{
		?>
		<div class="row">
			<div class="col-md-12">
				<form id="form-pass">
				<div class="form-group">
					<label>Password Lama</label>
					<input type="password" name="i-phppwd_old" class="form-control">
				</div>

				<div class="form-group">
					<label>Password Baru</label>
					<input type="password" name="i-phppwd_new" class="form-control">
				</div>

				<div class="form-group">
					<label>Password Baru (Konfirmasi)</label>
					<input type="password" name="i-phppwd_conf" class="form-control">
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-md btn-success btn-block"><i class="fa fa-download"></i> Update Password</button>
				</div>
				</form>
			</div>
		</div>

		<script>
		$(function() {
			$('#form-pass').submit(function(e) {
				e.preventDefault();
				$.post('/user/account_update/', $('#form-pass').serialize(), function(res, status, xhr) {
					if(contype(xhr) == 'json') {
						respond(res);
					} else {
						modalAccount.close();
					}
				});

				return false;
			});
		});
		</script>
		<?php
	}

	public function account_update()
	{
		$this->load->library('form_validation');

		try
		{
			$this->db->trans_start();

			$this->form_validation->set_rules('i-phppwd_old', 'Password lama', [
				'trim',
				'required',
				[
					'i-phppwd_old_callable',
					function($str)
					{
						$row = $this->db->query("SELECT PHPPWD FROM WEBUSER WHERE USERID = ?",$this->session->USERID)->row();
						if(password_verify($str, $row->PHPPWD))
						{
							return TRUE;
						}

						$this->form_validation->set_message('i-phppwd_old_callable', 'Password lama tidak sesuai');
						return FALSE;
					}
				]
			]);
			$this->form_validation->set_rules('i-phppwd_new', 'Password baru', 'trim|required');
			$this->form_validation->set_rules('i-phppwd_conf', 'Konfirmasi password baru', 'trim|required|matches[i-phppwd_new]');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$phppwd = password_hash($this->input->post('i-phppwd_new'), PASSWORD_DEFAULT, ['cost' => 8]);

			$this->db->query("UPDATE WEBUSER SET PHPPWD = ? WHERE USERID = ?", [$phppwd, $this->session->USERID]);

			$this->db->trans_commit();
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

	public function tahap()
	{
		$this->sip->is_menu('0101');
		$data['tahap'] = $this->m_set->getTahap();
		$this->load->view('tahap/v_tahap', $data);
	}

	public function tahap_save()
	{
		$this->sip->is_menu('0101');

		$this->load->library('form_validation');

		try
		{
			$this->db->trans_start();

			$userid = $this->input->post('i-userid');
			$kdtahap = $this->input->post('i-kdtahap');

			$this->form_validation->set_rules('i-userid', 'User Id', 'trim|required');
			$this->form_validation->set_rules('i-kdtahap', 'Kode Tahap', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$set = ['KDTAHAP' => $kdtahap];

			$affected = $this->m_user->update($userid, $set);
			if($affected !== 1)
			{
				throw new Exception('Update gagal.', 2);
			}

			$row = $this->db->query("
			SELECT
				NMTAHAP,
				(
					STUFF((
						SELECT
							'#' + O.ID_MENU
						FROM
							PHPWEBOTOR O
							JOIN PHPWEBROLE R ON O.ID_MENU = R.ID_MENU
						WHERE
							O.GROUPID = ?
						AND R.TIPE = 'D'
						AND (R.KDTAHAP = ? OR R.KDTAHAP IS NULL)
						FOR XML PATH('')
					), 1, 1, '' )
				) AS ID_MENU
			FROM TAHAP
			WHERE KDTAHAP = '{$kdtahap}'", [$this->session->GROUPID, $kdtahap])->row_array();

			$set = [
				'ID_MENU' => explode('#', $row['ID_MENU']),
				'KDTAHAP' => $kdtahap,
				'NMTAHAP' => $row['NMTAHAP']
			];

			$this->session->set_userdata($set);

			$this->db->trans_commit();

			$this->json['cod'] = 0;
			$this->json['msg'] = 'Tahap berhasil diubah.';
			$this->json['link'] = site_url('dashboard');
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

	public function urusan()
	{
		$this->sip->is_menu('0201');

		$data['urusan'] = $this->urusan_load(1, TRUE);

		$this->load->view('urusan/v_urusan', $data);
	}

	public function urusan_load($page = 1, $first = FALSE)
	{
		$this->sip->is_menu('0201');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$filter = '';

		if($search_key)
		{
			$search_type = ($search_type == '1') ? 'D.KDUNIT' : 'D.NMUNIT';
			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$user = $this->db->query("
		SELECT
			S.URUSKEY,
			D.KDUNIT,
			D.NMUNIT
		FROM
			URUSANUNIT S
			LEFT JOIN DAFTUNIT D ON S.URUSKEY = D.UNITKEY
		WHERE
			S.UNITKEY = '{$unitkey}'
			{$filter}
		ORDER BY S.URUSKEY
		")->result_array();

		$load = '';
		foreach($user as $u):
		$u = settrim($u);
		$load .= "
		<tr>
			<td class='text-center'>{$u['KDUNIT']}</td>
			<td>{$u['NMUNIT']}</td>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$u['URUSKEY']}'>
					<label></label>
				</div>
			</td>
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

	public function urusan_form()
	{
		$this->sip->is_menu('0201');

		$data['urusan'] = $this->urusan_form_load(1, TRUE);
		$data['unitkey'] = $this->input->post('f-unitkey');

		$this->load->view('urusan/v_urusan_form', $data);
	}

	public function urusan_form_load($page = 1, $first = FALSE)
	{
		$this->sip->is_menu('0201');

		$per_page = 10;

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$filter = "
			AND D.KDLEVEL = '2'
			AND D.UNITKEY NOT IN (
				SELECT URUSKEY
				FROM URUSANUNIT
				WHERE UNITKEY = '{$unitkey}'
			)
		";

		if($search_key)
		{
			$search_type = ($search_type == '1') ? 'D.KDUNIT' : 'D.NMUNIT';
			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$total = $this->m_set->getUrusan($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$user = $this->m_set->getUrusan($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($user) < 1):
		$page--;
		$user = $this->m_set->getUrusan($filter, [$per_page, $page])->result_array();
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
		<tr>
			<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$u['UNITKEY']}'>
					<label></label>
				</div>
			</td>
			<td class='text-center'>{$u['KDUNIT']}</td>
			<td>{$u['NMUNIT']}</td>
		</tr>";
		endforeach;

		$load .= "
		<tr class='hidden'><td class='pagetemp'>{$this->pagination->create_links()}</td></tr>
		<script>
		$(function() {
			$(blockUrusanForm + '.block-pagination').html($(blockUrusanForm + '.pagetemp').html());
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

	public function urusan_save($act)
	{
		$this->sip->is_menu('0201');

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
			$this->db->trans_start();

			if($act == 'add')
			{
				$this->form_validation->set_rules('i-check[]', 'Urusan', 'trim|required');
			}
			elseif($act == 'delete')
			{
				$this->form_validation->set_rules('i-check[]', 'Urusan', 'trim|required');
			}

			$this->form_validation->set_rules('f-unitkey', 'Unit', 'trim|required');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));

			if($act == 'add')
			{
				$list_uruskey = $this->input->post('i-check[]');

				$set = [];
				foreach($list_uruskey as $uruskey):
				$set[] = [
					'UNITKEY' => $unitkey,
					'URUSKEY' => $uruskey
				];
				endforeach;

				$list_uruskey = implode("','", $list_uruskey);

				if( ! empty($set))
				{
					$this->db->query("DELETE FROM URUSANUNIT WHERE URUSKEY IN ('{$list_uruskey}') AND UNITKEY = ?", $unitkey);
					$this->db->insert_batch('URUSANUNIT', $set);
				}
			}
			elseif($act == 'delete')
			{
				$list_uruskey = $this->input->post('i-check[]');

				$list_uruskey = implode("','", $list_uruskey);

				$this->db->query("DELETE FROM URUSANUNIT WHERE URUSKEY IN ('{$list_uruskey}') AND UNITKEY = ?", $unitkey);
				$this->db->affected_rows();
			}

			$this->db->trans_commit();
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

	public function password_reset()
	{
		$this->sip->is_menu('0003');
		$this->load->view('akses/v_password_reset');
	}

	public function password_reset_save()
	{
		$this->load->library('form_validation');

		try
		{
			$this->db->trans_start();

			$this->form_validation->set_rules('i-userid', 'User Id', 'trim|required');
			$this->form_validation->set_rules('i-phppwd_new', 'Password baru', 'trim|required');
			$this->form_validation->set_rules('i-phppwd_conf', 'Konfirmasi password baru', 'trim|required|matches[i-phppwd_new]');

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$userid = $this->input->post('i-userid');
			$phppwd = password_hash($this->input->post('i-phppwd_new'), PASSWORD_DEFAULT, ['cost' => 8]);

			$this->db->query("UPDATE WEBUSER SET PHPPWD = ? WHERE USERID = ?", [$phppwd, $userid]);

			$this->db->trans_commit();
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

	public function akses()
	{
		$this->sip->is_menu('0001');
		$data['akses'] = $this->akses_load(1, TRUE);
		$this->load->view('akses/v_akses', $data);
	}
	//ari
	public function userindex()
	{
		$this->sip->is_menu('0008');
		$this->load->view('user/v_entry_user');
	}

	public function user_load($page = 1, $first = FALSE){
		$per_page = 12;
	
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');
	
		$filter = "";
	
		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'NAMA' OR 'NMUNIT'; break;
				case '2' : $search_type = 'USERID'; break;
				case '3' : $search_type = 'NIP'; break;
			}
			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}
	
		$total = $this->m_user->getuser($filter, NULL, TRUE)->row_array()['TOTAL_ROW'];
		$rows = $this->m_user->getuser($filter, [$per_page, $page])->result_array();
		while ($page > 1 AND count($rows) < 1):
		$page--;
		$rows = $this->m_user->getuser($filter, [$per_page, $page])->result_array();
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
		$iduser = null;
		$nmnit = null;
		foreach($rows as $r):
		$r = settrim($r);
		$iduser = $r['USERID'];
		$nmnit = $r['NMUNIT'];
		if ($nmnit == '')
		{
			$nmnit = $r['NAMA'];
		}
		?>
			<tr id="tr-user-<?php echo $r['USERID']; ?>">
			<td><?php echo $nmnit; ?></td>
			<td><?php echo $r['USERID']; ?></td>
			<td><?php echo $r['NIP']; ?></td>
			<td class="text-center"><a href="javascript:void(0)" class="btn-user-tambah" data-act="edit"><u>Edit</u></a></td>
			<td class="text-center">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" name="i-check[]" value="<?php echo $r['USERID']; ?>">
					<label></label>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr class="hidden"><td class="pagetemp"><?php echo $this->pagination->create_links(); ?></td></tr>
		<script>
		$(function() {
			$(blockuser + '.block-pagination').html($(blockuser + '.pagetemp').html());
		});
		$(function() {
			$(document).off('click', blockuser + '.check-all');
			$(document).on('click', blockuser + '.check-all', function(e) {
				var checkboxes = $(blockuser + "input[name='i-check[]']:checkbox");
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

	public function user_form($act)
	{

		$this->load->library('form_validation');
		$is_admin 	= $this->sip->is_admin();
		$userid 	= $this->input->post('i-userid');
		if($act == 'add')
		{
	
			if($this->form_validation->run() == FALSE)
			{
				$this->json['cod'] = 2;
				$this->json['msg'] = custom_errors(validation_errors());
			}

			$data = [
				'act'					=> $act,
				'userid'				=>'',
				'kdtahap'				=>4,
				'nip'					=>NULL,
				'unitkey'				=>'',
				'pwd'					=>'ÔŒÙ',
				'nama'					=>NULL,
				'blokid'				=>NULL,
				'transecure'			=>1,
				'stinsert'				=>1,
				'stupdate'				=>1,
				'stdelete'				=>1,
				'ket'					=>'',
				'groupid'				=>'',	
				'curdShow'				=> $this->sip->curdShow('I')
			];
		}

		elseif($act == 'edit')
		{

				$row = $this->db->query("
				SELECT
					*
					FROM WEBUSER P
					WHERE
						USERID = ?",			
				[
					$userid

				])->row_array();

				$r = settrim($row);

					$data = [
						'act'					=> $act,
						'userid'				=> $userid,
						'kdtahap'				=> $r['KDTAHAP'],
						'nip'					=> $r['NIP'],
						'unitkey'				=> $r['UNITKEY'],
						'pwd'					=> $r['PWD'],
						'nama'					=> $r['NAMA'],
						'blokid'				=> $r['BLOKID'],
						'transecure'			=> $r['TRANSECURE'],
						'stinsert'				=> $r['STINSERT'],
						'stupdate'				=> $r['STUPDATE'],
						'stdelete'				=> $r['STDELETE'],
						'ket'					=> $r['KET'],
						'groupid'				=> $r['GROUPID'],	 
						'curdShow'				=> $this->sip->curdShow('U')
				];
		}
		$this->load->view('user/v_entry_user_form', $data);
	}

	public function user_save($act)
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
			$this->form_validation->set_rules('i-userid', 'Username', 'trim|required');
			$this->form_validation->set_rules('i-phppwd', 'Password', 'trim|required');
			$this->form_validation->set_rules('i-passcon', 'Konfirmasi Password', 'required|matches[i-phppwd]', array('matches'	=> '%s tidak sesuai dengan password'));

			$userid				= $this->input->post('i-userid');
			$nmpengguna			= $this->input->post('i-nama');
			$unitkey 			= $this->input->post('f-unitkey');
			$groupid			= $this->input->post('f-groupid');
			$password			= $this->input->post('i-phppwd');
			
			if ($groupid !== "31_"){
				$unitkey = NULL;
				$this->form_validation->set_rules('i-nama', 'Nama', 'trim|required');
			}else{
				$nmpengguna = NULL;
				$this->form_validation->set_rules('f-unitkey', 'Perangkat Daerah', 'trim|required');
			}

			if($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			if($act == 'add')
			{
				$userid				= $this->input->post('i-userid');
				$set = [
					'USERID'	=>$userid,
					'KDTAHAP'	=>$this->KDTAHAP,
					'NIP'		=>NULL,
					'UNITKEY'	=>$unitkey, 
					'PWD'		=>'ÔŒÙ',
					'NAMA'		=>$nmpengguna,
					'BLOKID'	=>NULL,
					'TRANSECURE'=>1,
					'STINSERT'	=>1,
					'STUPDATE'	=>1,
					'STDELETE'	=>1,
					'KET'		=>NULL,
					'GROUPID'	=>$groupid,
					'PHPPWD'	=>password_hash($password, PASSWORD_BCRYPT),
				];

				$affected = $this->m_user->adduser($set);
			}

			elseif($act == 'edit')
			{
				$userid1		= $this->input->post('i-userid1');
				$set = [
					'USERID'	=>$userid1,
					'NIP'		=>NULL,
					'UNITKEY'	=>$unitkey,
					'PWD'		=>'ÔŒÙ',
					'NAMA'		=>$nmpengguna,
					'BLOKID'	=>NULL,
					'TRANSECURE'=>1,
					'STINSERT'	=>1,
					'STUPDATE'	=>1,
					'STDELETE'	=>1,
					'KET'		=>NULL,
					'GROUPID'	=>$groupid,
					'PHPPWD'	=>password_hash($password, PASSWORD_BCRYPT),
				];

				$where = [
					'KDTAHAP'			=> $this->KDTAHAP,
					'USERID'			=> $userid,
				];

				$affected = $this->m_user->updateuser($where, $set);

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

	public function user_delete()
	{
		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{

			$userid	= $this->input->post('i-check[]');
			$this->db->query("
			DELETE FROM WEBUSER
			WHERE
				KDTAHAP = ?
			AND USERID IN ?",
			[
				$this->KDTAHAP,
				$userid
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

	public function akses_load($page = 1, $first = FALSE)
	{
		$this->sip->is_menu('0001');

		$groupid		= $this->input->post('f-groupid');
		$search_type	= $this->input->post('f-search_type');
		$search_key		= $this->input->post('f-search_key');

		$filter = "AND O.GROUPID = '{$groupid}' ";

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'R.GROUPID'; break;
				case '2' : $search_type = 'R.NMMENU'; break;
			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$rows = $this->db->query("
		SELECT
			ROW_NUMBER() OVER(ORDER BY O.ID_MENU) AS ROWNUM,
			O.GROUPID,
			R.ID_MENU,
			R.NMMENU,
			R.TIPE,
			ISNULL(T.NMTAHAP, 'Semua Tahap') AS NMTAHAP
		FROM
			PHPWEBOTOR O
			JOIN PHPWEBROLE R ON O.ID_MENU = R.ID_MENU
			LEFT JOIN TAHAP T ON R.KDTAHAP = T.KDTAHAP
		WHERE
			1 = 1
			{$filter}
		")->result_array();

		$load = '';
		foreach($rows as $r):
		$r = settrim($r);

		$r['PUSH'] =
			(strlen($r['ID_MENU']) == 2) ? 0 : (
				(strlen($r['ID_MENU']) == 4) ? 2 : (
					(strlen($r['ID_MENU']) == 6) ? 4 : 0
				)
			);

		$bold = ($r['TIPE'] == 'H') ? 'text-bold' : '';

		$load .= "
		<tr>
			<td class='{$bold}'>{$r['ID_MENU']}</td>
				<td class='{$bold}'>". str_repeat('&emsp;', $r['PUSH']) ."{$r['NMMENU']}</td>
				<td class='text-center {$bold}'>{$r['TIPE']}</td>
				<td class='text-center'>{$r['NMTAHAP']}</td>
				<td class='text-center'>
				<div class='checkbox checkbox-inline'>
					<input type='checkbox' name='i-check[]' value='{$r['ID_MENU']}'>
					<label></label>
				</div>
			</td>
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

	public function akses_form()
	{
		$this->sip->is_menu('0001');

		$groupid = $this->input->post('f-groupid');

		$row = $this->db->query("
		SELECT
			R.ID_MENU,
			R.ID_PARE,
			R.NMMENU,
			R.TIPE,
			(CASE WHEN (O.ID_MENU IS NOT NULL) THEN 'Y' ELSE NULL END) AS ADA
		FROM
			PHPWEBROLE R
			LEFT JOIN PHPWEBOTOR O ON R.ID_MENU = O.ID_MENU AND O.GROUPID = ?
		ORDER BY R.ID_MENU ASC", $groupid)->result_array();

		$data_tree = [];
		$data_tree[] = [
			'id'		=> '##',
			'parent'	=> '#',
			'text'		=> '<strong>SIPPD</strong>',
			'type'		=> 'root',
			'state'		=> [
				'opened' => (bool) TRUE
			]
		];

		foreach($row as $r)
		{
			$r = settrim($r);
			$selected = ($r['ADA'] == 'Y') ? (($r['TIPE'] == 'D') ? TRUE : FALSE) : FALSE;
			#$opened = FALSE;
			#$disabled = FALSE;

			$data_tree[] = [
				"id"		=> (string) $r['ID_MENU'],
				"parent"	=> (string) (($r['ID_PARE'] == '') ? '##' : $r['ID_PARE']),
				"text"		=> (string) (($r['TIPE'] == 'H') ? "<strong>{$r['NMMENU']}</strong>" : $r['NMMENU']),
				"type"		=> (string) $r['TIPE'],
				"state"		=> [
					#"opened"	=> (bool) $opened,
					#"disabled"	=> (bool) $disabled,
					"selected"	=> (bool) $selected
				]
			];
		}

		?>
		<div class="row">
			<div class="col-md-12">
				<form class="form-akses">
					<div class="pre-scrollable" style="max-height:90%;">
						<div id="tree_ha" style="padding:10px;"></div>
					</div>
				</form>
				<br>
			</div>

			<div class="col-md-12 text-right">
				<button type="button" class="btn btn-success btn-akses-save <?php $this->sip->curdShow('I'); ?>"><i class="fa fa-download"></i> Simpan</button>
			</div>
		</div>
		<script>
		$(function() {
			$('#tree_ha').jstree({
				'core' : {
					'themes'	: { 'name' : 'default-dark' },
					'data'		: <?php echo json_encode($data_tree); ?>
				},
				'checkbox' : {
					'keep_selected_style' : false
				},
				'types' : {
					'default'	: { 'icon' : 'fa-folder-o' },
					'root'		: { 'icon' : 'fa fa-desktop' },
					'H'			: { 'icon' : 'fa fa-file-text-o' },
					'D'			: { 'icon' : 'fa fa-file' }
				},
				'plugins'		: [ 'checkbox', 'types', 'themes', 'changed' ]
			});

			$('.btn-akses-save').on('click', function(e) {
				e.preventDefault();

				if(isEmpty(getVal('#f-groupid'))) return false;

				var selected = $('#tree_ha').jstree().get_selected(), i, j;
				for(i = 0, j = selected.length; i < j; i++) {
					selected = selected.concat($('#tree_ha').jstree().get_node(selected[i]).parents);
				}
				selected = $.vakata.array_unique(selected);
				selected = jQuery.grep(selected, function( n, i ) {
					return ( n != '#' && n != '##' );
				});
				selected = selected.join('|');
				var count = selected.length;
				if(count == 0) {
					return false;
				}

				var data = $.extend({},
					$('.form-akses').serializeObject(),
					{
						'i-groupid' : getVal('#f-groupid'),
						'i-id_menu' : selected
					}
				);

				$.post('/user/akses_save', data, function(res) {
					dataLoad();
					modalAksesForm.close();
				});

				return false;
			});
		});
		</script>
		<?php
	}

	public function akses_save()
	{
		$this->sip->is_menu('0001');

		$this->sip->is_curd('I');
		$this->sip->is_curd('U');

		$this->load->library('form_validation');

		try
		{
			$groupid = $this->input->post('i-groupid');
			$id_menus = $this->input->post('i-id_menu[]');
			$id_menus = explode("|", $id_menus);

			$this->db->query("DELETE FROM PHPWEBOTOR WHERE GROUPID = ?", $groupid);
			foreach($id_menus as $id_menu)
			{
				$this->db->query("INSERT INTO PHPWEBOTOR (GROUPID, ID_MENU) VALUES (?, ?)", [$groupid, $id_menu]);
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

	public function akses_delete()
	{
		$this->sip->is_menu('0001');

		$this->sip->is_curd('D');

		$this->load->library('form_validation');

		try
		{
			$groupid = $this->input->post('i-groupid');
			$id_menus = $this->input->post('i-check[]');

			foreach($id_menus AS $id_menu)
			{
				$this->db->query("DELETE FROM PHPWEBOTOR WHERE GROUPID = ? AND ID_MENU LIKE '{$id_menu}%'", $groupid);
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

	public function verif_rka()
	{
		$this->sip->is_menu('0002');
		$data['verif'] = $this->verif_rka_load(1, TRUE);
		$this->load->view('verif/v_verif_rka', $data);
	}

	public function verif_rka_load($page = 1, $first = FALSE)
	{
		$this->sip->is_menu('0002');

		$unitkey = $this->sip->unitkey($this->input->post('f-unitkey'));
		$search_type = $this->input->post('f-search_type');
		$search_key = $this->input->post('f-search_key');

		$filter = '';

		if($search_key)
		{
			switch($search_type)
			{
				case '1' : $search_type = 'MP.NMPRGRM'; break;
				case '2' : $search_type = 'MK.NMKEG'; break;
				case '3' : $search_type = 'MR.KDPER'; break;
				case '4' : $search_type = 'DR.URAIAN'; break;
			}

			$filter .= " AND {$search_type} LIKE '%{$search_key}%'";
		}

		$rows = $this->db->query("
		SELECT
			MK.PGRMRKPDKEY,
			DR.KEGRKPDKEY,
			DR.MTGKEY,

			(MP.NUPRGRM + ' - ' + MP.NMPRGRM) AS NMPRGRM,
			(MK.NUKEG + ' - ' + MK.NMKEG) AS NMKEG,
			(MR.KDPER + ' - ' + MR.NMPER) AS NMPER,

			DR.KDJABAR,
			DR.URAIAN,
			DR.JUMBYEK,
			DR.SATUAN,
			DR.TARIF,
			DR.SUBTOTAL
		FROM
			PRARASKDETR DR
			LEFT JOIN PRARASKR R ON
				DR.KDTAHUN = R.KDTAHUN
				AND DR.KDTAHAP = R.KDTAHAP
				AND DR.UNITKEY = R.UNITKEY
				AND DR.KEGRKPDKEY = R.KEGRKPDKEY
				AND DR.MTGKEY = R.MTGKEY
			LEFT JOIN MKEGRKPD MK ON
				DR.KEGRKPDKEY = MK.KEGRKPDKEY
				AND DR.KDTAHUN = MK.KDTAHUN
			LEFT JOIN MPGRMRKPD MP ON
				MK.PGRMRKPDKEY = MP.PGRMRKPDKEY
				AND MK.KDTAHUN = MP.KDTAHUN
			LEFT JOIN MATANGR MR ON
				DR.MTGKEY = MR.MTGKEY AND DR.KDTAHUN = MR.KDTAHUN
		WHERE
			DR.KDTAHUN = ?
		AND	DR.KDTAHAP = ?
		AND	DR.UNITKEY = ?
		AND DR.TYPE = 'D'
		AND (
			DR.KDSSH = ''
			OR
			DR.KDSSH IS NULL
		)
		{$filter}
		ORDER BY
			MK.PGRMRKPDKEY,
			DR.KEGRKPDKEY,
			DR.MTGKEY,
			DR.KDJABAR
		",
		[$this->KDTAHUN, $this->KDTAHAP, $unitkey])->result_array();

		$spgr = '';
		$skeg = '';
		$sreg = '';
		$load = '';
		foreach($rows as $r):
		$r = settrim($r);

		if($spgr != $r['PGRMRKPDKEY']):
			$load .= "<tr style='background:#CCCCCC'><td colspan='9'>{$r['NMPRGRM']}</td></tr>";
			$spgr = $r['PGRMRKPDKEY'];
		endif;

		if($skeg != $r['KEGRKPDKEY']):
			$load .= "<tr><td></td><td style='background:#DDDDDD' colspan='8'>{$r['NMKEG']}</td></tr>";
			$skeg = $r['KEGRKPDKEY'];
		endif;

		if($sreg != $r['MTGKEY']):
			$load .= "<tr><td colspan='2'></td><td style='background:#EEEEEE' colspan='7'>{$r['NMPER']}</td></tr>";
			$sreg = $r['MTGKEY'];
		endif;

		$load .= "
		<tr>
			<td colspan='3'></td>
			<td>{$r['KDJABAR']}</td>
			<td>{$r['URAIAN']}</td>
			<td class='text-right nu2d'>{$r['JUMBYEK']}</td>
			<td class='text-center'>{$r['SATUAN']}</td>
			<td class='text-right nu2d'>{$r['TARIF']}</td>
			<td class='text-right nu2d'>{$r['SUBTOTAL']}</td>
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
}
