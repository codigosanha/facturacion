<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {
	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Ventas_model");
		$this->load->library("Configuraciones_lib");
	}

	public function index(){
		$fechainicio = $this->input->post("fechainicio");
		$fechafin = $this->input->post("fechafin");
		if ($this->input->post("buscar")) {
			$ventas = $this->Ventas_model->getVentasbyDate($fechainicio,$fechafin);
		}
		else{
			$ventas = $this->Ventas_model->getVentas();
		}
		$data = array(
			"ventas" => $ventas,
			"fechainicio" => $fechainicio,
			"fechafin" => $fechafin
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/reportes/ventas",$data);
		$this->load->view("layouts/footer");
	}

	protected function addSpaces($text,$length,$dir = RIGHT,$character =' '){
		if ($dir == LEFT) {
			return str_pad($text, $length, $character, STR_PAD_LEFT);
		}else{
			return str_pad($text, $length); 
		}
		
	}

	public function printVenta($idventa){
		$venta = json_encode($this->Ventas_model->getVenta($idventa));
		$detalles = json_encode($this->Ventas_model->getDetalle($idventa));
		redirect("http://localhost/test/imprimir/?venta=$venta&detalles=$detalles");

		//header("location:http://localhost/test/print");
	}

	public function printVenta2($idventa){
		$this->load->library("EscPos.php");
		try {
			$venta = $this->Ventas_model->getVenta($idventa);
			$detalles = $this->Ventas_model->getDetalle($idventa);
			$connector = new Escpos\PrintConnectors\WindowsPrintConnector("LPT1");
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
			$printer -> text($this->addSpaces('SUBTOTAL',20,LEFT).$this->addSpaces($venta->subtotal,10,LEFT)."\n");
			$printer -> text($this->addSpaces('ITBIS',20,LEFT).$this->addSpaces($venta->itbis,10,LEFT)."\n");
			$printer -> text($this->addSpaces('TOTAL',20,LEFT).$this->addSpaces($venta->monto,10,LEFT)."\n");
			$printer -> setEmphasis(false);
			$printer -> feed();
			$printer -> setJustification(Escpos\Printer::JUSTIFY_CENTER);
			$printer -> text("Gracias por su preferencia\n");
			$printer -> feed();
			$printer -> feed();
			$printer -> feed();
			$printer -> feed();
			
			/* Cut the receipt and open the cash drawer */
			$printer -> cut();
			$printer -> pulse();
			$printer -> close();
			/* A wrapper to do organise item names & prices into columns */
			$this->session->set_flashdata("success", "Se imprimio la venta ".$venta->serie.$venta->numero);

			redirect(base_url()."reportes/ventas");
		} catch (Exception $e) {
			//echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
			$this->session->set_flashdata("error", "Error al intentar imprimir una venta");

			redirect(base_url()."reportes/ventas");
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
        $numberColsQuantity = 5;
        $numberColsName = 13;
        $numberColsAmount = 5;
    
        $quantity = str_pad($this -> quantity, $numberColsQuantity) ;
        $name = str_pad($this -> name, $numberColsName) ;
       
        $amount = str_pad($this -> amount, $numberColsAmount, ' ', STR_PAD_LEFT);
        return "$quantity$name$amount\n";
    }
}