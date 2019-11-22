<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias_model extends CI_Model {

	function __construct() {
        parent::__construct(); 
    }

    function allcategorias_count()
    {   
        $query = $this
                ->db
                ->get('categorias');
    
        return $query->num_rows();  

    }
    
    function allcategorias($limit,$start,$col,$dir)
    {   
       $query = $this
                ->db
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('categorias');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function categorias_search($limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db
                ->like('nombre',$search)
                ->or_like('descripcion',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('categorias');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function categorias_search_count($search)
    {
        $query = $this
                ->db
                ->like('nombre',$search)
                ->or_like('descripcion',$search)
                ->get('categorias');
    
        return $query->num_rows();
    } 

	public function getCategorias(){
		$this->db->where("estado","1");
		$resultados = $this->db->get("categorias");
		return $resultados->result();
	}

	public function save($data){
		return $this->db->insert("categorias",$data);
	}
	public function getCategoria($id){
		$this->db->where("id",$id);
		$resultado = $this->db->get("categorias");
		return $resultado->row();

	}

	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update("categorias",$data);
	}
}
