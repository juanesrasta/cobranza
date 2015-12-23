
<div class="page-header" style = "text-align: center;">
	<h2><?php echo __('Productos Registrados'); ?></h2>
</div>

<div>
<div>
	<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar producto nuevo',
		 array(
			 'controller' => 'productos',
			 'action' => 'add'
		 ),array('title'=>'Agregar producto',
			'escape'=>false,
			'style' => 'margin-left: 15px; font-size: 12px;'
		)
	). '<br><br>';
	foreach ($clientes as $cliente) {
?>
		<h4><?php echo '&nbsp;&nbsp;Cliente: '.$cliente['Cliente']['nombre']?></h4>
		<?php if (!empty($cliente['Producto']['0'])) { ?>
			<table table table-hover user_table">

				<tr>
					<th><?php echo $this->Paginator->sort('Producto.0.producto','Producto'); 
					?></th>
					<th><?php echo $this->Paginator->sort('nombre','Cliente'); ?></th>
					<th class="actions"><?php echo __('Herramientas'); ?></th>
			</tr>
			<?php foreach ($cliente['Producto'] as $producto): ?>
			<tr>
			
				<td><?php echo $producto['producto']; ?>&nbsp;</td>
				<td><?php echo $cliente['Cliente']['nombre']; ?>&nbsp;</td>
				
				<td class="actions">
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('<img src="'.$this->webroot.'img/listEdit.png" />', array('action' => 'edit', $producto['id']),array('escape'=>false,'class' => 'tooltip','title'=>'Editar')); ?>&nbsp;&nbsp;
						<?php 
								// $img = ($producto['status']!='activo')?$this->webroot.'img/activate.png':$this->webroot.'img/deactivate.png';
								// $msg = ($producto['status']=='activo')?"Desea desactivar producti?":"Desea activar este producto";
								// $title = ($producto['status']!='activo')?'Activar':'Desactivar';
								// echo $this->Form->postLink('<img src="'.$img.'" />', array('action' => 'activar', $producto['id']),array('escape'=>false,'class' => 'tooltip','title'=>$title), __($msg));
						?>
					</td>
					
			</tr>
			<?php endforeach; ?>
			</table>
			<br>
			<?php } else {
				echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Este cliente no posee productos</p>';
			}
		} ?>
	<p>
	</div>
	</div>


