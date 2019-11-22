<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Compras
        <small>Nueva</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <form action="<?php echo base_url();?>movimientos/compras/store" method="POST">
                <div class="row">
                    <!--Inicio Primer Columna-->
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="">Producto:</label>
                            <div class="input-group barcode">
                                <div class="input-group-addon">
                                    <i class="fa fa-barcode"></i>
                                </div>
                                <input type="text" class="form-control" id="searchProductoCompra" placeholder="Buscar por codigo de barras o nombre del proucto">
                            </div>
                        </div>
                        <div class="form-group">
                            <table id="tb-detalle-compra" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Precio Compra</th>
                                        <th>Precio Venta</th>
                                        <th>Cantidad</th>
                                        <th>Importe</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Inicio 2da Columna-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Comprobante:</label>
                            <select name="comprobante" class="form-control" required="required" id="comprobanteCompra">
                                <option value="">Seleccione</option>
                                <?php foreach ($comprobantes as $comprobante): ?>
                                    <?php $data = $comprobante->id."*".$comprobante->solicitar_cliente;?>
                                    <option value="<?php echo $data; ?>"><?php echo $comprobante->nombre;?></option>
                                <?php endforeach ?>
                            </select>
                            <input type="hidden" id="idcomprobante" name="idcomprobante">
                        </div>
                        <div class="form-group">
                            <label for="">Tipo de Pago:</label>
                            <select name="tipo_pago" id="tipo_pago" class="form-control" required>
                                <option value="1">Efectivo</option>
                                <option value="0">Credito</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">No. Comprobante:</label>
                            <input type="text" class="form-control"  name="numero_comprobante" placeholder="Escriba el No. de Comprobante" required="required">
                        </div>
                        <div class="form-group">
                            <label for="">No. Factura:</label>
                            <input type="text" class="form-control"  name="numero_factura" placeholder="Escriba el No. de Factura" required="required">
                        </div>
                        <div class="form-group">
                            <label for="">Fecha de Compra:</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo date("Y-m-d");?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Proveedor:</label>
                            <select name="proveedor" id="proveedor" class="form-control select2" style="width: 100%;">
                                <option value="">Seleccione</option>
                                <?php if (!empty($proveedores)): ?>
                                    <?php foreach ($proveedores as $proveedor): ?>
                                        <option value="<?php echo $proveedor->id;?>">
                                            <?php echo $proveedor->razon_social." - ".$proveedor->rnc;?>
                                        </option>
                                    <?php endforeach ?>
                                <?php endif ?>
                                
                            </select>
                        </div>
                    
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 50%;">Subtotal:</span>
                            <input type="text" class="form-control" placeholder="0.00" name="subtotal"  readonly="readonly" required="required">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 50%;">ITBIS:</span>
                            <input type="text" class="form-control" placeholder="0.00" name="itbis"  readonly="readonly" required="required">
                        </div>
                
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 50%;">Total:</span>
                            <input type="text" class="form-control" placeholder="0.00" name="total" readonly="readonly" required="required">
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-flat" id="btn-guardar-compra" disabled="disabled"><i class="fa fa-save"></i> Guardar Compra</button>
                            <a href="<?php echo base_url().$this->uri->segment(1).'/'.$this->uri->segment(2); ?>" class="btn btn-danger btn-flat"><i class="fa fa-times"></i> Cancelar</a>
                        </div>
                    </div>        
                </div>
                </form>
            </div>
                <!--end row1-->
        </div>
            <!-- /.box-body -->
    </section>

    <!-- /.content -->

</div>
<!-- /.content-wrapper -->