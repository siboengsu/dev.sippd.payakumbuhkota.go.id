<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sip {

	protected $CI;
	
	public $user_level = [
		7 => ['dev', 'Dev'],
		6 => ['admin', 'Admin'],
		5 => ['bappeda', 'Bappeda'],
		4 => ['', ''],
		3 => ['opd', 'OPD'],
		2 => ['kecamatan', 'Kecamatan'],
		1 => ['kelurahan', 'Kelurahan'],
		0 => ['home', 'Public']
	];
	
	public $status = [
		0 => 'Belum Dikirim',
		1 => 'Diproses Kecamatan',
		2 => 'Ditolak Kecamatan',
		3 => 'Dipilih Kecamatan',
		4 => 'Diproses OPD',
		5 => 'Ditolak OPD',
		6 => 'Diterima OPD'
	];
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	public function is_menu($id_menu)
	{
		if(!in_array($id_menu, $this->CI->session->ID_MENU))
		{
			if($this->CI->input->is_ajax_request())
			{
				header('Content-Type: application/json');
				echo json_encode([
					'cod' => 0, 
					'msg' => 'Anda tidak memiliki akses halaman ini.', 
					'link' => base_url()
				]);
			}
			else
			{
				redirect('/');
			}
			
			exit;
		}
	}
	
	public function is_logged()
	{
		$id_user = ($this->CI->session->USERID) ? TRUE : FALSE;
		
		if($id_user === FALSE)
		{
			if($this->CI->input->is_ajax_request())
			{
				header('Content-Type: application/json');
				echo json_encode([
					'cod' => 0, 
					'msg' => 'Anda tidak memiliki sesi login.', 
					'link' => base_url()
				]);
			}
			else
			{
				redirect('/');
			}
			
			exit;
		}
	}
	
	public function is_lock()
	{
		$is_lock = ($this->CI->session->is_lock) ? $this->CI->session->is_lock : FALSE;
		
		if($is_lock === '1')
		{
			if($this->CI->input->is_ajax_request())
			{
				header('Content-Type: application/json');
				echo json_encode([
					'cod' => 2, 
					'msg' => 'Anda tidak memiliki hak akses proses.', 
					'link' => 'warning'
				]);
			}
			else
			{
				echo 'Anda tidak memiliki hak akses proses.';
			}
			
			exit;
		}
	}
	
	public function is_level_allowed($level)
	{
		return (in_array($this->CI->session->user_level, $level));
	}
	
	public function unitkey($i)
	{
		return ($this->is_admin()) ? $i : $this->CI->session->UNITKEY;
	}

	public function is_admin()
	{
		if(empty($this->CI->session->UNITKEY))
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	public function is_curd($k)
	{
		$sess = 0;
		switch($k)
		{
			case 'I': $sess = $this->CI->session->STINSERT; break;
			case 'U': $sess = $this->CI->session->STUPDATE; break;
			case 'D': $sess = $this->CI->session->STDELETE; break;
		}
		
		if($sess !== '1')
		{
			if($this->CI->input->is_ajax_request())
			{
				header('Content-Type: application/json');
				echo json_encode([
					'cod' => 2, 
					'msg' => 'Anda tidak memiliki hak akses proses.', 
					'link' => 'warning'
				]);
			}
			else
			{
				echo 'Anda tidak memiliki hak akses proses.';
			}
			
			exit;
		}
	}

	public function curdShow($k)
	{
		$sess = 0;
		switch($k)
		{
			case 'I': $sess = $this->CI->session->STINSERT; break;
			case 'U': $sess = $this->CI->session->STUPDATE; break;
			case 'D': $sess =$this->CI->session->STDELETE; break;
		}
		
		echo ($sess === '1') ? '' : 'hidden';
	}
}