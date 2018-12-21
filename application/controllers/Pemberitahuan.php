<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pemberitahuan extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
		$this->load->Model('Auth');
    }

    function index_get() {
		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login		
			$id = $this->get('id');
				
			$this->db->where('id_mahasiswa', $id);
			$pemberitahuan = $this->db->get('tbl_pemberitahuan')->result();
			$this->response(array('pemberitahuan' => $pemberitahuan), 200);

		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 502);
		}
	}
	
}
?>