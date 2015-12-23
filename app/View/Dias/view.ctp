<div class="page-header" style = "text-align: center;">
	<h1><?php echo 'Mis datos' ?> <br> </h1>
</div>

<table class="table table-hover user_table">
		<tr>
			<th>
				<?php echo 'Nombre de Usuario'; ?>
			</th>
			<th>
				<?php echo 'Nombre Completo'; ?>
			</th>
			<th>
				<?php echo 'Status'; ?>
			</th>
			<th>
				<?php echo 'Tipo'; ?>
			</th>
			<th>
				<?php echo 'Rol'; ?>
			</th>
			<th>
				<?php //echo 'Teléfono'; ?>
			</th>
			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
		
		<tr>
			<td>
				<?php echo $user['User']['username']; ?>
			</td>
			<td>
				<?php echo $user['User']['nombre_completo']; ?>
			</td>
			<td>
				<?php echo $user['User']['status']; ?>
			</td>
			<td>
				<?php echo $user['User']['tipo']; ?>
			</td>
			<td>
				<?php echo $user['User']['rol']; ?>
			</td>
			<td>
				<?php //echo $user['User']['telefono']; ?>
			</td>
			<td style = "text-align: center;">
				<?php
					
					echo $this->Html->link(
						$this->Html->image('listEdit.png', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Editar Datos')) . '  ',
						array(
						 'controller' => 'users',
						 'action' => 'edit',
						 $user['User']['id']
						),array('escape'=>false)
					);
				?>
			</td>
		</tr>
</table>