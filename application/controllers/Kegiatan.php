<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kegiatan extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
		$this->load->Model('Auth');
    }

    function index_get(){

		$token=$this->Auth->cek_login();
		if ($token == 1){ // cek login

			$id = $this->get('id');
			$sts = $this->get('sts');
			
			//Menampilkan data kegiatan tanpa parameter id dan status
			if ($id == '' and $sts == '') {
				$kegiatan = $this->db->get('tbl_kegiatan');
					foreach ($kegiatan->result() as $row) 
					{
						$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;	
						$data[] = $row;
					}	
			}else {
				if ($sts != '') { //Menampilkan data kegiatan berdasarkan status
					$this->db->where('status', $sts);
					$kegiatan = $this->db->get('tbl_kegiatan');
					foreach ($kegiatan->result() as $row) 
					{
						$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;	
						$data[] = $row;
					}
				}else if ($id != ''	){ 
					//Menampilkan data kegiatan berdasarkan id
					$this->db->where('id_kegiatan', $id);
					$kegiatan = $this->db->get('tbl_kegiatan');
					foreach ($kegiatan->result() as $row) 
					{
						$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;	
						$data[] = $row;
					}
				} 

			}
			
			$this->response(array('kegiatan' => $data), 200);
		}else{ // gagal login
			$this->response(array('Message' => 'Fail Auth'), 502);
		}
    }
	
	//insert update delete
	function index_post() {
		$token=$this->Auth->cek_login();
		if ($token == 1){
			$role = $this->post('role');
			$id = $this->post('id');
			
			$data = array(
				'nama_kegiatan'		=> $this->post('nama'),
				'tgl_kegiatan'		=> $this->post('tgl_kegiatan'),
				'tgl_rapat_perdana'	=> $this->post('tgl_rapat_perdana'),
				'deskripsi'			=> $this->post('des')
			);

			if($role=='update'){
				$uploaddir = './images/';
				$file_name = underscore($_FILES['foto']['name']);
				$uploadfile = $uploaddir.$file_name;

				if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile)) {
					$data = array(
						'nama_kegiatan'		=> $this->post('nama'),
						'tgl_kegiatan'		=> $this->post('tgl_kegiatan'),
						'tgl_rapat_perdana'	=> $this->post('tgl_rapat_perdana'),
						'deskripsi'			=> $this->post('des'),
						'foto_kegiatan'		=> $file_name
					);
				}
				$this->db->where('id_kegiatan', $id);
				$posting = $this->db->update('tbl_kegiatan', $data);			
			}else if ($role=='delete'){
				$data = array(
					'status'	=> 0
					);
				$this->db->where('id_kegiatan', $id);
				$posting = $this->db->update('tbl_kegiatan', $data);
			}else if($role=='active'){
				$data = array(
					'status'	=> 1
					);
				$this->db->where('id_kegiatan', $id);
				$posting = $this->db->update('tbl_kegiatan', $data);
			}else if($role=='insert'){
				$uploaddir = './images/';
				$file_name = underscore($_FILES['foto']['name']);
				$uploadfile = $uploaddir.$file_name;

				if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile)) {
					$data = array(
						'nama_kegiatan'		=> $this->post('nama'),
						'tgl_kegiatan'		=> $this->post('tgl_kegiatan'),
						'tgl_rapat_perdana'	=> $this->post('tgl_rapat_perdana'),
						'deskripsi'			=> $this->post('des'),
						'foto_kegiatan'		=> $file_name
					);
					$posting = $this->db->insert('tbl_kegiatan', $data);
					$this->db->select('id_kegiatan');
					$this->db->order_by('id_kegiatan', 'DESC');
					$this->db->limit(1);
					$kegiatan = $this->db->get('tbl_kegiatan');
					
					foreach ($kegiatan->result() as $row) 
						{
							$data = $row;
						}
					}
			}
			
			if ($posting) {
				$this->response($data, 200);
			} else { 
				$this->response(array('status' => 'fail', 502));
			}

		}else{
			$this->response(array('Message' => 'Fail Auth'), 502);
		}
    }
	

}
?>