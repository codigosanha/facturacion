<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Abonos extends CI_Controller {

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
		$fechainicio = date("Y-m-d");
		$fechafin = date("Y-m-d");
		if ($this->input->post("fechainicio") && $this->input->post("fechafin")) {
			$fechainicio = $this->input->post("fechainicio");
			$fechafin = $this->input->post("fechafin");
		}
		$data = array(
			"clientes" => $this->Ventas_creditos_model->getClientesDeudores($fechainicio,$fechafin),
			"fechainicio" => $fechainicio,
			"fechafin" => $fechafin
			
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/reportes/abonos",$data);
		$this->load->view("layouts/footer");

	}

}
 