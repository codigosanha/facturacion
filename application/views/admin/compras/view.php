
<div class="row">
	<div class="col-xs-12 text-center">
		<b><?php echo $this->configuraciones_lib->getEmpresa();?></b><br>
		RNC:<?php echo $this->configuraciones_lib->getRNC();?><br>
		Direccion:<?php echo $this->configuraciones_lib->getDireccion();?><br>
		Tel. <?php echo $this->configuraciones_lib->getTelefono();?> <br>
	</div>
</div> <br>
<div class="row">
	<div class="col-xs-6">	
		<b>CLIENTE</b><br>
		<b>RNC:</b> <?php echo $compra->rnc;?> <br>
		<b>Razon Social:</b> <?php echo $compra->razon_social;?>
	</div>	
	<div class="col-xs-6">	
		<b>COMPROBANTE</b> <br>
		<b>Tipo de Comprobante:</b> <?php echo $compra->comprobante;?><br>
		
		<b>Numero de Comprobante:</b><?php echo $compra->numero_comprobante;?><br>
		<b>Numero de Factura:</b><?php echo $compra->numero_factura;?><br>
		<b>Fecha</b> <?php echo $compra->fecha;?>
	</div>	
</div>
<br>
<div class="row">
	<div class="col-xs-12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Codigo</th>
					<th>Nombre</th>
					<th>Precio</th>
					<th>Cantidad</th>
					<th>Importe</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($detalles as $detalle):?>
				<tr>
					<td><?php echo $detalle->codigo;?></td>
					<td><?php echo $detalle->nombre;?></td>
					<td><?php echo $detalle->precio;?></td>
					<td><?php echo $detalle->cantidad;?></td>
					<td><?php echo $detalle->importe;?></td>
				</tr>
				<?php endforeach;?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
					<td><?php echo $compra->subtotal;?></td>
				</tr>

				<tr>
					<td colspan="4" class="text-right"><strong>ITBIS:</strong></td>
					<td><?php echo $compra->itbis;?></td>
				</tr>
				<tr>
					<td colspan="4" class="text-right"><strong>Total:</strong></td>
					<td><?php echo $compra->total;?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>