<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuentas_cobrar extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->session->userdata("login")) {
			redirect(base_url());
		}
		$this->load->model("Ventas_creditos_model");
		$this->load->model("Pagos_model");
		$this->load->helper("get_names_tables_relationship");
	}
	public function index()
	{
		$data = array(
			"ventas" => $this->Ventas_creditos_model->getVentas(),
			
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/cuentas_cobrar/list",$data);
		$this->load->view("layouts/footer");

	}

	public function abonar(){
		$idCuenta = $this->input->post("idCuenta");
		$monto = $this->input->post("monto");
		$abonar = $this->input->post("abonar");
		$pagado = $this->input->post("pagado");

		$condicion = 0;
		$calculo = $monto - ($abonar + $pagado);
		$pagado = $pagado + $abonar;
		if ($calculo == 0) {
			$condicion = 1;
		}
		
		$data = array(
			'pagado' => $pagado,
			'condicion' => $condicion,
		);

		$dataPago = array(
			'cuenta_id' => $idCuenta,
			'monto' => $abonar,
			'fecha' => date("Y-m-d H:i:s")
		);

		if ($this->Ventas_creditos_model->update($idCuenta, $data)) {
			$this->Pagos_model->save($dataPago);
			$this->session->set_flashdata("success", "El abono ha sido registrado");

			redirect(base_url()."cuentas_cobrar");
		}else{
			$this->session->set_flashdata("error", "El abono no ha sido registrado");

			redirect(base_url()."cuentas_cobrar");
		}
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
