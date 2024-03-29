<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comprobantes_model extends CI_Model {

	public function getComprobantes(){
		$resultados = $this->db->get("comprobantes");
		return $resultados->result();
	}

	public function save($data){
		return $this->db->insert("comprobantes",$data);
	}
	public function getComprobante($id){
		$this->db->where("id",$id);
		$resultado = $this->db->get("comprobantes");
		return $resultado->row();

	}

	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update("comprobantes",$data);
	}

	public function removePredeterminado($id, $data){
		$this->db->where("id !=",$id);
		return $this->db->update("comprobantes",$data);
	}

	public function getComprobanteByNombre($nombre){
		$this->db->where("nombre",$nombre);
		$resultado = $this->db->get("comprobantes");
		if ($resultado->num_rows() > 0) {
			return $resultado->row();
		}

		return false;
	}
}
