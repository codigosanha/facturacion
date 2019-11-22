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
        Ventas
        <small>Listado</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php if($permisos->insert == 1):?>
                            <?php if ($caja && $caja->estado==1): ?>
                                <a href="<?php echo base_url();?>movimientos/ventas/agregar_venta_contado" class="btn btn-primary btn-flat"> Agregar Venta Contado</a>
                                <a href="<?php echo base_url();?>movimientos/ventas/agregar_venta_credito" class="btn btn-warning btn-flat"> Agregar Venta Credito</a>
                            <?php endif ?>
                        <?php endif ?>
                        <input type="hidden" id="permisos" value='<?php echo json_encode($permisos);?>'>
                        <input type="hidden" id="modulo" value="ventas">
                        <input type="hidden" id="caja" value="<?php  echo $caja !=false ? $caja->id:''; ?>">
                    </div>
                    <div class="col-md-6 text-right">
                        <?php if (!$caja): ?>
                            <a href="<?php echo base_url();?>movimientos/ventas/aperturarCaja" class="btn btn-success btn-flat">
                                <i class="fa fa-unlock" aria-hidden="true"></i>
                                Abrir Caja
                            </a>
                        <?php endif ?>
                        <?php if ($caja && $caja->estado==0): ?>
                            <a href="<?php echo base_url();?>movimientos/ventas/aperturarCaja" class="btn btn-success btn-flat">
                                <i class="fa fa-unlock" aria-hidden="true"></i>
                                Abrir Caja
                            </a>
                        <?php endif ?>
                        
                        <?php if ($this->session->userdata('rol') == 1): ?>
                            <?php if ($caja && $caja->estado==1): ?>
                                <a href="<?php echo base_url();?>movimientos/ventas/cerrarCaja" class="btn btn-danger btn-flat">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                    Cerrar Caja
                                </a>
                            <?php endif ?>
                            
                        <?php endif ?>
                        
                    </div>
                </div>
                <br>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Ventas al Contado</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Ventas al Credito</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="table-responsive">
                                <table id="tbventas" class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Numero</th>
                                            <th>Comprobante</th>
                                            <th>Nombre Cliente</th>
                                            <th>RNC</th>
                                            
                                            
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Usuario</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                      <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="table-responsive">
                                <table id="tbventascreditos" class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Numero</th>
                                            <th>Comprobante</th>
                                            <th>Cedula</th>
                                            <th>Cliente</th>
                                            
                                            
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Usuario</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
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
