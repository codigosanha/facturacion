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
        Reporte de Abonos
        <small>Listado</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <form action="<?php echo current_url();?>" method="POST" class="form-horizontal" style="padding:0px 15px;">
                        <div class="form-group">
                            <label for="" class="col-md-1 control-label">Desde:</label>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="fechainicio" value="<?php echo !empty($fechainicio) ? $fechainicio:date("Y-m-d");?>">
                            </div>
                            <label for="" class="col-md-1 control-label">Hasta:</label>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="fechafin" value="<?php  echo !empty($fechafin) ? $fechafin:date("Y-m-d");?>">
                            </div>
                            <div class="col-md-4">
                                <input type="submit" name="buscar" value="Buscar" class="btn btn-primary">
                                <a href="<?php echo base_url(); ?>reportes/resumen" class="btn btn-danger">Restablecer</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        
                        <table id="tb-abonos" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cedula</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Nro de Cuentas</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0;?>
                                <?php if (!empty($clientes)): ?>
                                    <?php foreach ($clientes as $cliente): ?>

                                        <tr>
                                            <td><?php echo $cliente->fecha;?></td>
                                            <td><?php echo $cliente->cedula;?></td>
                                            <td><?php echo $cliente->nombres;?></td>
                                            <td><?php echo $cliente->apellidos;?></td>
                                            <td><?php echo $cliente->cuentas;?></td>
                                           
                                            <td><?php echo number_format($cliente->total - $cliente->pagado, 2, '.', '');?></td>

                                            <?php $total = $total + ($cliente->total - $cliente->pagado);?>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total</th>
                                    <th><?php echo number_format($total, 2, '.', '');?></th>

                                </tr>
                            </tfoot>
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
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

