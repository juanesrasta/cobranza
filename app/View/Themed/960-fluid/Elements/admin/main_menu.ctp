<ul class="nav main">
	<li>
		<?php
			if(!empty($username)){ 
				echo $this->Html->link('Archivo', '#');
			}
		?>
		<ul>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Usuarios del Sistema ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '15px')) . ' Usuarios del Sistema ',
									 array(
										 'controller' => 'users',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->image('listAddContacts.png', array('width' => '15px')) . ' Agregar Usuario ',
									 array(
										 'controller' => 'users',
										 'action' => 'add'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>
			
			
			<li>
				<?php
					if($permisos_usuarios['Permiso']['clientes'] ){
						echo $this->Html->link(
							 $this->Html->image('clientes.png' , array('width' => '14px')) .' Clientes del Sistema ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '14px')) . ' Listar Clientes ',
									 array(
										 'controller' => 'clientes',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->image('listAddContacts.png', array('width' => '14px')) . ' Agregar Cliente ',
									 array(
										 'controller' => 'clientes',
										 'action' => 'add'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>




			<li>
				<?php
					if($permisos_usuarios['Permiso']['productos'] ){
						echo $this->Html->link(
							 $this->Html->image('addListIcon.png' , array('width' => '14px')) .' Productos de clientes ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '14px')) . ' Listar productos',
									 array(
										 'controller' => 'productos',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->image('listAddContacts.png', array('width' => '14px')) . ' Agregar Producto ',
									 array(
										 'controller' => 'productos',
										 'action' => 'add'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '14px')) . ' Grupos de Productos',
									 array(
										 'controller' => 'gruposProductos',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>


			
			
			<li>
				<?php 
					if($permisos_usuarios['Permiso']['roles'] ){
						echo $this->Html->link(
							 $this->Html->image('roles.png' , array('width' => '15px')) . ' Roles ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li>
						<?php
							 echo $this->Html->link(
									 $this->Html->image('listsms.png', array('width' => '15px')) . ' Administrar Roles ',
									 array(
										 'controller' => 'permisos',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
						?>
					</li>
				</ul>
			</li>
			<li>
				<?php 
					if($permisos_usuarios['Permiso']['dias'] ){
						echo $this->Html->link(
							 $this->Html->image('info.png' , array('width' => '15px')) . ' Días No Laborables ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li>
						<?php
							 echo $this->Html->link(
									 $this->Html->image('listsms.png', array('width' => '15px')) . ' Administrar Días ',
									 array(
										 'controller' => 'dias',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
						?>
					</li>
				</ul>
			</li>
            <li>
                <?php
                //if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Status',
                        array(
                            'controller' => 'clientes', 'action' => 'status'
                        )
                        ,array('escape'=>false)
                    );
               // }
                ?>
            </li>
		</ul>	
	</li>
	<li> <!-- Empieza segundo menú (Gestión) -->
		<?php
			if(!empty($username)){ 
				echo $this->Html->link('Gestión', '#');
			}
		?>
		<ul>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Gestión de Cobranzas ',
							 array(
								 'controller' => 'gestion', 'action' => 'admin_index'
							 ),array('escape'=>false)
						 );
					}
				?>
			</li>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Gestiones del dia ',
							 array(
								 'controller' => 'gestion', 'action' => 'admin_gestion_dia'
							 ),array('escape'=>false)
						 );
					}
				?>
			</li>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . 'Gestiones por Gestión ',
							 array(
								 'controller' => 'gestion', 'action' => 'admin_gestiones_por_status'
							 ),array('escape'=>false)
						 );
					}
				?>
			</li>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Consulta General',
							 array(
								 'controller' => 'gestion', 'action' => 'admin_gestiones_general'
							 ),array('escape'=>false)
						 );
					}
				?>
			</li>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Gestiones por producto ',
							 array(
								 'controller' => 'gestion', 'action' => 'admin_gestiones_por_producto'
							)
							 ,array('escape'=>false)
						 );
					}
				?>
			</li>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Cambio de Fecha ',
							 array(
								 'controller' => 'gestion', 'action' => 'admin_cambio_fecha'
							)
							 ,array('escape'=>false)
						 );
					}
				?>
			</li>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Generar Archivo ',
							 array(
								 'controller' => 'gestion', 'action' => 'admin_generar_archivo'
							)
							 ,array('escape'=>false)
						 );
					}
				?>
			</li>
		</ul>
	</li>
	<li> <!-- Empieza tercer menú (Cartera) -->
		<?php
			if(!empty($username)){ 
				echo $this->Html->link('Cartera', '#');
			}
		?>
		<ul>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Recepción de cartera ',
							 array(
								 'controller' => 'cartera', 'action' => 'admin_recepcion_cartera'
							)
							 ,array('escape'=>false)
						 );
					}
				?>
			</li>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Anexar Cartera ',
							 array(
								 'controller' => 'cartera', 'action' => 'admin_anexar_cartera'
							)
							 ,array('escape'=>false)
						 );
					}
				?>
			</li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['usuarios'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Asignar Cartera ',
                        array(
                            'controller' => 'cartera', 'action' => 'admin_asignar'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['usuarios'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Cartera desincorporada',
                        array(
                            'controller' => 'cartera', 'action' => 'admin_cartera_desincorporada'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
		</ul>
	</li>
    <li> <!-- Empieza cuarto menú (Pago) -->
        <?php
        if(!empty($username)){
            echo $this->Html->link('Pagos', '#');
        }
        ?>
        <ul>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Recepción de pagos ',
                        array(
                            'controller' => 'pagos', 'action' => 'recepcion'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Confirmacion de pagos ',
                        array(
                            'controller' => 'pagos', 'action' => 'admin_confirmar'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Quitar confirmacion de pago',
                        array(
                            'controller' => 'pagos', 'action' => 'admin_quitar_confirmacion'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Pagos y comision de gestor',
                        array(
                            'controller' => 'pagos', 'action' => 'comision_gestor'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Consulta de Pago por Gestor',
                        array(
                            'controller' => 'pagos', 'action' => 'admin_relacion_pago'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Aplicaciones',
                        array(
                            'controller' => 'pagos', 'action' => 'aplicaciones'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Preliquidación',
                        array(
                            'controller' => 'pagos', 'action' => 'preliquidacion'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
        </ul>
    </li>
    <li> <!-- Empieza menú Reporte -->
        <?php
        if(!empty($username)){
            echo $this->Html->link('Reportes', '#');
        }
        ?>
        <ul>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['cartera'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Cartera ',
                        array(
                            'controller' => 'cartera', 'action' => 'admin_reporte_cartera'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
            <li>
                <?php
                if($permisos_usuarios['Permiso']['pagos'] ){
                    echo $this->Html->link(
                        $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Pagos ',
                        array(
                            'controller' => 'pagos', 'action' => 'admin_reporte_pagos'
                        )
                        ,array('escape'=>false)
                    );
                }
                ?>
            </li>
        </ul>
    </li>

	<?php if(!empty($username)){?>
		<li class = 'secondary'>
			<?php
				echo $this->Html->link(
					$this->Html->image('logout.png', array('width' => '15px')) . ' Cerrar Sesión ',
					array(
					 'controller' => 'users',
					 'action' => 'logout'
					),array('escape'=>false)
				);
			?>
		</li>
		<li class = "secondary">
			<?php echo $this->Html->link('Bienvenido (a) '. $username, '#'); ?>
			<ul>
				<li>
					<?php
						echo $this->Html->link(
							$this->Html->image('listLook.png', array('width' => '15px')) . ' Ver mis datos ',
							array(
							 'controller' => 'users',
							 'action' => 'view',
							 $id
							),array('escape'=>false)
						);
					?>
				</li>
				<li>
					<?php 
					if($rol_activo == 'administrador'){
						echo $this->Html->link(
							$this->Html->image('listEdit.png', array('width' => '15px')) . ' Editar mis datos ',
							array(
							 'controller' => 'users',
							 'action' => 'edit',
							 $id
							),array('escape'=>false)
						);
					}
					?>
				</li>
			</ul>
		</li>
	<?php }else{ ?>
			<li class = 'secondary'>
				<?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?>
			</li>
	<?php } ?>
	
</ul>
<script>
	
</script>