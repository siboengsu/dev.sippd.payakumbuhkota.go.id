<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Subkegiatan_a extends CI_Controller {
    function __construct()
	{
        $this->load->model('M_subkegiatan_a');
	}

    public function getSubkegiatan()
    {
        $data['row'] = $this->M_subkegiatan_a->get();
        $this->view->load('master/v_entry_program');
    }
}