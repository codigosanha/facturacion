<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuraciones extends CI_Controller {
	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Permisos_model");
		$this->load->model("Usuarios_model");
		$this->load->model("Configuraciones_model");
	}

	public function index(){
		$data  = array(
			'permisos' => $this->permisos,
			'configuracion' => $this->Configuraciones_model->getConfiguracion()
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/configuraciones/info",$data);
		$this->load->view("layouts/footer");
	}

	public function save(){
		$empresa = $this->input->post("empresa");
		$telefono = $this->input->post("telefono");
		$rnc = $this->input->post("rnc");
		$direccion = $this->input->post("direccion");
		$descripcion = $this->input->post("descripcion");
		$itbis = $this->input->post("itbis");
		$idConfiguracion = $this->input->post("idConfiguracion");

		$data = array(
			"empresa" => $empresa,
			"telefono" => $telefono,
			"rnc" => $rnc,
			"direccion" => $direccion,
			"itbis" => $itbis,
			"descripcion" => $descripcion,
		);

		if (!empty($idConfiguracion)) {
			$status = $this->Configuraciones_model->update($data);
		}else{
			$status = $this->Configuraciones_model->save($data);
		}

		if ($status) {
			$this->session->set_flashdata("success","Se ha guardado la informacion correctamente");
			redirect(base_url()."administrador/configuraciones");
		}else{
			$this->session->set_flashdata("error","No se pudo guardar la informacion");
			redirect(base_url()."administrador/configuraciones");
		}
		
	}

	public function edit($id){
		$data  = array(
			'comprobante' => $this->Comprobantes_model->getComprobante($id), 
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/comprobantes/edit",$data);
		$this->load->view("layouts/footer");
	}

	public function update(){
		$idcomprobante = $this->input->post("idcomprobante");
		$nombre = $this->input->post("nombre");
		$numero_inicial = $this->input->post("numero_inicial");
		$serie = $this->input->post("serie");
		$limite = $this->input->post("limite");
		$aviso = $this->input->post("aviso");
		$solicitar_cliente = $this->input->post("solicitar_cliente");
		$data = array(
			"nombre" => $nombre,
			"serie" => strtoupper($serie),
			"numero_inicial" => $numero_inicial,
			"limite" => $limite,
			"aviso" => $aviso,
			"solicitar_cliente" => $solicitar_cliente,
		);

		if ($this->Comprobantes_model->update($idcomprobante,$data)) {
			redirect(base_url()."administrador/comprobantes");
		}else{
			$this->session->set_flashdata("error","No se pudo guardar la informacion");
			redirect(base_url()."administrador/comprobantes/edit/".$idcomprobante);
		}
	}

	public function delete($id){
		$this->Comprobantes_model->delete($id);
		redirect(base_url()."administrador/comprobantes");
	}

	public function establecerPredeterminado(){
		$comprobante = $this->input->post("comprobante");
		$data = [
			"predeterminado" => 1
		];

		if ($this->Comprobantes_model->update($comprobante,$data)) {
			$data = [
				'predeterminado' => 0
			];
			$this->Comprobantes_model->removePredeterminado($comprobante,$data);
			$this->session->set_flashdata("success","Se establecio el comprobante predeterminado");
			redirect(base_url()."administrador/comprobantes");
		} else {
			$this->session->set_flashdata("error","No se pudo establecer el comprobante predeterminado");
			redirect(base_url()."administrador/comprobantes");
		}
	}
}