
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Clientes Juridicos
        <small>Editar</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if($this->session->flashdata("error")):?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p><i class="icon fa fa-ban"></i><?php echo $this->session->flashdata("error"); ?></p>
                                
                             </div>
                        <?php endif;?>
                        <form action="<?php echo base_url();?>mantenimiento/clientes_normales/update" method="POST">
                            <input type="hidden" name="idCliente" value="<?php echo $cliente->id;?>">
                            <div class="form-group <?php echo form_error("cedula") != false ? 'has-error':'';?>">
                                <label for="cedula">Cedula:</label>
                                <input type="text" class="form-control"  name="cedula" value="<?php echo $cliente->cedula;?>" required="required">
                                <?php echo form_error("cedula","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group">
                                <label for="nombres">Nombres:</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required="required" value="<?php echo $cliente->nombres;?>">
                            </div>
                            <div class="form-group">
                                <label for="apellidos">Apellidos:</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required="required" value="<?php echo $cliente->apellidos;?>">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Telefono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required="required" value="<?php echo $cliente->telefono;?>">
                            </div>
                            <div class="form-group">
                                <label for="celular">Celular:</label>
                                <input type="text" class="form-control" id="celular" name="celular" required="required" value="<?php echo $cliente->celular;?>">
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo:</label>
                                <input type="text" class="form-control" id="correo" name="correo" required="required" value="<?php echo $cliente->correo;?>">
                            </div>
                            <div class="form-group">
                                <label for="direccion">Direccion:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required="required" value="<?php echo $cliente->direccion;?>">
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
