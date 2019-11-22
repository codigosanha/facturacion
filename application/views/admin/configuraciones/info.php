
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Configuraciones
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php if($this->session->flashdata("error")):?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p><i class="icon fa fa-ban"></i><?php echo $this->session->flashdata("error"); ?></p>
                                
                             </div>
                        <?php endif;?>
                        <?php if($this->session->flashdata("success")):?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p><i class="icon fa fa-ban"></i><?php echo $this->session->flashdata("success"); ?></p>
                                
                             </div>
                        <?php endif;?>
                        <form action="<?php echo base_url();?>administrador/configuraciones/save" method="POST">
                            <input type="hidden" value="<?php echo isset($configuracion->id) ? $configuracion->id:'';?>" name="idConfiguracion">
                            <div class="form-group">
                                <label for="empresa">Empresa:</label>
                                <input type="text" class="form-control" id="empresa" name="empresa" value="<?php echo isset($configuracion->empresa) ? $configuracion->empresa:'';?>">
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripcion:</label>
                                <input type="text" class="form-control"  name="descripcion" value="<?php echo isset($configuracion->descripcion) ? $configuracion->descripcion:''?>">
                            </div>
                            <div class="form-group">
                                <label for="rnc">RNC:</label>
                                <input type="text" class="form-control"  name="rnc" value="<?php echo isset($configuracion->rnc) ? $configuracion->rnc:''?>">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Telefono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo isset($configuracion->telefono) ? $configuracion->telefono:''?>">
                            </div>
                            <div class="form-group">
                                <label for="direccion">Direccion:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo isset($configuracion->direccion) ? $configuracion->direccion:''?>">
                            </div>
                            <div class="form-group">
                                <label for="itbis">ITBIS:</label>
                                <input type="text" class="form-control" id="itbis" name="itbis" value="<?php echo isset($configuracion->itbis) ? $configuracion->itbis:''?>">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-flat">Guardar</button>
                            </div>
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
