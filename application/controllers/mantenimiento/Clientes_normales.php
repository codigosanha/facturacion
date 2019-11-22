<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_normales extends CI_Controller {
	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Clientes_normales_model");
	}

	public function index()
	{
		$data  = array(
			'permisos' => $this->permisos, 
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/clientes_normales/list",$data);
		$this->load->view("layouts/footer");

	}

	public function getClientesNormales()
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
  
        $totalData = $this->Clientes_normales_model->allclients_count();
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $clients = $this->Clientes_normales_model->allclients($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $clients =  $this->Clientes_normales_model->clients_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Clientes_normales_model->clients_search_count($search);
        }

        $data = array();
        if(!empty($clients))
        {
            foreach ($clients as $client)
            {

                $nestedData['id'] = $client->id;
                $nestedData['cedula'] = $client->cedula;
                $nestedData['nombres'] = $client->nombres;
                $nestedData['apellidos'] = $client->apellidos;
                $nestedData['telefono'] = $client->telefono;
                $nestedData['celular'] = $client->celular;
                $nestedData['correo'] = $client->correo;
                $nestedData['direccion'] = $client->direccion;
                
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
		$this->load->view("admin/clientes_normales/add");
		$this->load->view("layouts/footer");
	}
	public function store(){

		$cedula = $this->input->post("cedula");
		$nombres = $this->input->post("nombres");
		$apellidos = $this->input->post("apellidos");
		$telefono = $this->input->post("telefono");
		$direccion = $this->input->post("direccion");
		$correo = $this->input->post("correo");
		$celular = $this->input->post("celular");

		$this->form_validation->set_rules("cedula","Cedula","required|is_unique[clientes_normales.cedula]");

		if ($this->form_validation->run()) {
			$data  = array(
				'cedula' => $cedula, 
				'nombres' => $nombres,
				'apellidos' => $apellidos,
				'telefono' => $telefono,
				'celular' => $celular,
				'direccion' => $direccion,
				'correo' => $correo,
			);

			if ($this->Clientes_normales_model->save($data)) {
				redirect(base_url()."mantenimiento/clientes_normales");
			}
			else{
				$this->session->set_flashdata("error","No se pudo guardar la informacion");
				redirect(base_url()."mantenimiento/clientes_normales/add");
			}
		}
		else{
			$this->add();
		}

		
	}
	public function edit($id){
		$data  = array(
			'cliente' => $this->Clientes_normales_model->getCliente($id),
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/clientes_normales/edit",$data);
		$this->load->view("layouts/footer");
	}


	public function update(){
		$idCliente = $this->input->post("idCliente");
		$cedula = $this->input->post("cedula");
		$nombres = $this->input->post("nombres");
		$apellidos = $this->input->post("apellidos");
		$telefono = $this->input->post("telefono");
		$direccion = $this->input->post("direccion");
		$correo = $this->input->post("correo");
		$celular = $this->input->post("celular");

		$clienteActual = $this->Clientes_normales_model->getCliente($idCliente);

		if ($cedula == $clienteActual->cedula) {
			$is_unique = "";
		}else{
			$is_unique= '|is_unique[clientes_normales.cedula]';
		}

		$this->form_validation->set_rules("cedula","Cedula","required".$is_unique);

		if ($this->form_validation->run()) {
			$data = array(
				'cedula' => $cedula, 
				'nombres' => $nombres,
				'apellidos' => $apellidos,
				'telefono' => $telefono,
				'celular' => $celular,
				'direccion' => $direccion,
				'correo' => $correo,
			);

			if ($this->Clientes_normales_model->update($idCliente,$data)) {
				redirect(base_url()."mantenimiento/clientes_normales");
			}
			else{
				$this->session->set_flashdata("error","No se pudo actualizar la informacion");
				redirect(base_url()."mantenimiento/clientes_normales/edit/".$idCliente);
			}
		}else{
			$this->edit($idCliente);
		}

		

	}

	public function delete($id){
		$data  = array(
			'estado' => "0", 
		);
		$this->Clientes_normales_model->update($id,$data);
		echo "mantenimiento/clientes_normales";
	}
}