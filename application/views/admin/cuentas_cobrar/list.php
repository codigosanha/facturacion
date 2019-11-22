<?php if($this->session->flashdata("success")):?>
    <script>
        swal("Bien!", "<?php echo $this->session->flashdata('success');?>", "success");
    </script>
<?php endif; ?>
<?php if($this->session->flashdata("error")):?>
    <script>
        swal("Error!", "<?php echo $this->session->flashdata('error');?>", "error");
    </script>
<?php endif;?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Cuentas por Cobrar
        <small>Listado</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Cedula</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Nro de Cuentas</th>
                                    <th>Total</th>

                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($clientes)): ?>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <tr>
                                            <td><?php echo $cliente->cedula;?></td>
                                            <td><?php echo $cliente->nombres;?></td>
                                            <td><?php echo $cliente->apellidos;?></td>
                                            <td><?php echo $cliente->cuentas;?></td>
                                           
                                            <td><?php echo number_format($cliente->total - $cliente->pagado, 2, '.', '');?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm btn-cuentas" data-toggle="modal" data-target="#modal-abonar" value="<?php echo $cliente->id;?>">
                                                    Abonar
                                                </button>
                                                <!-- <?php if ($cliente->pagado > 0): ?>
                                                    <button type="button" class="btn btn-warning btn-sm btn-pagos" data-toggle="modal" data-target="#modal-pagos" value="<?php echo $cliente->venta_id;?>">
                                                        Ver Pagos
                                                    </button>
                                                <?php endif ?> -->
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>

                        <form action="<?php echo base_url();?>cuentas_cobrar/saldarCuentas" id="form-saldar-cuentas" method="POST">
                            <?php if (!empty($ventas)): ?>
                                <?php foreach ($ventas as $venta): ?>
                                    <input type="hidden" name="cuentas[]" value="<?php echo $venta->id?>">
                                    <input type="hidden" name="montos[]" value="<?php echo $venta->total?>">
                                    <input type="hidden" name="pagados[]" value="<?php echo $venta->pagado?>">
                                <?php endforeach ?>

                                <button type="submit" class="btn btn-success btn-flaat">Saldar todas las cuentas</button>         
                            <?php endif ?>
                            
                        </form>
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

<div class="modal fade" id="modal-abonar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informacion de cuentas</h4>
            </div>
            <form action="<?php echo base_url();?>cuentas_cobrar/abonar" method="POST">
            
            <div class="modal-body">
                <div class="form-group">    
                    <table id="tbcuentas" class="table table-bordered">
                        <thead>
                            <tr>    
                                <th>Factura</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Pagado</th>
                            </tr>
                        </thead>
                        <tbody> 

                        </tbody>
                    </table>
                </div>
                <div class="form-group text-center">
                    <label for="" >TOTAL DE DEUDAS:</label>
                    <input type="text" id="total" class="form-control input-lg" readonly="readonly" name="total" style="text-align: center;">
                </div>
                <div class="form-group text-center">
                    <label for="" class="text-center">CANTIDAD ABONAR:</label>
                    <input type="text" id="abonar" name="abonar" class="form-control input-lg" required="required" style="text-align: center;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade" id="modal-pagos">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informacion de Pagos</h4>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Numero de Factura:</label>
                    <input type="text" id="numero_factura" class="form-control" readonly="readonly" name="numero_factura">
                </div>
                <table id="tbpagos" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Monto</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
            </div>
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

