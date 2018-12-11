<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Login extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }
	
	//login
	function index_post() {
		$data = array(
					'username'		=> $this->post('username'),
					'password'		=> $this->post('password'),
					'status_active'		=> 1
				);
			$this->db->where($data);
			$login = $this->db->get('tbl_member');
			
			if ($login->num_rows() > 0){
				$token = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTU1234567890"),0,100);
				foreach ($login->result() as $row) 
				$data_login = array (
						'id_mahasiswa' => $row->id_mahasiswa,
						'nim' => $row->nim,
						'nama_mahasiswa' => $row->nama_mahasiswa,
						'angkatan' => $row->angkatan,
						'username' => $row->username,
						'foto_mahasiswa' => $row->foto_mahasiswa,
						'status_active' => $row->status_active,
						'role' => $row->role,
						'login_token' => $token
						);
				$this->db->where($data);
				$token = $this->db->update('tbl_member', $data_login);
			} 
			
			if ($token) {
				$this->response($data_login, 200);
			} else {
				$this->response(array('status' => 'fail', 502));
			}
	}
}
?>