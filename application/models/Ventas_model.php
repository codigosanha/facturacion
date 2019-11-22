<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_model extends CI_Model {

	function __construct() {
        parent::__construct(); 
    }

    function allventas_count()
    {   
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("usuario_id", $this->session->userdata("id"));
		}
        $query = $this->db->get('ventas');
    
        return $query->num_rows();  

    }
    
    function allventas($limit,$start,$col,$dir)
    {  
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("v.usuario_id", $this->session->userdata("id"));
		}
    	$this->db->limit($limit,$start);
    	$this->db->order_by($col,$dir);
    	$query = $this->db->get('ventas v');
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
    {	$this->db->select("v.*,CONCAT(c.serie,'',v.numero) as comprobante_numero,c.nombre,cj.razon_social,cj.rnc,u.username");
    	$this->db->from("ventas v");
    	$this->db->join("clientes_juridicos cj", "v.cliente_id = cj.id","left");
    	$this->db->join("comprobantes c", "v.comprobante_id = c.id");
    	$this->db->join("usuarios u", "v.usuario_id = u.id");
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("v.usuario_id", $this->session->userdata("id"));
		}
        $this->db->like("CONCAT(c.serie,'',v.numero)",$search);
        $this->db->or_like('cj.razon_social',$search);
        $this->db->or_like('cj.rnc',$search);
        $this->db->or_like('c.nombre',$search);
        $this->db->or_like('v.fecha',$search);
        $this->db->or_like('v.monto',$search);
        $this->db->or_like('u.username',$search);
        $this->db->limit($limit,$start);
        /*if ($col == 'comprobante') {
        	$this->db->order_by("c.".$col,$dir);
        }else{
        	$this->db->order_by("v.".$col,$dir);
        }*/
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
    	$this->db->select("v.*,CONCAT(c.serie,'',v.numero) as comprobante_numero,c.nombre,cj.razon_social,cj.rnc,u.username");
    	$this->db->from("ventas v");
    	$this->db->join("clientes_juridicos cj", "v.cliente_id = cj.id","left");
    	$this->db->join("comprobantes c", "v.comprobante_id = c.id");
    	$this->db->join("usuarios u", "v.usuario_id = u.id");
    	if ($this->session->userdata('rol')!=1) {
			$this->db->where("v.usuario_id", $this->session->userdata("id"));
		}
        $this->db->like("CONCAT(c.serie,'',v.numero)",$search);
        $this->db->or_like('cj.razon_social',$search);
        $this->db->or_like('cj.rnc',$search);
        $this->db->or_like('c.nombre',$search);
        $this->db->or_like('v.fecha',$search);
        $this->db->or_like('v.monto',$search);
        $this->db->or_like('u.username',$search);
        $query = $this->db->get();
    
        return $query->num_rows();
    } 

	public function getVentas(){
		$this->db->select("v.*,cli.razon_social,cli.rnc,c.nombre as comprobante,c.serie");
		$this->db->from("ventas v");
		$this->db->join("clientes_juridicos cli","v.cliente_id = cli.id",'left');
		$this->db->join("comprobantes c","v.comprobante_id = c.id");
		$resultados = $this->db->get();
		if ($resultados->num_rows() > 0) {
			return $resultados->result();
		}else
		{
			return false;
		}
	}



	public function getVentasbyDate($fechainicio,$fechafin){
		$this->db->select("v.*,cli.razon_social,cli.rnc,c.nombre as comprobante,c.serie");
		$this->db->from("ventas v");
		$this->db->join("clientes_juridicos cli","v.cliente_id = cli.id",'left');
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
		$this->db->select("v.*,cli.rnc,cli.razon_social,c.nombre as comprobante,c.serie");
		$this->db->from("ventas v");
		$this->db->join("clientes_juridicos cli","v.cliente_id = cli.id","left");
		$this->db->join("comprobantes c","v.comprobante_id = c.id");
		$this->db->where("v.id",$id);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function getDetalle($id){
		$this->db->select("dt.*,p.codigo,p.nombre");
		$this->db->from("detalle_venta dt");
		$this->db->join("productos p","dt.producto_id = p.id");
		$this->db->where("dt.venta_id",$id);
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
		return $this->db->insert("ventas",$data);
	}

	public function savePendiente($data){
		if ($this->db->insert('ventas_pendientes',$data)) {
			return $this->db->insert_id();
		}
		return false;
	}

	public function update($idventa, $data){
		$this->db->where("id", $idventa);
		return $this->db->update("ventas", $data);
	}
	public function deleteDetalleVenta($idventa){
		$this->db->where('venta_id', $idventa);
		return $this->db->delete('detalle_venta');
	}

	public function lastID(){
		return $this->db->insert_id();
	}

	public function updateComprobante($idcomprobante,$data){
		$this->db->where("id",$idcomprobante);
		$this->db->update("tipo_comprobante",$data);
	}

	public function save_detalle($data){
		$this->db->insert("detalle_venta",$data);
	}

	public function save_detalle_pendiente($data){
		$this->db->insert("ventas_pendientes_detalle",$data);
	}

	public function years(){
		$this->db->select("YEAR(fecha) as year");
		$this->db->from("ventas");
		$this->db->group_by("year");
		$this->db->order_by("year","desc");
		$resultados = $this->db->get();
		return $resultados->result();
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

	public function deleteVentaPendiente($idventapendiente){
		$this->db->where('id', $idventapendiente);
		$this->db->delete('ventas_pendientes');
	}

	public function deleteVentaPendienteDetalle($idventapendiente){
		$this->db->where('venta_pendiente_id', $idventapendiente);
		$this->db->delete('ventas_pendientes_detalle');
	}

	public function updateVentaPendiente($idventapendiente,$data){
		$this->db->where("id",$idventapendiente);
		return $this->db->update("ventas_pendientes",$data);
	}

	public function aperturarCaja($data){
		return $this->db->insert("caja",$data);
	}

	public function cerrarCaja($id,$data){
		$this->db->where('id',$id);
		return $this->db->update("caja",$data);
	}
	public function getInfoCaja($date){
		$this->db->where("DATE(fecha)",$date);
		$resultado = $this->db->get("caja");
		if ($resultado->num_rows() > 0) {
			return $resultado->row();
		}
		return false;
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
}