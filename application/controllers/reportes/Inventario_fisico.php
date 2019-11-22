<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario_fisico extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->session->userdata("login")) {
			redirect(base_url());
		}
		$this->load->model("Productos_model");

	}
	public function index()
	{
		
		if ($this->input->post("busqueda")) {
			if ($this->input->post("busqueda") == 1) {
				$productos = $this->Productos_model->getProductos();
			}elseif ($this->input->post("busqueda") == 2) {
				$productos = $this->Productos_model->getProductos(2);
			}else{
				$productos = $this->Productos_model->getProductos(3);
			}
		}else{
			$productos = $this->Productos_model->getProductos();
		}
		$data = array(
			"productos" => $productos,
			
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/inventario_fisico/list",$data);
		$this->load->view("layouts/footer");

	}
}
