<div class="page-header" style = "text-align: center;">
	<h1>Usuarios Registrados <br>  <small>Modifica los datos de los usuarios o elimínalos</small></h1>
</div>
<div>
<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar Nuevo Usuario',
		 array(
			 'controller' => 'users',
			 'action' => 'add'
		 ),array(
			'escape'=>false,
			'style' => 'margin-left: 12px; font-size: 15px;'
		)
	). '<br><br>';
?>
<table class="table table-hover user_table">
		<tr>
			<th>
			<?php echo $this->Paginator->sort('username','Usuario'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('nombre_completo','Nombre'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('status','Estado'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('tipo','Tipo'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('rol','Rol'); ?>
			</th>
			<th>
				<?php echo $this->Paginator->sort('supervisor_id','Supervisor'); ?>
			</th>
			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
<?php
	foreach($user as $u){ ?>
		
		<tr class = "<?php if($u['User']['id'] == $id){echo 'success';} ?> ">
			<td>
				<?php echo $u['User']['username']; ?>
			</td>
			<td>
				<?php echo $u['User']['nombre_completo']; ?>
			</td>
			<td>
				<?php echo $u['User']['status']; ?>
			</td>
			<td>
				<?php echo $u['User']['tipo']; ?>
			</td>
			<td>
				<?php echo $u['User']['rol']; ?>
			</td>
			<td>
				<?php if(!empty($supervisores[$u['User']['id']])) {
					echo $supervisores[$u['User']['id']];
				} else {
					echo 'Sin supervisor';
				} ?>
			</td>
			<td style = "text-align: center;">
				<?php
					echo $this->Html->link(
						$this->Html->image('listEdit.png', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Editar Usuario')) . '  ',
						array(
						 'controller' => 'users',
						 'action' => 'edit',
						 $u['User']['id']
						),array('escape'=>false)
					);
				// echo ' ';
					//if($id != $u['User']['id']) {
						//echo $this->Html->link(
							//$this->Html->image('delete-num16x16.jpg', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Eliminar Usuario')) . '  ',
							//array(
							// 'controller' => 'users',
							// 'action' => 'delete',
							// $u['User']['id']
							//),array('escape'=>false)
						//);
					//}
					
					echo ' ';
					if($id != $u['User']['id']) {
						$img = ($u['User']['status']!='activo')?$this->webroot.'img/activate.png':$this->webroot.'img/deactivate.png';
						$msg = ($u['User']['status']=='activo')?"Desea desactivar el usuario?":"Desea activar este usuario";
						$title = ($u['User']['status']!='activo')?'Activar':'Desactivar';
						echo $this->Form->postLink('<img src="'.$img.'" />', array('action' => 'activar', $u['User']['id']),array('escape'=>false,'class' => 'tooltip','title'=>$title), __($msg));
					}
					
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
		echo $this->Paginator->prev('< ' . __('Antetior'), array(), null, array('class' => 'prev disabled'));
		echo  ' ';
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo  ' ';
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	</div>