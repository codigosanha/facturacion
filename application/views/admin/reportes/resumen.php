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
        Reportes
        <small>Ventas</small>
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
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Ventas al Credito
                            </div>
                            <div class="panel-body no-padding">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table class="table table-bordered table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $totalVentaCredito = 0;?>
                                            <?php if (!empty($ventas)): ?>
                                                <?php foreach($ventas as $venta):?>
                                                    <tr>
                                                        <td><?php echo $venta->fecha;?></td>
                                                        <td><?php echo $venta->nombres." ".$venta->apellidos;?></td>
                                                        <td><?php echo $venta->total;?></td>
                                                    </tr>
                                                    <?php $totalVentaCredito = $totalVentaCredito + $venta->total;?>
                                                <?php endforeach;?>
                                            <?php endif ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Cobros o Abonos
                            </div>
                            <div class="panel-body no-padding">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table class="table table-bordered table-hover" width="100%" >
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $totalPagos = 0;?>
                                            <?php if (!empty($pagos)): ?>
                                                <?php foreach($pagos as $pago):?>
                                                    <tr>
                                                        <td><?php echo $pago->fecha;?></td>
                                                        <?php $cliente =getClienteNormal($pago->cliente_id); ?>
                                                        <td><?php echo $cliente->nombres." ".$cliente->apellidos;?></td>
                                                        <td><?php echo $pago->monto;?></td>
                                                    </tr>
                                                    <?php $totalPagos = $totalPagos + $pago->monto;?>
                                                <?php endforeach;?>
                                            <?php endif ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Cantidad Efectivo
                            </div>
                            <div class="panel-body no-padding">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table class="table table-bordered table-hover" width="100%" >
                                        <thead>
                                            <tr>
                                                <th>Total Ventas en Efectivo</th>
                                                <th>Total Cobros</th>
                                                <th>Total Ventas a Credito</th>
                                                <th>Cantidad en Efectivo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo number_format($totalVentasEfectivo, 2, '.', '');?></td>
                                                <td><?php echo number_format($totalPagos, 2, '.', '');?></td>
                                                <td><?php echo number_format($totalVentaCredito, 2, '.', '');?></td>
                                                <?php $totalEfectivo = $totalVentasEfectivo + $totalPagos - $totalVentaCredito;?>
                                                <td><?php echo number_format($totalEfectivo, 2, '.', '');?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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

<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion de la venta</h4>
      </div>
      <div class="modal-body">
        
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
