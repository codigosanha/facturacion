
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Inventario Contable
        <small>Listado de Productos</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <form action="<?php echo current_url();?>" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">Filtro:</span>
                            <select name="busqueda" id="busqueda" class="form-control">
                                <option value="1">Mostrar todos los productos</option>
                                <option value="2">Mostrar productos con existencia mayor o igual a 1</option>
                                <option value="3">Mostrar productos con existencia menor o igual a 0</option>
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Filtrar</button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                </div>
                </form>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table id="tb-inventario-contable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Stock</th>
                                    <th>Precio Compra</th>
                                    <th>Stock x P.Compra</th>
                                    <th>Precio Venta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalCompras = 0; ?>
                                <?php if (!empty($productos)): ?>
                                    <?php foreach ($productos as $p): ?>
                                        <tr>
                                            <td><?php echo $p->codigo?></td>
                                            <td><?php echo $p->nombre?></td>
                                            <td><?php echo $p->stock?></td>
                                            <td><?php echo $p->precio_compra?></td>
                                            <td><?php echo number_format($p->stock * $p->precio_compra, 2, '.', '');?></td>
                                            <?php $totalCompras = $totalCompras +  ($p->stock * $p->precio_compra);?>
                                            <td><?php echo $p->precio?></td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th>TOTALES</th>
                                    <td><?php echo number_format($totalCompras, 2, '.', '');?> </td>
                                    <td></td>
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
