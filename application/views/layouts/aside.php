        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">      
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MAIN NAVIGATION</li>
                    <li>
                        <a href="<?php echo base_url();?>dashboard">
                            <i class="fa fa-home"></i> <span>Inicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url();?>cuentas_cobrar">
                            <i class="fa fa-credit-card"></i> <span>Cuentas por Cobrar</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-money"></i> <span>Caja</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url();?>caja/cierre"><i class="fa fa-circle-o"></i> Cierre</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-cogs"></i> <span>Mantenimiento</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url(); ?>mantenimiento/ajuste"><i class="fa fa-circle-o"></i> Ajuste de Inventario</a></li>
                            <li><a href="<?php echo base_url();?>mantenimiento/categorias"><i class="fa fa-circle-o"></i> Categorias</a></li>
                            <li><a href="<?php echo base_url();?>mantenimiento/clientes_juridicos"><i class="fa fa-circle-o"></i> Clientes Juridicos</a></li>
                            <li><a href="<?php echo base_url();?>mantenimiento/clientes_normales"><i class="fa fa-circle-o"></i> Clientes Normales</a></li>
                            <li><a href="<?php echo base_url(); ?>mantenimiento/productos"><i class="fa fa-circle-o"></i> Productos</a></li>
                            <li><a href="<?php echo base_url(); ?>mantenimiento/proveedores"><i class="fa fa-circle-o"></i> Proveedores</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-share-alt"></i> <span>Movimientos</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url();?>movimientos/ventas"><i class="fa fa-circle-o"></i> Ventas</a></li>
                            <li><a href="<?php echo base_url();?>movimientos/compras"><i class="fa fa-circle-o"></i> Compras</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-print"></i> <span>Reportes</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url();?>reportes/abonos"><i class="fa fa-circle-o"></i> Abonos</a></li>
                            <li><a href="<?php echo base_url();?>reportes/inventario_contable"><i class="fa fa-circle-o"></i> Inventario Contable</a></li>
                            <li><a href="<?php echo base_url();?>reportes/inventario_fisico"><i class="fa fa-circle-o"></i> Inventario Fisico</a></li>
                            <li><a href="<?php echo base_url();?>reportes/ventas"><i class="fa fa-circle-o"></i> Reporte Ventas</a></li>
                            <li><a href="<?php echo base_url();?>reportes/resumen"><i class="fa fa-circle-o"></i> Resumen de Ventas</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-user-circle-o"></i> <span>Administrador</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url();?>administrador/configuraciones"><i class="fa fa-circle-o"></i> Configuraciones</a></li>
                            <li><a href="<?php echo base_url();?>administrador/comprobantes"><i class="fa fa-circle-o"></i> Comprobantes</a></li>
                            <li><a href="<?php echo base_url();?>administrador/usuarios"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                            <li><a href="<?php echo base_url();?>administrador/permisos"><i class="fa fa-circle-o"></i> Permisos</a></li>
                        </ul>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->