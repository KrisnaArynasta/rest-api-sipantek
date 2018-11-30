<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Member extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    function index_get() {
        $id = $this->get('id');
        $sts = $this->get('sts');
        $id_kegiatan = $this->get('id_kegiatan');
		
		//Menampilkan data kegiatan tanpa parameter id dan status
        if ($id =='' and $sts =='' and $id_kegiatan =='') {
            $kegiatan = $this->db->get('tbl_member')->result();
		}else {
			if ($sts != '') {
				$this->db->where('status_active', $sts);
				$kegiatan = $this->db->get('tbl_member')->result();
			}else if ($id != ''	){ 
				$this->db->where('id_mahasiswa', $id);
				$kegiatan = $this->db->get('tbl_member')->result();
			}else if ($id_kegiatan != ''){ 
				$this->db->select('*');
				$this->db->from('tbl_member m');;
				$this->db->join('tbl_kepanitiaan k', 'm.id_mahasiswa = k.id_mahasiswa');
				$this->db->join('tbl_sie_kegiatan sk', 'k.id_sie_kegiatan = sk.id_sie_kegiatan');
				$this->db->join('tbl_kegiatan kg', 'sk.id_kegiatan = kg.id_kegiatan');
				$this->db->where('sk.id_kegiatan',$id_kegiatan);
				$kegiatan = $this->db->get('tbl_member')->result();
			}  

        }
		
        $this->response(array('member' => $kegiatan), 200);
    }
	
	//insert
	function index_post() {
	$data = array(
				'nim'				=> $this->post('nim'),
				'nama_mahasiswa'	=> $this->post('nama'),
				'angkatan'			=> $this->post('angkatan'),
				'username'			=> $this->post('username'),
				'password'			=> $this->post('password')
			);
		$insert = $this->db->insert('tbl_member', $data);
		if ($insert) {
			$this->response($data, 200);
		} else {
			$this->response(array('status' => 'fail', 502));
		}
    }
	
	//update and delete
	function index_put() {
        $role = $this->put('role');
        $id = $this->put('id');
        $data = array(
				'nim'				=> $this->put('nim'),
				'nama_mahasiswa'	=> $this->put('nama'),
				'angkatan'			=> $this->put('angkatan'),
				'username'			=> $this->put('username'),
				'password'			=> $this->put('password')
				);
				
        if($role=='update'){
			$msg = "Updated";	
			$this->db->where('id_mahasiswa', $id);
			$update = $this->db->update('tbl_member', $data);	
			
		}else if ($role=='delete'){
			$data = array(
				'status_active'	=> 0
				);
			
			$msg = "Non-Actived";
			$this->db->where('id_mahasiswa', $id);
			$update = $this->db->update('tbl_member', $data);
		}else if($role=='active'){
			$data = array(
				'status_active'	=> 1
				);
			
			$msg = "Actived";			
			$this->db->where('id_mahasiswa', $id);
			$update = $this->db->update('tbl_member', $data);
		}
		
        if ($update) {
            $this->response($msg, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
?>