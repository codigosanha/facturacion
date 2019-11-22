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
        Compras
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                      
                        <a href="<?php echo base_url();?>movimientos/compras/add" class="btn btn-primary btn-flat"><span class="fa fa-plus"></span> Nueva Compra</a>
                        <input type="hidden" id="modulo" value="compras">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table id="tbcompras" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No. Comprobante</th>
                                    <th>No. Factura</th>
                                    <th>Comprobante</th>
                                    <th>Proveedor</th>
                                    <th>Fecha</th>
                                    <th>Tipo de Pago</th>
                                    <th>Total</th>
                                    <th>Estado</th>                                <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($compras)):?>
                                    <?php foreach($compras as $compra):?>
                                        <tr>
                                            <td><?php echo $compra->id;?></td>
                                            <td><?php echo $compra->fecha;?></td>
                                            <td><?php echo $compra->comprobante;?></td>
                                            <td><?php echo $compra->serie." - ".$compra->numero;?></td>
                                            
                                            <td><?php echo $compra->proveedor;?></td>
                                            <td><?php echo $compra->tipopago;?></td>
                                            <td><?php echo $compra->total;?></td>

                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success btn-info-compra"  data-toggle="modal"  data-target="#modal-compra" value="<?php echo $compra->id;?>"><span class="fa fa-search"></span></button>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
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

<div class="modal fade" id="modal-compra">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion de la Compra</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary btn-flat btn-print"><span class="fa fa-print"></span> Imprimir</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
