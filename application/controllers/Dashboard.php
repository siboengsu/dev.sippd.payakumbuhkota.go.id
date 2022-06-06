<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];

	private $KDTAHUN = NULL;
	private $KDTAHAP = NULL;

	private $app_hspk = 'http://dev.payakumbuhkota.go.id/';
	private $app_musrenbang = 'http://dev.musrenbang.payakumbuhkota.go.id/';
	private $app_epokir = 'http://epokir.payakumbuhkota.go.id/';

	private $thn_hspk = 2018;
	private $thn_musrenbang = 20;
	private $thn_epokir = 20;

	function __construct()
	{
		parent::__construct();

		$this->sip->is_logged();

		$this->KDTAHUN = $this->session->KDTAHUN;
		$this->KDTAHAP = $this->session->KDTAHAP;

		$this->thn_hspk = ((int)$this->KDTAHUN + 2000 - 1);
		$this->thn_musrenbang = ((int)$this->KDTAHUN - 1);
		$this->thn_epokir = ((int)$this->KDTAHUN - 1);
		$this->load->model(['m_set', 'm_user']);
	}

	public function index()
	{
		$sql = "
		SELECT
			R.ID_MENU,
			R.ID_PARE,
			R.NMMENU,
			R.TIPE
		FROM
			PHPWEBROLE R
			JOIN PHPWEBOTOR O ON R.ID_MENU = O.ID_MENU
		WHERE
			O.GROUPID = ?
			AND (
				R.KDTAHAP IS NULL
				OR
				R.KDTAHAP = ?
			)
		ORDER BY R.ID_MENU ASC";

		$data['menu'] = $this->db->query($sql, [$this->session->GROUPID, $this->session->KDTAHAP])->result_array();
		$data['tahun'] = $this->m_set->getTahun();
		echo $this->session->flashdata('msg');
		$this->load->view('v_dashboard', $data);
	}
	
	public function rubahtahun()
	{
		try
		{
			$this->db->trans_start();
			$ip = $this->input->ip_address();
			$userid = $this->session->USERID;
			$data['setid'] = $this->input->post('setid');
			$tahun = $data['setid'];
			
			$user = $this->m_user->login($tahun, $userid)->row_array();
			$user = settrim($user);

			$set = [
				'ID_MENU'	=> explode('#', $user['ID_MENU']),
				'KDTAHUN'	=> $user['KDTAHUN'],
				'NMTAHUN'	=> $user['NMTAHUN']
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

	public function hspk($id_kegdet)
	{
		$data['hspk_kode'] = $id_kegdet;
		$kd = explode('.', $data['hspk_kode']);

		$data['api_detail'] = "{$this->app_hspk}service/Servicepyk/detail/{$this->thn_hspk}/{$kd['0']}/{$kd['1']}/{$kd['2']}";

		$this->load->view('v_public_hspk', $data);
	}

	public function import($app = '')
	{
		if($this->sip->is_admin())
		{
			if($app == 'musrenbang'):
				$this->_import_musrenbang($this->app_musrenbang . "api/usulan/{$this->thn_musrenbang}/");
			elseif($app == 'pokir'):
				$this->_import_pokir($this->app_epokir . "api/usulan/");
			endif;
		}
	}

	public function _import_musrenbang($url)
	{
		try
		{
			$this->db->trans_begin();

			if(get_headers($url)[0] != 'HTTP/1.1 200 OK')
			{
				throw new Exception('Musrenbang Server Offline.');
			}

			$json = file_get_contents($url);
			$json = iconv("UTF-8", "ISO-8859-1//IGNORE", $json);
			$json = utf8_encode($json);
			$hspk = json_decode($json, TRUE);

			//print_r($hspk);
			$set = [];
			$set_update = [];

				foreach($hspk as $k => $v):
					$TIPE		= 2;
					$UNITKEY	= $v['UNITKEY'];
					$KEGRKPDKEY	= $v['KEGRKPDKEY'];
					$SUBKEGRKPDKEY	= $v['SUBKEGRKPDKEY'];
					$ID_USUL	= $v['ID_USUL'];
					$KDHSPK3	= $v['KDHSPK3'];
					$NMPEKERJAAN = $v['NMPEKERJAAN'];
					$JNPEKERJAAN = $v['JNPEKERJAAN'];
					$KECAMATAN	= $v['KECAMATAN'];
					$KELURAHAN	= $v['KELURAHAN'];
					$LOKASI		= $v['LOKASI'];
					$KETERANGAN = $v['KETERANGAN'];
					$SATUAN		= $v['SATUAN'];
					$VOLUME		= floatval($v['VOLUME']);
					$HARGA		= floatval($v['HARGA']);
					$TOTAL		= floatval($v['TOTAL']);

					$check = (int) $this->db->query("
					SELECT COUNT(ID_USUL) AS TOTAL
					FROM TB_IMPORT_MUSRENBANG
					WHERE
						KDTAHUN = ?
					", [$this->KDTAHUN])->row_array()['TOTAL'];

					if($check < 1):
						$set[] = [
							'UNITKEY' => $this->KDTAHUN,
							'KEGRKPDKEY' => $this->KDTAHAP,
							'SUBKEGRKPD' => $UNITKEY,
							'ID_USUL' => $KEGRKPDKEY,
							'KDHSPK3' =>$KDHSPK3,
							'NMPEKERJAAN' => $NMPEKERJAAN,
							'JNPEKERJAAN' => $JNPEKERJAAN,
							'KECAMATAN' => $KECAMATAN,
							'KELURAHAN' => $KELURAHAN,
							'LOKASI' => $LOKASI,
							'KETERANGAN' => $KETERANGAN,
							'SATUAN' => $SATUAN,
							'VOLUME' => $VOLUME,
							'HARGA' => $HARGA,
							'TOTAL' => $TOTAL,
							'KDTAHUN' => $this->KDTAHUN
						];
					//	print_r($set);
					else:
						$set_update[] = [];
					endif;
					endforeach;


			if( ! empty($set))
			{
			//	print_r($set);
			//	$this->db->insert_batch('TB_IMPORT_MUSRENBANG1', $set);
			}
			if( ! empty($set_update))
			{
				//$this->db->update_batch('PHPKEGHSPK', $set_update, "KDTAHUN+'|'+KDSSH");
			}

			$this->db->trans_commit();
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			echo $e->getMessage();
			echo '<br>';
		}
	}

	public function _import_pokir($url)
	{
		try
		{
			$this->db->trans_begin();

			if(get_headers($url)[0] != 'HTTP/1.1 200 OK')
			{
				throw new Exception('e-pokir Server Offline.');
			}

			$json = file_get_contents($url);
			$json = iconv("UTF-8", "ISO-8859-1//IGNORE", $json);
			$json = utf8_encode($json);
			$hspk = json_decode($json, TRUE);

			$set = [];
			$set_update = [];

			foreach($hspk as $k => $v):
				$TIPE		= 3;
				$UNITKEY	= $v[0];
				$KEGRKPDKEY	= $v[1];
				$ID_USUL	= $v[2];
				$KDHSPK3	= $v[3];
				$NMPEKERJAAN = $v[4];
				$JNPEKERJAAN = $v[5];
				$KECAMATAN	= $v[6];
				$KELURAHAN	= $v[7];
				$LOKASI		= $v[8];
				$KETERANGAN = $v[9];
				$SATUAN		= $v[10];
				$VOLUME		= floatval($v[11]);
				$HARGA		= floatval($v[12]);
				$TOTAL		= floatval($v[13]);

				$check = (int) $this->db->query("
				SELECT COUNT(ID_KEGHSPK) AS TOTAL
				FROM PHPKEGHSPK
				WHERE
					KDTAHUN = ?
				AND KDTAHAP = ?
				AND TIPE = ?
				AND REF_ID_USUL = ?
				", [$this->KDTAHUN, $this->KDTAHAP, $TIPE, $ID_USUL])->row_array()['TOTAL'];

				if($check < 1):
					$set[] = [
						'KDTAHUN' => $this->KDTAHUN,
						'KDTAHAP' => $this->KDTAHAP,
						'UNITKEY' => $UNITKEY,
						'KEGRKPDKEY' => $KEGRKPDKEY,

						'TIPE' => $TIPE,

						'KDHSPK3' => $KDHSPK3,
						'NMPEKERJAAN' => $NMPEKERJAAN,
						'JNPEKERJAAN' => $JNPEKERJAAN,
						'KECAMATAN' => $KECAMATAN,
						'KELURAHAN' => $KELURAHAN,
						'LOKASI' => $LOKASI,
						'KETERANGAN' => $KETERANGAN,
						'VOLUME' => $VOLUME,
						'SATUAN' => $SATUAN,
						'HARGA' => $HARGA,
						'TOTAL' => $TOTAL,

						'REF_ID_USUL' => $ID_USUL,
						'REF_KDHSPK3' => $KDHSPK3,
						'REF_NMPEKERJAAN' => $NMPEKERJAAN,
						'REF_JNPEKERJAAN' => $JNPEKERJAAN,
						'REF_KECAMATAN' => $KECAMATAN,
						'REF_KELURAHAN' => $KELURAHAN,
						'REF_LOKASI' => $LOKASI,
						'REF_KETERANGAN' => $KETERANGAN,
						'REF_VOLUME' => $VOLUME,
						'REF_SATUAN' => $SATUAN,
						'REF_HARGA' => $HARGA,
						'REF_TOTAL' => $TOTAL
					];
				else:
					$set_update[] = [];
				endif;
			endforeach;

			if( ! empty($set))
			{
				$this->db->insert_batch('PHPKEGHSPK', $set);
			}
			if( ! empty($set_update))
			{
				//$this->db->update_batch('PHPKEGHSPK', $set_update, "KDTAHUN+'|'+KDSSH");
			}

			$this->db->trans_commit();
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			echo $e->getMessage();
			echo '<br>';
		}
	}

	public function update()
	{
		if($this->sip->is_admin())
		{
			$this->_update_ssh($this->app_hspk . "service/Servicepyk/ssh/{$this->thn_hspk}/");
			$this->_update_hspk2($this->app_hspk . "service/Servicepyk/pekerjaan/{$this->thn_hspk}/");
			$this->_update_hspk3($this->app_hspk . "service/Servicepyk/jenis_pekerjaan/{$this->thn_hspk}/");
		}
	}

	public function _update_ssh($url)
	{
		try
		{
			$this->db->trans_begin();

			if(get_headers($url)[0] != 'HTTP/1.1 200 OK')
			{
				throw new Exception('HSPK Server Offline. (Nama Pekerjaan)');
			}

			$json = file_get_contents($url);
			$json = iconv("UTF-8", "ISO-8859-1//IGNORE", $json);
			$json = utf8_encode($json);
			$hspk = json_decode($json, TRUE);

			$set = [];
			$set_update = [];

			foreach($hspk as $k => $v):
				$kdssh = $v[1];
				$kdrek = $v[2];
				$ssh_nama = $v[3];
				$ssh_spek = $v[4];
				$ssh_satuan = $v[5];
				$ssh_harga = floatval($v[6]);

				$check = (int) $this->db->query("SELECT COUNT(KDSSH) AS TOTAL FROM PHPSSH WHERE KDTAHUN = '{$this->KDTAHUN}' AND KDSSH = '{$kdssh}'")->row_array()['TOTAL'];

				if($check < 1):
					$set[] = [
						'KDTAHUN' => $this->KDTAHUN,
						'KDSSH' => $kdssh,
						'KDREK' => $kdrek,
						'SSH_NAMA' => $ssh_nama,
						'SSH_SATUAN' => $ssh_satuan,
						'SSH_SPEK' => $ssh_spek,
						'SSH_HARGA' => $ssh_harga
					];
				else:
					$set_update[] = [
						"KDTAHUN+'|'+KDSSH" => $this->KDTAHUN .'|'. $kdssh,
						'KDREK' => $kdrek,
						'SSH_NAMA' => $ssh_nama,
						'SSH_SATUAN' => $ssh_satuan,
						'SSH_SPEK' => $ssh_spek,
						'SSH_HARGA' => $ssh_harga
					];
				endif;
			endforeach;

			if( ! empty($set))
			{
				$this->db->insert_batch('PHPSSH', $set);
			}
			if( ! empty($set_update))
			{
				$this->db->update_batch('PHPSSH', $set_update, "KDTAHUN+'|'+KDSSH");
			}

			$this->db->trans_commit();
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			echo $e->getMessage();
			echo '<br>';
		}
	}

	public function _update_hspk2($url)
	{
		try
		{
			$this->db->trans_begin();

			if(get_headers($url)[0] != 'HTTP/1.1 200 OK')
			{
				throw new Exception('HSPK Server Offline. (Nama Pekerjaan)');
			}

			$json = file_get_contents($url);
			$json = iconv("UTF-8", "ISO-8859-1//IGNORE", $json);
			$json = utf8_encode($json);
			$hspk = json_decode($json, TRUE);

			$set = [];
			$set_update = [];

			foreach($hspk as $k => $v):
				$kdhspk2 = $v['Kd_hspk1'].'.'.$v['Kd_hspk2'];
				$hspk2_nama = $v['Nm_hspk2'];

				$check = (int) $this->db->query("SELECT COUNT(KDHSPK2) AS TOTAL FROM PHPHSPK2 WHERE KDTAHUN = ? AND KDHSPK2 = ?", [$this->KDTAHUN, $kdhspk2])->row_array()['TOTAL'];

				if($check < 1):
					$set[] = [
						'KDTAHUN' => $this->KDTAHUN,
						'KDHSPK2' => $kdhspk2,
						'HSPK2_NAMA' => $hspk2_nama
					];
				else:
					$set_update[] = [
						"KDTAHUN+'|'+KDHSPK2" => $this->KDTAHUN .'|'. $kdhspk2,
						'HSPK2_NAMA' => $hspk2_nama
					];
				endif;
			endforeach;

			if( ! empty($set))
			{
				$this->db->insert_batch('PHPHSPK2', $set);
			}
			if( ! empty($set_update))
			{
				$this->db->update_batch('PHPHSPK2', $set_update, "KDTAHUN+'|'+KDHSPK2");
			}

			$this->db->trans_commit();
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			echo $e->getMessage();
			echo '<br>';
		}
	}

	public function _update_hspk3($url)
	{
		try
		{
			$this->db->trans_begin();

			if(get_headers($url)[0] != 'HTTP/1.1 200 OK')
			{
				throw new Exception('HSPK Server Offline. (Jenis Pekerjaan)');
			}

			$json = file_get_contents($url);
			$json = iconv("UTF-8", "ISO-8859-1//IGNORE", $json);
			$json = utf8_encode($json);
			$hspk = json_decode($json, TRUE);

			$set = [];
			$set_update = [];

			foreach($hspk as $k => $v):
				$kdhspk2 = $v['kd1'].'.'.$v['kd2'];
				$kdhspk3 = $v['kd1'].'.'.$v['kd2'].'.'.$v['kd3'];
				$hspk3_nama = $v['nm'];
				$hspk3_satuan = $v['st'];
				$hspk3_harga = $v['hsp'];

				$check = (int) $this->db->query("SELECT COUNT(KDHSPK3) AS TOTAL FROM PHPHSPK3 WHERE KDTAHUN = ? AND KDHSPK3 = ?", [$this->KDTAHUN, $kdhspk3])->row_array()['TOTAL'];

				if($check < 1):
					$set[] = [
						'KDTAHUN' => $this->KDTAHUN,
						'KDHSPK2' => $kdhspk2,
						'KDHSPK3' => $kdhspk3,
						'HSPK3_NAMA' => $hspk3_nama,
						'HSPK3_SATUAN' => $hspk3_satuan,
						'HSPK3_HARGA' => $hspk3_harga
					];
				else:
					$set_update[] = [
						"KDTAHUN+'|'+KDHSPK3" => $this->KDTAHUN .'|'. $kdhspk3,
						'KDHSPK2' => $kdhspk2,
						'KDHSPK3' => $kdhspk3,
						'HSPK3_NAMA' => $hspk3_nama,
						'HSPK3_HARGA' => $hspk3_harga,
					];
				endif;
			endforeach;

			if( ! empty($set))
			{
				$this->db->insert_batch('PHPHSPK3', $set);
			}
			if( ! empty($set_update))
			{
				$this->db->update_batch('PHPHSPK3', $set_update, "KDTAHUN+'|'+KDHSPK3");
			}

			$this->db->trans_commit();
		}
		catch (Exception $e)
		{
			$this->db->trans_rollback();
			echo $e->getMessage();
			echo '<br>';
		}
	}
}
