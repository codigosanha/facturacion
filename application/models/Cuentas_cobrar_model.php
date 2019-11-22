<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuentas_cobrar_model extends CI_Model {
	public function getCuentasByCliente($idcliente){
		$this->db->select("vc.*,
    					 c.serie");
    	$this->db->from("ventas_creditos vc");
    	$this->db->join("comprobantes c","vc.comprobante_id = c.id");
		$this->db->where("vc.estado",1);
		$this->db->where("vc.condicion",0);
		$this->db->where("vc.cliente_id",$idcliente);
		$resultado = $this->db->get();
		return $resultado->result();
	}
	public function ajusteActual(){
		$this->db->where("DATE(fecha)",date("Y-m-d"));
		$resultado = $this->db->get("ajustes");
		if ($resultado->num_rows() > 0) {
			return true;
		}else{
			return false;
		}
		
	}
	public function getAjuste($id){
		$this->db->where("id", $id);
		$resultado = $this->db->get("ajustes");
		return $resultado->row();
	}

	public function save($data){
		if ($this->db->insert("ajustes",$data)) {
			return $this->db->insert_id();
		}
		return false;
	}

	public function getAjusteProductos($idajuste){
		$this->db->where("ajuste_id",$idajuste);
		return $this->db->get("ajustes_productos")->result();
	}

	public function saveAjusteProductos($data){
		return $this->db->insert("ajustes_productos",$data);
	}

	public function updateAjuste($idajuste,$idproducto,$data){
		$this->db->where("ajuste_id",$idajuste);
		$this->db->where("producto_id",$idproducto);
		return $this->db->update("ajustes_productos",$data);
	}

	
}