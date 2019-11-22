<?php

class Clients_model extends CI_Model 
{
    function __construct() {
        parent::__construct(); 
        
    }

    function allclients_count()
    {   
        $query = $this
                ->db
                ->get('clientes');
    
        return $query->num_rows();  

    }
    
    function allclients($limit,$start,$col,$dir)
    {   
       $query = $this
                ->db
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('clientes');
        
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
                ->like('rnc',$search)
                ->or_like('razon_social',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('clientes');
        
       
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
                ->like('rnc',$search)
                ->or_like('razon_social',$search)
                ->get('clientes');
    
        return $query->num_rows();
    } 
   
}