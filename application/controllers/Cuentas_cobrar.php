<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuentas_cobrar extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->session->userdata("login")) {
			redirect(base_url());
		}
		$this->load->model("Ventas_creditos_model");
		$this->load->model("Cuentas_cobrar_model");
		$this->load->model("Pagos_model");
		$this->load->helper("get_names_tables_relationship");
	}
	public function index()
	{
		$data = array(
			"clientes" => $this->Ventas_creditos_model->getClientesDeudores(),
			
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/cuentas_cobrar/list",$data);
		$this->load->view("layouts/footer");

	}

	public function getCuentas($cliente){
		$cuentas = $this->Cuentas_cobrar_model->getCuentasByCliente($cliente);
		echo json_encode($cuentas);
	}

	public function abonar(){
		$cuentas = $this->input->post("cuentas");
		$montos = $this->input->post("montos");
		$abonar = $this->input->post("abonar");
	
		$pagados = $this->input->post("pagados");

		$i = 0;
		while ($abonar > 0) {//abonar = 150
			$monto_pagar = $montos[$i] - $pagados[$i];//100
			if ($abonar >= $monto_pagar) {
				$condicion = 1;
				$pagado = $monto_pagar;
			}else{
				$condicion = 0;
				$pagado = $abonar;
			}

			$data = array(
				'pagado' => $pagado,
				'condicion' => $condicion,
			);

			$dataPago = array(
				'cuenta_id' => $cuentas[$i],
				'monto' => $pagado,
				'fecha' => date("Y-m-d H:i:s")
			);

			$this->Ventas_creditos_model->update($cuentas[$i], $data);
			$this->Pagos_model->save($dataPago);

			$abonar = $abonar - $monto_pagar;
			$i++;
		}

		
		$this->session->set_flashdata("success", "El abono ha sido registrado");

		redirect(base_url()."cuentas_cobrar");
	}


	public function getPagos($idCuenta){
		$pagos = $this->Pagos_model->getPagos($idCuenta);
		echo json_encode($pagos);
	}

	public function saldarCuentas(){
		$cuentas = $this->input->post("cuentas");
		$montos = $this->input->post("montos");
		$pagados = $this->input->post("pagados");

		for ($i=0; $i < count($cuentas); $i++) { 
			$dataVenta = array(
				'pagado' => $montos[$i],
				'condicion' => 1
			);

			$this->Ventas_creditos_model->update($cuentas[$i],$dataVenta);

			$montoPago = $montos[$i] - $pagados[$i];
			$dataPago = array(
				'fecha' => date("Y-m-d H:i:s"),
				'monto' => $montoPago,
				'cuenta_id' => $cuentas[$i]
			);

			$this->Pagos_model->save($dataPago);
		}

		echo "1";
	}

}
