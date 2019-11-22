<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores_model extends CI_Model {

	function __construct() {
        parent::__construct(); 
    }

    function allproveedores_count()
    {   
        $query = $this
                ->db
                ->get('proveedores');
    
        return $query->num_rows();  

    }
    
    function allproveedores($limit,$start,$col,$dir)
    {   
       $query = $this
                ->db
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('proveedores');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function proveedores_search($limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db
                ->like('rnc',$search)
                ->or_like('razon_social',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('proveedores');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function proveedores_search_count($search)
    {
        $query = $this
                ->db
                ->like('rnc',$search)
                ->or_like('razon_social',$search)
                ->get('proveedores');
    
        return $query->num_rows();
    } 

	public function getProveedores(){
		$this->db->where("estado","1");
		$resultados = $this->db->get("proveedores");
		return $resultados->result();
	}

	public function save($data){
		return $this->db->insert("proveedores",$data);
	}
	public function getProveedor($id){
		$this->db->where("id",$id);
		$resultado = $this->db->get("proveedores");
		return $resultado->row();

	}

	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update("proveedores",$data);
	}
}
