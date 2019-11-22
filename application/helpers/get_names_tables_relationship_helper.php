
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('getUsuario'))
{
	function getUsuario($idusuario)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idusuario);
		$query = $ci->db->get('usuarios');
		return $query->row();
	 
	}
}
 
if(!function_exists('getClienteNormal'))
{
	function getClienteNormal($idcliente)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idcliente);
		$query = $ci->db->get('clientes_normales');
		return $query->row();
	 
	}
}



if(!function_exists('getComprobante'))
{
	function getComprobante($idcomprobante)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idcomprobante);
		$query = $ci->db->get('comprobantes');
		return $query->row();
	 
	}
}

if(!function_exists('getProveedor'))
{
	function getProveedor($idproveedor)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idproveedor);
		$query = $ci->db->get('proveedores');
		return $query->row();
	 
	}
}

if(!function_exists('getCategoria'))
{
	function getCategoria($idcategoria)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idcategoria);
		$query = $ci->db->get('categorias');
		return $query->row();
	 
	}
}

if(!function_exists('getClienteJuridico'))
{
	function getClienteJuridico($idcliente)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idcliente);
		$query = $ci->db->get('clientes_juridicos');
		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return false;
		
	 
	}
}

if(!function_exists('getProducto'))
{
	function getProducto($idproducto)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idproducto);
		$query = $ci->db->get('productos');
		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return false;
		
	 
	}
}

if(!function_exists('getAjuste'))
{
	function getAjuste($idajuste)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idajuste);
		$query = $ci->db->get('ajustes');
		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return false;
		
	 
	}
}

if(!function_exists('get_municipio'))
{
	function get_municipio($idmunicipio)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id_municipio',$idmunicipio);
		$query = $ci->db->get('municipios');
		return $query->row();
	 
	}
}

if(!function_exists('get_nivel_escolaridad'))
{
	function get_nivel_escolaridad($idnivelescolar)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idnivelescolar);
		$query = $ci->db->get('nivel_escolaridad');
		return $query->row();
	 
	}
}

if(!function_exists('get_tipo_identificacion'))
{
	function get_tipo_identificacion($idtipoidentificacion)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idtipoidentificacion);
		$query = $ci->db->get('tipo_identificacion');
		return $query->row();
	 
	}
}

if(!function_exists('get_vivienda'))
{
	function get_vivienda($idvivienda)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idvivienda);
		$query = $ci->db->get('viviendas');
		return $query->row();
	 
	}
}

if(!function_exists('get_ahorro'))
{
	function get_ahorro($idahorro)
	{
	    //asignamos a $ci el super objeto de codeigniter
		//$ci será como $this
		$ci =& get_instance();

		$ci->db->where('id',$idahorro);
		$query = $ci->db->get('ahorros');
		return $query->row();
	 
	}
}
//end application/helpers/ayuda_helper.php