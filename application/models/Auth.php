<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Model {

	function cek_login(){
			$token = $this->input->get_request_header('Authorization');
			$data = array(
					'login_token' => $token
			); 
			
			$this->input->get_request_header('Authorization');
				$this->db->where($data);
				$login = $this->db->get('tbl_member');
				
				if ($login->num_rows() == 1){
					return 1;
				}else{
					return 0;
				}		
		return 0;
		}
}