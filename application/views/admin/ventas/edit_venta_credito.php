
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Ventas
        <small>Editar</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="<?php echo base_url();?>movimientos/ventas_creditos/update" method="POST" id="form-add-venta">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="idVenta" id="idVenta" value="<?php echo $venta->id;?>">

                                    <div class="form-group">
                                        <label for="">Comprobante:</label>
                                        <input type="text" class="form-control" readonly="readonly" name="num_comprobante" value="<?php echo $venta->comprobante." - ".$venta->serie.$venta->numero;?>">
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label class="control-label" for="rnc" style="color: #000 !important;">Cliente</label>
                                        
                                        <input type="text" class="form-control" id="cedula" name="cedula" autocomplete="false" value="<?php echo $venta->cedula." - ".$venta->nombres;?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Producto:</label>
                                <div class="input-group barcode">
                                    <div class="input-group-addon">
                                        <i class="fa fa-barcode"></i>
                                    </div>
                                    <input type="text" class="form-control" id="searchProductoVenta" placeholder="Buscar por codigo de barras">
                                </div>
                                <h4 class="text-center">Productos Agregado a la Venta</h4>
                                <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tborden">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th width="25%">Cantidad</th>
                                            <th>Importe</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($detalles as $detalle): ?>
                                            <tr>
                                                <td>
                                                    <input type='hidden' name='productos[]' value="<?php echo $detalle->producto_id;?>">
                                                    <?php echo $detalle->nombre;?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="precios[]" class="form-control" value="<?php echo $detalle->precio;?>">
                                                    <?php echo $detalle->precio;?>
                                                </td>

                                                <td>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                    <button class="btn btn-danger btn-menos btn-sm" type="button"><span class="fa fa-minus"></span></button></span>
                                                    <input type="number" name="cantidades[]" class="form-control input-cantidad input-sm" value="<?php echo $detalle->cantidad;?>">
                                                    
                                                    <span class="input-group-btn">
                                                    <button class="btn btn-primary btn-mas btn-sm" type="button"><span class="fa fa-plus"></span></button></span></div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="importes[]" class="form-control"  value="<?php echo $detalle->importe;?>">
                                                    <p><?php echo $detalle->importe;?></p>
                                                </td>
                                                <td>
                                                    <button type='button' class='btn btn-danger btn-sm btn-remove-producto'><span class='fa fa-remove'></span></button>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">SUBTOTAL</th>
                                            <td colspan="2" class="subtotal">
                                                <?php echo $venta->subtotal; ?>  
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-right">ITBIS</th>
                                            <td colspan="2" class="itbis">
                                                <?php echo $venta->itbis; ?> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-right">TOTAL</th>
                                            <td colspan="2" class="total">
                                                <?php echo $venta->total; ?> 
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                            </div>
                            <input type="hidden" name="subtotal" value="<?php echo $venta->subtotal;?>">
                            <input type="hidden" name="itbis" value="<?php echo $venta->itbis;?>">
                            <input type="hidden" name="total" value="<?php echo $venta->total;?>">

                            <div class="form-group text-center">
                                <button id="btn-save" type="submit" class="btn btn-success btn-flat btn-guardar">Guardar</button>
                                <a href="<?php echo base_url();?>movimientos/ventas" class="btn btn-danger">Volver</a>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <h4>Seleccion de Productos</h4>
                        <div class="form-group">
                            <select name="categoria" id="categoria" class="form-control">
                                <option value="">Seleccione Categoria</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria->id;?>"><?php echo $categoria->nombre;?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <table class="table table-bordered table-hover" id="tb-productos">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Producto</th>
                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            
                        </table>

                        
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="modal-venta">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion de la orden</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button id="btn-cmodal" type="button" class="btn btn-danger pull-left btn-cerrar-imp" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-primary btn-flat btn-print"><span class="fa fa-print"></span> Imprimir</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade" id="modal-lista-espera">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Listado de Ventas en Espera</h4>
            </div>
            <div class="modal-body">
                <?php if (!empty($pendientes)): ?>
                    <div class="panel-group" id="accordion">
                        <?php foreach ($pendientes as $pendiente): ?>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $pendiente->id;?>" class="panel-title expand">
                                        <div class="right-arrow pull-right">+</div>
                                        <a href="#"><?php echo $pendiente->comprobante." - ".$pendiente->numero_mesa?></a>
                                    </h4>
                                </div>
                                <div id="collapse<?php echo $pendiente->id;?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php if ($pendiente->cliente_id == 0): ?>
                                            <p class="text-center text-red" >Nota: Para este tipo de comprobante no es necesario informacion del cliente</p>
                                        <?php else: ?>
                                            <dl class="dl-horizontal">
                                                <dt>Nombre:</dt>
                                                <dd><?php echo $pendiente->razon_social;?></dd>
                                                <dt>RNC:</dt>
                                                <dd><?php echo $pendiente->rnc;?></dd>
                                            </dl>
                                        <?php endif ?>
                                        
                                        <table class="table table-striped table-bordered">
                                            <caption class="text-center">Detalle de Venta</caption>
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                    <th>Importe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pendiente->detalles as $detalle): ?>
                                                    <tr>
                                                        <td><?php echo $detalle->nombre;?></td>
                                                        <td><?php echo $detalle->precio;?></td>
                                                        <td><?php echo $detalle->cantidad;?></td>
                                                        <td><?php echo $detalle->importe;?></td>

                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-center">Total</th>
                                                    <td><?php echo $pendiente->monto;?></td>
                                                </tr>

                                            </tfoot>
                                        </table>
                                        <button type="button" class="btn btn-success btn-lg" id="btn-procesar-pendiente" value='<?php echo json_encode($pendiente); ?>'>Procesar</button>
                                        
                                    </div>
                                </div>
                            </div>
                            
                        <?php endforeach ?>
                    </div>

                <?php else: ?>
                    <p class="text-center">No hay Ventas en Esperas</p>
                <?php endif ?>


            </div>
            <div class="modal-footer">
                <button id="btn-cmodal" type="button" class="btn btn-danger pull-left btn-cerrar-imp" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btn-flat btn-print"><span class="fa fa-print"></span> Imprimir</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
