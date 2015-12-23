<div class="page-header" style = "text-align: center;">
	<h1><?php echo 'Roles' ?> <br> </h1>
</div>
<?php

 // debug($roles);

 ?>
 
<table class="table table-hover user_table">
	<tr>
			<th>
				<?php echo 'Rol'; ?>
			</th>
			<th>
				<?php echo 'Menú Usuarios'; ?>
			</th>
			<th>
				<?php echo 'Menú Clientes'; ?>
			</th>
			<th>
				<?php echo 'Menú Productos'; ?>
			</th>
			<th>
				<?php echo 'Menú Roles'; ?>
			</th>
			<th>
				<?php echo 'Menú Gestion de Cobranza'; ?>
			</th>
			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
	<?php foreach($permisos as $p){ ?>
		
		
		<tr>
			<td>
				<?php	
				echo $p['Permiso']['nombre']; ?>
			</td>
			<td>
				<?php 
					if($p['Permiso']['usuarios']){ ?>
						<?php echo $this->Html->image('check.png', array('width' => '20px;')); ?>
				<?php
					}
				?>
			</td>
			
			<td>
				<?php 
					if($p['Permiso']['clientes']){ ?>
						<?php echo $this->Html->image('check.png', array('width' => '20px;')); ?>
				<?php
					}
				?>
			</td>
			
			<td>
				<?php 
					if($p['Permiso']['productos']){ ?>
						<?php echo $this->Html->image('check.png', array('width' => '20px;')); ?>
				<?php
					}
				?>
			</td>
			
			<td>
				<?php 
					if($p['Permiso']['roles']){ ?>
						<?php echo $this->Html->image('check.png', array('width' => '20px;')); ?>
				<?php
					}
				?>
			</td>
			<td>
				<?php 
					if($p['Permiso']['gestion']){ ?>
						<?php echo $this->Html->image('check.png', array('width' => '20px;')); ?>
				<?php
					}
				?>
			</td>
			<td style = "text-align: center;">
				<?php
					echo $this->Html->link(
						$this->Html->image('listEdit.png', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Editar permisos del Usuario')) . '  ',
						array(
						 'controller' => 'permisos',
						 'action' => 'edit',
						 $p['Permiso']['id']
						),array('escape'=>false)
					);
				?>
			</td>
		</tr>
		<?php } ?> 
</table>