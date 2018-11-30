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
			$kegiatan = $this->db->get('tbl_member');
			foreach ($kegiatan->result() as $row) 
			{
				$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/".$row->foto_mahasiswa;	
				$data[] = $row;
			}
		}else {
			if ($sts != '') {
				$this->db->where('status_active', $sts);
				$kegiatan = $this->db->get('tbl_member');
				foreach ($kegiatan->result() as $row) 
				{
					$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/".$row->foto_mahasiswa;	
					$data[] = $row;
				}
				
			}else if ($id != ''	){ 
				$this->db->where('id_mahasiswa', $id);
				$kegiatan = $this->db->get('tbl_member');
				foreach ($kegiatan->result() as $row) 
				{
					$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/".$row->foto_mahasiswa;	
					$data[] = $row;
				}
				
			}else if ($id_kegiatan != ''){ 
				$this->db->select('*');
				$this->db->from('tbl_member m');;
				$this->db->join('tbl_kepanitiaan k', 'm.id_mahasiswa = k.id_mahasiswa');
				$this->db->join('tbl_sie_kegiatan sk', 'k.id_sie_kegiatan = sk.id_sie_kegiatan');
				$this->db->join('tbl_kegiatan kg', 'sk.id_kegiatan = kg.id_kegiatan');
				$this->db->where('sk.id_kegiatan',$id_kegiatan);
				$kegiatan = $this->db->get('tbl_member');
				foreach ($kegiatan->result() as $row) 
				{
					$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/".$row->foto_mahasiswa;	
					$data[] = $row;
				}
			}  

        }
		
        $this->response(array('member' => $data), 200);
    }
	
	//insert and update
	function index_post() {
		$role = $this->post('role');
        $id = $this->post('id');
				
        if($role=='update'){
			$uploaddir = './images/member/';
			$file_name = underscore($_FILES['foto']['name']);
			$uploadfile = $uploaddir.$file_name;
			
			if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile)) {
				$data = array(
					'nim'				=> $this->post('nim'),
					'nama_mahasiswa'	=> $this->post('nama'),
					'angkatan'			=> $this->post('angkatan'),
					'username'			=> $this->post('username'),
					'password'			=> $this->post('password'),
					'foto_mahasiswa'	=> $file_name
					);
			}
			
			$msg = "Updated";	
			$this->db->where('id_mahasiswa', $id);
			$posting = $this->db->update('tbl_member', $data);	
			
		}else if ($role=='delete'){
			$data = array(
				'status_active'	=> 0
				);
			
			$msg = "Non-Actived";
			$this->db->where('id_mahasiswa', $id);
			$posting = $this->db->update('tbl_member', $data);
		}else if($role=='active'){
			$data = array(
				'status_active'	=> 1
				);
			
			$msg = "Actived";			
			$this->db->where('id_mahasiswa', $id);
			$posting = $this->db->update('tbl_member', $data);
			
		}else if($role=='insert'){
				$data = array(
				'nim'				=> $this->post('nim'),
				'nama_mahasiswa'	=> $this->post('nama'),
				'angkatan'			=> $this->post('angkatan'),
				'username'			=> $this->post('username'),
				'password'			=> $this->post('password')
			);
			$msg = "Registered";	
			$posting = $this->db->insert('tbl_member', $data);
		}
		

		if ($posting) {
			$this->response(array('message' => $msg), 200);
		}else {
			$this->response(array('status' => 'fail'), 502);
		}
    }

}
?>