
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Ventas
        <small>Nuevo</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="<?php echo base_url();?>movimientos/ventas_creditos/store" method="POST" id="form-add-venta">
                            
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="idcomprobante">Tipo de Comprobante:</label>
                                        <select name="idcomprobante" id="idcomprobante" class="form-control" required>
                                            
                                            <?php foreach($comprobantes as $comprobante):?> 
                                                
                                                <option value="<?php echo $comprobante->id;?>" <?php echo strtolower($comprobante->nombre) == 'factura'?'selected':'';?>><?php echo $comprobante->nombre;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label class="control-label" for="cedula" style="color: #000 !important;">Cliente</label>
                                        
                                        <input type="text" class="form-control" id="cliente" name="cliente" required="required">
                                        <span class="form-control-feedback"></span>
                                    </div>
                                </div>
                                <input type="hidden" name="idcliente" id="idcliente">
                            </div>
                            <div class="alert alert-success" id="alert-success-cedula" role="alert" style="display: none;">
                              <p class="text-center">INFORMACION DEL CLIENTE</p>
                              <ul>
                                  <li>Nombre: Jose Miguel</li>
                                  <li>Cedula: 90090001</li>
                              </ul>
                            </div>
                            <div class="alert alert-danger text-center" id="alert-error-cedula" role="alert" style="display: none;">
                              La Cedula ingresado no coindicen con ningun registro
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
                                        <tr class="message">
                                            <td colspan="5" class="text-center">Aun no se han agregado producto al detalle</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">SUBTOTAL</th>
                                            <td colspan="2" class="subtotal">2.00</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-right">ITBIS</th>
                                            <td colspan="2" class="itbis">2.00</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-right">TOTAL</th>
                                            <td colspan="2" class="total">2.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                            </div>
                            <input type="hidden" name="subtotal" value="0">
                            <input type="hidden" name="itbis" value="0">
                            <input type="hidden" name="total" value="0">
                            <div class="form-group text-center">
                                <button id="btn-save-venta-credito" type="submit" class="btn btn-success btn-flat btn-guardar" disabled="disabled">Guardar</button>
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

