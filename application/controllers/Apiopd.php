<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
    
class Apiopd extends REST_Controller {
    
    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //get data
    function opd_get() 
    {
        $id = $this->get('NIP');
        if ($id == '') {
            $pegawai = $this->db->get('PEGAWAI')->result();
        } else {
            $this->db->where('NIP', $id);
            $pegawai = $this->db->get('PEGAWAI')->result();
        }
        $this->response($pegawai);
    }

    function opd_put() {
        $unitkey = $this->put('UNITKEY');
        $datap = array(
            'NIP'       => $this->put('NIP'),
            'KDGOL'     => $this->put('KDGOL'),
            'UNITKEY'   => $this->put('UNITKEY'),
            'NAMA'      => $this->put('NAMA'),
            'ALAMAT'    => $this->put('ALAMAT'),
            'JABATAN'   => $this->put('JABATAN'),
            'PDDK'      => $this->put('PDDK'),
        );
        $this->db->where('UNITKEY', $unitkey);
        $update = $this->db->update('PEGAWAI', $datap);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }

        $dataa = array(
            'NIP'       => $this->put('NIP'),
            'UNITKEY'   => $this->put('UNITKEY'),
        );
        $this->db->where('UNITKEY', $unitkey);
        $update = $this->db->update('ATASBEND', $dataa);
    }
}
