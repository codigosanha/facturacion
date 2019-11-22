<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_normales_model extends CI_Model {
	public function getClientes(){
		
		$resultados = $this->db->get("clientes_normales");
		return $resultados->result();
	}

	public function getCliente($id){
		$this->db->where("id",$id);
		$resultado = $this->db->get("clientes_normales");
		return $resultado->row();

	}
	public function save($data){
		return $this->db->insert("clientes_normales",$data);
	}
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update("clientes_normales",$data);
	}


    function __construct() {
        parent::__construct(); 
        
    }

    function allclients_count()
    {   
        $query = $this
                ->db
                ->get('clientes_normales');
    
        return $query->num_rows();  

    }
    
    function allclients($limit,$start,$col,$dir)
    {   
       $query = $this
                ->db
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('clientes_normales');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function clients_search($limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db
                ->like('nombres',$search)
                ->or_like('apellidos',$search)
                ->or_like('cedula',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('clientes_normales');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function clients_search_count($search)
    {
        $query = $this
                ->db
                ->like('nombres',$search)
                ->or_like('apellidos',$search)
                ->or_like('cedula',$search)
                ->get('clientes_normales');
    
        return $query->num_rows();
    } 
}