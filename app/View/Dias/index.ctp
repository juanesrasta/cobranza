<div class="page-header" style = "text-align: center;">
	<h1>Dias No Laborales <br>  <small>Agrega, elimina o modifica días no laborables</small></h1>
</div>
<div>
<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar Nuevo Día No Laborable',
		 array(
			 'controller' => 'dias',
			 'action' => 'add'
		 ),array(
			'escape'=>false,
			'style' => 'margin-left: 12px; font-size: 15px;'
		)
	). '<br><br>';
?>

<div class = "search_box" style= "margin: 1%;">
	<?php echo $this->element('dias_search'); ?>
</div>

<table class="table table-hover user_table">
		<tr>
			<th>
			<?php echo $this->Paginator->sort('fecha','Fecha'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('descripcion','Descripción'); ?>
			</th>
			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
<?php
	foreach($dias as $d){ ?>
		
		<tr>
			<td>
				<?php 
				$date = date_create($d['Dia']['fecha']);
				echo date_format($date, 'd-m-Y'); ?>
			</td>
			<td>
				<?php echo $d['Dia']['descripcion']; ?>
			</td>
			<td style = "text-align: center;">
				<?php
					echo $this->Html->link(
						$this->Html->image('listEdit.png', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Editar Fecha')) . '  ',
						array(
						 'controller' => 'dias',
						 'action' => 'edit',
						 $d['Dia']['id']
						),array('escape'=>false)
					);
				echo ' ';
					//if($id != $d['Dia']['id']) {
						echo $this->Html->link(
							$this->Html->image('delete-num16x16.jpg', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Eliminar Fecha')) . '  ',
							array(
							'controller' => 'dias',
							'action' => 'delete',
							$d['Dia']['id']
							),array('escape'=>false)
						);
					//}
					
					// echo ' ';
					// if($id != $u['User']['id']) {
						// $img = ($u['User']['status']!='activo')?$this->webroot.'img/activate.png':$this->webroot.'img/deactivate.png';
						// $msg = ($u['User']['status']=='activo')?"Desea desactivar cliente?":"Desea activar este cliente";
						// $title = ($u['User']['status']!='activo')?'Activar':'Desactivar';
						// echo $this->Form->postLink('<img src="'.$img.'" />', array('action' => 'activar', $u['User']['id']),array('escape'=>false,'class' => 'tooltip','title'=>$title), __($msg));
					// }
					
				?>
			</td>
		</tr>
		
<?php
	}
?>

</table>

<div class="listas index grid_12">
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior'), array(), null, array('class' => 'prev disabled'));
		echo  ' ';
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo  ' ';
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	</div>