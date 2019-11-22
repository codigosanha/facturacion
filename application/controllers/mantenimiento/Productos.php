<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller {
	private $permisos;
	public function __construct(){
		parent::__construct();
		$this->permisos = $this->backend_lib->control();
		$this->load->model("Productos_model");
		$this->load->model("Categorias_model");
		$this->load->helper("get_names_tables_relationship");
	}

	public function index()
	{
		$data  = array(
			'permisos' => $this->permisos,
			'productos' => $this->Productos_model->getProductos(), 
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/productos/list",$data);
		$this->load->view("layouts/footer");

	}

	public function getProductos()
	{

		$columns = array( 
            0 => 'id', 
            1 => 'codigo', 
            2 => 'nombre',
            3 => 'descripcion',
            4 => 'precio_compra',
            5 => 'precio',
            6 => 'stock',
            7 => 'categoria',
        );

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->Productos_model->allproductos_count();
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value']))
        {            
            $productos = $this->Productos_model->allproductos($limit,$start,$order,$dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $productos =  $this->Productos_model->productos_search($limit,$start,$search,$order,$dir);

            $totalFiltered = $this->Productos_model->productos_search_count($search);
        }

        $data = array();
        if(!empty($productos))
        {
            foreach ($productos as $producto)
            {

                $nestedData['id'] = $producto->id;
                $nestedData['codigo'] = $producto->codigo;
                $nestedData['nombre'] = $producto->nombre;
                $nestedData['descripcion'] = $producto->descripcion;
                $nestedData['precio_compra'] = $producto->precio_compra;
                $nestedData['precio'] = $producto->precio;
                $nestedData['stock'] = $producto->stock;
                $nestedData['categoria'] = getCategoria($producto->categoria_id)->nombre;
                $nestedData['imagen'] = $producto->imagen;
                
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
		$data =array( 
			"categorias" => $this->Categorias_model->getCategorias()
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/productos/add",$data);
		$this->load->view("layouts/footer");
	}

	public function store(){
		$codigo = $this->input->post("codigo");
		$nombre = $this->input->post("nombre");
		$descripcion = $this->input->post("descripcion");
		$precio = $this->input->post("precio");
		$stock = $this->input->post("stock");
		$categoria = $this->input->post("categoria");
		$precio_compra = $this->input->post("precio_compra");
		$this->form_validation->set_rules("codigo","Codigo","required|is_unique[productos.codigo]");
		$this->form_validation->set_rules("nombre","Nombre","required");
		$this->form_validation->set_rules("precio","Precio","required");
		$this->form_validation->set_rules("stock","Stock","required");

		if ($this->form_validation->run()) {
			$imagen = '';
			if (!empty($_FILES['imagen']['name'])) {
				$config['upload_path']          = './assets/images/productos/';
                $config['allowed_types']        = 'gif|jpg|png';

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('imagen'))
                {
  					$data = array('upload_data' => $this->upload->data());
                    $imagen = $data['upload_data']['file_name'];
                } 
                
			}
			$data  = array(
				'codigo' => $codigo, 
				'nombre' => $nombre,
				'descripcion' => $descripcion,
				'precio' => $precio,
				'precio_compra' => $precio_compra,
				'stock' => $stock,
				'categoria_id' => $categoria,
				'estado' => "1",
				'imagen' => $imagen

			);

			if ($this->Productos_model->save($data)) {
				redirect(base_url()."mantenimiento/productos");
			}
			else{
				$this->session->set_flashdata("error","No se pudo guardar la informacion");
				redirect(base_url()."mantenimiento/productos/add");
			}
		}
		else{
			$this->add();
		}

		
	}

	public function edit($id){
		$data =array( 
			"producto" => $this->Productos_model->getProducto($id),
			"categorias" => $this->Categorias_model->getCategorias()
		);
		$this->load->view("layouts/header");
		$this->load->view("layouts/aside");
		$this->load->view("admin/productos/edit",$data);
		$this->load->view("layouts/footer");
	}

	public function update(){
		$idproducto = $this->input->post("idproducto");
		$codigo = $this->input->post("codigo");
		$nombre = $this->input->post("nombre");
		$descripcion = $this->input->post("descripcion");
		$precio = $this->input->post("precio");
		$precio_compra = $this->input->post("precio_compra");
		$stock = $this->input->post("stock");
		$categoria = $this->input->post("categoria");

		$productoActual = $this->Productos_model->getProducto($idproducto);

		if ($codigo == $productoActual->codigo) {
			$is_unique = '';
		}
		else{
			$is_unique = '|is_unique[productos.codigo]';
		}

		$this->form_validation->set_rules("codigo","Codigo","required".$is_unique);
		$this->form_validation->set_rules("nombre","Nombre","required");
		$this->form_validation->set_rules("precio","Precio","required");
		$this->form_validation->set_rules("stock","Stock","required");


		if ($this->form_validation->run()) {
			$producto = $this->Productos_model->getProducto($idproducto);
			$imagen = $producto->imagen;
			if (!empty($_FILES['imagen']['name'])) {
				$config['upload_path']          = './assets/images/productos/';
                $config['allowed_types']        = 'gif|jpg|png';

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('imagen'))
                {
  					$data = array('upload_data' => $this->upload->data());
                    $imagen = $data['upload_data']['file_name'];
                } 
                
			}
			$data  = array(
				'codigo' => $codigo, 
				'nombre' => $nombre,
				'descripcion' => $descripcion,
				'precio' => $precio,
				'precio_compra' => $precio_compra,
				'stock' => $stock,
				'categoria_id' => $categoria,
				'imagen' => $imagen
			);
			if ($this->Productos_model->update($idproducto,$data)) {
				redirect(base_url()."mantenimiento/productos");
			}
			else{
				$this->session->set_flashdata("error","No se pudo guardar la informacion");
				redirect(base_url()."mantenimiento/productos/edit/".$idproducto);
			}
		}else{
			$this->edit($idproducto);
		}

		
	}
	public function delete($id){
		$data  = array(
			'estado' => "0", 
		);
		$this->Productos_model->update($id,$data);
		echo "mantenimiento/productos";
	}

}