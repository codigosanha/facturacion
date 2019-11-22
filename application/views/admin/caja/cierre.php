
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Cierre de Caja
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <form action="<?php echo current_url();?>" method="POST">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon success">Buscar por una fecha:</span>
                                    <input type="date" class="form-control" value="<?php echo $date;?>" name="fecha">
                                    <span class="input-group-btn">
                                        <input type="submit" value="Buscar" class="btn btn-success" name="buscar">
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <input type="hidden" id="dateSelected" value="<?php echo $dateSelected;?>">
                
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-center title" style="color: #3c8dbc;">Listado de productos vendidos del día (<?php echo $dateSelected;?>)</h2>
                        
                        <table id="tbcierre" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock Actual</th>
                                    <th>Cantidad vendidas</th>
                                    <th>Importe</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0;?>
                                <?php if(!empty($productos)):?>
                                    <?php foreach($productos as $producto):?>
                                        <tr>
                                            <td><?php echo $producto->nombre;?></td>
                                            <td><?php echo $producto->stock;?></td>
                                            <td><?php echo $producto->cantidad;?></td>
                                            <td class="text-right"><?php echo number_format($producto->precio * $producto->cantidad, 2, '.', '');?></td>
                                            <td><?php echo $producto->username;?></td>
                                            <?php $total = $total + number_format($producto->precio * $producto->cantidad, 2, '.', ''); ?>

                                        </tr>
                                    <?php endforeach;?>
                                
                                <?php endif;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">Monto Acumulado</th>
                                    <th class="text-right"><?php echo number_format($total, 2, '.', '');?></th>
                                    <th></th>
                                    
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

<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion de la Categoria</h4>
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
