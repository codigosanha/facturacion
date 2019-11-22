<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_creditos_model extends CI_Model {

	function __construct() {
        parent::__construct(); 
    }


    public function getClientesDeudores($fechainicio =false,$fechafin=false){
    	$this->db->select("cn.id,
    					 vc.id as venta_id,
    					 cn.cedula, 
    					 cn.nombres, 
    					 cn.apellidos,
    					 vc.fecha,
    					 SUM(vc.total) as total,
    					 COUNT(vc.id) as cuentas,
    					 SUM(vc.pagado) as pagado");
    	$this->db->from("ventas_creditos vc");
    	$this->db->join("clientes_normales cn","vc.cliente_id = cn.id");
    	$this->db->where("vc.estado",1);
    	$this->db->where("vc.condicion",0);
    	if ($fechainicio !== false && $fechafin!== false) {
    		$this->db->where("DATE(vc.fecha) >=",$fechainicio);
			$this->db->where("DATE(vc.fecha) <=",$fechafin);
    	}
    	$this->db->group_by("cn.id");
    	$resultados = $this->db->get();

    	return $resultados->result();
    }

    public function getClientesDeudoresByDate($fechainicio,$fechafin){
    	$this->db->select("cn.id,
    					 vc.id as venta_id,
    					 vc.fecha,
    					 cn.cedula, 
    					 cn.nombres, 
    					 cn.apellidos,
    					 vc.total");
    	$this->db->from("ventas_creditos vc");
    	$this->db->join("clientes_normales cn","vc.cliente_id = cn.id");
    	$this->db->where("vc.estado",1);
    	$this->db->where("DATE(vc.fecha) >=",$fechainicio);
		$this->db->where("DATE(vc.fecha) <=",$fechafin);
    	$resultados = $this->db->get();

    	return $resultados->result();
    }

    function allventas_count()
    {   
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("usuario_id", $this->session->userdata("id"));
		}
        $query = $this->db->get('ventas_creditos');
    
        return $query->num_rows();  

    }
    
    function allventas($limit,$start,$col,$dir)
    {  
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("v.usuario_id", $this->session->userdata("id"));
		}
    	$this->db->limit($limit,$start);
    	$this->db->order_by($col,$dir);
    	$query = $this->db->get('ventas_creditos v');
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function ventas_search($limit,$start,$search,$col,$dir)
    {
    	$this->db->select("v.*,CONCAT(c.serie,'',v.numero) as comprobante_numero,c.nombre,cn.cedula,CONCAT(cn.nombres,' ',cn.apellidos) as cliente,u.username");
    	$this->db->from("ventas_creditos v");
    	$this->db->join("clientes_normales cn", "v.cliente_id = cn.id");
    	$this->db->join("comprobantes c", "v.comprobante_id = c.id");
    	$this->db->join("usuarios u", "v.usuario_id = u.id");
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("v.usuario_id", $this->session->userdata("id"));
		}
        $this->db->like("CONCAT(c.serie,'',v.numero)",$search);
        $this->db->or_like('c.nombre',$search);
        $this->db->or_like('cn.cedula',$search);
        $this->db->or_like("CONCAT(cn.nombres,' ',cn.apellidos)",$search);
        $this->db->or_like('v.fecha',$search);
        $this->db->or_like('v.total',$search);
        $this->db->or_like('u.username',$search);
        $this->db->limit($limit,$start);
        $this->db->order_by($col,$dir);
        $query = $this->db->get();
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function ventas_search_count($search)
    {
    	$this->db->select("v.*,CONCAT(c.serie,'',v.numero) as comprobante_numero,c.nombre,cn.cedula,CONCAT(cn.nombres,' ',cn.apellidos) as cliente,u.username");
    	$this->db->from("ventas_creditos v");
    	$this->db->join("clientes_normales cn", "v.cliente_id = cn.id");
    	$this->db->join("comprobantes c", "v.comprobante_id = c.id");
    	$this->db->join("usuarios u", "v.usuario_id = u.id");
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("v.usuario_id", $this->session->userdata("id"));
		}
        $this->db->like("CONCAT(c.serie,'',v.numero)",$search);
        $this->db->or_like('c.nombre',$search);
        $this->db->or_like('cn.cedula',$search);
        $this->db->or_like("CONCAT(cn.nombres,' ',cn.apellidos)",$search);
        $this->db->or_like('v.fecha',$search);
        $this->db->or_like('v.total',$search);
        $this->db->or_like('u.username',$search);
        $query = $this->db->get();
    
        return $query->num_rows();
    } 

	public function getVentas(){
		$this->db->where("estado",1);
		$this->db->where("condicion",0);
		$resultados = $this->db->get("ventas_creditos");
		if ($resultados->num_rows() > 0) {
			return $resultados->result();
		}else
		{
			return false;
		}
	}

	public function getVentasPendientes(){
		$this->db->select("vp.*,cli.razon_social,cli.rnc,c.nombre as comprobante,c.serie");
		$this->db->from("ventas_pendientes vp");
		$this->db->join("clientes_juridicos cli","vp.cliente_id = cli.id",'left');
		$this->db->join("comprobantes c","vp.comprobante_id = c.id");
		$resultados = $this->db->get();
		$return = array();

	    foreach ($resultados->result() as $venta)
	    {
	        $return[$venta->id] = $venta;
	        $return[$venta->id]->detalles = $this->getDetalleVentaPendiente($venta->id); // Get the categories sub categories
	    }

	    return $return;
	}

	public function getDetalleVentaPendiente($id)
	{

	    $this->db->select("dt.*,p.codigo,p.nombre");
		$this->db->from("ventas_pendientes_detalle dt");
		$this->db->join("productos p","dt.producto_id = p.id");
		$this->db->where("dt.venta_pendiente_id",$id);
		$resultados = $this->db->get();
		return $resultados->result();
	}
	public function getVentasbyDate($fechainicio,$fechafin){
		$this->db->select("v.*,cli.razon_social,cli.rnc,c.nombre as comprobante,c.serie");
		$this->db->from("ventas v");
		$this->db->join("clientes cli","v.cliente_id = cli.id",'left');
		$this->db->join("comprobantes c","v.comprobante_id = c.id");
		$this->db->where("DATE(v.fecha) >=",$fechainicio);
		$this->db->where("DATE(v.fecha) <=",$fechafin);
		$resultados = $this->db->get();
		if ($resultados->num_rows() > 0) {
			return $resultados->result();
		}else
		{
			return false;
		}
	}

	public function getVenta($id){
		$this->db->select("v.*,cli.cedula,cli.nombres,cli.apellidos,c.nombre as comprobante,c.serie");
		$this->db->from("ventas_creditos v");
		$this->db->join("clientes_normales cli","v.cliente_id = cli.id","left");
		$this->db->join("comprobantes c","v.comprobante_id = c.id");
		$this->db->where("v.id",$id);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function getDetalle($id){
		$this->db->select("dt.*,p.codigo,p.nombre");
		$this->db->from("detalle_venta_credito dt");
		$this->db->join("productos p","dt.producto_id = p.id");
		$this->db->where("dt.venta_credito_id",$id);
		$resultados = $this->db->get();
		return $resultados->result();
	}

	public function getComprobantes(){
		$this->db->where('cantidad_realizada < limite');
		$resultados = $this->db->get("comprobantes");
		return $resultados->result();
	}

	public function getComprobante($idcomprobante){
		$this->db->where("id",$idcomprobante);
		$resultado = $this->db->get("tipo_comprobante");
		return $resultado->row();
	}

	public function getproductos($valor){
		$this->db->select("id,codigo,nombre as label,precio,stock");
		$this->db->from("productos");
		$this->db->like("nombre",$valor);
		$resultados = $this->db->get();
		return $resultados->result_array();
	}

	public function save($data){
		return $this->db->insert("ventas_creditos",$data);
	}

	public function savePendiente($data){
		if ($this->db->insert('ventas_pendientes',$data)) {
			return $this->db->insert_id();
		}
		return false;
	}

	public function update($idventa, $data){
		$this->db->where("id", $idventa);
		return $this->db->update("ventas_creditos", $data);
	}
	public function deleteDetalleVenta($idventa){
		$this->db->where('venta_credito_id', $idventa);
		return $this->db->delete('detalle_venta_credito');
	}

	public function lastID(){
		return $this->db->insert_id();
	}

	public function updateComprobante($idcomprobante,$data){
		$this->db->where("id",$idcomprobante);
		$this->db->update("tipo_comprobante",$data);
	}

	public function save_detalle($data){
		$this->db->insert("detalle_venta_credito",$data);
	}


	public function montos($year){
		$this->db->select("MONTH(fecha) as mes, SUM(total) as monto");
		$this->db->from("ventas");
		$this->db->where("fecha >=",$year."-01-01");
		$this->db->where("fecha <=",$year."-12-31");
		$this->db->group_by("mes");
		$this->db->order_by("mes");
		$resultados = $this->db->get();
		return $resultados->result();
	}

	public function getProductosByCategoria($categoria){
		$this->db->where("categoria_id",$categoria);
		$this->db->where("estado","1");
		$resultados = $this->db->get("productos");
		return $resultados->result();
	}

	public function getProductoByCode($codigo_barra){
		
		$this->db->where("codigo", $codigo_barra);
		$resultados = $this->db->get('productos');
		if ($resultados->num_rows() > 0) {
			return $resultados->row();
		}else{
			return false;
		}
		
	}

	public function searchRNC($rnc){
		$this->db->where('rnc', $rnc);
		$resultados = $this->db->get("clientes_juridicos");
		if ($resultados->num_rows() > 0) {
			return $resultados->row();
		}
		return false;
	}

	public function searchCedula($cedula){
		$this->db->where('cedula', $cedula);
		$resultados = $this->db->get("clientes_normales");
		if ($resultados->num_rows() > 0) {
			return $resultados->row();
		}
		return false;
	}

	public function getClientesNormales($valor){
		$this->db->select("id,CONCAT(cedula,' - ',nombres,' ',apellidos) as label");
		$this->db->from("clientes_normales");
		$this->db->like("CONCAT(cedula,' - ',nombres,' ',apellidos)",$valor);
		$resultados = $this->db->get();
		return $resultados->result_array();
	}

}