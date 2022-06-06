<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	private $json = ['cod' => NULL, 'msg' => NULL, 'link' => NULL];

	function __construct()
	{
		parent::__construct();

		$this->load->model(['m_set', 'm_user']);
	}

	public function index()
	{
		$data['tahun'] = $this->m_set->getTahun();

		$this->load->view('v_home', $data);
	}

	public function login()
	{
		$this->load->library('form_validation');

		try
		{
			$this->db->trans_start();

			$this->form_validation->set_rules('i-userid', 'User Id', 'trim|required');
			$this->form_validation->set_rules('i-phppwd', 'Password', 'trim|required');
			$this->form_validation->set_rules('i-tahun', 'Tahun', 'trim|required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new Exception(custom_errors(validation_errors()), 2);
			}

			$ip = $this->input->ip_address();
			$userid = $this->input->post('i-userid');
			$phppwd = $this->input->post('i-phppwd');
			$tahun = $this->input->post('i-tahun');

			$user = $this->m_user->login($tahun, $userid)->row_array();
			$user = settrim($user);	

			if(empty($user))
			{
				throw new Exception("User Id dan Password tidak sesuai", 2);
			}

			if(!password_verify($phppwd, $user['PHPPWD']))
			{
				throw new Exception("User Id dan Password tidak sesuai", 2);
			}

			$set = [
				'ID_MENU'	=> explode('#', $user['ID_MENU']),
				'KDTAHUN'	=> $user['KDTAHUN'],
				'NMTAHUN'	=> $user['NMTAHUN'],
				'KDTAHAP'	=> $user['KDTAHAP'],
				'NMTAHAP'	=> $user['NMTAHAP'],
				'UNITKEY'	=> $user['UNITKEY'],
				'KDUNIT'	=> $user['KDUNIT'],
				'NMUNIT'	=> $user['NMUNIT'],
				'USERID'	=> $user['USERID'],
				'GROUPID'	=> $user['GROUPID'],
				'NMGROUP'	=> $user['NMGROUP'],
				'NAMA'		=> $user['NAMA'],
				'STINSERT'	=> $user['STINSERT'],
				'STUPDATE'	=> $user['STUPDATE'],
				'STDELETE'	=> $user['STDELETE'],
				'TRANSECURE' => $user['TRANSECURE']
			];

			$this->session->set_userdata($set);

			$this->db->trans_commit();

			$this->json['cod'] = 1;
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

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}
}
