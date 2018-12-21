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
				$this->db->select('*');
				$this->db->from('tbl_kepanitiaan p');
				$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
				$this->db->join('tbl_kegiatan k', 'sk.id_kegiatan = k.id_kegiatan');
				$kegiatan = $this->db->get('tbl_kepanitiaan');
				foreach ($kegiatan->result() as $row) 
					{
						$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;
						$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/member/".$row->foto_mahasiswa;						
						$data[] = $row;
					}
			}else {
				if ($sts != '') { 
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_kegiatan k', 'sk.id_kegiatan = k.id_kegiatan');
					$this->db->where('status', $sts);
					$kegiatan = $this->db->get('tbl_kepanitiaan');
					foreach ($kegiatan->result() as $row) 
						{
							$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;
							$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/member/".$row->foto_mahasiswa;							
							$data[] = $row;
						}
				}else if ($id != ''	){ 					
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_kegiatan k', 'sk.id_kegiatan = k.id_kegiatan');
					$this->db->where('p.id_kepanitiaan', $id);
					$kegiatan = $this->db->get('tbl_kepanitiaan');
					foreach ($kegiatan->result() as $row) 
						{
							$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;	
							$data[] = $row;
						}
				}else if ($id_mahasiswa != '' && $sts_kegiatan != ''){ 
					$where = array(
						'p.id_mahasiswa' => $id_mahasiswa,
						'status' => $sts_kegiatan
					);
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_kegiatan k', 'sk.id_kegiatan = k.id_kegiatan');
					$this->db->where($where);
					$kegiatan = $this->db->get('tbl_kepanitiaan');
					foreach ($kegiatan->result() as $row) 
						{
							$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;
							$data[] = $row;
						}

				}else if ($id_kegiatan != ''){ 
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_kegiatan k', 'sk.id_kegiatan = k.id_kegiatan');
					$this->db->where('sk.id_kegiatan',$id_kegiatan);
					$kegiatan = $this->db->get('tbl_kepanitiaan');
					foreach ($kegiatan->result() as $row) 
						{
							$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;
							$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/member/".$row->foto_mahasiswa;							
							$data[] = $row;
						}

				}else if ($id_sie_kegiatan != ''){ 
					$this->db->select('*');
					$this->db->from('tbl_kepanitiaan p');
					$this->db->join('tbl_sie_kegiatan sk', 'p.id_sie_kegiatan = sk.id_sie_kegiatan');
					$this->db->join('tbl_member m', 'p.id_mahasiswa = m.id_mahasiswa');
					$this->db->where('p.id_sie_kegiatan',$id_sie_kegiatan);
					$kegiatan = $this->db->get('tbl_kepanitiaan');
					foreach ($kegiatan->result() as $row) 
						{
							$row->foto_kegiatan = "http://172.17.100.2/rest_ci/images/".$row->foto_kegiatan;	
							$row->foto_mahasiswa = "http://172.17.100.2/rest_ci/images/member/".$row->foto_mahasiswa;
							$data[] = $row;
						}

				} 

			}
			
			$this->response(array('kepanitiaan' => $data), 200);
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
								
				// api key firebase				
				define( 'API_ACCESS_KEY', 'AAAAVwqaT0k:APA91bHY_YBLsFhagGpqM88I24izJeTeKEhyeS3A0CH_AawmSCWuXXGd_Hmk3KhBK1IkyHjTLO0X6A06AAZy5LR2lhhDO7lWybaelAY3Vl1Jl5MxGyL0vs2WLG8JLkKg6cauKDdvkiDq' );

				$this->db->select('*');
				$this->db->from('tbl_kepanitiaan k');
				$this->db->join('tbl_member m', 'm.id_mahasiswa = k.id_mahasiswa');
				$this->db->join('tbl_sie_kegiatan sk', 'k.id_sie_kegiatan = sk.id_sie_kegiatan');
				$this->db->join('tbl_kegiatan kg', 'sk.id_kegiatan = kg.id_kegiatan');
				$this->db->where('k.id_kepanitiaan', $id);
				$kegiatan = $this->db->get('tbl_kepanitiaan');
				foreach ($kegiatan->result() as $row) 
				{

					$singleID = $row->id_perangkat; // id perangkat	
					$panitian_cut = $row->nama_kegiatan;
					$nama_mahasiswa_cut = $row->nama_mahasiswa;
					$id_mahasiswa_cut = $row->id_mahasiswa;
				}

				$title = 'Cut Kepanitiaan';
				$pesan = 'Hallo '. $nama_mahasiswa_cut .', Anda di Cut di kepanitiaan '. $panitian_cut .'. Jangan Berkecil Hati Ikuti Kegiatan Yang Akan Datang Lainnya';
				
				$fcmMsg = array(
					'body' => $pesan,
					'title' => $title,
					'sound' => "default",
						'color' => "#203E78" 
				);
				
				$fcmFields = array(
					'to' => $singleID,
					'priority' => 'high',
					'notification' => $fcmMsg
				);

				$headers = array(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
				 
				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
				$result = curl_exec($ch );
				curl_close( $ch );
				echo $result . "\n\n";
				
				$data_notif = array(
						'id_mahasiswa'		=> $id_mahasiswa_cut,
						'judul'				=> $title,
						'pesan'				=> $pesan
					);
				$insert = $this->db->insert('tbl_pemberitahuan', $data_notif);
				
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