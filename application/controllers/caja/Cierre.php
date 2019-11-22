<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cierre extends CI_Controller {

	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Caja_model");
	}

	public function index()
	{
		$date = date("Y-m-d");
		if($this->input->post('buscar')){
			$date = $this->input->post('fecha');
		}

		$dateSelected = new DateTime($date);
		$data  = array(
			'permisos' => $this->permisos,
			'productos' => $this->Caja_model->getCierre($this->session->userdata('id'),$date), 
			'dateSelected' => $dateSelected->format("d-m-Y"),
			'date' => $date
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/caja/cierre",$data);
		$this->load->view("layouts/footer");

	}

}
