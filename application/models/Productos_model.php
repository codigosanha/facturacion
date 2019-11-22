<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends CI_Model {
	function __construct() {
        parent::__construct(); 
    }

    function allproductos_count()
    {   
        $query = $this
                ->db
                ->get('productos');
    
        return $query->num_rows();  

    }
    
    function allproductos($limit,$start,$col,$dir)
    {   
       $query = $this
                ->db
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('productos');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function productos_search($limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db
                ->like('nombre',$search)
                ->or_like('descripcion',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('productos');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function productos_search_count($search)
    {
        $query = $this
                ->db
                ->like('nombre',$search)
                ->or_like('descripcion',$search)
                ->get('productos');
    
        return $query->num_rows();
    } 

    public function resetear_stock_negative($data){
        
        $this->db->where("stock <", 0);
        return $this->db->update("productos", $data);
    }

	public function getProductos($stock =false){
		$this->db->select("p.*,c.nombre as categoria");
		$this->db->from("productos p");
		$this->db->join("categorias c","p.categoria_id = c.id");
		$this->db->where("p.estado","1");
        $this->db->order_by("p.nombre","asc");

        if ($stock !== false) {
            if ($stock == 2) {
                $this->db->where("p.stock >=",1);
            }else{
                $this->db->where("p.stock <=",0);
            }
            
        }
		$resultados = $this->db->get();
		return $resultados->result();
	}
	public function getProducto($id){
		$this->db->where("id",$id);
		$resultado = $this->db->get("productos");
		return $resultado->row();
	}
	public function save($data){
		return $this->db->insert("productos",$data);
	}

	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update("productos",$data);
	}

}