<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class SieKegiatan extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
		$this->load->Model('Auth');
    }

    
    function index_get() {
		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login		
			$id = $this->get('id');
			$id_kegiatan = $this->get('kegiatan');
			
			//Menampilkan data sie kegiatan tanpa parameter 
			if ($id_kegiatan == '' and $id == '') {
				$kegiatan = $this->db->get('tbl_sie_kegiatan')->result();
				$this->response(array('sie_kegiatan' => $kegiatan), 200);
			}elseif($id != ''){
				$this->db->where('id_sie_kegiatan', $id);
				$kegiatan = $this->db->get('tbl_sie_kegiatan');
				foreach ($kegiatan->result() as $row) 
					{
						$data = $row;
					}
					
				$this->response($data, 200);
			}else {
				//Menampilkan data sie kegiatan berdasarakan kegiatan
				$this->db->where('id_kegiatan', $id_kegiatan);
				$kegiatan = $this->db->get('tbl_sie_kegiatan')->result();
				$this->response(array('sie_kegiatan' => $kegiatan), 200);
			}
		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 200);
		}
	}
	
	//insert
	function index_post() {
		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login		
			$role = $this->post('role');
			if($role=="insert"){
				$data = array(
							'id_kegiatan'		=> $this->post('id_kegiatan'),
							'sie'				=> $this->post('sie'),
							'job_desc'			=> $this->post('job'),
							'kuota'				=> $this->post('kuota'),
							'nama_koor'			=> $this->post('koor'),
							'id_line_koor'		=> $this->post('line')
						);
					$insert = $this->db->insert('tbl_sie_kegiatan', $data);
					if ($insert) {
						$this->response($data, 200);
					} else {
						$this->response(array('status' => 'fail', 502));
					}
			}else{
				$id=$this->post('id');
				$this->db->where('id_sie_kegiatan', $id);
				$delete = $this->db->delete('tbl_sie_kegiatan');
				if ($delete){
					$this->response(array('status' => "deleted"), 200);
				}else{
					$this->response(array('status' => 'fail'), 502);
				}
			}
		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 200);
		}	
	}
	
	//update
	function index_put() {
		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login			
			$id = $this->put('id');
			$data = array(
					'sie'				=> $this->put('sie'),
					'job_desc'			=> $this->put('job'),
					'kuota'				=> $this->put('kuota'),
					'nama_koor'			=> $this->put('koor'),
					'id_line_koor'		=> $this->put('line')
					);
					
			$this->db->where('id_sie_kegiatan', $id);
			$update = $this->db->update('tbl_sie_kegiatan', $data);			

			if ($update) {
				$this->response($data, 200);
			} else {
				$this->response(array('status' => 'fail', 502));
			}
 		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 200);
		}	   
	}
}
?>