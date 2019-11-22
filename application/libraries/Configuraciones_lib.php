<?php

class Configuraciones_lib{
	private $CI;
	protected $empresa = "Empresa X";
	protected $descripcion = "Descripcion de la empresa";
	protected $telefono = "555-555-555";
	protected $direccion = "Calle Callao 430";
	protected $itbis= "18";
	protected $rnc= "1020102010";

	public function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->model("Configuraciones_model");
		$configuracion = $this->CI->Configuraciones_model->getConfiguracion();
		if ($configuracion != false) {
			$this->empresa = $configuracion->empresa;
			$this->descripcion = $configuracion->descripcion;
			$this->rnc = $configuracion->rnc;  
			$this->direccion = $configuracion->direccion; 
			$this->telefono = $configuracion->telefono; 
			$this->itbis = $configuracion->itbis; 
		}

	}

	public function getEmpresa(){
		return $this->empresa;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}
	public function getDireccion(){
		return $this->direccion;
	}
	public function getTelefono(){
		return $this->telefono;
	}
	public function getRNC(){
		return $this->rnc;
	}
	public function getITBIS(){
		return $this->itbis;
	}

}