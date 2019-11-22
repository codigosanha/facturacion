<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {
	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Ventas_model");
		$this->load->model("Clientes_juridicos_model");
		$this->load->model("Productos_model");
		$this->load->model("Categorias_model");
		$this->load->model("Comprobantes_model");
		$this->load->helper("get_names_tables_relationship");
		$this->load->library("Configuraciones_lib");
	}

	public function index(){
		$data = array(
			'caja' => $this->Ventas_model->getInfoCaja(date("Y-m-d")),
			'permisos' => $this->permisos,
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/ventas/list",$data);
		$this->load->view("layouts/footer");
	}

	public function getVentasAll()
	{

		$columns = array( 
            0 => 'v.id', 
            1 => 'comprobante_numero', 
            2 => 'c.nombre',
            3 => 'cj.razon_social',
            4 => 'cj.rnc',
            5 => 'v.fecha',
            6 => 'v.monto',
            7 => 'v.estado',
            8 => 'u.username'
        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Ventas_model->allventas_count();
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $ventas = $this->Ventas_model->allventas($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $ventas =  $this->Ventas_model->ventas_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Ventas_model->ventas_search_count($search);
        }

        $data = array();
        if(!empty($ventas))
        {
            foreach ($ventas as $venta)
            {

                $nestedData['id'] = $venta->id;
                $nestedData['numero'] = getComprobante($venta->comprobante_id)->serie.$venta->numero;
                $nestedData['comprobante'] = getComprobante($venta->comprobante_id)->nombre;
                $rnc = '';
                $nombre_cliente = '';
                if (getClienteJuridico($venta->cliente_id)) {
                	$rnc = getClienteJuridico($venta->cliente_id)->rnc;
                	$nombre_cliente = getClienteJuridico($venta->cliente_id)->razon_social;
                }
                $nestedData['nombre_cliente'] = $nombre_cliente;
                $nestedData['rnc'] = $rnc;
                $nestedData['fecha'] = $venta->fecha;
                $nestedData['total'] = $venta->monto;
                $nestedData['estado'] = $venta->estado;
                $nestedData['usuario'] = getUsuario($venta->usuario_id)->username;
                
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($this->input->post('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
	}
	public function agregar_venta_contado(){
		$factura = $this->Comprobantes_model->getComprobanteByNombre('factura');
		$data = array(
			"comprobantes" => $this->Ventas_model->getComprobantes(),
			'categorias' => $this->Categorias_model->getCategorias(),
			'factura' => $factura,
			'pendientes' => $this->Ventas_model->getVentasPendientes(),
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/ventas/add_venta_contado",$data);
		$this->load->view("layouts/footer");
	}

	public function agregar_venta_credito(){
		$factura = $this->Comprobantes_model->getComprobanteByNombre('factura');
		$data = array(
			"comprobantes" => $this->Ventas_model->getComprobantes(),
			'categorias' => $this->Categorias_model->getCategorias(),
			'factura' => $factura,
			'pendientes' => $this->Ventas_model->getVentasPendientes(),
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/ventas/add_venta_credito",$data);
		$this->load->view("layouts/footer");
	}

	public function getVentas(){
		$valor = $this->input->post("valor");
		$clientes = $this->Ventas_model->getVentas($valor);
		echo json_encode($clientes);
	}

	public function store(){
		$fecha = date("Y-m-d H:i:s");
		$total = $this->input->post("total");
		$idcomprobante = $this->input->post("idcomprobante");
		$idcliente = $this->input->post("idcliente");
		$idusuario = $this->session->userdata("id");
		$subtotal = $this->input->post("subtotal");
		$itbis = $this->input->post("itbis");
		$numero = $this->generarNumero($idcomprobante);

		$productos = $this->input->post("productos");
		$precios = $this->input->post("precios");
		$cantidades = $this->input->post("cantidades");
		$importes = $this->input->post("importes");
		$idVentaPendiente = $this->input->post("idVentaPendiente");

		$data = array(
			'fecha' => $fecha,
			'monto' => $total,
			'comprobante_id' => $idcomprobante,
			'cliente_id' => $idcliente,
			'usuario_id' => $idusuario,
			'numero' => $numero,
			'subtotal' => $subtotal,
		);

		if ($this->Ventas_model->save($data)) {
			$idventa = $this->Ventas_model->lastID();
			//$this->updateComprobante($idcomprobante);
			$this->save_detalle($productos,$idventa,$precios,$cantidades,$importes);
			//$this->reset_stock_negative();
			$this->updateComprobante($idcomprobante);
			if (!empty($idVentaPendiente)) {
				$this->Ventas_model->deleteVentaPendiente($idVentaPendiente);
				$this->Ventas_model->deleteVentaPendienteDetalle($idVentaPendiente);
			}
			$this->printVenta($idventa);
			

		}else{
			redirect(base_url()."movimientos/ventas/add");
		}
	}

	public function edit($idventa){
		$data = array(
			'venta' => $this->Ventas_model->getVenta($idventa),
			'detalles' => $this->Ventas_model->getDetalle($idventa),
			'categorias' => $this->Categorias_model->getCategorias(),
		);

		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/ventas/edit",$data);
		$this->load->view("layouts/footer");
	}

	public function update(){
		$idVenta = $this->input->post("idVenta");
		$total = $this->input->post("total");
		$subtotal = $this->input->post("subtotal");
		$itbis = $this->input->post("itbis");
		$productos = $this->input->post("productos");
		$precios = $this->input->post("precios");
		$cantidades = $this->input->post("cantidades");
		$importes = $this->input->post("importes");
		$num_comprobante = $this->input->post("num_comprobante");
		$dataComprobante = explode(" - ", $num_comprobante);

		$data = array(
			'fecha' => date("Y-m-d"),
			'monto' => $total,
			'subtotal' => $subtotal,
			'itbis' => $itbis,
		);
		if ($this->Ventas_model->update($idVenta, $data)) {

			$this->returnStockVenta($idVenta);
			$this->Ventas_model->deleteDetalleVenta($idVenta);
			$this->save_detalle($productos,$idVenta,$precios,$cantidades,$importes);
			$this->reset_stock_negative();


			$this->session->set_flashdata("success", "Se ha modificado la informaicon de la venta -  ".$dataComprobante[1]);

			redirect(base_url()."movimientos/ventas");
		}else{
			$this->session->set_flashdata("error", "No se pudo actualizar la venta - ".$venta->serie.$venta->numero);

			redirect(base_url()."movimientos/ventas");
		}


	}

	public function reset_stock_negative(){
		$data = array(
			"stock" => 0
		);
		$products = $this->Productos_model->resetear_stock_negative($data);
	}

	public function anular($idventa){
		$data = array(
			'estado' => 0
		);

		if ($this->Ventas_model->update($idventa, $data)) {
			$this->returnStockVenta($idventa);
			$dataJSON = array(
				'status' => 1,
				'message' => "La venta fue anulada"
			);
		}else{
			$dataJSON = array(
				'status' => 0,
				'message' => "No se pudo anular la venta"
			);
		}

		echo json_encode($dataJSON);
	}

	protected function returnStockVenta($idventa){
		$detalles = $this->Ventas_model->getDetalle($idventa);

		foreach ($detalles as $detalle) {
			$infoProducto = $this->Productos_model->getProducto($detalle->producto_id);

			$data = array(
				"stock" => $infoProducto->stock + $detalle->cantidad
			);

			$this->Productos_model->update($infoProducto->id, $data);
		}
	}

	protected function save_detalle($productos,$idventa,$precios,$cantidades,$importes){
		for ($i=0; $i < count($productos); $i++) { 
			$data  = array(
				'producto_id' => $productos[$i], 
				'venta_id' => $idventa,
				'precio' => $precios[$i],
				'cantidad' => $cantidades[$i],
				'importe'=> $importes[$i],
			);

			$this->Ventas_model->save_detalle($data);
			$this->updateProducto($productos[$i],$cantidades[$i]);

		}
	}

	protected function updateProducto($idproducto,$cantidad){
		$productoActual = $this->Productos_model->getProducto($idproducto);
		$data = array(
			'stock' => $productoActual->stock - $cantidad, 
		);
		$this->Productos_model->update($idproducto,$data);
	}

	public function view(){
		$idventa = $this->input->post("id");
		$data = array(
			"venta" => $this->Ventas_model->getVenta($idventa),
			"detalles" =>$this->Ventas_model->getDetalle($idventa)
		);
		$this->load->view("admin/ventas/view",$data);
	}

	public function getProductosByCategoria(){
		$idcategoria = $this->input->post("idcategoria");
		$productos = $this->Ventas_model->getProductosByCategoria($idcategoria);
		echo json_encode($productos);
	}

	public function getProductoByCode(){
		$codigo_barra = $this->input->post("codigo_barra");
		$producto = $this->Ventas_model->getProductoByCode($codigo_barra);

		if ($producto != false) {
			echo json_encode($producto);
		}else{
			echo "0";
		}
	}

	public function searchRNC(){
		$rnc = $this->input->post('rnc');
		$cliente = $this->Ventas_model->searchRNC($rnc);
		if (!$cliente) {
			echo "0";
		} else {
			echo json_encode($cliente);
		}
	}

	public function searchCedula(){
		$cedula = $this->input->post('cedula');
		$cliente = $this->Ventas_model->searchCedula($cedula);
		if (!$cliente) {
			echo "0";
		} else {
			echo json_encode($cliente);
		}
	}

	protected function generarNumero($comprobante_id){
		$comprobante = $this->Comprobantes_model->getComprobante($comprobante_id);
		return str_pad($comprobante->numero_inicial + $comprobante->cantidad_realizada, 8, "0", STR_PAD_LEFT);
	}

	protected function updateComprobante($comprobante_id){
		$comprobante = $this->Comprobantes_model->getComprobante($comprobante_id);
		$data = [
			'cantidad_realizada' => $comprobante->cantidad_realizada + 1
		];

		$this->Comprobantes_model->update($comprobante_id,$data);
	}

	public function savePendiente(){
		$fecha = date("Y-m-d H:i:s");
		$total = $this->input->post("total");
		$idcomprobante = $this->input->post("idcomprobante");
		$idcliente = $this->input->post("idcliente");
		$numero_mesa = $this->input->post("numero_mesa");
		$subtotal = $this->input->post("subtotal");
		$itbis = $this->input->post("itbis");
		$idVentaPendiente = $this->input->post("idVentaPendiente");

		$productos = $this->input->post("productos");
		$precios = $this->input->post("precios");
		$cantidades = $this->input->post("cantidades");
		$importes = $this->input->post("importes");

		$data = array(
			'fecha' => $fecha,
			'monto' => $total,
			'comprobante_id' => $idcomprobante,
			'cliente_id' => $idcliente,
			'numero_mesa' => $numero_mesa,
			'subtotal' => $subtotal,
			'itbis' => $itbis,
		);
		if (!empty($idVentaPendiente)) {
			if ($this->Ventas_model->updateVentaPendiente($idVentaPendiente,$data)) {
				$this->Ventas_model->deleteVentaPendienteDetalle($idVentaPendiente);
				$this->save_detalle_pendiente($productos,$idVentaPendiente,$precios,$cantidades,$importes);
				echo '1';

			}else{
				echo '0';
			}
		}else{
			$venta_pendiente_id = $this->Ventas_model->savePendiente($data);
			if ($venta_pendiente_id != false) {
				//$this->updateComprobante($idcomprobante);
				$this->save_detalle_pendiente($productos,$venta_pendiente_id,$precios,$cantidades,$importes);
				echo '1';

			}else{
				echo '0';
			}
		}
		
	}

	protected function save_detalle_pendiente($productos,$idventapendiente,$precios,$cantidades,$importes){
		for ($i=0; $i < count($productos); $i++) { 
			$data  = array(
				'producto_id' => $productos[$i], 
				'venta_pendiente_id' => $idventapendiente,
				'precio' => $precios[$i],
				'cantidad' => $cantidades[$i],
				'importe'=> $importes[$i],
			);

			$this->Ventas_model->save_detalle_pendiente($data);

		}
	}

	public function printVenta($idventa){
		$this->load->library("EscPos.php");
		$connector = new Escpos\PrintConnectors\WindowsPrintConnector("POS-58C");
		try {
			$venta = $this->Ventas_model->getVenta($idventa);
			$detalles = $this->Ventas_model->getDetalle($idventa);
			
			/*$connector = new Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.43", 9100);*/
			/* Information for the receipt */
			$items = array();
			foreach ($detalles as $detalle) {
				$items[] = new item($detalle->cantidad,$detalle->nombre,$detalle->importe);
			}
			
			$printer = new Escpos\Printer($connector);
			$printer -> setJustification(Escpos\Printer::JUSTIFY_CENTER);
			
			/* Name of shop */
			$printer -> selectPrintMode();
			$printer -> text($this->configuraciones_lib->getEmpresa()."\n");
			$printer -> selectPrintMode();
			$printer -> text($this->configuraciones_lib->getDescripcion()."\n");
			$printer -> text("RNC: ".$this->configuraciones_lib->getRNC()."\n");
			$printer -> text("TELEFONO: ".$this->configuraciones_lib->getTelefono()."\n");
			$printer -> text($this->configuraciones_lib->getDireccion()."\n");
			$printer -> setJustification(Escpos\Printer::JUSTIFY_RIGHT);
			$printer -> text(date('d/m/Y', strtotime($venta->fecha))."\n");
			$printer -> feed();
			/* Title of receipt */
			$printer -> setJustification(Escpos\Printer::JUSTIFY_CENTER);
			$printer -> setEmphasis(true);
			$printer -> text($venta->comprobante."\n");
			$printer -> setEmphasis(false);
			$printer -> text($venta->serie.$venta->numero."\n");
			$printer -> feed();

			if ($venta->cliente_id != 0) {
				$printer -> setEmphasis(true);
				$printer -> text("CLIENTE\n");
				$printer -> setEmphasis(false);
				$printer -> text("RNC: ".$venta->rnc."\n");
				$printer -> text("Razon Social: ".$venta->razon_social."\n");
				$printer -> feed();
			}
			$printer->setJustification(Escpos\Printer::JUSTIFY_LEFT);
			$printer->setEmphasis(true);
			$printer->text($this->addSpaces('CANT.', 7) . $this->addSpaces('DESCRIPCION', 15) . $this->addSpaces('IMPORTE', 8,LEFT) . "\n");
			/* Items */
			$printer -> setEmphasis(false);
			foreach ($items as $item) {
			    $printer -> text($item);
			}
			$printer -> setEmphasis(true);
			$printer -> text($this->addSpaces('SUBTOTAL',20,LEFT).$this->addSpaces($venta->subtotal,12,LEFT)."\n");
			$printer -> text($this->addSpaces('ITBIS',20,LEFT).$this->addSpaces($venta->itbis,12,LEFT)."\n");
			$printer -> text($this->addSpaces('TOTAL',20,LEFT).$this->addSpaces($venta->monto,12,LEFT)."\n");
			$printer -> setEmphasis(false);
			$printer -> feed();
			$printer -> setJustification(Escpos\Printer::JUSTIFY_CENTER);
			$printer -> text("Gracias por su preferencia\n");
			$printer -> feed();
			$printer -> feed();
			
			/* Cut the receipt and open the cash drawer */
			$printer -> cut();
			$printer -> pulse();
			$printer -> close();
			/* A wrapper to do organise item names & prices into columns */
			$this->session->set_flashdata("success", "Se imprimio la venta ".$venta->serie.$venta->numero);

			redirect(base_url()."movimientos/ventas");
		} catch (Exception $e) {
			//echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
			$this->session->set_flashdata("error",$e -> getMessage());

			redirect(base_url()."movimientos/ventas");
		}


	}

	protected function addSpaces($text,$length,$dir = RIGHT,$character =' '){
		if ($dir == LEFT) {
			return str_pad($text, $length, $character, STR_PAD_LEFT);
		}else{
			return str_pad($text, $length); 
		}
		
	}

	public function aperturarCaja(){
		$cajaHoy = $this->Ventas_model->getInfoCaja(date("Y-m-d"));

		if ($cajaHoy) {
			$data = array(
				'fecha' => date("Y-m-d H:i:s"),
				'estado' => 1,
				'user_created' => $this->session->userdata('id')
			);

			if ($this->Ventas_model->cerrarCaja($cajaHoy->id,$data)) {
				$this->session->set_flashdata("success","La caja hoy fue apertura");
				redirect(base_url()."movimientos/ventas");
			} else {
				$this->session->set_flashdata("error","La caja no se pudo aperturar");
				redirect(base_url()."movimientos/ventas");
			}
		}else{
			$data = array(
				'fecha' => date("Y-m-d H:i:s"),
				'estado' => 1,
				'user_created' => $this->session->userdata('id')
			);

			if ($this->Ventas_model->aperturarCaja($data)) {
				$this->session->set_flashdata("success","La caja hoy fue apertura");
				redirect(base_url()."movimientos/ventas");
			} else {
				$this->session->set_flashdata("error","La caja no se pudo aperturar");
				redirect(base_url()."movimientos/ventas");
			}
		}
	}

	public function cerrarCaja(){
		$cajaHoy = $this->Ventas_model->getInfoCaja(date("Y-m-d"));
		$data = array(
			'fecha' => date("Y-m-d H:i:s"),
			'estado' => 0,
			'user_updated' => $this->session->userdata('id')
		);

		if ($this->Ventas_model->cerrarCaja($cajaHoy->id,$data)) {
			$this->session->set_flashdata("success","La caja hoy fue cerrada");
			redirect(base_url()."movimientos/ventas");
		} else {
			$this->session->set_flashdata("error","La caja no se pudo cerrar");
			redirect(base_url()."movimientos/ventas");
		}
	}

}

class item
{
    private $quantity;
    private $name;
    private $amount;
    public function __construct($quantity = '', $name = '', $amount = '')
    {
        $this -> quantity = $quantity;
        $this -> name = $name;
        $this -> amount = $amount;
    }
    
    public function __toString()
    {
        $numberColsQuantity = 6;
        $numberColsName = 20;
        $numberColsAmount = 6;
    
        $quantity = str_pad($this -> quantity, $numberColsQuantity) ;
        $name = str_pad($this -> name, $numberColsName) ;
       
        $amount = str_pad($this -> amount, $numberColsAmount, ' ', STR_PAD_LEFT);
        return "$quantity$name$amount\n";
    }
}