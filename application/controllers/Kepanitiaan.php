<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kepanitiaan extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
		$this->load->Model('Auth');
    }

    function index_get() {
		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login		
			$id = $this->get('id');
			$id_mahasiswa = $this->get('id_mahasiswa');
			$sts_kegiatan = $this->get('sts_kegiatan');
			$id_kegiatan = $this->get('id_kegiatan');
			$id_sie_kegiatan = $this->get('id_sie_kegiatan');
			$sts = $this->get('sts');
			
			//Menampilkan data kegiatan tanpa parameter id dan status
			if ($id == '' and $sts == '' and $id_mahasiswa == '' and $id_kegiatan == '' and $id_sie_kegiatan == '') {
				$kegiatan = $this->db->get('tbl_kepanitiaan')->result();
			}else {
				if ($sts != '') { 
					$this->db->where('status_panitia', $sts);
					$kegiatan = $this->db->get('tbl_kepanitiaan')->result();
				}else if ($id != ''	){ 
					$this->db->where('id_kepanitiaan', $id);
					$kegiatan = $this->db->get('tbl_kepanitiaan')->result();
				}else if ($id_mahasiswa != '' && $sts_kegiatan != ''){ 
					$where = array(
						'id_mahasiswa' => $id_mahasiswa,
						'status' => $sts_kegiatan
					);
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_kegiatan k', 'sk.id_kegiatan = k.id_kegiatan');
					$this->db->where($where);
					$kegiatan = $this->db->get()->result();
				}else if ($id_kegiatan != ''){ 
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_kegiatan k', 'sk.id_kegiatan = k.id_kegiatan');
					$this->db->where('sk.id_kegiatan',$id_kegiatan);
					$kegiatan = $this->db->get()->result();
				}else if ($id_sie_kegiatan != ''){ 
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_member m', 'p.id_mahasiswa = m.id_mahasiswa');
					$this->db->where('p.id_sie_kegiatan',$id_sie_kegiatan);
					$kegiatan = $this->db->get()->result();
				} 

			}
			
			$this->response(array('kepanitiaan' => $kegiatan), 200);
		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 502);
		}
	}
	
	//insert
	function index_post() {
		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login		
			$data = array(
						'id_mahasiswa'		=> $this->post('id_mahasiswa'),
						'id_sie_kegiatan'	=> $this->post('id_sie_kegiatan'),
						'tgl_daftar'		=> $this->post('tgl_daftar'),
						'alasan'			=> $this->post('alasan')
					);
				$insert = $this->db->insert('tbl_kepanitiaan', $data);
				if ($insert) {
					$this->response($data, 200);
				} else {
					$this->response(array('status' => 'fail', 502));
				}
		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 502);
		}	
    }
	
	//update and delete
	function index_put() {
		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login	
			$role = $this->put('role');
			$id = $this->put('id');
			$data = array(
					'id_mahasiswa'		=> $this->put('id_mahasiswa'),
					'id_sie_kegiatan'	=> $this->put('id_sie_kegiatan'),
					'tgl_daftar'		=> $this->put('tgl_daftar'),
					'alasan'			=> $this->put('alasan')
					);
					
			if($role=='update'){
				$msg = "Updated";	
				$this->db->where('id_kepanitiaan', $id);
				$update = $this->db->update('tbl_kepanitiaan', $data);	
				
			}else if ($role=='delete'){
				$data = array(
					'status_panitia'	=> 0
					);
				
				$msg = "Non-Actived";
				$this->db->where('id_kepanitiaan', $id);
				$update = $this->db->update('tbl_kepanitiaan', $data);
			}else if($role=='active'){
				$data = array(
					'status_panitia'	=> 1
					);
				
				$msg = "Actived";			
				$this->db->where('id_kepanitiaan', $id);
				$update = $this->db->update('tbl_kepanitiaan', $data);
			}
			
			if ($update) {
				$this->response($msg, 200);
			} else {
				$this->response(array('status' => 'fail', 502));
			}
		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 502);
		}
	}
	
}
?>