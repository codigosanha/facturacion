<?php

class Backend_lib{
	private $CI;
	public function __construct(){
		$this->CI = & get_instance();
	}

	public function control(){
		if (!$this->CI->session->userdata("login")) {
			redirect(base_url());
		}
		$url = $this->CI->uri->segment(1);
		if ($this->CI->uri->segment(2)) {
			$url = $this->CI->uri->segment(1)."/".$this->CI->uri->segment(2);
		}

		$infomenu = $this->CI->Backend_model->getID($url);

		$permisos = $this->CI->Backend_model->getPermisos($infomenu->id,$this->CI->session->userdata("rol"));
		if ($permisos->read == 0 ) {
			redirect(base_url()."dashboard");
		}else{
			return $permisos;
		}

	}

	public function notificaciones(){

		$this->CI->load->model("Comprobantes_model");
		$comprobantes = $this->CI->Comprobantes_model->getComprobantes();

		$items = '';
		$count = 0;
		foreach ($comprobantes as $comprobante) {
			$calculo = $comprobante->limite - $comprobante->cantidad_realizada;
			if ($calculo == $comprobante->aviso) {
					$items .= '<li style="padding:0px 5px; border-bottom:1px solid #f4f4f4;">';
					$items .=	"El tipo de comprobante <b>".$comprobante->nombre."</b> ha llegado a su limite, el cual es ".$comprobante->aviso;
                            
                    $items.='</li>';
				$count++;
			}
		}

		$notificaciones = '<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>';

              if ($count > 0) {
              	$notificaciones .= '<span class="label label-danger">'.$count.'</span>';
              }
              

        $notificaciones .= '</a>
            <ul class="dropdown-menu">
              <li class="header">Tienes '.$count.' notificaciones</li>';
              if (!empty($count)) {
              	$notificaciones .= '<li>
                <ul class="menu menu-notificaciones">';
                $notificaciones.= $items;
                $notificaciones .= '</ul></li>';

              }
              
              $notificaciones .= '</ul></li>';
        
        return $notificaciones;
	}
}