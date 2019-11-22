<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caja_model extends CI_Model {

	public function getCierre($user,$date){

		$this->db->select("p.nombre,SUM(dv.cantidad) as cantidad,dv.precio,p.stock,u.username");
		$this->db->from("detalle_venta dv");
		$this->db->join("ventas v", "dv.venta_id = v.id");
		$this->db->join("productos p","dv.producto_id = p.id");
		$this->db->join("usuarios u","v.usuario_id = u.id");
		$this->db->where("v.fecha >=",$date." 00:00:01");
 		$this->db->where("v.fecha <=",$date." 23:59:59");
 		$this->db->where("v.estado", 1);
		if ($this->session->userdata('rol')!=1) {
			$this->db->where("v.usuario_id", $user);
			$this->db->group_by('dv.producto_id'); 
			$this->db->order_by('p.nombre', 'desc');
		}else{
			
			$this->db->group_by('v.usuario_id'); 
			$this->db->group_by('dv.producto_id');
			$this->db->order_by('u.username', 'desc');
		}
		
 		
 		
		$resultados = $this->db->get();
		return $resultados->result();
	}

}
