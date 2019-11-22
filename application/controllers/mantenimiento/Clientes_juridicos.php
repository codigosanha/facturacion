<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_juridicos extends CI_Controller {
	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Clientes_juridicos_model");
	}

	public function index()
	{
		$data  = array(
			'permisos' => $this->permisos, 
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/clientes_juridicos/list",$data);
		$this->load->view("layouts/footer");

	}

	public function getClientesJuridicos()
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
  
        $totalData = $this->Clientes_juridicos_model->allclients_count();
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $clients = $this->Clientes_juridicos_model->allclients($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $clients =  $this->Clientes_juridicos_model->clients_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Clientes_juridicos_model->clients_search_count($search);
        }

        $data = array();
        if(!empty($clients))
        {
            foreach ($clients as $client)
            {

                $nestedData['id'] = $client->id;
                $nestedData['rnc'] = $client->rnc;
                $nestedData['razon_social'] = $client->razon_social;
                
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
		$this->load->view("admin/clientes_juridicos/add");
		$this->load->view("layouts/footer");
	}
	public function store(){

		$razon_social = $this->input->post("razon_social");
		$rnc = $this->input->post("rnc");

		$this->form_validation->set_rules("razon_social","Nombre del Cliente","required");
		$this->form_validation->set_rules("rnc","Numero del Documento","required|is_unique[clientes_juridicos.rnc]");

		if ($this->form_validation->run()) {
			$data  = array(
				'razon_social' => $razon_social, 
				'rnc' => $rnc,
			);

			if ($this->Clientes_juridicos_model->save($data)) {
				redirect(base_url()."mantenimiento/clientes_juridicos");
			}
			else{
				$this->session->set_flashdata("error","No se pudo guardar la informacion");
				redirect(base_url()."mantenimiento/clientes_juridicos/add");
			}
		}
		else{
			$this->add();
		}

		
	}
	public function edit($id){
		$data  = array(
			'cliente' => $this->Clientes_juridicos_model->getCliente($id),
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/clientes_juridicos/edit",$data);
		$this->load->view("layouts/footer");
	}


	public function update(){
		$idcliente = $this->input->post("idcliente");
		$razon_social = $this->input->post("razon_social");
		$rnc = $this->input->post("rnc");

		$clienteActual = $this->Clientes_juridicos_model->getCliente($idcliente);

		if ($rnc == $clienteActual->rnc) {
			$is_unique = "";
		}else{
			$is_unique= '|is_unique[clientes_juridicos.rnc]';
		}

		$this->form_validation->set_rules("razon_social","Nombre del Cliente","required");
		$this->form_validation->set_rules("rnc","Numero del Documento","required".$is_unique);

		if ($this->form_validation->run()) {
			$data = array(
				'razon_social' => $razon_social, 
				'rnc' => $rnc,
			);

			if ($this->Clientes_juridicos_model->update($idcliente,$data)) {
				redirect(base_url()."mantenimiento/clientes_juridicos");
			}
			else{
				$this->session->set_flashdata("error","No se pudo actualizar la informacion");
				redirect(base_url()."mantenimiento/clientes_juridicos/edit/".$idcliente);
			}
		}else{
			$this->edit($idcliente);
		}

		

	}

	public function delete($id){
		$data  = array(
			'estado' => "0", 
		);
		$this->Clientes_juridicos_model->update($id,$data);
		echo "mantenimiento/clientes_juridicos_juridicos";
	}
}