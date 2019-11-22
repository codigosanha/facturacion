<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras_model extends CI_Model {

	function __construct() {
        parent::__construct(); 
    }

    function allcompras_count()
    {   
        $query = $this
                ->db
                ->get('compras');
    
        return $query->num_rows();  

    }
    
    function allcompras($limit,$start,$col,$dir)
    {   
       $query = $this
                ->db
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('compras');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function compras_search($limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db
                ->like('numero',$search)
                ->or_like('fecha',$search)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('compras');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function compras_search_count($search)
    {
        $query = $this
                ->db
                ->like('numero',$search)
                ->or_like('fecha',$search)
                ->get('compras');
    
        return $query->num_rows();
    } 

    public function getProveedores(){
    	$query = $this
                ->db
                ->get('proveedores');
    
        return $query->result();  

    }

	public function getVentas(){
		$this->db->select("v.*,cli.razon_social,cli.rnc,c.nombre as comprobante,c.serie");
		$this->db->from("ventas v");
		$this->db->join("clientes cli","v.cliente_id = cli.id",'left');
		$this->db->join("comprobantes c","v.comprobante_id = c.id");
		$resultados = $this->db->get();
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
		$this->db->join("clientes cli","vp.cliente_id = cli.id",'left');
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
		$this->db->select("v.*,c.nombre,tc.nombre as tipocomprobante");
		$this->db->from("ventas v");
		$this->db->join("clientes c","v.cliente_id = c.id");
		$this->db->join("tipo_comprobante tc","v.tipo_comprobante_id = tc.id");
		$this->db->where("v.fecha >=",$fechainicio);
		$this->db->where("v.fecha <=",$fechafin);
		$resultados = $this->db->get();
		if ($resultados->num_rows() > 0) {
			return $resultados->result();
		}else
		{
			return false;
		}
	}

	public function getCompra($id){
		$this->db->select("c.*,p.razon_social,p.rnc,cp.nombre as comprobante");
		$this->db->from("compras c");
		$this->db->join("proveedores p","c.proveedor_id = p.id");
		$this->db->join("comprobantes cp","c.comprobante_id = cp.id");
		$this->db->where("c.id",$id);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function getDetalle($id){
		$this->db->select("dt.*,p.codigo,p.nombre,p.precio,p.precio_compra");
		$this->db->from("detalle_compra dt");
		$this->db->join("productos p","dt.producto_id = p.id");
		$this->db->where("dt.compra_id",$id);
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

	

	public function save($data){
		return $this->db->insert("compras",$data);
	}

	public function savePendiente($data){
		if ($this->db->insert('ventas_pendientes',$data)) {
			return $this->db->insert_id();
		}
		return false;
	}

	public function update($idcompra, $data){
		$this->db->where("id", $idcompra);
		return $this->db->update("compras", $data);
	}
	public function deleteDetalleCompra($idcompra){
		$this->db->where('compra_id', $idcompra);
		return $this->db->delete('detalle_compra');
	}

	public function lastID(){
		return $this->db->insert_id();
	}

	public function updateComprobante($idcomprobante,$data){
		$this->db->where("id",$idcomprobante);
		$this->db->update("tipo_comprobante",$data);
	}

	public function save_detalle($data){
		$this->db->insert("detalle_compra",$data);
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
		$this->db->where("stock >", "0");
		$this->db->where("estado","1");
		$resultados = $this->db->get("productos");
		return $resultados->result();
	}

	

	public function getproductos($valor){
		$this->db->select("id,CONCAT(codigo,' - ',nombre) as label,nombre,codigo,precio_compra,precio,stock");
		$this->db->from("productos");
		$this->db->like("CONCAT(codigo,'',nombre)",$valor);
		$resultados = $this->db->get();
		return $resultados->result_array();
	}

	public function searchRNC($rnc){
		$this->db->where('rnc', $rnc);
		$resultados = $this->db->get("clientes");
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

	public function getProductoByCode($codigo_barra){
		
		$this->db->where("codigo", $codigo_barra);
		$resultados = $this->db->get('productos');
		if ($resultados->num_rows() > 0) {
			return $resultados->row();
		}else{
			return false;
		}
		
	}

}