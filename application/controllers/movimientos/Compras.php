<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends CI_Controller {
	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Ventas_model");
		
		$this->load->model("Productos_model");
		$this->load->model("Categorias_model");
		$this->load->model("Comprobantes_model");
		$this->load->model("Compras_model");
		$this->load->helper("get_names_tables_relationship");
		$this->load->library("Configuraciones_lib");
	}

	public function index(){
		$data  = array(
			'permisos' => $this->permisos, 
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/compras/list",$data);
		$this->load->view("layouts/footer");
	}

	public function getComprasAll()
	{
		
		$columns = array( 
            0 => 'id', 
            1 => 'numero', 
            2 => 'comprobante',
            3 => 'proveedor',
            4 => 'fecha',
            5 => 'total',
            6 => 'estado',
        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Compras_model->allcompras_count();
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $compras = $this->Compras_model->allcompras($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $compras =  $this->Compras_model->compras_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Compras_model->compras_search_count($search);
        }

        $data = array();
        if(!empty($compras))
        {
            foreach ($compras as $compra)
            {

                $nestedData['id'] = $compra->id;
                $nestedData['numero_comprobante'] = $compra->numero_comprobante;
                $nestedData['numero_factura'] = $compra->numero_factura;
                $nestedData['tipo_pago'] = $compra->tipo_pago==1 ? 'Efectivo':'Credito';
                $nestedData['comprobante'] = getComprobante($compra->comprobante_id)->nombre;
                $nestedData['proveedor'] = getProveedor($compra->proveedor_id)->razon_social;
                $nestedData['fecha'] = $compra->fecha;
                $nestedData['total'] = $compra->total;
                $nestedData['estado'] = $compra->estado;
                
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
	public function add(){
		$data = array(
			"comprobantes" => $this->Ventas_model->getComprobantes(),
			"proveedores" => $this->Compras_model->getProveedores(),
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/compras/add",$data);
		$this->load->view("layouts/footer");
	}

	public function store(){
		$fecha = $this->input->post("fecha");
		$total = $this->input->post("total");
		$subtotal = $this->input->post("subtotal");
		$itbis = $this->input->post("itbis");
		$tipo_pago = $this->input->post("tipo_pago");
		$idcomprobante = $this->input->post("idcomprobante");
		$numero_comprobante = $this->input->post("numero_comprobante");
		$numero_factura = $this->input->post("numero_factura");
		$idproveedor = $this->input->post("proveedor");
		$idusuario = $this->session->userdata("id");

		$productos = $this->input->post("idproductos");
		$precios_compras = $this->input->post("precios_compras");
		$precios_ventas = $this->input->post("precios_ventas");
		$cantidades = $this->input->post("cantidades");
		$importes = $this->input->post("importes");

		$data = array(
			'fecha' => $fecha,
			'total' => $total,
			'comprobante_id' => $idcomprobante,
			'proveedor_id' => $idproveedor,
			'usuario_id' => $idusuario,
			'numero_comprobante' => strtoupper($numero_comprobante),
			'numero_factura' => strtoupper($numero_factura),
			'subtotal' => $subtotal,
			'itbis' => $itbis,
			'estado'=> 1,
			'tipo_pago' => $tipo_pago,
		);

		if ($this->Compras_model->save($data)) {
			$idcompra = $this->Compras_model->lastID();
			//$this->updateComprobante($idcomprobante);
			$this->save_detalle($productos,$idcompra,$precios_compras,$precios_ventas,$cantidades,$importes);
			
			$this->printCompra($idcompra);

		}else{
			redirect(base_url()."movimientos/compras/add");
		}
	}

	public function edit($idcompra){
		$data = array(
			'compra' => $this->Compras_model->getCompra($idcompra),
			'detalles' => $this->Compras_model->getDetalle($idcompra),
			'proveedores' => $this->Compras_model->getProveedores(),
			'comprobantes' => $this->Comprobantes_model->getComprobantes(),
		);

		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/compras/edit",$data);
		$this->load->view("layouts/footer");
	}

	public function update(){
		$idCompra = $this->input->post("idCompra");
		$fecha = $this->input->post("fecha");
		$total = $this->input->post("total");
		$subtotal = $this->input->post("subtotal");
		$itbis = $this->input->post("itbis");
		$tipo_pago = $this->input->post("tipo_pago");
		$idcomprobante = $this->input->post("comprobante");
		$numero_comprobante = $this->input->post("numero_comprobante");
		$numero_factura = $this->input->post("numero_factura");
		$idproveedor = $this->input->post("proveedor");
		$idusuario = $this->session->userdata("id");

		$productos = $this->input->post("idproductos");
		$precios_compras = $this->input->post("precios_compras");
		$precios_ventas = $this->input->post("precios_ventas");
		$cantidades = $this->input->post("cantidades");
		$importes = $this->input->post("importes");

		$data = array(
			'fecha' => $fecha,
			'total' => $total,
			'comprobante_id' => $idcomprobante,
			'proveedor_id' => $idproveedor,
			'usuario_id' => $idusuario,
			'numero_comprobante' => strtoupper($numero_comprobante),
			'numero_factura' => strtoupper($numero_factura),
			'subtotal' => $subtotal,
			'itbis' => $itbis,
			'tipo_pago' => $tipo_pago,
		);
		if ($this->Compras_model->update($idCompra, $data)) {

			$this->returnStockCompra($idCompra);
			$this->Compras_model->deleteDetalleCompra($idCompra);
			$this->save_detalle($productos,$idCompra,$precios_compras,$precios_ventas,$cantidades,$importes);


			$this->session->set_flashdata("success", "Se ha modificado la informacion de la compra - ".strtoupper($numero));

			redirect(base_url()."movimientos/compras");
		}else{
			$this->session->set_flashdata("error", "No se pudo actualizar la compra - ".strtoupper($numero));

			redirect(base_url()."movimientos/compras");
		}


	}

	public function anular($idcompra){
		$data = array(
			'estado' => 0
		);

		if ($this->Compras_model->update($idcompra, $data)) {
			$this->returnStockCompra($idcompra);
			$dataJSON = array(
				'status' => 1,
				'message' => "La compra fue anulada"
			);
		}else{
			$dataJSON = array(
				'status' => 0,
				'message' => "No se pudo anular la compra"
			);
		}
		echo json_encode($dataJSON);
	}

	protected function returnStockCompra($idcompra){
		$detalles = $this->Compras_model->getDetalle($idcompra);

		foreach ($detalles as $detalle) {
			$infoProducto = $this->Productos_model->getProducto($detalle->producto_id);

			$data = array(
				"stock" => $infoProducto->stock - $detalle->cantidad
			);

			$this->Productos_model->update($infoProducto->id, $data);
		}
	}

	protected function save_detalle($productos,$idcompra,$precios_compras,$precios_ventas,$cantidades,$importes){
		for ($i=0; $i < count($productos); $i++) { 
			$data  = array(
				'producto_id' => $productos[$i], 
				'compra_id' => $idcompra,
				'cantidad' => $cantidades[$i],
				'importe' => $importes[$i]
			);

			$this->Compras_model->save_detalle($data);
			$this->updateProducto($productos[$i],$cantidades[$i],$precios_compras[$i],$precios_ventas[$i]);

		}
	}

	protected function updateProducto($idproducto,$cantidad,$precio_compra, $precio_venta){
		$productoActual = $this->Productos_model->getProducto($idproducto);
		$data = array(
			'stock' => $productoActual->stock + $cantidad, 
			'precio' => $precio_venta,
			'precio_compra' => $precio_compra
		);
		$this->Productos_model->update($idproducto,$data);
	}

	public function view(){
		$idcompra = $this->input->post("id");
		$data = array(
			"compra" => $this->Compras_model->getCompra($idcompra),
			"detalles" =>$this->Compras_model->getDetalle($idcompra)
		);
		$this->load->view("admin/compras/view",$data);
	}


	public function getProductoByCode(){
		$codigo_barra = $this->input->post("codigo_barra");
		$producto = $this->Compras_model->getProductoByCode($codigo_barra);

		if ($producto != false) {
			echo json_encode($producto);
		}else{
			echo "0";
		}
	}

	public function getProductos(){
		$valor = $this->input->post("valor");
		$productos = $this->Compras_model->getProductos($valor);
		echo json_encode($productos);
	}


	public function printCompra($idCompra){
		$this->load->library("EscPos.php");
		try {
			$compra = $this->Compras_model->getCompra($idCompra);
			$detalles = $this->Compras_model->getDetalle($idCompra);
			$connector = new Escpos\PrintConnectors\WindowsPrintConnector("LPT1");
			//$connector = new Escpos\PrintConnectors\NetworkPrintConnector("192.168.1.43", 9100);
			/* Information for the receipt */
			$items = array();
			foreach ($detalles as $detalle) {
				$items[] = new item($detalle->cantidad,$detalle->nombre,$detalle->importe);
			}
			
			$printer = new Escpos\Printer($connector);
			$printer -> setJustification(Escpos\Printer::JUSTIFY_CENTER);
			
			/* Name of shop */
			$printer -> selectPrintMode();
			$printer -> text("PANADERIA Y REPOSTERIA\n");
			$printer -> selectPrintMode();
			$printer -> text("EL SABOR DE MI TIA GRACITA\n");
			$printer -> text("RNC: 1300991482\n");
			$printer -> text("TELEFONO: 829-847-2333\n");
			$printer -> text("COTUI LACUEVA RD\n");
			$printer -> setJustification(Escpos\Printer::JUSTIFY_RIGHT);
			$printer -> text(date('d/m/Y', strtotime($compra->fecha))."\n");
			$printer -> feed();
			/* Title of receipt */
			$printer -> setJustification(Escpos\Printer::JUSTIFY_CENTER);
			$printer -> setEmphasis(true);
			$printer -> text($compra->comprobante."\n");
			$printer -> setEmphasis(false);
			$printer -> text($compra->numero_comprobante."\n");
			$printer -> feed();
			$printer -> setEmphasis(true);
			$printer -> text("N° FACTURA\n");
			$printer -> setEmphasis(false);
			$printer -> text($compra->numero_factura."\n");
			$printer -> feed();

			$printer -> setEmphasis(true);
			$printer -> text("TIPO DE PAGO\n");
			$printer -> setEmphasis(false);
			$printer -> text($compra->tipo == 1 ? 'EFECTIVO':'CREDITO'."\n");
			$printer -> feed();


			if ($compra->proveedor_id != 0) {
				$printer -> setEmphasis(true);
				$printer -> text("PROVEEDOR\n");
				$printer -> setEmphasis(false);
				$printer -> text("RNC: ".$compra->rnc."\n");
				$printer -> text("Razon Social: ".$compra->razon_social."\n");
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
			$printer -> text($this->addSpaces('SUBTOTAL',20,LEFT).$this->addSpaces($compra->subtotal,10,LEFT)."\n");
			$printer -> text($this->addSpaces('ITBIS',20,LEFT).$this->addSpaces($compra->itbis,10,LEFT)."\n");
			$printer -> text($this->addSpaces('TOTAL',20,LEFT).$this->addSpaces($compra->monto,10,LEFT)."\n");
			$printer -> setEmphasis(false);
			$printer -> feed();
			
			
			/* Cut the receipt and open the cash drawer */
			$printer -> cut();
			$printer -> pulse();
			$printer -> close();
			/* A wrapper to do organise item names & prices into columns */
			$this->session->set_flashdata("success", "Se imprimio la compra N° ".$compra->id);

			redirect(base_url()."movimientos/compras");
		} catch (Exception $e) {
			//echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
			$this->session->set_flashdata("error", "Error al intentar imprimir una compra");

			redirect(base_url()."movimientos/compras");
		}


	}

	protected function addSpaces($text,$length,$dir = RIGHT,$character =' '){
		if ($dir == LEFT) {
			return str_pad($text, $length, $character, STR_PAD_LEFT);
		}else{
			return str_pad($text, $length); 
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
        $numberColsQuantity = 7;
        $numberColsName = 15;
        $numberColsAmount = 8;
    
        $quantity = str_pad($this -> quantity, $numberColsQuantity) ;
        $name = str_pad($this -> name, $numberColsName) ;
       
        $amount = str_pad($this -> amount, $numberColsAmount, ' ', STR_PAD_LEFT);
        return "$quantity$name$amount\n";
    }

    public function getProductoByCode(){
		$codigo_barra = $this->input->post("codigo_barra");
		$producto = $this->Compras_model->getProductoByCode($codigo_barra);

		if ($producto != false) {
			echo json_encode($producto);
		}else{
			echo "0";
		}
	}
}