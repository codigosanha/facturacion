<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuraciones_model extends CI_Model {
	
	public function getConfiguracion(){
		
		$resultado = $this->db->get("configuraciones");
		if ($resultado->num_rows() > 0) {
			return $resultado->row();
		}
		return false;
		
	}

	public function save($data){
		return $this->db->insert("configuraciones",$data);
	}

	public function update($data){
		return $this->db->update("configuraciones",$data);
	}

	
}