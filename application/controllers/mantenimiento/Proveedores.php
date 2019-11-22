<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores extends CI_Controller {

	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Proveedores_model");
	}

	public function index()
	{
		$data  = array(
			'permisos' => $this->permisos, 
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/proveedores/list",$data);
		$this->load->view("layouts/footer");

	}

	public function getProveedores()
	{

		$columns = array( 
                            0 =>'id', 
                            1 =>'rnc',
                            2=> 'razon_social',
                            3=> 'id',
                        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Proveedores_model->allproveedores_count();
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $proveedores = $this->Proveedores_model->allproveedores($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $proveedores =  $this->Proveedores_model->proveedores_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Proveedores_model->proveedores_search_count($search);
        }

        $data = array();
        if(!empty($proveedores))
        {
            foreach ($proveedores as $proveedor)
            {

                $nestedData['id'] = $proveedor->id;
                $nestedData['rnc'] = $proveedor->rnc;
                $nestedData['razon_social'] = $proveedor->razon_social;
                
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

		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/proveedores/add");
		$this->load->view("layouts/footer");
	}

	public function store(){

		$rnc = $this->input->post("rnc");
		$razon_social = $this->input->post("razon_social");

		$this->form_validation->set_rules("rnc","RNC","required|is_unique[proveedores.rnc]");
		$this->form_validation->set_rules("razon_social","Razon Social","required|is_unique[proveedores.razon_social]");

		if ($this->form_validation->run()==TRUE) {

			$data  = array(
				'rnc' => $rnc, 
				'razon_social' => $razon_social,
			);

			if ($this->Proveedores_model->save($data)) {
				redirect(base_url()."mantenimiento/proveedores");
			}
			else{
				$this->session->set_flashdata("error","No se pudo guardar la informacion");
				redirect(base_url()."mantenimiento/proveedores/add");
			}
		}
		else{
			/*redirect(base_url()."mantenimiento/categorias/add");*/
			$this->add();
		}

		
	}

	public function edit($id){
		$data  = array(
			'proveedor' => $this->Proveedores_model->getProveedor($id), 
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/proveedores/edit",$data);
		$this->load->view("layouts/footer");
	}

	public function update(){
		$idProveedor = $this->input->post("idProveedor");
		$rnc = $this->input->post("rnc");
		$razon_social = $this->input->post("razon_social");

		$proveedorActual = $this->Proveedores_model->getProveedor($idProveedor);

		if ($rnc == $proveedorActual->rnc) {
			$is_unique_rnc = "";
		}else{
			$is_unique_rnc = "|is_unique[proveedores.rnc]";
		}

		if ($razon_social == $proveedorActual->razon_social) {
			$is_unique_razon_social = "";
		}else{
			$is_unique_razon_social = "|is_unique[proveedores.razon_social]";

		}


		$this->form_validation->set_rules("rnc","RNC","required".$is_unique_rnc);
		$this->form_validation->set_rules("razon_social","Razon Social","required".$is_unique_razon_social);
		if ($this->form_validation->run()==TRUE) {
			$data = array(
				'rnc' => $rnc, 
				'razon_social' => $razon_social,
			);

			if ($this->Proveedores_model->update($idProveedor,$data)) {
				redirect(base_url()."mantenimiento/proveedores");
			}
			else{
				$this->session->set_flashdata("error","No se pudo actualizar la informacion");
				redirect(base_url()."mantenimiento/proveedores/edit/".$idProveedor);
			}
		}else{
			$this->edit($idProveedor);
		}

		
	}

	public function view($id){
		$data  = array(
			'proveedor' => $this->Proveedores_model->getProveedor($id), 
		);
		$this->load->view("admin/proveedores/view",$data);
	}

	public function delete($id){
		$data  = array(
			'estado' => "0", 
		);
		$this->Proveedores_model->update($id,$data);
		echo "mantenimiento/proveedores";
	}
}
