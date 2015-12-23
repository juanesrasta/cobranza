
<div class="page-header" style = "text-align: center;">
	<h2><?php echo __('Clientes Registrados'); ?></h2>
	<?php if(empty($clientes)): ?>	
	<p>Usted no posee Clientes Asociados. Puede agregar un Cliente haciendo click <?php echo $this->Html->link('aquí', array('action' => 'add')); ?></p>	
<?php else: ?>
</div>
<div>
<div>
<div>
	<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar cliente nuevo',
		 array(
			 'controller' => 'clientes',
			 'action' => 'add'
		 ),array('title'=>'Agregar cliente',
			'escape'=>false,
			'style' => 'margin-left: 15px; font-size: 12px;'
		)
	). '<br><br>';
?>
	<table table table-hover user_table">

		<tr>
			<th><?php echo $this->Paginator->sort('nombre','Nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('rif','RIF'); ?></th>
			<th><?php echo $this->Paginator->sort('contacto','Contacto'); ?></th>
			<th><?php echo $this->Paginator->sort('cargo','Cargo'); ?></th>
			<th><?php echo $this->Paginator->sort('departamento','Departamento'); ?></th>
			<th><?php echo $this->Paginator->sort('n_caracteres','Caracteres'); ?></th>
			<th><?php echo $this->Paginator->sort('status','Estado'); ?></th>
			<th class="actions"><?php echo __('Herramientas'); ?></th>
	</tr>
	<?php foreach ($clientes as $cliente): ?>
	<tr>
		<td><?php echo $cliente['Cliente']['nombre']; ?>&nbsp;</td>
		<td><?php echo $cliente['Cliente']['rif']; ?>&nbsp;</td>
		<td><?php echo $cliente['Cliente']['contacto']; ?>&nbsp;</td>
		<td><?php echo $cliente['Cliente']['cargo']; ?>&nbsp;</td>
		<td><?php echo $cliente['Cliente']['departamento']; ?>&nbsp;</td>
		<td><?php echo $cliente['Cliente']['n_caracteres']; ?>&nbsp;</td>
		<td><?php echo $cliente['Cliente']['status']; ?>&nbsp;</td>
		
		<td class="actions">
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('<img src="'.$this->webroot.'img/listEdit.png" />', array('action' => 'edit', $cliente['Cliente']['id']),array('escape'=>false,'class' => 'tooltip','title'=>'Editar')); ?>&nbsp;&nbsp;
				<?php 
						$img = ($cliente['Cliente']['status']!='activo')?$this->webroot.'img/activate.png':$this->webroot.'img/deactivate.png';
						$msg = ($cliente['Cliente']['status']=='activo')?"Desea desactivar cliente?":"Desea activar este cliente";
						$title = ($cliente['Cliente']['status']!='activo')?'Activar':'Desactivar';
						echo $this->Form->postLink('<img src="'.$img.'" />', array('action' => 'activar', $cliente['Cliente']['id']),array('escape'=>false,'class' => 'tooltip','title'=>$title), __($msg));
				?>
			</td>
			
	</tr>
	<?php endforeach; ?>
	</table>
	<p>
	</div>
	
</div>

</div>
<div class="listas index grid_12">
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Antetior'), array(), null, array('class' => 'prev disabled'));
		echo  ' ';
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo  ' ';
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
<?php endif; ?>
</div>



