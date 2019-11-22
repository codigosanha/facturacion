<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_model extends CI_Model {
	public function getID($link){
		$this->db->like("link",$link);
		$resultado = $this->db->get("menus");
		return $resultado->row();
	}

	public function getPermisos($menu,$rol){
		$this->db->where("menu_id",$menu);
		$this->db->where("rol_id",$rol);
		$resultado = $this->db->get("permisos");
		return $resultado->row();
	}

	public function rowCount($tabla){
		$resultados = $this->db->get($tabla);
		return $resultados->num_rows();
	}

	public function getTotalVentasEfectivo($fechainicio, $fechafin){
		$this->db->select("SUM(monto) as total");
		$this->db->from("ventas");
		$this->db->where("estado",1);
		$this->db->where("DATE(fecha) >=",$fechainicio);
		$this->db->where("DATE(fecha) <=",$fechafin);
		$this->db->group_by("estado");
		$resultados = $this->db->get();
		$total = 0;
		if ($resultados->num_rows() > 0 ) {
			$total = $resultados->row()->total;
		}
		
		return $total;

	}

	
}