<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_model extends CI_Model {
	public function save($data){
		return $this->db->insert("pagos",$data);
	}

	public function getPagosByDate($fechainicio, $fechafin){
		$this->db->select("p.*,vc.cliente_id");
    	$this->db->from("pagos p");
    	$this->db->join("ventas_creditos vc","p.cuenta_id = vc.id");
    	$this->db->where("DATE(p.fecha) >=",$fechainicio);
		$this->db->where("DATE(p.fecha) <=",$fechafin);
    	$resultados = $this->db->get();

    	return $resultados->result();
	}

	public function getPagos($idCuenta){
		$this->db->where("cuenta_id",$idCuenta);
		$resultado = $this->db->get("pagos");
		return $resultado->result();
	}

	public function rowCount($tabla){
		$resultados = $this->db->get($tabla);
		return $resultados->num_rows();
	}

	
}